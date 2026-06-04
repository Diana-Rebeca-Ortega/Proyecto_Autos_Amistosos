const express = require('express');
const router = express.Router();
const administradorController = require('../controllers/administradorController');

// 1. Ruta para renderizar el panel principal (DataTables)
router.get('/administrador', administradorController.listarEmpleados);

//---------------------ABCC de vendedor--------------------
router.post('/administrador/crear', administradorController.crearEmpleado);// 2. Ruta para procesar el formulario de alta
router.get('/administrador/detalle/:idVendedor', administradorController.verDetalle);// 3. Ruta para ver el detalle de un vendedor específico
router.get('/administrador/editar/:idVendedor', administradorController.editarFormulario);// 4  Ruta para mostrar el formulario de edición
router.post('/administrador/editar/:idVendedor', administradorController.actualizarEmpleado);//5 Ruta para guardar los cambios
router.post('/administrador/eliminar/:idVendedor', administradorController.eliminarEmpleado);


module.exports = router;