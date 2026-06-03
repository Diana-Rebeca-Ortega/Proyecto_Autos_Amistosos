const db = require('../models/db');
const crypto = require('crypto'); 

const gestorLogin = {
    // 1. Mostrar la página de login
    mostrarLogin: (req, res) => {
        res.render('login');
    },

    // 2. Procesar el login
    validarUsuario: async (req, res) => {
        const { user, pass } = req.body;
        
        try {
            const [rows] = await db.query('SELECT * FROM usuarios WHERE Usuario = ?', [user]);
            if (rows.length === 0) {
                return res.send('Usuario no encontrado');
            }
            const usuarioDB = rows[0];
            // Determinamos el algoritmo según la longitud del hash guardado (40=SHA1, 64=SHA256)
            const algoritmo = usuarioDB.Password.length === 64 ? 'sha256' : 'sha1';
            // Calculamos el hash de la contraseña ingresada
            const hashIngresado = crypto.createHash(algoritmo).update(pass).digest('hex');
            // Comparamos los hashes
            if (hashIngresado === usuarioDB.Password) {
                // Guardamos al usuario en la sesión
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