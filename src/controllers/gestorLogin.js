const db = require('../models/db');
const crypto = require('crypto'); 

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
        const algoritmo = usuarioDB.Password.length === 64 ? 'sha256' : 'sha1';
        const hashIngresado = crypto.createHash(algoritmo).update(pass).digest('hex');
        
        if (hashIngresado === usuarioDB.Password) {
            req.session.usuario = usuarioDB;

            // --- DIRECCIONAMIENTO INTELIGENTE ---
            console.log("Redireccionando según perfil:", usuarioDB.Perfil);

            switch (usuarioDB.Perfil) {
                case 'administrador':
                    return res.redirect('/empleados');
                case 'dueno':
                    return res.redirect('/dueno');
                case 'vendedor':
                    return res.redirect('/vendedor');
                default:
                    return res.send('Perfil no reconocido');
            }
            // -----------------------------------------------------------

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