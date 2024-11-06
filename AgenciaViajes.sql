USE AgenciaViajes;
GO

ALTER TABLE Pagos
ALTER COLUMN MetodoPago NVARCHAR(30);
GO

-- Insertar datos en la tabla de usuarios
INSERT INTO Usuarios (Nombre, Email, Telefono, Password) VALUES 
('Carlos P�rez', 'carlos.perez@example.com', '555-1234', 'password123'),
('Mar�a L�pez', 'maria.lopez@example.com', '555-5678', '123456'),
('Luis Mart�nez', 'luis.martinez@example.com', '555-8765', 'qwerty'),
('Ana Garc�a', 'ana.garcia@example.com', '555-4321', 'abc123'),
('Jos� Gonz�lez', 'jose.gonzalez@example.com', '555-9876', 'contrase�a');

-- Insertar datos en la tabla de destinos
INSERT INTO Destinos (Nombre, Pais, Descripcion) VALUES 
('Canc�n', 'M�xico', 'Destino tur�stico en el Caribe Mexicano, ideal para vacaciones de playa.'),
('Patagonia', 'Argentina', 'Regi�n con paisajes naturales impresionantes y actividades de aventura.'),
('Par�s', 'Francia', 'Capital de Francia, famosa por su cultura, arte y gastronom�a.'),
('Nairobi', 'Kenia', 'Lugar ideal para disfrutar de safaris y ver fauna africana en su h�bitat natural.');

-- Insertar datos en la tabla de paquetes
INSERT INTO Paquetes (Nombre, IdDestino, Descripcion, Precio, NumDias, FechaInicio, FechaFin, TipoPaquete) VALUES 
('Paquete Caribe�o', 1, 'Disfruta de las playas paradis�acas y un todo incluido de lujo.', 1200.00, 7, '2024-11-10', '2024-11-17', 'Lujo'),
('Aventura en la Patagonia', 2, 'Explora los glaciares y la naturaleza virgen en un tour de 7 d�as.', 1800.00, 7, '2024-12-01', '2024-12-08', 'Aventura'),
('Descubre Europa', 3, 'Un tour de 10 d�as por las principales ciudades europeas.', 2500.00, 10, '2025-01-15', '2025-01-25', 'Lujo'),
('Safari en Kenia', 4, 'Observa la incre�ble fauna africana en su h�bitat natural.', 3000.00, 7, '2025-02-20', '2025-02-27', 'Aventura');

-- Insertar datos en la tabla de reservas
INSERT INTO Reservas (IdUsuario, IdPaquete, Estado) VALUES 
(1, 1, 'Confirmada'),
(2, 2, 'Pendiente'),
(3, 3, 'Confirmada'),
(4, 4, 'Cancelada');

-- Insertar datos en la tabla de pagos
INSERT INTO Pagos (IdReserva, Monto, MetodoPago, EstadoPago) VALUES 
(1, 1200.00, 'Tarjeta de Cr�dito', 'Completado'),
(2, 1800.00, 'PayPal', 'Pendiente'),
(3, 2500.00, 'Transferencia Bancaria', 'Completado');

-- Insertar datos en la tabla de promociones
INSERT INTO Promociones (IdPaquete, Descripcion, DescuentoPorcentaje, FechaInicio, FechaFin) VALUES 
(1, 'Descuento especial de temporada', 10.00, '2024-10-01', '2024-11-01'),
(2, 'Promoci�n de aventura en la Patagonia', 15.00, '2024-11-15', '2024-12-15');
