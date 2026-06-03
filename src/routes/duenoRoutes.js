const express = require('express');
const router = express.Router();

// Ruta para el dueño
router.get('/dueno', (req, res) => {
    res.send('<h1>Bienvenido Panel de Dueño - Próximamente</h1>');
});

module.exports = router;