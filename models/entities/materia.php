<?php
class Materia {
    private $conn;

    public $codigo;
    public $nombre;
    public $programa; 

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function listar() {
        $query = "SELECT m.codigo, m.nombre, m.programa, p.nombre AS nombre_programa
                  FROM materias m
                  JOIN programas p ON m.programa = p.codigo
                  ORDER BY m.nombre";
        return $this->conn->query($query);
    }

    public function listarPorPrograma($codigo_programa) {
        $query = "SELECT m.codigo, m.nombre, m.programa, p.nombre AS nombre_programa
                  FROM materias m
                  JOIN programas p ON m.programa = p.codigo
                  WHERE m.programa = :programa
                  ORDER BY m.nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':programa', $codigo_programa);
        $stmt->execute();
        return $stmt;
    }

    public function crear() {
        $query = "INSERT INTO materias (codigo, nombre, programa)
                  VALUES (:codigo, :nombre, :programa)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':codigo', $this->codigo);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':programa', $this->programa);
        return $stmt->execute();
    }

    public function obtenerPorCodigo($codigo) {
        $query = "SELECT m.codigo, m.nombre, m.programa, p.nombre AS nombre_programa
                  FROM materias m
                  JOIN programas p ON m.programa = p.codigo
                  WHERE m.codigo = :codigo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function bloqueadaPorRelacion() {
        $q1 = $this->conn->prepare("SELECT COUNT(*) FROM notas WHERE materia = :codigo");
        $q1->bindParam(':codigo', $this->codigo);
        $q1->execute();
        $tieneNotas = (int)$q1->fetchColumn() > 0;

       
        $q2 = $this->conn->prepare("SELECT COUNT(*) FROM estudiantes WHERE programa = (SELECT programa FROM materias WHERE codigo = :codigo)");
        $q2->bindParam(':codigo', $this->codigo);
        $q2->execute();
        $tieneEstudiantes = (int)$q2->fetchColumn() > 0;

        return $tieneNotas || $tieneEstudiantes;
    }

    public function actualizar() {
        $query = "UPDATE materias SET nombre = :nombre, programa = :programa WHERE codigo = :codigo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':programa', $this->programa);
        $stmt->bindParam(':codigo', $this->codigo);
        return $stmt->execute();
    }

    public function eliminar() {
        $stmt = $this->conn->prepare("DELETE FROM materias WHERE codigo = :codigo");
        $stmt->bindParam(':codigo', $this->codigo);
        return $stmt->execute();
    }
}
?>
