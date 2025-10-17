<?php
class Programa {
    private $conn;
    private $tabla = "programa";

    public $id_programa;
    public $codigo;
    public $nombre;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Consultar todos los programas
    public function listar() {
        $query = "SELECT * FROM " . $this->tabla;
        return $this->conn->query($query);
    }

    // Registrar programa
    public function crear() {
        $query = "INSERT INTO " . $this->tabla . " (codigo, nombre)
                  VALUES (:codigo, :nombre)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":codigo", $this->codigo);
        $stmt->bindParam(":nombre", $this->nombre);

        return $stmt->execute();
    }

    // Buscar programa por ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE id_programa = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verificar si el programa tiene estudiantes relacionados
    public function tieneEstudiantes() {
        $query = "SELECT COUNT(*) FROM estudiante WHERE id_programa = :id_programa";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_programa", $this->id_programa);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Verificar si el programa tiene materias relacionadas
    public function tieneMaterias() {
        $query = "SELECT COUNT(*) FROM materia WHERE id_programa = :id_programa";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_programa", $this->id_programa);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Modificar programa
    public function actualizar() {
        // Verificar si tiene relaciones
        if ($this->tieneEstudiantes() || $this->tieneMaterias()) {
            return false;
        }

        $query = "UPDATE " . $this->tabla . "
                  SET nombre = :nombre
                  WHERE id_programa = :id_programa";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":id_programa", $this->id_programa);

        return $stmt->execute();
    }

    // Eliminar programa
    public function eliminar() {
        // Verificar si tiene relaciones
        if ($this->tieneEstudiantes() || $this->tieneMaterias()) {
            return false;
        }

        $query = "DELETE FROM " . $this->tabla . " WHERE id_programa = :id_programa";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_programa", $this->id_programa);
        return $stmt->execute();
    }
}
?>
