// Sistema de autenticación y login

const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');

const SECRET_KEY = process.env.SECRET_KEY || 'tu_clave_secreta_aqui';

const login = async (db, username, password) => {
  try {
    const [usuarios] = await db.execute(
      `SELECT * FROM usuarios WHERE username = ? AND activo = true`,
      [username]
    );

    if (usuarios.length === 0) {
      return { exito: false, mensaje: "Usuario no encontrado" };
    }

    const usuario = usuarios[0];
    const passwordValida = await bcrypt.compare(password, usuario.password);

    if (!passwordValida) {
      return { exito: false, mensaje: "Contraseña incorrecta" };
    }

    // Generar token JWT
    const token = jwt.sign(
      { id: usuario.id, username: usuario.username, rol: usuario.rol },
      SECRET_KEY,
      { expiresIn: '24h' }
    );

    return {
      exito: true,
      mensaje: "Login exitoso",
      usuario: {
        id: usuario.id,
        username: usuario.username,
        nombre: usuario.nombre,
        email: usuario.email,
        rol: usuario.rol
      },
      token
    };

  } catch (error) {
    return { exito: false, mensaje: "Error en el servidor", error };
  }
};

const verificarToken = (token) => {
  try {
    const decoded = jwt.verify(token, SECRET_KEY);
    return { valido: true, datos: decoded };
  } catch (error) {
    return { valido: false, mensaje: "Token inválido o expirado" };
  }
};

module.exports = { login, verificarToken };