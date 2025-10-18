<?php
class Materia {
    private $conn;
    private $tabla = "materia";

    public $id_materia;
    public $codigo;
    public $nombre;
    public $id_programa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Consultar todas las materias
    public function listar() {
        $query = "SELECT m.*, p.nombre as nombre_programa 
                 FROM " . $this->tabla . " m 
                 LEFT JOIN programa p ON m.id_programa = p.id_programa";
        return $this->conn->query($query);
    }

    // Consultar materias por programa
    public function listarPorPrograma($id_programa) {
        $query = "SELECT m.*, p.nombre as nombre_programa 
                 FROM " . $this->tabla . " m 
                 LEFT JOIN programa p ON m.id_programa = p.id_programa 
                 WHERE m.id_programa = :id_programa";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_programa", $id_programa);
        $stmt->execute();
        return $stmt;
    }

    // Registrar materia
    public function crear() {
        $query = "INSERT INTO " . $this->tabla . " (codigo, nombre, id_programa)
                  VALUES (:codigo, :nombre, :id_programa)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":codigo", $this->codigo);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":id_programa", $this->id_programa);

        return $stmt->execute();
    }

    // Buscar materia por ID
    public function obtenerPorId($id) {
        $query = "SELECT m.*, p.nombre as nombre_programa 
                 FROM " . $this->tabla . " m 
                 LEFT JOIN programa p ON m.id_programa = p.id_programa 
                 WHERE m.id_materia = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verificar si la materia tiene notas registradas
    public function tieneNotas() {
        $query = "SELECT COUNT(*) FROM nota WHERE id_materia = :id_materia";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_materia", $this->id_materia);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Modificar materia
    public function actualizar() {
        // Verificar si tiene notas registradas
        if ($this->tieneNotas()) {
            return false;
        }

        $query = "UPDATE " . $this->tabla . "
                  SET nombre = :nombre, id_programa = :id_programa
                  WHERE id_materia = :id_materia";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":id_programa", $this->id_programa);
        $stmt->bindParam(":id_materia", $this->id_materia);

        return $stmt->execute();
    }

    // Eliminar materia
    public function eliminar() {
        // Verificar si tiene notas registradas
        if ($this->tieneNotas()) {
            return false;
        }

        $query = "DELETE FROM " . $this->tabla . " WHERE id_materia = :id_materia";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_materia", $this->id_materia);
        return $stmt->execute();
    }
}
?>
