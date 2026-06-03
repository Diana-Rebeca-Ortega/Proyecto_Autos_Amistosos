const express = require('express');
const router = express.Router();

// Ruta para el vendedor (Donde entrará Ana)
router.get('/vendedor', (req, res) => {
    res.send('<h1>Bienvenido Panel de Vendedor (Ana) - Próximamente</h1>');
});

module.exports = router;