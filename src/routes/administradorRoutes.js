const express = require('express');
const router = express.Router();
const auth = require('../middleware/auth');
const administradorController = require('../controllers/administradorController');

// 1. Ruta para renderizar el panel principal (DataTables)
router.get('/administrador',  auth  ,  administradorController.listarEmpleados);

//---------------------ABCC de vendedor--------------------
router.post('/administrador/crear',   auth  , administradorController.crearEmpleado);// 2. Ruta para procesar el formulario de alta
router.get('/administrador/detalle/:idVendedor',   auth  ,administradorController.verDetalle);// 3. Ruta para ver el detalle de un vendedor específico
router.get('/administrador/editar/:idVendedor',   auth    ,administradorController.editarFormulario);// 4  Ruta para mostrar el formulario de edición
router.post('/administrador/editar/:idVendedor',    auth  , administradorController.actualizarEmpleado);//5 Ruta para guardar los cambios
router.post('/administrador/eliminar/:idVendedor',    auth   ,administradorController.eliminarEmpleado);

module.exports = router;