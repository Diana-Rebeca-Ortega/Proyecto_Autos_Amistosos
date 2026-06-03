const express = require('express');
const session = require('express-session');
const path = require('path');
require('dotenv').config(); // Cargar variables de entorno

const app = express();

app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'src/views'));

// 2. MIDDLEWARES BÁSICOS Y ARCHIVOS ESTÁTICOS
app.use(express.urlencoded({ extended: true }));
app.use(express.json());
app.use(express.static(path.join(__dirname, 'public')));

// 3. CONFIGURACIÓN DE SESIONES (10 minutos de caducidad)
app.use(session({
    secret: 'clave_secreta_autos_amistosos',
    resave: false,
    saveUninitialized: true,
    cookie: {
        maxAge: 600000,
        secure: false
    }
}));

// 4. MIDDLEWARE GLOBAL (Para inyectar Usuario y Alertas en TODAS las vistas automáticamente)
app.use((req, res, next) => {
    res.locals.usuario = req.session.usuario || null;
    res.locals.alertAlert = req.session.successMessage || null;
    res.locals.errorMessage = req.session.errorMessage || null;
    
    // Limpiamos los mensajes de la sesión para que no se repitan al recargar
    req.session.successMessage = null;
    req.session.errorMessage = null;
    next();
});

// 5. ENRUTAMIENTO 
const authRoutes     = require('./src/routes/authRoutes');
const empleadoRoutes = require('./src/routes/empleadoRoutes');
const duenoRoutes    = require('./src/routes/duenoRoutes');
const vendedorRoutes = require('./src/routes/vendedorRoutes');

// Rutas base
app.get('/', (req, res) => res.render('index'));

// Módulos de rutas del sistema
app.use('/auth', authRoutes);
app.use('/', empleadoRoutes); 
app.use('/', duenoRoutes);
app.use('/', vendedorRoutes);

// 6. MANEJO DE ERROR 404 
app.use((req, res, next) => {
    res.status(404).render('404');
});

// 7. ARRANQUE DEL SERVIDOR
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Servidor listo en http://localhost:${PORT}`);
});