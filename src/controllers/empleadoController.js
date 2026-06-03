const db = require('../models/db');
const empleadoController = {
    listarEmpleados: async (req, res) => {
        try {
            
        
    const [rows] = await db.query('SELECT idVendedor, Nombre, Apellido1, Salario_Base FROM vendedor');
            res.render('vendedores', { vendedores: rows });
        } catch (error) {
            console.error(error);
            res.status(500).send('Earror al obtener los datos');
        }
    },

    crearEmpleado: async (req, res) => {
        try {
            const { Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comision } = req.body;

        // --- VALIDACIÓN DE BACKEND ---
        const salario = parseFloat(Salario_Base);
        if (isNaN(salario) || salario <= 0) {
            req.session.errorMessage = "El salario debe ser un número mayor a 0.";
            return res.redirect('/empleados');
        }
        // -----------------------------

            const querySQL = `
                INSERT INTO vendedor (Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comision)
                VALUES (?, ?, ?, ?, ?)
            `;

            await db.query(querySQL, [Nombre, Apellido1, Apellido2 || null, Salario_Base, Porcentaje_Comision]);

            // Guardamos mensaje de éxito en la sesión antes de redireccionar
            req.session.successMessage = "¡Vendedor registrado exitosamente!";
            res.redirect('/empleados');

        } catch (error) {
            console.error(error);
            res.status(500).send('Error crítico al dar de alta al vendedor');
        }
    },

    verDetalle: async (req, res) => {
        try {
            const id = req.params.idVendedor;
            const [rows] = await db.query('SELECT * FROM vendedor WHERE idVendedor = ?', [id]);
            
            if (rows.length === 0) {
                return res.status(404).send('Vendedor no encontrado');
            }
            res.render('Vistas_Vendedores/detalleVendedor', { vendedor: rows[0] });
        } catch (error) {
            console.error(error);
            res.status(500).send('Error al obtener el detalle');
        }
    },
editarFormulario: async (req, res) => {
    try {
        const { idVendedor } = req.params;
        const [rows] = await db.query('SELECT * FROM vendedor WHERE idVendedor = ?', [idVendedor]);
        
        if (rows.length === 0) return res.status(404).send('Vendedor no encontrado');
        // Renderizas la vista de formulario pasando los datos del vendedor
        res.render('Vistas_Vendedores/formEditarVendedor', { vendedor: rows[0] });
    } catch (error) {
        console.error(error);
        res.status(500).send('Error al cargar el formulario');
    }
},

actualizarEmpleado: async (req, res) => {
    try {
        const { idVendedor } = req.params;
        const { Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comision } = req.body;
        
        const sql = `UPDATE vendedor SET Nombre = ?, Apellido1 = ?, Apellido2 = ?, Salario_Base = ?, Porcentaje_Comision = ? WHERE idVendedor = ?`;
        await db.query(sql, [Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comision, idVendedor]);
        
        req.session.successMessage = "¡Vendedor actualizado!";
        res.redirect('/empleados');
    } catch (error) {
        console.error(error);
        res.status(500).send('Error al actualizar');
    }
},

eliminarEmpleado: async (req, res) => {
    try {
        const { idVendedor } = req.params;
        await db.query('DELETE FROM vendedor WHERE idVendedor = ?', [idVendedor]);
        
        req.session.successMessage = "¡Vendedor eliminado correctamente!";
        res.redirect('/empleados');
    } catch (error) {
        console.error(error);
        res.status(500).send('Error al eliminar al vendedor');
    }
}

};

module.exports = empleadoController;