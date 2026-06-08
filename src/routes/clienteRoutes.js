const express = require('express');
const router = express.Router();
const auth = require('../middleware/auth');
const clienteController = require('../controllers/clienteController');

//clientes
router.get('/cliente', auth, clienteController.mostrarCarros);
router.get('/cliente/explorar_autos', auth, clienteController.explorarAutos);
router.get('/cliente/detalle/:id', auth, clienteController.verDetalle);



module.exports = router;