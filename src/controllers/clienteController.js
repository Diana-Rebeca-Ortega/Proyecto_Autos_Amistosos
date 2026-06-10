const db = require('../models/db');

const clienteController = {
    mostrarCarros: async (req, res) => {
        try {
            // Consulta a la tabla 'automovil' filtrando los disponibles
            const [carros] = await db.query("SELECT * FROM automovil WHERE Estado = 'DISPONIBLE'");

            res.render('vistas_cliente/panel_cliente', {
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
        const idUsuario = req.session.usuario.ID_Usuario;

        // 1. Buscamos el auto
        const [rows] = await db.query("SELECT * FROM automovil WHERE idAutomovil = ?", [id]);
        if (rows.length === 0) return res.status(404).send('Auto no encontrado');

        // 2. Verificamos si este usuario tiene este auto en favoritos
        const [favoritos] = await db.query(
            "SELECT * FROM Favoritos WHERE id_usuario = ? AND id_automovil = ?", 
            [idUsuario, id]
        );

        // 3. Renderizamos pasando el estado 'esFavorito' (true/false)
        res.render('Vistas_Cliente/detalle_auto', {
            usuario: req.session.usuario,
            auto: rows[0],
            esFavorito: favoritos.length > 0 // true si existe en favoritos
        });
    } catch (error) {
        console.error(error);
        res.status(500).send('Error al cargar detalle');
    }
},
favoritos: async (req, res) => {
    try {
        let idAutomovil = req.body.idAutomovil.toString().trim();
        const idUsuario = req.session.usuario.ID_Usuario;

        // 1. Buscamos primero en la tabla usando el ID recortado (trim)
        // Usamos trim() aquí para asegurar que los espacios extra del CHAR(17) no rompan la búsqueda
        const [existe] = await db.query(
            "SELECT * FROM Favoritos WHERE id_usuario = ? AND id_automovil = ?", 
            [idUsuario, idAutomovil]
        );

        console.log("Resultado de búsqueda:", existe);

        if (existe && existe.length > 0) {
            // Si ya existe, DELETE
            await db.query("DELETE FROM Favoritos WHERE id_usuario = ? AND id_automovil = ?", 
            [idUsuario, idAutomovil]);
            
            return res.json({ success: true, accion: 'eliminado' });
        } else {
            // Si NO existe, INSERT
            // Importante: inserta el ID limpio, MySQL se encarga de rellenar con espacios si es CHAR(17)
            await db.query("INSERT INTO Favoritos (id_usuario, id_automovil) VALUES (?, ?)", 
            [idUsuario, idAutomovil]);
            
            return res.json({ success: true, accion: 'agregado' });
        }
    } catch (error) {
        console.error("Error al gestionar favorito:", error);
        return res.json({ success: false, message: 'Error interno.' });
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