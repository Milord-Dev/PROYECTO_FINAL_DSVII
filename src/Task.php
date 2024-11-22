<?php
class Task {
    public $id;
    public $idUsuario;
    public $idPaquete;
    public $estado;
    public $usuario;
    public $paquete;

    // Constructor para crear un objeto Task a partir de los datos de la base de datos
    public function __construct($data) {
        $this->id = $data['IdReserva'];
        $this->idUsuario = $data['IdUsuario'];
        $this->idPaquete = $data['IdPaquete'];
        $this->estado = $data['Estado'];
        $this->usuario = $this->getUsuario($this->idUsuario);
        $this->paquete = $this->getPaquete($this->idPaquete);
    }

    // Obtener los detalles del usuario asociado a la tarea
    private function getUsuario($idUsuario) {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM Usuarios WHERE IdUsuario = ?");
        $stmt->execute([$idUsuario]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Usuario($data) : null;
    }

    // Obtener los detalles del paquete asociado a la tarea
    private function getPaquete($idPaquete) {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM Paquetes WHERE IdPaquete = ?");
        $stmt->execute([$idPaquete]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Paquete($data) : null;
    }
}
