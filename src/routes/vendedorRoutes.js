const express = require('express');
const router = express.Router();
const auth = require('../middleware/auth');
const vendedorController = require('../controllers/vendedorController');
const gestorVendedor = require('../controllers/vendedorController');
//clientes
router.get('/vendedor', auth, vendedorController.mostrarVentas);
router.get('/vendedor/clientes', auth, vendedorController.listarClientes);
//router.get('/vendedor/clientes/editar/:id', auth, vendedorController.editarFormulario);
router.get('/clientes', auth, vendedorController.listarClientes);
//abcc
router.post('/vendedor/clientes/crear', auth, vendedorController.crearCliente);
router.post('/vendedor/clientes/eliminar/:id', gestorVendedor.eliminarCliente);
router.get('/vendedor/clientes/editar/:id', gestorVendedor.mostrarFormularioEditar); // Para cargar el formulario
router.post('/vendedor/clientes/editar/:id', gestorVendedor.actualizarCliente); // Para guardar cambios


// VENTAS DE AUTO
router.get('/vendedor/ventas', auth, vendedorController.listarVentas);
router.post('/vendedor/ventas/crear', auth, vendedorController.registrarVenta);


router.get('/vendedor/ventas/editar/:id', auth, vendedorController.editarVentaForm);
router.post('/vendedor/ventas/editar/:id', auth, vendedorController.actualizarVenta);
router.post('/vendedor/ventas/eliminar/:id', auth, vendedorController.eliminarVenta);
module.exports = router;