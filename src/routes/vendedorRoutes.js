const express = require('express');
const router = express.Router();
const auth = require('../middleware/auth');
const vendedorController = require('../controllers/vendedorController');

router.get('/vendedor', auth, vendedorController.mostrarVentas);
router.get('/vendedor/clientes', auth, vendedorController.listarClientes);
//router.get('/vendedor/clientes/editar/:id', auth, vendedorController.editarFormulario);
router.get('/clientes', auth, vendedorController.listarClientes);
router.post('/vendedor/clientes/crear', auth, vendedorController.crearCliente);



// VENTAS DE AUTO
router.get('/vendedor/ventas', auth, vendedorController.listarVentas);
router.post('/vendedor/ventas/crear', auth, vendedorController.registrarVenta);

// Vamos a probar si estas 3 son las culpables:
console.log("DEBUG: editarVentaForm es:", typeof vendedorController.editarVentaForm);
router.get('/vendedor/ventas/editar/:id', auth, vendedorController.editarVentaForm);

console.log("DEBUG: actualizarVenta es:", typeof vendedorController.actualizarVenta);
router.post('/vendedor/ventas/editar/:id', auth, vendedorController.actualizarVenta);

console.log("DEBUG: eliminarVenta es:", typeof vendedorController.eliminarVenta);
router.post('/vendedor/ventas/eliminar/:id', auth, vendedorController.eliminarVenta);
module.exports = router;