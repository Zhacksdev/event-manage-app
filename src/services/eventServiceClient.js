const axios = require('axios');
require('dotenv').config();

const BASE_URL = process.env.EVENT_SERVICE_URL || 'http://localhost:8002/api';

/**
 * Fetch event detail from EventService
 * @param {number} eventId
 * @returns {object} event data
 */
const getEventById = async (eventId) => {
  const response = await axios.get(`${BASE_URL}/events/${eventId}`, {
    timeout: 5000,
    headers: { 'Content-Type': 'application/json' },
  });
  return response.data;
};

/**
 * Update quota (decrement registered_count) after successful registration
 * @param {number} eventId
 */
const updateEventQuota = async (eventId) => {
  const response = await axios.put(
    `${BASE_URL}/events/${eventId}/quota`,
    {},
    {
      timeout: 5000,
      headers: { 'Content-Type': 'application/json' },
    }
  );
  return response.data;
};

module.exports = { getEventById, updateEventQuota };
