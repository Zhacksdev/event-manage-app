const axios = require('axios');
require('dotenv').config();

const BASE_URL = process.env.NOTIF_SERVICE_URL || 'http://localhost:8004/api';

/**
 * Send notification after registration is confirmed
 * @param {string} registrationId
 * @param {number} userId
 * @param {string} type  e.g. 'registration_confirmed'
 */
const sendNotification = async (registrationId, userId, type = 'registration_confirmed') => {
  const response = await axios.post(
    `${BASE_URL}/notify`,
    { registration_id: registrationId, user_id: userId, type },
    {
      timeout: 5000,
      headers: { 'Content-Type': 'application/json' },
    }
  );
  return response.data;
};

module.exports = { sendNotification };
