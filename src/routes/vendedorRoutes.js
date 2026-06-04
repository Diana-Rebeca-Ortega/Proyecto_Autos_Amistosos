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



console.log("DEBUG: editarVentaForm es:", typeof vendedorController.editarVentaForm);
router.get('/vendedor/ventas/editar/:id', auth, vendedorController.editarVentaForm);

console.log("DEBUG: actualizarVenta es:", typeof vendedorController.actualizarVenta);
router.post('/vendedor/ventas/editar/:id', auth, vendedorController.actualizarVenta);

console.log("DEBUG: eliminarVenta es:", typeof vendedorController.eliminarVenta);
router.post('/vendedor/ventas/eliminar/:id', auth, vendedorController.eliminarVenta);
module.exports = router;