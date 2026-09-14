const express = require('express');
const router = express.Router();
const clienteRegistroController = require('../controllers/clienteRegistroController');

router.get('/registro', clienteRegistroController.formularioRegistro);
router.post('/registro', clienteRegistroController.registrar);

module.exports = router;