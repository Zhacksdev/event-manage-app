const { v4: uuidv4 } = require('uuid');
const Registration = require('../models/Registration');
const userClient = require('../services/userServiceClient');
const eventClient = require('../services/eventServiceClient');
const notifClient = require('../services/notifServiceClient');

// ────────────────────────────────────────────────
// POST /api/registrations
// Provider + Consumer: validate user & event, then create registration
// ────────────────────────────────────────────────
const createRegistration = async (req, res) => {
  const { user_id, event_id } = req.body;

  if (!user_id || !event_id) {
    return res.status(400).json({
      success: false,
      message: 'user_id and event_id are required',
    });
  }

  try {
    // 1. Validate user from UserService (CONSUMER)
    let userData;
    try {
      const userResponse = await userClient.getUserById(user_id);
      userData = userResponse.data || userResponse;
    } catch (err) {
      return res.status(404).json({
        success: false,
        message: `User with id ${user_id} not found in UserService`,
        error: err.response?.data || err.message,
      });
    }

    // 2. Validate event & check quota from EventService (CONSUMER)
    let eventData;
    try {
      const eventResponse = await eventClient.getEventById(event_id);
      eventData = eventResponse.data || eventResponse;
    } catch (err) {
      return res.status(404).json({
        success: false,
        message: `Event with id ${event_id} not found in EventService`,
        error: err.response?.data || err.message,
      });
    }

    // 3. Check event status
    if (eventData.status === 'closed' || eventData.status === 'cancelled') {
      return res.status(400).json({
        success: false,
        message: `Event is ${eventData.status}. Registration not allowed.`,
      });
    }

    // 4. Check quota
    if (eventData.registered_count >= eventData.quota) {
      return res.status(400).json({
        success: false,
        message: 'Event quota is full. Registration not allowed.',
      });
    }

    // 5. Check duplicate registration
    const existing = await Registration.findOne({
      where: { user_id, event_id, status: ['pending', 'confirmed'] },
    });
    if (existing) {
      return res.status(409).json({
        success: false,
        message: 'User already registered for this event.',
      });
    }

    // 6. Create registration (PROVIDER)
    const registration = await Registration.create({
      id: uuidv4(),
      user_id,
      event_id,
      user_name: userData.name,
      event_title: eventData.title,
      status: 'confirmed',
      registered_at: new Date(),
      confirmed_at: new Date(),
    });

    // 7. Update event quota in EventService
    try {
      await eventClient.updateEventQuota(event_id);
    } catch (err) {
      // Non-blocking: log but don't fail the registration
      console.warn('[WARN] Failed to update event quota:', err.message);
    }

    // 8. Send notification to NotificationService (CONSUMER)
    try {
      await notifClient.sendNotification(registration.id, user_id, 'registration_confirmed');
    } catch (err) {
      // Non-blocking: notification failure should not break registration
      console.warn('[WARN] Failed to send notification:', err.message);
    }

    return res.status(201).json({
      success: true,
      message: 'Registration successful',
      data: registration,
    });
  } catch (err) {
    console.error('[ERROR] createRegistration:', err);
    return res.status(500).json({
      success: false,
      message: 'Internal server error',
      error: err.message,
    });
  }
};

// ────────────────────────────────────────────────
// GET /api/registrations/:id
// Provider: consumed by NotificationService
// ────────────────────────────────────────────────
const getRegistrationById = async (req, res) => {
  try {
    const registration = await Registration.findByPk(req.params.id);
    if (!registration) {
      return res.status(404).json({ success: false, message: 'Registration not found' });
    }
    return res.json({ success: true, data: registration });
  } catch (err) {
    console.error('[ERROR] getRegistrationById:', err);
    return res.status(500).json({ success: false, message: 'Internal server error', error: err.message });
  }
};

// ────────────────────────────────────────────────
// GET /api/registrations/user/:userId
// Provider: consumed by UserService
// ────────────────────────────────────────────────
const getRegistrationsByUser = async (req, res) => {
  try {
    const registrations = await Registration.findAll({
      where: { user_id: req.params.userId },
      order: [['registered_at', 'DESC']],
    });
    return res.json({
      success: true,
      total: registrations.length,
      data: registrations,
    });
  } catch (err) {
    console.error('[ERROR] getRegistrationsByUser:', err);
    return res.status(500).json({ success: false, message: 'Internal server error', error: err.message });
  }
};

// ────────────────────────────────────────────────
// GET /api/registrations/event/:eventId
// Provider: consumed by EventService
// ────────────────────────────────────────────────
const getRegistrationsByEvent = async (req, res) => {
  try {
    const registrations = await Registration.findAll({
      where: { event_id: req.params.eventId },
      order: [['registered_at', 'ASC']],
    });
    return res.json({
      success: true,
      total: registrations.length,
      data: registrations,
    });
  } catch (err) {
    console.error('[ERROR] getRegistrationsByEvent:', err);
    return res.status(500).json({ success: false, message: 'Internal server error', error: err.message });
  }
};

// ────────────────────────────────────────────────
// PUT /api/registrations/:id/status
// Provider: update status registrasi
// ────────────────────────────────────────────────
const updateRegistrationStatus = async (req, res) => {
  const { status } = req.body;
  const allowed = ['pending', 'confirmed', 'cancelled'];

  if (!status || !allowed.includes(status)) {
    return res.status(400).json({
      success: false,
      message: `status must be one of: ${allowed.join(', ')}`,
    });
  }

  try {
    const registration = await Registration.findByPk(req.params.id);
    if (!registration) {
      return res.status(404).json({ success: false, message: 'Registration not found' });
    }

    registration.status = status;
    if (status === 'confirmed') registration.confirmed_at = new Date();
    await registration.save();

    return res.json({ success: true, message: 'Status updated', data: registration });
  } catch (err) {
    console.error('[ERROR] updateRegistrationStatus:', err);
    return res.status(500).json({ success: false, message: 'Internal server error', error: err.message });
  }
};

// ────────────────────────────────────────────────
// DELETE /api/registrations/:id
// Provider: batalkan registrasi
// ────────────────────────────────────────────────
const cancelRegistration = async (req, res) => {
  try {
    const registration = await Registration.findByPk(req.params.id);
    if (!registration) {
      return res.status(404).json({ success: false, message: 'Registration not found' });
    }
    if (registration.status === 'cancelled') {
      return res.status(400).json({ success: false, message: 'Registration is already cancelled' });
    }

    registration.status = 'cancelled';
    await registration.save();

    return res.json({ success: true, message: 'Registration cancelled', data: registration });
  } catch (err) {
    console.error('[ERROR] cancelRegistration:', err);
    return res.status(500).json({ success: false, message: 'Internal server error', error: err.message });
  }
};

module.exports = {
  createRegistration,
  getRegistrationById,
  getRegistrationsByUser,
  getRegistrationsByEvent,
  updateRegistrationStatus,
  cancelRegistration,
};
