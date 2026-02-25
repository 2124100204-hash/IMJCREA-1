// Versión mejorada - Crear cuentas automáticamente

const sqlite3 = require('sqlite3').verbose();
const bcrypt = require('bcrypt');
const path = require('path');

const dbPath = path.join(__dirname, 'usuarios.db');
const db = new sqlite3.Database(dbPath);

const inicializarCuentas = () => {
  return new Promise((resolve, reject) => {
    // Crear tabla
    db.run(`
      CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        nombre TEXT NOT NULL,
        rol TEXT NOT NULL,
        activo INTEGER DEFAULT 1,
        fechaCreacion DATETIME DEFAULT CURRENT_TIMESTAMP
      )
    `, (err) => {
      if (err) {
        console.error("Error creando tabla:", err);
        reject(err);
        return;
      }

      console.log("✓ Tabla de usuarios verificada");

      const cuentas = [
        { username: 'admin', password: 'admin123', email: 'admin@imjcrea.com', nombre: 'Administrador', rol: 'admin' },
        { username: 'empleado1', password: 'empleado123', email: 'empleado1@imjcrea.com', nombre: 'Juan Pérez', rol: 'empleado' },
        { username: 'empleado2', password: 'empleado123', email: 'empleado2@imjcrea.com', nombre: 'María García', rol: 'empleado' }
      ];

      let cuenta_procesada = 0;

      cuentas.forEach(cuenta => {
        // Verificar si ya existe
        db.get(`SELECT * FROM usuarios WHERE username = ?`, [cuenta.username], (err, row) => {
          if (err) {
            console.error("Error verificando usuario:", err);
            return;
          }

          if (!row) {
            // No existe, crear
            const passwordHash = bcrypt.hashSync(cuenta.password, 10);
            
            db.run(
              `INSERT INTO usuarios (username, password, email, nombre, rol) VALUES (?, ?, ?, ?, ?)`,
              [cuenta.username, passwordHash, cuenta.email, cuenta.nombre, cuenta.rol],
              function(err) {
                if (err) {
                  console.error(`Error creando ${cuenta.username}:`, err);
                } else {
                  console.log(`✓ Cuenta creada: ${cuenta.username}`);
                }
                
                cuenta_procesada++;
                if (cuenta_procesada === cuentas.length) {
                  resolve();
                }
              }
            );
          } else {
            console.log(`ℹ Cuenta ya existe: ${cuenta.username}`);
            cuenta_procesada++;
            if (cuenta_procesada === cuentas.length) {
              resolve();
            }
          }
        });
      });
    });
  });
};

module.exports = { inicializarCuentas, db };