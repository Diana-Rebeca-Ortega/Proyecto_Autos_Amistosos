const express = require('express');
const router = express.Router();
const empleadoController = require('../controllers/empleadoController');

// 1. Ruta para renderizar el panel principal (DataTables)
router.get('/', empleadoController.listarEmpleados);

//---------------------ABCC de vendedor--------------------

router.post('/crear', empleadoController.crearEmpleado);// 2. Ruta para procesar el formulario de alta
router.get('/detalle/:idVendedor', empleadoController.verDetalle);// 3. Ruta para ver el detalle de un vendedor específico
router.get('/editar/:idVendedor', empleadoController.editarFormulario);// 4  Ruta para mostrar el formulario de edición
router.post('/editar/:idVendedor', empleadoController.actualizarEmpleado);//5 Ruta para guardar los cambios



module.exports = router;