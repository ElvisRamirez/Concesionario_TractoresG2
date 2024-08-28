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

-- Revocar el permiso de SELECT en la tabla pagos para el rol Usuarios
REVOKE SELECT ON pagos FROM Usuarios;
-- Revocar el permiso de SELECT en la vista vista_pagos_con_detalles para el rol Usuarios
REVOKE SELECT ON vista_pagos_con_detalles FROM Usuarios;

GRANT SELECT ON  ultimos_tractores_disponibles TO Usuarios;
-- Revocar permisos de lectura en la tabla Clientes
REVOKE SELECT ON TABLE Clientes FROM Usuarios;
-- Revocar permisos de inserción, actualización y eliminación en la tabla Clientes
REVOKE INSERT, UPDATE, DELETE ON TABLE Clientes FROM Usuarios;
-- Revocar permisos del rol `Usuarios` en la vista
REVOKE SELECT ON vista_pagos_con_detalles FROM Usuarios;


-- Crear el rol 'empleados'
CREATE ROLE empleados;

-- Otorgar permisos sobre la tabla Clientes
GRANT SELECT, INSERT, UPDATE, DELETE ON Clientes TO empleados;

-- Otorgar permisos sobre la tabla Tractores
GRANT SELECT, INSERT, UPDATE, DELETE ON Tractores TO empleados;

-- Otorgar permisos sobre la tabla Ventas
GRANT SELECT, INSERT, UPDATE, DELETE ON Ventas TO empleados;

-- Otorgar permisos sobre la tabla DetallesVenta (asociada a Ventas)
GRANT SELECT, INSERT, UPDATE, DELETE ON DetallesVenta TO empleados;
-- Otorgar permisos sobre la tabla DetallesVenta (asociada a Ventas)
GRANT SELECT, INSERT, UPDATE, DELETE ON tractores TO empleados;
GRANT SELECT, INSERT, UPDATE, DELETE ON ModelosTractores TO empleados;
-- Otorgar permisos sobre la tabla Alquileres
GRANT SELECT, INSERT, UPDATE, DELETE ON Alquileres TO empleados;

-- Otorgar permisos sobre la tabla DetallesAlquiler (asociada a Alquileres)
GRANT SELECT, INSERT, UPDATE, DELETE ON DetallesAlquiler TO empleados;

-- Otorgar permisos sobre la tabla Facturas
GRANT SELECT, INSERT, UPDATE, DELETE ON Facturas TO empleados;

-- Otorgar permisos sobre la tabla DetallesFactura (asociada a Facturas)
GRANT SELECT, INSERT, UPDATE, DELETE ON DetallesFactura TO empleados;

-- Otorgar permisos sobre la tabla Pagos (relacionada con Facturas)
GRANT SELECT, INSERT, UPDATE, DELETE ON Pagos TO empleados;
-- Primero, asegúrate de que el rol 'empleados' exista
CREATE ROLE empleados;

-- Concede todos los permisos en el esquema público (o el esquema en el que estás trabajando)
GRANT USAGE ON SCHEMA public TO empleados;

-- Concede todos los permisos en las tablas existentes
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO empleados;

-- Concede permisos para crear nuevas tablas y otros objetos en el esquema
GRANT CREATE ON SCHEMA public TO empleados;

-- Concede todos los permisos en las vistas existentes
GRANT SELECT ON ALL VIEWS IN SCHEMA public TO empleados;
-- Concede permisos en vistas específicas
GRANT SELECT ON VistaTractoresDisponibles TO empleados;
GRANT SELECT ON  VistaFacturasDetalles TO empleados;
GRANT SELECT ON VistaDetallesAlquileres TO empleados;
GRANT SELECT ON ultimos_tractores_disponibles TO empleados;
GRANT SELECT ON vista_inventario TO empleados;
GRANT SELECT ON vista_pagos_con_detalles TO empleados;
-- Establece permisos predeterminados para futuras tablas
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT SELECT, INSERT, UPDATE, DELETE ON TABLES TO empleados;

-- Establece permisos predeterminados para futuras vistas
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT SELECT ON TABLES TO empleados;


-- Si en el futuro se crean nuevas tablas o vistas, se deben ajustar los permisos de manera similar:
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT SELECT, INSERT, UPDATE, DELETE ON TABLES TO empleados;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT SELECT ON VIEWS TO empleados;

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
SELECT datname FROM pg_database WHERE datname = 'concesionario_tractores';

-- Otorgar todos los permisos sobre todas las tablas existentes
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO postgres;

-- Otorgar todos los permisos sobre todas las secuencias existentes
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO postgres;

-- Otorgar todos los permisos sobre todas las funciones existentes
GRANT ALL PRIVILEGES ON ALL FUNCTIONS IN SCHEMA public TO postgres;
-- Otorgar permisos por defecto en tablas futuras
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL PRIVILEGES ON TABLES TO postgres;

-- Otorgar permisos por defecto en secuencias futuras
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL PRIVILEGES ON SEQUENCES TO postgres;

GRANT USAGE, CREATE ON SCHEMA public TO Administrador;




SELECT r.rolname
FROM pg_roles r
JOIN pg_auth_members m ON r.oid = m.roleid
JOIN pg_user u ON u.usesysid = m.member
WHERE u.usename = 'admin1';


GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE Tractores TO admin1;
GRANT SELECT, INSERT, DELETE ON TABLE Tractores TO admin1;
GRANT SELECT, INSERT, DELETE ON TABLE Inventario TO admin1;
GRANT SELECT ON TABLE ModelosTractores TO admin1;
GRANT SELECT ON TABLE Proveedores TO admin1;
GRANT USAGE ON SCHEMA public TO admin1;

-- Conceder permisos de uso de la base de datos
GRANT USAGE ON SCHEMA public TO Administrador;
-- Ver permisos en tablas específicas
SELECT *
FROM pg_table_privileges
WHERE grantee = 'Administrador';

-- Ver roles y usuarios
SELECT * FROM pg_roles;


GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE Tractores TO Administrador;
GRANT SELECT, INSERT, DELETE ON TABLE Tractores TO Administrador;
GRANT SELECT, INSERT, DELETE ON TABLE Inventario TO Administrador;
GRANT SELECT ON TABLE ModelosTractores TO Administrador;
GRANT SELECT ON TABLE Proveedores TO Administrador;
GRANT USAGE ON SCHEMA public TO Administrador;
GRANT SELECT ON ultimos_tractores_disponibles TO Administrador;

-- Conceder permisos de uso de la base de datos
GRANT USAGE ON SCHEMA public TO Administrador;
GRANT SELECT ON VistaTractoresDisponibles TO Administrador;
GRANT SELECT ON VistaFacturasDetalles TO Administrador;
GRANT SELECT ON VistaDetallesAlquileres TO Administrador;
GRANT SELECT ON ultimos_tractores_disponibles TO Administrador;
GRANT SELECT ON vista_inventario TO Administrador;
GRANT SELECT ON vista_pagos_con_detalles TO Administrador;



GRANT ALL PRIVILEGES ON TABLE Clientes TO Administrador;
GRANT ALL PRIVILEGES ON TABLE Empleados TO Administrador;
GRANT ALL PRIVILEGES ON TABLE Proveedores TO Administrador;
GRANT ALL PRIVILEGES ON TABLE ModelosTractores TO Administrador;
GRANT ALL PRIVILEGES ON TABLE Tractores TO Administrador;
GRANT ALL PRIVILEGES ON TABLE Ventas TO Administrador;
GRANT ALL PRIVILEGES ON TABLE DetallesVenta TO Administrador;
GRANT ALL PRIVILEGES ON TABLE Alquileres TO Administrador;
GRANT ALL PRIVILEGES ON TABLE DetallesAlquiler TO Administrador;
GRANT ALL PRIVILEGES ON TABLE Facturas TO Administrador;
GRANT ALL PRIVILEGES ON TABLE DetallesFactura TO Administrador;
GRANT ALL PRIVILEGES ON TABLE Pagos TO Administrador;
GRANT ALL PRIVILEGES ON TABLE Inventario TO Administrador;


-- Permiso de uso del esquema
GRANT USAGE ON SCHEMA public TO Administrador;