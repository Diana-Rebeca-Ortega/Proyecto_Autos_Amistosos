const express = require('express');
const router = express.Router();
const empleadoController = require('../controllers/empleadoController');

router.get('/empleados', empleadoController.listarEmpleados);

module.exports = router;