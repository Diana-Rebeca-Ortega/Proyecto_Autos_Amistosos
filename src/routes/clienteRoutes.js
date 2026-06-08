const express = require('express');
const router = express.Router();
const auth = require('../middleware/auth');
const clienteController = require('../controllers/clienteController');
//clientes
router.get('/cliente', auth, clienteController.mostrarCarros);

module.exports = router;