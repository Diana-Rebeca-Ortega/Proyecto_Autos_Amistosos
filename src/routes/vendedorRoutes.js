const express = require('express');
const router = express.Router();
const vendedorController = require('../controllers/vendedorController');

router.get('/vendedor_autos', vendedorController.mostrarVentas);
module.exports = router;