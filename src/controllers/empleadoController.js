const db = require('../models/db');

const empleadoController = {
    // 1. Listar empleados
    listarEmpleados: async (req, res) => {
        try {
            const [rows] = await db.query('SELECT idVendedor, Nombre, Apellido1, Salario_Base FROM vendedor');
            
            // Recuperamos mensajes de sesión
            const alertAlert = req.session.successMessage;
            const errorMessage = req.session.errorMessage;
            
            // Limpiamos mensajes tras leerlos
            req.session.successMessage = null;
            req.session.errorMessage = null;

            res.render('Vistas_Vendedores/vendedores', {
                vendedores: rows,
                alertAlert: alertAlert,
                errorMessage: errorMessage
                // Ya no hace falta pasar 'usuario' aquí gracias al middleware
            });
        } catch (error) {
            console.error(error);
            res.status(500).send('Error al obtener los datos');
        }
    },

    // 2. Crear empleado
    crearEmpleado: async (req, res) => {
        try {
            const { Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comision } = req.body;

            const salario = parseFloat(Salario_Base);
            if (isNaN(salario) || salario <= 0) {
                req.session.errorMessage = "El salario debe ser un número mayor a 0.";
                return res.redirect('/empleados');
            }

            const querySQL = `INSERT INTO vendedor (Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comision) VALUES (?, ?, ?, ?, ?)`;
            await db.query(querySQL, [Nombre, Apellido1, Apellido2 || null, Salario_Base, Porcentaje_Comision]);

            req.session.successMessage = "¡Vendedor registrado exitosamente!";
            res.redirect('/empleados');
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
            
            res.render('Vistas_Vendedores/detalleVendedor', { vendedor: rows[0] });
        } catch (error) {
            console.error(error);
            res.status(500).send('Error al obtener el detalle');
        }
    },

    // 4. Cargar formulario edición
    editarFormulario: async (req, res) => {
        try {
            const [rows] = await db.query('SELECT * FROM vendedor WHERE idVendedor = ?', [req.params.idVendedor]);
            if (rows.length === 0) return res.status(404).send('Vendedor no encontrado');
            
            res.render('Vistas_Vendedores/formEditarVendedor', { vendedor: rows[0] });
        } catch (error) {
            console.error(error);
            res.status(500).send('Error al cargar el formulario');
        }
    },

    // 5. Actualizar empleado
    actualizarEmpleado: async (req, res) => {
        try {
            const { Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comision } = req.body;
            const { idVendedor } = req.params;
            
            const salario = parseFloat(Salario_Base);
            if (isNaN(salario) || salario <= 0) {
                req.session.errorMessage = "El salario debe ser un número mayor a 0.";
                return res.redirect('/empleados');
            }

            const sql = `UPDATE vendedor SET Nombre = ?, Apellido1 = ?, Apellido2 = ?, Salario_Base = ?, Porcentaje_Comision = ? WHERE idVendedor = ?`;
            await db.query(sql, [Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comision, idVendedor]);
            
            req.session.successMessage = "¡Vendedor actualizado!";
            res.redirect('/empleados');
        } catch (error) {
            console.error(error);
            res.status(500).send('Error al actualizar');
        }
    },

    // 6. Eliminar empleado
    eliminarEmpleado: async (req, res) => {
        try {
            await db.query('DELETE FROM vendedor WHERE idVendedor = ?', [req.params.idVendedor]);
            req.session.successMessage = "¡Vendedor eliminado correctamente!";
            res.redirect('/empleados');
        } catch (error) {
            console.error(error);
            res.status(500).send('Error al eliminar al vendedor');
        }
    }
};

module.exports = empleadoController;