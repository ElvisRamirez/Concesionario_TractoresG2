CREATE VIEW VistaTractoresDisponibles AS
SELECT t.TractorID, m.Marca, m.Modelo
FROM Tractores t
INNER JOIN ModelosTractores m ON t.ModeloID = m.ModeloID
WHERE t.Estado = 'disponible';

CREATE VIEW VistaFacturasDetalles AS
SELECT
    f.FacturaID,
    f.FechaFactura,
    c.Nombre AS NombreCliente,
    c.Apellido AS ApellidoCliente,
    e.Nombre AS NombreEmpleado,
    e.Apellido AS ApellidoEmpleado,
    df.Descripcion AS DescripcionDetalle,
    df.PrecioUnitario AS PrecioUnitarioDetalle,
    df.Cantidad AS CantidadDetalle
FROM Facturas f
INNER JOIN Clientes c ON f.ClienteID = c.ClienteID
INNER JOIN Empleados e ON f.EmpleadoID = e.EmpleadoID
INNER JOIN DetallesFactura df ON f.FacturaID = df.FacturaID
ORDER BY f.FacturaID;


CREATE VIEW VistaDetallesAlquileres AS
SELECT
    a.AlquilerID,
    c.Nombre AS Cliente,
    e.Nombre AS Empleado,
    m.Marca,
    m.Modelo,
    da.PrecioUnitario,
    da.Cantidad,
    a.FechaInicio,
    a.FechaFin,
    a.TotalAlquiler
FROM Alquileres a
INNER JOIN Clientes c ON a.ClienteID = c.ClienteID
INNER JOIN Empleados e ON a.EmpleadoID = e.EmpleadoID
INNER JOIN DetallesAlquiler da ON a.AlquilerID = da.AlquilerID
INNER JOIN Tractores t ON da.TractorID = t.TractorID
INNER JOIN ModelosTractores m ON t.ModeloID = m.ModeloID;




CREATE VIEW ultimos_tractores_disponibles AS
SELECT t.Imagen, mt.Marca, mt.Modelo, t.Año, t.Estado, mt.Descripcion AS DescripcionTractor
FROM Tractores t
INNER JOIN ModelosTractores mt ON t.ModeloID = mt.ModeloID
WHERE t.Estado = 'disponible'
ORDER BY t.TractorID DESC
LIMIT 3;



CREATE VIEW vista_inventario AS
SELECT mt.Modelo, p.Nombre AS Proveedor, i.FechaIngreso, i.Cantidad, i.PrecioUnitario, i.PrecioCompra
FROM Inventario i
JOIN Tractores t ON i.TractorID = t.TractorID
JOIN ModelosTractores mt ON t.ModeloID = mt.ModeloID
JOIN Proveedores p ON i.ProveedorID = p.ProveedorID;



CREATE VIEW vista_pagos_con_detalles AS
SELECT
    p.pagoid,
    p.facturaid,
    p.formapago,
    p.fechapago,
    p.montopago,
    f.fechafactura,
    f.totalfactura,
    df.descripcion AS descripciondetalle,
    df.preciounitario AS preciounitariodetalle,
    df.cantidad AS cantidaddetalle
FROM Pagos p
INNER JOIN Facturas f ON p.facturaid = f.facturaid
LEFT JOIN DetallesFactura df ON f.facturaid = df.facturaid;

