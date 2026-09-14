const db = require('../models/db');
const crypto = require('crypto'); 
const bcrypt = require('bcrypt');

const gestorLogin = {
    // 1. Mostrar la página de login
    mostrarLogin: (req, res) => {
    res.render('login', {
        usuario: req.session.usuario
    });
},

    // 2. Procesar el login
   validarUsuario: async (req, res) => {
        const { user, pass } = req.body;
        
        try {
            const [rows] = await db.query('SELECT * FROM usuarios WHERE Usuario = ?', [user]);
            if (rows.length === 0) return res.send('Usuario no encontrado');
            
            const usuarioDB = rows[0];
            let esValida = false;

            // 1. Intentar validar con BCRYPT
            // El hash de bcrypt empieza por $2a$ o $2b$
            if (usuarioDB.Password.startsWith('$2')) {
                esValida = await bcrypt.compare(pass, usuarioDB.Password);
            } else {
                // 2. Si no es bcrypt, validar con el método antiguo (SHA1/SHA256)
                const algoritmo = usuarioDB.Password.length === 64 ? 'sha256' : 'sha1';
                const hashIngresado = crypto.createHash(algoritmo).update(pass).digest('hex');
                
                if (hashIngresado === usuarioDB.Password) {
                    esValida = true;
                    // --- MIGRACIÓN AUTOMÁTICA ---
                    // Como la contraseña antigua era válida, la convertimos a BCRYPT ahora
                    const nuevoHash = await bcrypt.hash(pass, 10);
                    await db.query('UPDATE usuarios SET Password = ? WHERE ID_Usuario = ?', [nuevoHash, usuarioDB.ID_Usuario]);
                    //console.log(`Usuario ${user} migrado a bcrypt exitosamente.`);
                }
            }
            
            if (esValida) {
                req.session.usuario = usuarioDB;
                switch (usuarioDB.Perfil) {
                    case 'administrador': return res.redirect('/administrador');
                    case 'dueno': return res.redirect('/dueno');
                    case 'vendedor': return res.redirect('/vendedor');
                    case 'cliente': return res.redirect('/cliente');
                    default: return res.send('Perfil no reconocido');
                }
            } else {
                return res.send('Contraseña incorrecta');
            }
        } catch (error) {
            console.error(error);
            res.status(500).send('Error en el sistema');
        }
    },
cerrarSesion: (req, res) => {
        req.session.destroy((err) => {
            if (err) {
                console.error("Error al destruir sesión:", err);
                return res.redirect('/' + (req.session.usuario ? req.session.usuario.Perfil : ''));
            }
            res.clearCookie('connect.sid');
            res.redirect('/');
        });
    }
};

module.exports = gestorLogin;