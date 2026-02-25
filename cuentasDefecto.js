// Cuentas predefinidas de administrador y empleados

const cuentasDefecto = {
  administradores: [
    {
      id: 1,
      username: "admin",
      password: "admin123", // En producción usar hash
      email: "admin@imjcrea.com",
      nombre: "Administrador",
      rol: "admin",
      activo: true
    }
  ],
  empleados: [
    {
      id: 2,
      username: "empleado1",
      password: "empleado123",
      email: "empleado1@imjcrea.com",
      nombre: "Juan Pérez",
      rol: "empleado",
      activo: true
    },
    {
      id: 3,
      username: "empleado2",
      password: "empleado123",
      email: "empleado2@imjcrea.com",
      nombre: "María García",
      rol: "empleado",
      activo: true
    }
  ]
};

module.exports = cuentasDefecto;