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
},
verDetalle: async (req, res) => {
    try {
        const { id } = req.params;
        // Buscamos el auto por su idAutomovil
        const [rows] = await db.query("SELECT * FROM automovil WHERE idAutomovil = ?", [id]);
        
        if (rows.length === 0) return res.status(404).send('Auto no encontrado');
        
        res.render('Vistas_Cliente/detalle_auto', {
            usuario: req.session.usuario,
            auto: rows[0] 
        });
    } catch (error) {
        console.error(error);
        res.status(500).send('Error al cargar detalle');
    }
}

};

module.exports = clienteController;