const express = require('express');
const router = express.Router();
const gestorLogin = require('../controllers/gestorLogin');

router.get('/login', gestorLogin.mostrarLogin);
router.post('/login', gestorLogin.validarUsuario);

module.exports = router;