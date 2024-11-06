IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'AgenciaViajes')
BEGIN
    CREATE DATABASE AgenciaViajes;
END;
GO

-- Usar la base de datos
USE AgenciaViajes;
GO

-- Crear tabla de usuarios
CREATE TABLE Usuarios (
    Id INT IDENTITY(1,1) PRIMARY KEY,
    Nombre NVARCHAR(50) NOT NULL,
    Email NVARCHAR(100) UNIQUE NOT NULL,
    Telefono NVARCHAR(15),
    Password NVARCHAR(255) NOT NULL,
    CreadoEn DATETIME DEFAULT GETDATE()
);

-- Crear tabla de destinos
CREATE TABLE Destinos (
    Id INT IDENTITY(1,1) PRIMARY KEY,
    Nombre NVARCHAR(100) NOT NULL,
    Pais NVARCHAR(50) NOT NULL,
    Descripcion TEXT,
    CreadoEn DATETIME DEFAULT GETDATE()
);

-- Crear tabla de paquetes de viaje
CREATE TABLE Paquetes (
    Id INT IDENTITY(1,1) PRIMARY KEY,
    Nombre NVARCHAR(100) NOT NULL,
    IdDestino INT NOT NULL,
    Descripcion TEXT,
    Precio DECIMAL(10, 2) NOT NULL,
    NumDias INT NOT NULL,
    FechaInicio DATE,
    FechaFin DATE,
    TipoPaquete NVARCHAR(20) CHECK (TipoPaquete IN ('Lujo', 'Económico', 'Familiar', 'Aventura')) DEFAULT 'Económico',
    CreadoEn DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (IdDestino) REFERENCES Destinos(Id) ON DELETE CASCADE
);

-- Crear tabla de reservas
CREATE TABLE Reservas (
    Id INT IDENTITY(1,1) PRIMARY KEY,
    IdUsuario INT NOT NULL,
    IdPaquete INT NOT NULL,
    FechaReserva DATETIME DEFAULT GETDATE(),
    Estado NVARCHAR(20) CHECK (Estado IN ('Pendiente', 'Confirmada', 'Cancelada')) DEFAULT 'Pendiente',
    FOREIGN KEY (IdUsuario) REFERENCES Usuarios(Id) ON DELETE CASCADE,
    FOREIGN KEY (IdPaquete) REFERENCES Paquetes(Id) ON DELETE CASCADE
);

-- Crear tabla de pagos
CREATE TABLE Pagos (
    Id INT IDENTITY(1,1) PRIMARY KEY,
    IdReserva INT NOT NULL,
    Monto DECIMAL(10, 2) NOT NULL,
    MetodoPago NVARCHAR(20) CHECK (MetodoPago IN ('Tarjeta de Crédito', 'PayPal', 'Transferencia Bancaria')) NOT NULL,
    FechaPago DATETIME DEFAULT GETDATE(),
    EstadoPago NVARCHAR(20) CHECK (EstadoPago IN ('Completado', 'Pendiente', 'Fallido')) DEFAULT 'Pendiente',
    FOREIGN KEY (IdReserva) REFERENCES Reservas(Id) ON DELETE CASCADE
);

-- Crear tabla de promociones
CREATE TABLE Promociones (
    Id INT IDENTITY(1,1) PRIMARY KEY,
    IdPaquete INT NOT NULL,
    Descripcion NVARCHAR(255),
    DescuentoPorcentaje DECIMAL(5, 2) CHECK (DescuentoPorcentaje >= 0 AND DescuentoPorcentaje <= 100),
    FechaInicio DATE,
    FechaFin DATE,
    FOREIGN KEY (IdPaquete) REFERENCES Paquetes(Id) ON DELETE CASCADE
);
