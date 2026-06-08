const db = require('../models/db');

const clienteController = {
    mostrarCarros: async (req, res) => {
        try {
            // Consulta a la tabla 'automovil' filtrando los disponibles
            const [carros] = await db.query("SELECT * FROM automovil WHERE Estado = 'DISPONIBLE'");

            res.render('Vistas_Cliente/panel_cliente', {
                usuario: req.session.usuario,
                listaCarros: carros
            });
        } catch (error) {
            console.error(error);
            res.status(500).send('Error al cargar el inventario');
        }
    },
explorarAutos: async (req, res) => {
    try {
        const [carros] = await db.query("SELECT * FROM automovil WHERE Estado = 'DISPONIBLE'");        
        res.render('Vistas_Cliente/explorar_autos', { 
            usuario: req.session.usuario,
            listaCarros: carros
        });
    } catch (error) {
        console.error(error);
        res.status(500).send('Error al cargar catálogo');
    }
}

};

module.exports = clienteController;