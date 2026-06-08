const express = require('express');
const router = express.Router();
const auth = require('../middleware/auth');

//clientes
router.get('/cliente', auth, vendedorController.mostrarVentas);