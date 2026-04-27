'use strict';

module.exports = {
  up: async (queryInterface, Sequelize) => {
    await queryInterface.createTable('registrations', {
      id: {
        type: Sequelize.STRING(36),
        primaryKey: true,
        allowNull: false
      },
      user_id: {
        type: Sequelize.BIGINT.UNSIGNED,
        allowNull: false
      },
      event_id: {
        type: Sequelize.BIGINT.UNSIGNED,
        allowNull: false
      },
      user_name: {
        type: Sequelize.STRING(100),
        allowNull: false
      },
      event_title: {
        type: Sequelize.STRING(200),
        allowNull: false
      },
      status: {
        type: Sequelize.ENUM('pending', 'confirmed', 'cancelled'),
        defaultValue: 'pending'
      },
      registered_at: {
        type: Sequelize.DATE,
        defaultValue: Sequelize.NOW
      },
      confirmed_at: {
        type: Sequelize.DATE,
        allowNull: true
      }
    });
  },

  down: async (queryInterface) => {
    await queryInterface.dropTable('registrations');
  }
};