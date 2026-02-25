// Archivo principal actualizado

const express = require('express');
const { inicializarCuentas, db } = require('./inicializarCuentas');
const { login, verificarToken } = require('./autenticacion');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());

// Inicializar cuentas cuando inicia el servidor
inicializarCuentas()
  .then(() => {
    console.log("✓ Cuentas inicializadas correctamente");
  })
  .catch(err => {
    console.error("Error al inicializar cuentas:", err);
  });

// Ruta de login
app.post('/api/login', (req, res) => {
  const { username, password } = req.body;
  
  if (!username || !password) {
    return res.json({ exito: false, mensaje: "Usuario y contraseña requeridos" });
  }

  db.get(`SELECT * FROM usuarios WHERE username = ? AND activo = 1`, [username], (err, usuario) => {
    if (err) {
      return res.json({ exito: false, mensaje: "Error en el servidor" });
    }

    if (!usuario) {
      return res.json({ exito: false, mensaje: "Usuario no encontrado" });
    }

    const bcrypt = require('bcrypt');
    const passwordValida = bcrypt.compareSync(password, usuario.password);

    if (!passwordValida) {
      return res.json({ exito: false, mensaje: "Contraseña incorrecta" });
    }

    // Generar token
    const jwt = require('jsonwebtoken');
    const token = jwt.sign(
      { id: usuario.id, username: usuario.username, rol: usuario.rol },
      'tu_clave_secreta',
      { expiresIn: '24h' }
    );

    res.json({
      exito: true,
      mensaje: "Login exitoso",
      token,
      usuario: { id: usuario.id, username: usuario.username, nombre: usuario.nombre, rol: usuario.rol }
    });
  });
});

// Ruta de prueba
app.get('/', (req, res) => {
  res.json({ mensaje: "Servidor corriendo" });
});

app.listen(PORT, () => {
  console.log(`✓ Servidor escuchando en puerto ${PORT}`);
});