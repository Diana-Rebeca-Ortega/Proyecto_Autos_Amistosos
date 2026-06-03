const db = require('../models/db');

const administradorController = {
    // 1. Listar empleados
    listarEmpleados: async (req, res) => {
        try {
            const [rows] = await db.query('SELECT idVendedor, Nombre, Apellido1, Salario_Base FROM vendedor');
            
            // Recuperamos mensajes de sesión para SweetAlert2
            const alertAlert = req.session.successMessage;
            const errorMessage = req.session.errorMessage;
            
            // Limpiamos mensajes tras leerlos para evitar que se repitan al recargar
            req.session.successMessage = null;
            req.session.errorMessage = null;

            // Renderizamos mandando la info de los vendedores Y del usuario firmado
            res.render('Vistas_Administrador/vendedores', {
                vendedores: rows,
                alertAlert: alertAlert,
                errorMessage: errorMessage,
                usuario: req.session.usuario // <- CORREGIDO: Mantiene viva la sesión en la cabecera
            });
        } catch (error) {
            console.error(error);
            res.status(500).send('Error al obtener los datos de la base de datos');
        }
    },

    // 2. Crear empleado (Alta)
    crearEmpleado: async (req, res) => {
        try {
            const { Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comision } = req.body;

            const salario = parseFloat(Salario_Base);
            if (isNaN(salario) || salario <= 0) {
                req.session.errorMessage = "El salario debe ser un número mayor a 0.";
                return res.redirect('/administrador'); // <- CORREGIDO: Ruta actualizada
            }

            const querySQL = `INSERT INTO vendedor (Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comision) VALUES (?, ?, ?, ?, ?)`;
            await db.query(querySQL, [Nombre, Apellido1, Apellido2 || null, Salario_Base, Porcentaje_Comision]);

            req.session.successMessage = "¡Vendedor registrado exitosamente!";
            res.redirect('/administrador'); // <- CORREGIDO: Ruta actualizada
        } catch (error) {
            console.error(error);
            res.status(500).send('Error crítico al dar de alta al vendedor');
        }
    },

    // 3. Ver detalle
    verDetalle: async (req, res) => {
        try {
            const [rows] = await db.query('SELECT * FROM vendedor WHERE idVendedor = ?', [req.params.idVendedor]);
            if (rows.length === 0) return res.status(404).send('Vendedor no encontrado');
            
            res.render('Vistas_Administrador/detalleVendedor', { 
                vendedor: rows[0],
                usuario: req.session.usuario // Mantiene la barra de navegación estable
            });
        } catch (error) {
            console.error(error);
            res.status(500).send('Error al obtener el detalle del vendedor');
        }
    },

    // 4. Cargar formulario edición
    editarFormulario: async (req, res) => {
        try {
            const [rows] = await db.query('SELECT * FROM vendedor WHERE idVendedor = ?', [req.params.idVendedor]);
            if (rows.length === 0) return res.status(404).send('Vendedor no encontrado');
            
            res.render('Vistas_Administrador/formEditarVendedor', { 
                vendedor: rows[0],
                usuario: req.session.usuario // Mantiene la barra de navegación estable
            });
        } catch (error) {
            console.error(error);
            res.status(500).send('Error al cargar el formulario de edición');
        }
    },

    // 5. Actualizar empleado (Cambios)
    actualizarEmpleado: async (req, res) => {
        try {
            const { Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comision } = req.body;
            const { idVendedor } = req.params;
            
            const salario = parseFloat(Salario_Base);
            if (isNaN(salario) || salario <= 0) {
                req.session.errorMessage = "El salario debe ser un número mayor a 0.";
                return res.redirect('/administrador'); // <- CORREGIDO: Ruta actualizada
            }

            const sql = `UPDATE vendedor SET Nombre = ?, Apellido1 = ?, Apellido2 = ?, Salario_Base = ?, Porcentaje_Comision = ? WHERE idVendedor = ?`;
            await db.query(sql, [Nombre, Apellido1, Apellido2 || null, Salario_Base, Porcentaje_Comision, idVendedor]); // <- CORREGIDO: Validación de nulos limpia
            
            req.session.successMessage = "¡Vendedor actualizado exitosamente!";
            res.redirect('/administrador'); // <- CORREGIDO: Ruta actualizada
        } catch (error) {
            console.error(error);
            res.status(500).send('Error al actualizar los datos del vendedor');
        }
    },

    // 6. Eliminar empleado (Bajas)
    eliminarEmpleado: async (req, res) => {
        try {
            await db.query('DELETE FROM vendedor WHERE idVendedor = ?', [req.params.idVendedor]);
            
            req.session.successMessage = "¡Vendedor eliminado correctamente!";
            res.redirect('/administrador'); // <- CORREGIDO: Ruta actualizada
        } catch (error) {
            console.error(error);
            res.status(500).send('Error al eliminar al vendedor de los registros');
        }
    }
};

module.exports = administradorController;