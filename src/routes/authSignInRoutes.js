const express = require('express');
const router = express.Router();
const clienteController = require('../controllers/clienteController');

router.get('/registro', clienteController.formularioRegistro);
router.post('/registro', clienteController.registrar);

module.exports = router;