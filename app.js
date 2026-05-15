const express = require('express');
const session = require('express-session');
require('dotenv').config();//sirve para cargar variables de entorno

const app = express();
// Configuración de Sesiones
app.use(session({
    secret: 'clave_secreta_autos_amistosos',
    resave: false,
    saveUninitialized: false,
    cookie: {
        //Terminación de sesión por tiempo 10 min de espera antes de caducarse
        maxAge: 600000,
        secure: false
    }
}));
// Para poder leer datos de formularios (POST)
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

//RUTAS
app.use('/', require('./src/routes/empleadoRoutes'));
app.get('/', (req, res) => {
    res.send('<h1>Bienvenido a Autos Amistosos</h1><p>Sesiones configuradas.</p>');
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Servidor listo en http://localhost:${PORT}`);
});