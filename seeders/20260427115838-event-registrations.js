'use strict';
const { v4: uuidv4 } = require('uuid');

module.exports = {
  up: async (queryInterface, Sequelize) => {
    await queryInterface.bulkInsert('registrations', [
      {
        id: uuidv4(),
        user_id: 1,
        event_id: 1,
        user_name: 'Budi Santoso',
        event_title: 'Seminar AI & Machine Learning 2025',
        status: 'confirmed',
        registered_at: new Date(),
        confirmed_at: new Date(),
      },
      {
        id: uuidv4(),
        user_id: 2,
        event_id: 1,
        user_name: 'Siti Rahayu',
        event_title: 'Seminar AI & Machine Learning 2025',
        status: 'pending',
        registered_at: new Date(),
        confirmed_at: null,
      },
      {
        id: uuidv4(),
        user_id: 3,
        event_id: 2,
        user_name: 'Andi Wijaya',
        event_title: 'Workshop Web Development',
        status: 'confirmed',
        registered_at: new Date(),
        confirmed_at: new Date(),
      },
      {
        id: uuidv4(),
        user_id: 1,
        event_id: 3,
        user_name: 'Budi Santoso',
        event_title: 'Lomba UI/UX Design',
        status: 'cancelled',
        registered_at: new Date(),
        confirmed_at: null,
      },
    ]);
  },

  down: async (queryInterface) => {
    await queryInterface.bulkDelete('registrations', null, {});
  }
};