const db = require('../models/db');
const bcrypt = require('bcrypt');

const gestorLogin = {
    // 1. Mostrar la página de login
    mostrarLogin: (req, res) => {
        res.render('login');
    },

    // 2. Procesar el login
    validarUsuario: async (req, res) => {
        const { usuario, password } = req.body;

        try {
            // Buscamos al usuario por su nombre de usuario (en tu tabla unificada)
            const [rows] = await db.query('SELECT * FROM usuarios WHERE Usuario = ?', [usuario]);

            if (rows.length === 0) {
                return res.send('Usuario no encontrado');
            }

            const usuarioDB = rows[0];

            // Comparamos el password con bcrypt (¡es más seguro!)
            const esValida = await bcrypt.compare(password, usuarioDB.Password);

            if (esValida) {
                // Guardamos al usuario en la sesión para que la app lo "recuerde"
                req.session.usuario = usuarioDB;
                return res.redirect('/empleados');
            } else {
                return res.send('Contraseña incorrecta');
            }
        } catch (error) {
            console.error(error);
            res.status(500).send('Error en el sistema');
        }
    }
};

module.exports = gestorLogin;