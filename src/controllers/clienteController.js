exports.formularioRegistro = (req, res) => {
    res.render('vistas_cliente/registro'); 
};
const bcrypt = require('bcrypt');
const db = require('../models/db'); // Tu archivo de conexión a la BD

exports.registrar = async (req, res) => {
    const { Nombre, Apellido1, Apellido2, Direccion, Telefono, Email, password } = req.body;
    
    // 1. Preparar datos para la tabla 'usuarios'
    const nombreCompleto = `${Nombre} ${Apellido1} ${Apellido2 || ''}`.trim();
    const usuario = Email.split('@')[0]; // Lo que está antes del @
    const perfil = 'cliente';

    // Obtener una conexión del pool para manejar la transacción
    const connection = await db.getConnection();

    try {
        await connection.beginTransaction(); // INICIA LA TRANSACCIÓN

        // 2. Insertar en tabla 'Cliente'
        const sqlCliente = `INSERT INTO Cliente (Nombre, Apellido1, Apellido2, Direccion, Telefono, Email) 
                            VALUES (?, ?, ?, ?, ?, ?)`;
        await connection.execute(sqlCliente, [Nombre, Apellido1, Apellido2, Direccion, Telefono, Email]);

        // 3. Encriptar contraseña e insertar en tabla 'usuarios'
        const hashedPassword = await bcrypt.hash(password, 10);
        const sqlUsuario = `INSERT INTO usuarios (Nombre, Usuario, Password, Perfil) 
                            VALUES (?, ?, ?, ?)`;
        await connection.execute(sqlUsuario, [nombreCompleto, usuario, hashedPassword, perfil]);

        await connection.commit(); // SI TODO SALIÓ BIEN, GUARDAMOS AMBOS
        
        return res.status(200).json({ success: true, message: 'Registro completo realizado con éxito' });

    } catch (error) {
        await connection.rollback(); // SI ALGO FALLÓ, SE CANCELA TODO
        console.error("Error en la transacción:", error);
        return res.status(500).json({ success: false, message: 'Error al procesar el registro' });
    } finally {
        connection.release(); // LIBERAR CONEXIÓN
    }
};