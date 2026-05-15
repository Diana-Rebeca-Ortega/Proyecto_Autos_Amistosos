const express = require('express');
require('dotenv').config();//sirve para cargar variables de entorno
const app = express();
const PORT = process.env.PORT || 3000;

app.use('/', require('./src/routes/empleadoRoutes'));

app.get('/', (req, res) => {
    res.send('¡Servidor de Node funcionando para el proyecto!');
});

app.listen(PORT, () => {
    console.log(`Servidor listo en http://localhost:${PORT}`);
});