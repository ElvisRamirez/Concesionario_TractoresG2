-- Crear rol Administrador con permisos completos
CREATE ROLE Administrador;
-- Crear rol Usuarios con permisos limitados
CREATE ROLE Usuarios;

-- Crear rol empleado con permisos limitados
CREATE ROLE empleados;
GRANT SELECT ON  ultimos_tractores_disponibles TO empleados;
-- Crear usuario y asignar rol admin
CREATE USER admin1 WITH PASSWORD 'admin';
GRANT Administrador TO admin1;

GRANT CONNECT ON DATABASE Concesionario_Tractores TO admin1;




-- Crear usuario y asignar rol Usuarios
CREATE USER usuario1 WITH PASSWORD 'usuario';
GRANT Usuarios TO usuario1;
GRANT SELECT ON ultimos_tractores_disponibles TO Usuarios;

-- Crear usuario y asignar rol empleado
CREATE USER empleado1 WITH PASSWORD 'empleado';
GRANT empleados TO empleado1;


GRANT USAGE ON SCHEMA public TO Administrador;
GRANT USAGE ON SCHEMA public TO Usuarios;
GRANT USAGE ON SCHEMA public TO empleados;



--USUARIOS
GRANT SELECT ON  ultimos_tractores_disponibles TO Usuarios;
-- Revocar permisos de lectura en la tabla Clientes
REVOKE SELECT ON TABLE Clientes FROM Usuarios;
-- Revocar permisos de inserción, actualización y eliminación en la tabla Clientes
REVOKE INSERT, UPDATE, DELETE ON TABLE Clientes FROM Usuarios;


-- Revocar permisos de lectura en las tablas
REVOKE SELECT ON TABLE Clientes FROM Usuarios;
REVOKE SELECT ON TABLE Empleados FROM Usuarios;
REVOKE SELECT ON TABLE Proveedores FROM Usuarios;
REVOKE SELECT ON TABLE ModelosTractores FROM Usuarios;
REVOKE SELECT ON TABLE Tractores FROM Usuarios;
REVOKE SELECT ON TABLE Ventas FROM Usuarios;
REVOKE SELECT ON TABLE DetallesVenta FROM Usuarios;
REVOKE SELECT ON TABLE Alquileres FROM Usuarios;
REVOKE SELECT ON TABLE DetallesAlquiler FROM Usuarios;
REVOKE SELECT ON TABLE Facturas FROM Usuarios;
REVOKE SELECT ON TABLE DetallesFactura FROM Usuarios;
REVOKE SELECT ON TABLE Pagos FROM Usuarios;
REVOKE SELECT ON TABLE Inventario FROM Usuarios;
-- Revocar permisos de escritura (INSERT, UPDATE, DELETE) en las tablas
REVOKE INSERT, UPDATE, DELETE ON TABLE Clientes FROM Usuarios;
REVOKE INSERT, UPDATE, DELETE ON TABLE Empleados FROM Usuarios;
REVOKE INSERT, UPDATE, DELETE ON TABLE Proveedores FROM Usuarios;
REVOKE INSERT, UPDATE, DELETE ON TABLE ModelosTractores FROM Usuarios;
REVOKE INSERT, UPDATE, DELETE ON TABLE Tractores FROM Usuarios;
REVOKE INSERT, UPDATE, DELETE ON TABLE Ventas FROM Usuarios;
REVOKE INSERT, UPDATE, DELETE ON TABLE DetallesVenta FROM Usuarios;
REVOKE INSERT, UPDATE, DELETE ON TABLE Alquileres FROM Usuarios;
REVOKE INSERT, UPDATE, DELETE ON TABLE DetallesAlquiler FROM Usuarios;
REVOKE INSERT, UPDATE, DELETE ON TABLE Facturas FROM Usuarios;
REVOKE INSERT, UPDATE, DELETE ON TABLE DetallesFactura FROM Usuarios;
REVOKE INSERT, UPDATE, DELETE ON TABLE Pagos FROM Usuarios;
REVOKE INSERT, UPDATE, DELETE ON TABLE Inventario FROM Usuarios;
