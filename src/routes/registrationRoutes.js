const express = require('express');
const router = express.Router();
const ctrl = require('../controllers/registrationController');

// POST
router.post('/', ctrl.createRegistration);

// GET ALL ✅
router.get('/', ctrl.getAllRegistrations);

// GET BY USER
router.get('/user/:userId', ctrl.getRegistrationsByUser);

// GET BY EVENT
router.get('/event/:eventId', ctrl.getRegistrationsByEvent);

// GET BY ID
router.get('/:id', ctrl.getRegistrationById);

// UPDATE
router.put('/:id/status', ctrl.updateRegistrationStatus);

// DELETE
router.delete('/:id', ctrl.cancelRegistration);

module.exports = router;