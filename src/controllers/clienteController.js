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
},
favoritos: async (req, res) => {
    try {
        let idAutomovil = req.body.idAutomovil.toString().trim();
        while (idAutomovil.length < 17) {
            idAutomovil += ' ';
        }
        const idUsuario = req.session.usuario.ID_Usuario;

        console.log("Insertando:", { idUsuario, idAutomovil });

        const [resultado] = await db.query(
            "INSERT INTO Favoritos (id_usuario, id_automovil) VALUES (?, ?)", 
            [idUsuario, idAutomovil]
        );
        
        res.send('Auto añadido a favoritos');
    } catch (error) {
        console.error("Error al guardar:", error);
        res.status(500).send('Error al gestionar favoritos');
    }
}, 

mostrarFavoritos: async (req, res) => {
    try {
        const idUsuario = req.session.usuario.ID_Usuario;
        
        // Hacemos un JOIN para traer la info del auto según los favoritos del usuario
        const [favoritos] = await db.query(`
            SELECT a.* FROM automovil a
            JOIN Favoritos f ON a.idAutomovil = f.id_automovil
            WHERE f.id_usuario = ?`, [idUsuario]);

        res.render('Vistas_Cliente/favoritos', {
            usuario: req.session.usuario,
            listaFavoritos: favoritos
        });
    } catch (error) {
        console.error(error);
        res.status(500).send('Error al cargar favoritos');
    }
}

};

module.exports = clienteController;