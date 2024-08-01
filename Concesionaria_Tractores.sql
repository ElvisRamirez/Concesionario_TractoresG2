USE DATABASE  Concesionario_Tractores


-- Ahora crea las tablas y los índices en la nueva base de datos

CREATE TABLE Clientes (
    ClienteID SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL,
    Apellido VARCHAR(50) NOT NULL,
    Cedula VARCHAR(20) UNIQUE NOT NULL,
    Dirección VARCHAR(100),
    Teléfono VARCHAR(20) NOT NULL,
    Email VARCHAR(50) UNIQUE NOT NULL
);



CREATE INDEX idx_clientes_nombre ON Clientes(Nombre);
CREATE INDEX idx_clientes_apellido ON Clientes(Apellido);
CREATE INDEX idx_clientes_email ON Clientes(Email);

CREATE TABLE Empleados (
    EmpleadoID SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL,
    Apellido VARCHAR(50) NOT NULL,
    Puesto VARCHAR(50) NOT NULL,
    Cedula VARCHAR(20) UNIQUE NOT NULL,  -- Añadido el campo Cedula
    Teléfono VARCHAR(20) NOT NULL,
    Email VARCHAR(50) UNIQUE NOT NULL
);


CREATE INDEX idx_empleados_puesto ON Empleados(Puesto);
CREATE INDEX idx_empleados_email ON Empleados(Email);

CREATE TABLE Proveedores (
    ProveedorID SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL,
    Dirección VARCHAR(100),
    Teléfono VARCHAR(20) NOT NULL,
    Email VARCHAR(50) UNIQUE NOT NULL
);

CREATE INDEX idx_proveedores_nombre ON Proveedores(Nombre);
CREATE INDEX idx_proveedores_email ON Proveedores(Email);

CREATE TABLE ModelosTractores (
    ModeloID SERIAL PRIMARY KEY,
    Marca VARCHAR(50) NOT NULL,
    Modelo VARCHAR(50) NOT NULL,
    Descripcion TEXT
);

CREATE INDEX idx_modelos_tractores_marca ON ModelosTractores(Marca);

CREATE TABLE Tractores (
    TractorID SERIAL PRIMARY KEY,
    ModeloID INT NOT NULL,
    Imagen BYTEA,
    Año INT NOT NULL CHECK (Año > 1900 AND Año <= EXTRACT(YEAR FROM CURRENT_DATE)),
    Estado VARCHAR(20) NOT NULL CHECK (Estado IN ('disponible', 'vendido', 'alquilado')),
    FOREIGN KEY (ModeloID) REFERENCES ModelosTractores(ModeloID)
);

CREATE INDEX idx_tractores_estado ON Tractores(Estado);

CREATE TABLE Ventas (
    VentaID SERIAL PRIMARY KEY,
    ClienteID INT NOT NULL,
    EmpleadoID INT NOT NULL,
    FechaVenta DATE NOT NULL,
    TotalVenta DECIMAL(10, 2) NOT NULL CHECK (TotalVenta >= 0),
    FOREIGN KEY (ClienteID) REFERENCES Clientes(ClienteID),
    FOREIGN KEY (EmpleadoID) REFERENCES Empleados(EmpleadoID)
);

CREATE INDEX idx_ventas_cliente_id ON Ventas(ClienteID);
CREATE INDEX idx_ventas_empleado_id ON Ventas(EmpleadoID);

CREATE TABLE DetallesVenta (
    DetalleVentaID SERIAL PRIMARY KEY,
    VentaID INT NOT NULL,
    TractorID INT NOT NULL,
    PrecioUnitario DECIMAL(10, 2) NOT NULL CHECK (PrecioUnitario >= 0),
    Cantidad INT NOT NULL CHECK (Cantidad > 0),
    FOREIGN KEY (VentaID) REFERENCES Ventas(VentaID),
    FOREIGN KEY (TractorID) REFERENCES Tractores(TractorID)
);




CREATE INDEX idx_detalles_venta_venta_id ON DetallesVenta(VentaID);
CREATE INDEX idx_detalles_venta_tractor_id ON DetallesVenta(TractorID);

CREATE TABLE Alquileres (
    AlquilerID SERIAL PRIMARY KEY,
    ClienteID INT NOT NULL,
    EmpleadoID INT NOT NULL,
    FechaInicio DATE NOT NULL,
    FechaFin DATE NOT NULL CHECK (FechaFin > FechaInicio),
    TotalAlquiler DECIMAL(10, 2) NOT NULL CHECK (TotalAlquiler >= 0),
    FOREIGN KEY (ClienteID) REFERENCES Clientes(ClienteID),
    FOREIGN KEY (EmpleadoID) REFERENCES Empleados(EmpleadoID)
);

CREATE INDEX idx_alquileres_cliente_id ON Alquileres(ClienteID);
CREATE INDEX idx_alquileres_empleado_id ON Alquileres(EmpleadoID);

CREATE TABLE DetallesAlquiler (
    DetalleAlquilerID SERIAL PRIMARY KEY,
    AlquilerID INT NOT NULL,
    TractorID INT NOT NULL,
    PrecioUnitario DECIMAL(10, 2) NOT NULL CHECK (PrecioUnitario >= 0),
    Cantidad INT NOT NULL CHECK (Cantidad > 0),
    FOREIGN KEY (AlquilerID) REFERENCES Alquileres(AlquilerID),
    FOREIGN KEY (TractorID) REFERENCES Tractores(TractorID)
);


CREATE INDEX idx_detalles_alquiler_alquiler_id ON DetallesAlquiler(AlquilerID);
CREATE INDEX idx_detalles_alquiler_tractor_id ON DetallesAlquiler(TractorID);



CREATE TABLE Facturas (
    FacturaID SERIAL PRIMARY KEY,
    ClienteID INT NOT NULL,
    EmpleadoID INT NOT NULL,
    FechaFactura DATE NOT NULL,
    TotalFactura DECIMAL(10, 2) NOT NULL CHECK (TotalFactura >= 0),
    FOREIGN KEY (ClienteID) REFERENCES Clientes(ClienteID),
    FOREIGN KEY (EmpleadoID) REFERENCES Empleados(EmpleadoID)
)


-- ///AQUI REVISA AS EL PASO A PASO
	ALTER TABLE Facturas RENAME TO Facturas_old;
    -- CREAS DE NUEVO LA TABLA
CREATE TABLE Facturas (
    FacturaID SERIAL,
    ClienteID INT NOT NULL,
    EmpleadoID INT NOT NULL,
    FechaFactura DATE NOT NULL,
    TotalFactura DECIMAL(10, 2) NOT NULL CHECK (TotalFactura >= 0),
    FOREIGN KEY (ClienteID) REFERENCES Clientes(ClienteID),
    FOREIGN KEY (EmpleadoID) REFERENCES Empleados(EmpleadoID),
    PRIMARY KEY (FacturaID, FechaFactura)
) PARTITION BY RANGE (FechaFactura);
-- Eliminar las foreign keys
ALTER TABLE DetallesFactura DROP CONSTRAINT detallesfactura_facturaid_fkey;
ALTER TABLE Pagos DROP CONSTRAINT pagos_facturaid_fkey;

-- Eliminar las vistas DESPUES DE LO DEMAS LAS VUELVES A CREAR 
DROP VIEW IF EXISTS VistaFacturasDetalles;
DROP VIEW IF EXISTS Vista_Pagos_Con_Detalles;
-- CREAS LAS DEMAS TABLAS 
CREATE TABLE Facturas_2024 PARTITION OF Facturas
    FOR VALUES FROM ('2024-01-01') TO ('2025-01-01');
CREATE TABLE Facturas_2025 PARTITION OF Facturas
    FOR VALUES FROM ('2025-01-01') TO ('2026-01-01');

CREATE TABLE Facturas_2026 PARTITION OF Facturas
    FOR VALUES FROM ('2026-01-01') TO ('2027-01-01');

CREATE TABLE Facturas_default PARTITION OF Facturas
    DEFAULT;
-- INSERTAS DE NUEVO
INSERT INTO Facturas SELECT * FROM Facturas_old;

-- Y ESTO ES PARA 
SELECT MIN(FechaFactura), MAX(FechaFactura) FROM Facturas_old;



-- Recrear los índices
CREATE INDEX idx_facturas_cliente_id ON Facturas(ClienteID);
CREATE INDEX idx_facturas_empleado_id ON Facturas(EmpleadoID);
-- Actualizar la secuencia
SELECT setval(pg_get_serial_sequence('Facturas', 'FacturaID'), 
              (SELECT MAX(FacturaID) FROM Facturas));
-- Recrear las foreign keys
ALTER TABLE DetallesFactura 
ADD CONSTRAINT detallesfactura_facturaid_fkey 
FOREIGN KEY (FacturaID) REFERENCES Facturas(FacturaID);

ALTER TABLE Pagos 
ADD CONSTRAINT pagos_facturaid_fkey 
FOREIGN KEY (FacturaID) REFERENCES Facturas(FacturaID);


-- ELIMINAS LA OTRA
DROP TABLE Facturas_old;


CREATE TABLE DetallesFactura (
    DetalleFacturaID SERIAL PRIMARY KEY,
    FacturaID INT NOT NULL,
    Descripcion TEXT NOT NULL,
    PrecioUnitario DECIMAL(10, 2) NOT NULL CHECK (PrecioUnitario >= 0),
    Cantidad INT NOT NULL CHECK (Cantidad > 0),
    FOREIGN KEY (FacturaID) REFERENCES Facturas(FacturaID)
);




CREATE INDEX idx_detalles_factura_factura_id ON DetallesFactura(FacturaID);
select * from Pagos
CREATE TABLE Pagos (
    PagoID SERIAL PRIMARY KEY,
    FacturaID INT NOT NULL,
	FormaPago VARCHAR(10) NOT NULL,
    FechaPago DATE NOT NULL,
    MontoPago DECIMAL(10, 2) NOT NULL CHECK (MontoPago >= 0),
    FOREIGN KEY (FacturaID) REFERENCES Facturas(FacturaID)
);

CREATE INDEX idx_pagos_factura_id ON Pagos(FacturaID);

CREATE TABLE Inventario (
    InventarioID SERIAL PRIMARY KEY,
    TractorID INT NOT NULL,
    ProveedorID INT NOT NULL,
    FechaIngreso DATE NOT NULL,
    Cantidad INT NOT NULL CHECK (Cantidad > 0),
    PrecioCompra DECIMAL(10, 2) NOT NULL CHECK (PrecioCompra >= 0),
	 PrecioUnitario DECIMAL(10, 2) NOT NULL CHECK (PrecioUnitario >= 0),
    FOREIGN KEY (TractorID) REFERENCES Tractores(TractorID),
    FOREIGN KEY (ProveedorID) REFERENCES Proveedores(ProveedorID)
);


CREATE INDEX idx_inventario_tractor_id ON Inventario(TractorID);
CREATE INDEX idx_inventario_proveedor_id ON Inventario(ProveedorID);

select * from Facturas_2025