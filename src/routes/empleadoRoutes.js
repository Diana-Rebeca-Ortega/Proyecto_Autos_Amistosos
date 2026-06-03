const express = require('express');
const router = express.Router();
const empleadoController = require('../controllers/empleadoController');

// 1. Ruta para renderizar el panel principal (DataTables)
router.get('/', empleadoController.listarEmpleados);

//---------------------ABCC de vendedor--------------------
// 2. Ruta para procesar el formulario de alta
router.post('/crear', empleadoController.crearEmpleado);
// 3. Ruta para ver el detalle de un vendedor específico
console.log("¿El controlador es una función?:", typeof empleadoController.verDetalle);//depurar
router.get('/detalle/:idVendedor', empleadoController.verDetalle);




module.exports = router;