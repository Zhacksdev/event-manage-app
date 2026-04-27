const express = require('express');
const router = express.Router();
const ctrl = require('../controllers/registrationController');

// POST   /api/registrations            — daftar event (consumer + provider)
router.post('/', ctrl.createRegistration);

// GET    /api/registrations/user/:userId — riwayat registrasi user (dikonsumsi UserService)
router.get('/user/:userId', ctrl.getRegistrationsByUser);

// GET    /api/registrations/event/:eventId — semua peserta event (dikonsumsi EventService)
router.get('/event/:eventId', ctrl.getRegistrationsByEvent);

// GET    /api/registrations/:id         — detail registrasi (dikonsumsi NotificationService)
router.get('/:id', ctrl.getRegistrationById);

// PUT    /api/registrations/:id/status  — update status registrasi
router.put('/:id/status', ctrl.updateRegistrationStatus);

// DELETE /api/registrations/:id         — batalkan registrasi
router.delete('/:id', ctrl.cancelRegistration);

module.exports = router;
