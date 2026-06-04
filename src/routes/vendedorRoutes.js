const express = require('express');
const router = express.Router();
const auth = require('../middleware/auth');
const vendedorController = require('../controllers/vendedorController');

router.get('/vendedor', auth, vendedorController.mostrarVentas);
router.get('/vendedor/clientes', auth, vendedorController.listarClientes);
//router.get('/vendedor/clientes/editar/:id', auth, vendedorController.editarFormulario);
router.get('/clientes', auth, vendedorController.listarClientes);
router.post('/vendedor/clientes/crear', auth, vendedorController.crearCliente);
module.exports = router;