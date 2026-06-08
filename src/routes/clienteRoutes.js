const express = require('express');
const router = express.Router();
const auth = require('../middleware/auth');
const clienteController = require('../controllers/clienteController');

//clientes
router.get('/cliente', auth, clienteController.mostrarCarros);
router.get('/cliente/explorar_autos', auth, clienteController.explorarAutos);
router.get('/cliente/detalle/:id', auth, clienteController.verDetalle);
// Para ver la página de favoritos (GET)
router.get('/cliente/favoritos', auth, clienteController.mostrarFavoritos);
// Para realizar la acción de añadir/quitar de favoritos (POST)
router.post('/cliente/favoritos/accion', auth, clienteController.favoritos);
module.exports = router;