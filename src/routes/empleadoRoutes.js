const express = require('express');
const router = express.Router();
const empleadoController = require('../controllers/empleadoController');

// 1. Ruta para renderizar el panel principal (DataTables)
router.get('/empleados', empleadoController.listarEmpleados);

// 2. Ruta para procesar el formulario de alta
router.post('/empleados/crear', empleadoController.crearEmpleado);
module.exports = router;