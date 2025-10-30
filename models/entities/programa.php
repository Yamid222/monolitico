<?php
class Programa {
    private $conn;

    public $codigo;
    public $nombre;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function listar() {
        $query = "SELECT codigo, nombre FROM programas ORDER BY nombre";
        return $this->conn->query($query);
    }

    public function crear() {
        try {
        $query = "INSERT INTO programas (codigo, nombre) VALUES (:codigo, :nombre)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':codigo', $this->codigo);
        $stmt->bindParam(':nombre', $this->nombre);
        return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return false;
            }
            throw $e;
        }
    }

    public function obtenerPorCodigo($codigo) {
        $stmt = $this->conn->prepare("SELECT codigo, nombre FROM programas WHERE codigo = :codigo");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function tieneEstudiantes() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM estudiantes WHERE programa = :codigo");
        $stmt->bindParam(':codigo', $this->codigo);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function tieneMaterias() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM materias WHERE programa = :codigo");
        $stmt->bindParam(':codigo', $this->codigo);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function actualizar() {
        if ($this->tieneEstudiantes() || $this->tieneMaterias()) {
            return false;
        }
        $stmt = $this->conn->prepare("UPDATE programas SET nombre = :nombre WHERE codigo = :codigo");
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':codigo', $this->codigo);
        return $stmt->execute();
    }

    public function eliminar() {
        if ($this->tieneEstudiantes() || $this->tieneMaterias()) {
            return false;
        }
        $stmt = $this->conn->prepare("DELETE FROM programas WHERE codigo = :codigo");
        $stmt->bindParam(':codigo', $this->codigo);
        return $stmt->execute();
    }
}
?>
