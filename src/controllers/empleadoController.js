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

            // Principio de seguridad: Consultas preparadas usando placeholders (?) evitan SQL Injection de raíz
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

    }
}

module.exports = empleadoController;