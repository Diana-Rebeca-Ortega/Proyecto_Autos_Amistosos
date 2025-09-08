// Configuración de la base de datos
const dbConfig = {
  host: 'localhost',
  user: 'root',
  password: '1819diana',
  database: 'usuarios'
};

// Función para conectar a la base de datos
async function conectarDB() {
  try {
    const conn = await mysql.createConnection(dbConfig);
    return conn;
  } catch (error) {
    console.error('Error al conectar a la base de datos:', error);
    return null;
  }
}

// Función para verificar las credenciales del usuario
async function verificarCredenciales(email, password) {
  const conn = await conectarDB();
  if (!conn) return null;

  try {
    const [rows] = await conn.execute('SELECT * FROM usuarios WHERE email = ?', [email]);
    if (rows.length === 0) {
      return null; // Usuario no encontrado
    }

    const row = rows[0];
    const passwordValido = await verificarPassword(password, row.password);
    if (!passwordValido) {
      return null; // Contraseña incorrecta
    }

    // Iniciar sesión
    // Nota: En Node.js, no hay una forma directa de iniciar sesión como en PHP.
    // Puedes utilizar una biblioteca de autenticación como Passport.js para manejar la autenticación.
    return row;
  } catch (error) {
    console.error('Error al verificar las credenciales:', error);
    return null;
  } finally {
    conn.end();
  }
}

// Función para verificar la contraseña
async function verificarPassword(password, hash) {
  // Nota: En este ejemplo, asumimos que la contraseña está almacenada en forma de hash utilizando bcrypt.
  const bcrypt = require('bcrypt');
  return await bcrypt.compare(password, hash);
}

// Ejemplo de uso
const express = require('express');
const app = express();
app.use(express.json());

app.post('/login', async (req, res) => {
  const { email, password } = req.body;
  const usuario = await verificarCredenciales(email, password);
  if (!usuario) {
    res.status(401).send('Credenciales inválidas');
  } else {
    // Iniciar sesión y redirigir a la página de inicio
    // Nota: La forma de iniciar sesión y redirigir varía según la biblioteca de autenticación que utilices.
    res.send('Bienvenido, ' + usuario.nombre);
  }
});

app.listen(3000, () => {
  console.log('Servidor escuchando en el puerto 3000');
});
