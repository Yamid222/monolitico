<?php
class Estudiante {
    private $conn;
    private $tabla = "estudiante";

    public $id_estudiante;
    public $codigo;
    public $nombre;
    public $correo;
    public $id_programa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 🔹 Consultar todos los estudiantes
    public function listar() {
        $query = "SELECT * FROM " . $this->tabla;
        return $this->conn->query($query);
    }

    // 🔹 Registrar estudiante
    public function crear() {
        $query = "INSERT INTO " . $this->tabla . " (codigo, nombre, correo, id_programa)
                  VALUES (:codigo, :nombre, :correo, :id_programa)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":codigo", $this->codigo);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":id_programa", $this->id_programa);

        return $stmt->execute();
    }

    // 🔹 Buscar estudiante por ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE id_estudiante = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔹 Modificar estudiante
    public function tieneNotas() {
        $query = "SELECT COUNT(*) FROM nota WHERE id_estudiante = :id_estudiante";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_estudiante", $this->id_estudiante);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function actualizar() {
        // Verificar si el estudiante tiene notas registradas
        if ($this->tieneNotas()) {
            return false;
        }

        $query = "UPDATE " . $this->tabla . "
                  SET nombre = :nombre, correo = :correo, id_programa = :id_programa
                  WHERE id_estudiante = :id_estudiante";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":id_programa", $this->id_programa);
        $stmt->bindParam(":id_estudiante", $this->id_estudiante);

        return $stmt->execute();
    }

    // 🔹 Eliminar estudiante
    public function eliminar() {
        // Verificar si el estudiante tiene notas registradas
        if ($this->tieneNotas()) {
            return false;
        }

        $query = "DELETE FROM " . $this->tabla . " WHERE id_estudiante = :id_estudiante";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_estudiante", $this->id_estudiante);
        return $stmt->execute();
    }

    // Buscar estudiante por código (clave natural)
    public function obtenerPorCodigo($codigo) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE codigo = :codigo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":codigo", $codigo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Listar estudiantes por el código de programa (join con tabla programa)
    public function listarPorProgramaCodigo($codigo_programa) {
        $query = "SELECT e.* FROM " . $this->tabla . " e
                  JOIN programa p ON e.id_programa = p.id_programa
                  WHERE p.codigo = :codigo
                  ORDER BY e.nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":codigo", $codigo_programa);
        $stmt->execute();
        return $stmt;
    }
}
?>