const express = require('express');
const router = express.Router();
const auth = require('../middleware/auth');
const vendedorController = require('../controllers/vendedorController');

router.get('/vendedor', auth, vendedorController.mostrarVentas);
router.get('/vendedor/clientes', auth, vendedorController.listarClientes);
router.get('/clientes', auth, vendedorController.listarClientes);
module.exports = router;