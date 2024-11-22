<?php

class TaskManager {
    private $db;

    public function __construct() {
        // Obtenemos la conexión a la base de datos
        $this->db = Database::getInstance()->getConnection();
    }

    // Método para obtener todas las tareas
    public function getAllTasks() {
        $stmt = $this->db->query("SELECT * FROM tasks ORDER BY created_at DESC");
        $tasks = [];

        // Convertimos cada fila en un objeto Task
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tasks[] = new Task($data); // Creamos un objeto Task para cada fila
        }

        return $tasks;
    }

    // Método para obtener una tarea por su ID
    public function getTaskById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si se encuentra la tarea, la devolvemos como objeto
        return $data ? new Task($data) : null;
    }

    // Método para crear una nueva tarea
    public function createTask($title) {
        $task = new Task([
            'title' => $title,
            'is_completed' => false,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Guardamos la tarea en la base de datos
        $task->save();
        return $task;
    }

    // Método para cambiar el estado de una tarea (completada/no completada)
    public function toggleTask($id) {
        // Recuperamos la tarea por su ID
        $task = $this->getTaskById($id);
        
        if ($task) {
            $task->isCompleted = !$task->isCompleted; // Cambiamos el estado
            $task->save(); // Guardamos los cambios en la base de datos
            return $task; // Retornamos la tarea actualizada
        }
        
        return null; // Si no se encuentra la tarea
    }

    // Método para eliminar una tarea
    public function deleteTask($id) {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
