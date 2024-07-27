-- Crear rol Administrador con permisos completos
CREATE ROLE Administrador;
-- Crear rol Usuarios con permisos limitados
CREATE ROLE Usuarios;

-- Crear rol empleado con permisos limitados
CREATE ROLE empleados;

-- Crear usuario y asignar rol admin
CREATE USER admin1 WITH PASSWORD 'admin';
GRANT Administrador TO admin1;

GRANT CONNECT ON DATABASE Concesionario_Tractores TO admin1;




-- Crear usuario y asignar rol Usuarios
CREATE USER usuario1 WITH PASSWORD 'usuario';
GRANT Usuarios TO usuario1;

-- Crear usuario y asignar rol empleado
CREATE USER empleado1 WITH PASSWORD 'empleado';
GRANT empleados TO empleado1;




