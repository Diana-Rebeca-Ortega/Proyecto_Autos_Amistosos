const express = require('express');
const router = express.Router();
const gestorLogin = require('../controllers/gestorLogin');

router.get('/login', gestorLogin.mostrarLogin);
router.post('/login', gestorLogin.validarUsuario);
router.get('/logout', gestorLogin.cerrarSesion);
module.exports = router;