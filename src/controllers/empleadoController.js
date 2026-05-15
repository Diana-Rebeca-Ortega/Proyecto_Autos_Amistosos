const db = require('../models/db');

const empleadoController = {
    listarEmpleados: async (req, res) => {
        try {
            
        
const [rows] = await db.query('SELECT idVendedor, Nombre, Apellido1, Salario_Base FROM vendedor');
            res.json(rows);
        } catch (error) {
            console.error(error);
            res.status(500).send('Earror al obtener los datos');
        }
    }
};

module.exports = empleadoController;