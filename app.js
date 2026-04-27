require('dotenv').config();
const express = require('express');
const cors = require('cors');
const sequelize = require('./src/config/database');
const registrationRoutes = require('./src/routes/registrationRoutes');

const app = express();
const PORT = process.env.PORT || 8003;

// ── Middleware ──────────────────────────────────
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// ── Routes ──────────────────────────────────────
app.use('/api/registrations', registrationRoutes);

// ── Health check ────────────────────────────────
app.get('/', (req, res) => {
  res.json({
    service: 'RegistrationService',
    version: '1.0.0',
    status: 'running',
    port: PORT,
  });
});

// ── 404 handler ─────────────────────────────────
app.use((req, res) => {
  res.status(404).json({ success: false, message: `Route ${req.originalUrl} not found` });
});

// ── Global error handler ─────────────────────────
app.use((err, req, res, next) => {
  console.error('[ERROR]', err);
  res.status(500).json({ success: false, message: 'Internal server error', error: err.message });
});

// ── Start ────────────────────────────────────────
sequelize
  .authenticate()
  .then(() => {
    console.log('[DB] MySQL connected to registrations_db');
    return sequelize.sync({ alter: true }); // auto-migrate
  })
  .then(() => {
    app.listen(PORT, () => {
      console.log(`[SERVER] RegistrationService running on http://localhost:${PORT}`);
    });
  })
  .catch((err) => {
    console.error('[DB] Connection failed:', err.message);
    process.exit(1);
  });
