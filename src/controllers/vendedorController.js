const db = require('../models/db');

const vendedorController = {
    mostrarVentas: async (req, res) => {
        try {
            res.render('Vistas_Vendedores/panel_vendedorAuto', {
                usuario: req.session.usuario
            });
        } catch (error) {
            console.error(error);
            res.status(500).send('Error al cargar el panel de ventas');
        }
    },

    listarClientes: async (req, res) => {
        try {
            const [rows] = await db.query('SELECT * FROM Cliente');
            res.render('Vistas_Vendedores/listaClientes', { 
                clientes: rows,
                usuario: req.session.usuario 
            });
        } catch (error) {
            console.error(error);
            res.redirect('/vendedor');
        }
    },

    
    crearCliente: async (req, res) => {
        const { nombre, apellido1, apellido2, direccion, telefono, email } = req.body;

        try {
            if (!nombre || !apellido1 || !telefono) {
                req.session.errorMessage = "Por favor, completa los campos obligatorios.";
                return res.redirect('/vendedor/clientes');
            }

            const sql = `INSERT INTO Cliente (Nombre, Apellido1, Apellido2, Direccion, Telefono, Email) VALUES (?, ?, ?, ?, ?, ?)`;
            await db.query(sql, [nombre, apellido1, apellido2, direccion, telefono, email]);

            req.session.successMessage = "Cliente registrado correctamente.";
            res.redirect('/vendedor/clientes');

        } catch (error) {
            console.error("Error al registrar cliente:", error);
            req.session.errorMessage = "Hubo un error al guardar el cliente.";
            res.redirect('/vendedor/clientes');
        }
    }
};

module.exports = vendedorController;