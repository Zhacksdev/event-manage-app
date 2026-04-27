const axios = require('axios');
require('dotenv').config();

const BASE_URL = process.env.USER_SERVICE_URL || 'http://localhost:8001/api';

/**
 * Fetch user detail from UserService
 * @param {number} userId
 * @returns {object} user data
 */
const getUserById = async (userId) => {
  const response = await axios.get(`${BASE_URL}/users/${userId}`, {
    timeout: 5000,
    headers: { 'Content-Type': 'application/json' },
  });
  return response.data;
};

module.exports = { getUserById };
