-- Crear la base de datos
CREATE DATABASE AgenciaViajes;
GO

USE AgenciaViajes;
GO

-- Crear la tabla de Usuarios
CREATE TABLE Usuarios (
    IdUsuario INT PRIMARY KEY IDENTITY,
    Nombre NVARCHAR(100),
    Email NVARCHAR(100),
    Telefono NVARCHAR(15),
    Password NVARCHAR(100)
);
GO

-- Crear la tabla de Destinos
CREATE TABLE Destinos (
    IdDestino INT PRIMARY KEY IDENTITY,
    Nombre NVARCHAR(100),
    Pais NVARCHAR(100),
    Descripcion NVARCHAR(255)
);
GO

-- Crear la tabla de Paquetes
CREATE TABLE Paquetes (
    IdPaquete INT PRIMARY KEY IDENTITY,
    Nombre NVARCHAR(100),
    IdDestino INT FOREIGN KEY REFERENCES Destinos(IdDestino),
    Descripcion NVARCHAR(255),
    Precio DECIMAL(10, 2),
    NumDias INT,
    FechaInicio DATE,
    FechaFin DATE,
    TipoPaquete NVARCHAR(50)
);
GO

-- Crear la tabla de Reservas
CREATE TABLE Reservas (
    IdReserva INT PRIMARY KEY IDENTITY,
    IdUsuario INT FOREIGN KEY REFERENCES Usuarios(IdUsuario),
    IdPaquete INT FOREIGN KEY REFERENCES Paquetes(IdPaquete),
    Estado NVARCHAR(50)
);
GO

-- Crear la tabla de Pagos
CREATE TABLE Pagos (
    IdPago INT PRIMARY KEY IDENTITY,
    IdReserva INT FOREIGN KEY REFERENCES Reservas(IdReserva),
    Monto DECIMAL(10, 2),
    MetodoPago NVARCHAR(30),
    EstadoPago NVARCHAR(50)
);
GO

-- Crear la tabla de Promociones
CREATE TABLE Promociones (
    IdPromocion INT PRIMARY KEY IDENTITY,
    IdPaquete INT FOREIGN KEY REFERENCES Paquetes(IdPaquete),
    Descripcion NVARCHAR(255),
    DescuentoPorcentaje DECIMAL(5, 2),
    FechaInicio DATE,
    FechaFin DATE
);
GO


--Aqui esta insecion de los datos de las tablas (primero crear la base de datos)

-- Insertar usuarios
INSERT INTO Usuarios (Nombre, Email, Telefono, Password) VALUES 
('Carlos Pérez', 'carlos.perez@gmail.com', '564-1234', 'sistem435'),
('María López', 'maria.lopez@gmail.com', '486-5678', 'holamundo123'),
('Luis Martínez', 'luis.martinez@gmail.com', '663-8765', 'qwerty'),
('Ana García', 'ana.garcia@gmail.com', '234-4321', 'abcd123'),
('José González', 'jose.gonzalez@gmail.com', '156-9876', 'master47');
GO

-- Insertar destinos
INSERT INTO Destinos (Nombre, Pais, Descripcion) VALUES 
('Cancún', 'México', 'Destino turístico en el Caribe Mexicano, ideal para vacaciones de playa.'),
('Patagonia', 'Argentina', 'Región con paisajes naturales impresionantes y actividades de aventura.'),
('París', 'Francia', 'Capital de Francia, famosa por su cultura, arte y gastronomía.'),
('Nairobi', 'Kenia', 'Lugar ideal para disfrutar de safaris y ver fauna africana en su hábitat natural.');
GO

-- Insertar paquetes
INSERT INTO Paquetes (Nombre, IdDestino, Descripcion, Precio, NumDias, FechaInicio, FechaFin, TipoPaquete) VALUES 
('Paquete Caribeño', 1, 'Disfruta de las playas paradisíacas y un todo incluido de lujo.', 1200.00, 7, '2024-11-10', '2024-11-17', 'Lujo'),
('Aventura en la Patagonia', 2, 'Explora los glaciares y la naturaleza virgen en un tour de 7 días.', 1800.00, 7, '2024-12-01', '2024-12-08', 'Aventura'),
('Descubre Europa', 3, 'Un tour de 10 días por las principales ciudades europeas.', 2500.00, 10, '2025-01-15', '2025-01-25', 'Lujo'),
('Safari en Kenia', 4, 'Observa la increíble fauna africana en su hábitat natural.', 3000.00, 7, '2025-02-20', '2025-02-27', 'Aventura');
GO

-- Insertar reservas
INSERT INTO Reservas (IdUsuario, IdPaquete, Estado) VALUES 
(1, 1, 'Confirmada'),
(2, 2, 'Pendiente'),
(3, 3, 'Confirmada'),
(4, 4, 'Cancelada');
GO

-- Insertar pagos
INSERT INTO Pagos (IdReserva, Monto, MetodoPago, EstadoPago) VALUES 
(1, 1200.00, 'Tarjeta de Crédito', 'Completado'),
(2, 1800.00, 'PayPal', 'Pendiente'),
(3, 2500.00, 'Transferencia Bancaria', 'Completado');
GO

-- Insertar promociones
INSERT INTO Promociones (IdPaquete, Descripcion, DescuentoPorcentaje, FechaInicio, FechaFin) VALUES 
(1, 'Descuento especial de temporada', 10.00, '2024-10-01', '2024-11-01'),
(2, 'Promoción de aventura en la Patagonia', 15.00, '2024-11-15', '2024-12-15');
GO
