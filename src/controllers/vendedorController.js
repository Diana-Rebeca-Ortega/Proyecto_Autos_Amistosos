const db = require('../models/db');

const vendedorController = {
    mostrarVentas: async (req, res) => {
        try {
            res.render('Vistas_Vendedores/panel_vendedorAuto', {
                usuario: req.session.usuario // <- Para que diga "Hola, Ana" arriba
            
            });
        } catch (error) {
            console.error(error);
            res.status(500).send('Error al cargar el panel de ventas');
        }
    }
};

module.exports = vendedorController;