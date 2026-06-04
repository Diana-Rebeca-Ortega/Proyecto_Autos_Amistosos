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
    },
listarVentas: async (req, res) => {
    try {
        const sql = `
    SELECT v.idVenta, 
           DATE_FORMAT(v.Fecha_Venta, '%d/%m/%Y') AS Fecha_Venta, 
           v.Precio_Final, 
           v.Impuesto_Venta, 
           c.Nombre AS Cliente, 
           a.Modelo AS Automovil
    FROM Venta v
    JOIN Cliente c ON v.Cliente_idCliente = c.idCliente
    JOIN automovil a ON v.idAutomovil = a.idAutomovil
`;
        const [ventas] = await db.query(sql);
        const [clientes] = await db.query('SELECT idCliente, Nombre FROM Cliente');
        const [autos] = await db.query('SELECT idAutomovil, Modelo, Color FROM automovil WHERE Estado = "DISPONIBLE"');
        
        res.render('Vistas_Vendedores/listaVentas', {
            ventas,
            clientes,
            autos,
            usuario: req.session.usuario
        });
    } catch (error) {
        console.error("DETALLE DEL ERROR:", error);
        res.status(500).send('Error al cargar ventas: ' + error.message);
    }
},
   
mostrarFormularioVenta: async (req, res) => {
    try {
        const sql = `SELECT v.idVenta, v.Fecha_Venta, v.Precio_Final, c.Nombre as Cliente, ven.Nombre as Vendedor
                FROM Venta v
                 JOIN Cliente c ON v.Cliente_idCliente = c.idCliente
                 JOIN Vendedor ven ON v.Vendedor_idVendedor = ven.idVendedor`;
        const [rows] = await db.query(sql);
        res.render('Vistas_Vendedores/listarVentas', {
            ventas: rows,
            usuario: req.session.usuario
        });
    } catch (error) {
        console.error(error);
        res.status(500).send('Error al cargar la página');
    }
},
editarVentaForm: async (req, res) => {
    // ...
},
registrarVenta: async (req, res) => {
    console.log("CONTENIDO DE LA SESIÓN EN EL MOMENTO DE LA VENTA:", req.session.usuario);
    const { 
        Fecha_Venta, 
        Precio_Final, 
        Impuesto_Venta, 
        Costo_Licencia, 
        Cliente_idCliente, 
        idAutomovil 
    } = req.body;
    
   const idVendedor = req.session.usuario.Vendedor_idVendedor;
    try {
        const sql = `INSERT INTO Venta
                     (Fecha_Venta, Precio_Final, Impuesto_Venta, Costo_Licencia, Vendedor_idVendedor, Cliente_idCliente, idAutomovil) 
                     VALUES (?, ?, ?, ?, 3, ?, ?)`;
       await db.query(sql, [
            Fecha_Venta,     
            Precio_Final,     
            Impuesto_Venta,     
            Costo_Licencia,     
            Cliente_idCliente,  
            idAutomovil        
        ]);

        req.session.successMessage = "¡Venta registrada exitosamente!";
        res.redirect('/vendedor/ventas');
    } catch (error) {
        console.error("Error al registrar venta:", error);
        req.session.errorMessage = "Error: " + error.message;
        res.redirect('/vendedor/ventas');
    }
},
actualizarVenta: async (req, res) => {
        const { id } = req.params; // El ID viene de la URL: /vendedor/ventas/editar/:id
        const { cliente_id, fecha_venta, precio_final } = req.body;

        try {
            // Validamos que los datos básicos existan
            if (!cliente_id || !fecha_venta || !precio_final) {
                console.error("Faltan datos obligatorios para actualizar");
                return res.redirect(`/vendedor/ventas/editar/${id}`);
            }

            // Realizamos el UPDATE en la base de datos
            const sql = `UPDATE Venta 
                         SET Cliente_idCliente = ?, 
                             Fecha_Venta = ?, 
                             Precio_Final = ? 
                         WHERE idVenta = ?`;
            
            await db.query(sql, [cliente_id, fecha_venta, precio_final, id]);

            // Redireccionamos con un mensaje de éxito
            req.session.successMessage = "Venta actualizada correctamente.";
            res.redirect('/vendedor/ventas');

        } catch (error) {
            console.error("Error al actualizar venta:", error);
            req.session.errorMessage = "Hubo un error al actualizar la venta.";
            res.redirect(`/vendedor/ventas/editar/${id}`);
        }
    },
    eliminarVenta: async (req, res) => {
        const { id } = req.params; // Capturamos el ID de la URL

        try {
            // Ejecutamos la eliminación
            const sql = `DELETE FROM Venta WHERE idVenta = ?`;
            await db.query(sql, [id]);

            // Mensaje de confirmación
            req.session.successMessage = "Venta eliminada correctamente.";
            res.redirect('/vendedor/ventas');

        } catch (error) {
            console.error("Error al eliminar venta:", error);
            req.session.errorMessage = "Hubo un error al intentar eliminar la venta.";
            res.redirect('/vendedor/ventas');
        }
    }
};
console.log("DEBUG: Keys en vendedorController:", Object.keys(vendedorController));
module.exports = vendedorController;