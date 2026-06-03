const express = require('express');
const session = require('express-session');
const path = require('path');
require('dotenv').config();//sirve para cargar variables de entorno
const app = express();

// 1. CONFIGURACIONES (Motores y Middlewares)
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'src/views'));

// Configuración de Sesiones
app.use(session({
    secret: 'clave_secreta_autos_amistosos',
    resave: false,
    saveUninitialized: true,
    cookie: {
        //Terminación de sesión por tiempo 10 min de espera antes de caducarse
        maxAge: 600000,
        secure: false
    }
}));
// Para poder leer datos de formularios (POST)
app.use(express.urlencoded({ extended: true }));
app.use(express.json());
app.use(express.static(path.join(__dirname, 'public')));
app.use((req, res, next) => {
    res.locals.alertAlert = req.session.successMessage || null;
    res.locals.errorMessage = req.session.errorMessage || null;
    req.session.successMessage = null;
    req.session.errorMessage = null;
    next();
});
//RUTAS
app.get('/', (req, res) => {
    res.render('index');
});
app.get('/login', (req, res) => {
    res.render('login');
});
app.use('/empleados', require('./src/routes/empleadoRoutes'));

app.use((req, res, next) => {
    res.status(404).render('404');
});

//puerto adaptado para produccion
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Servidor listo en http://localhost:${PORT}`);
});