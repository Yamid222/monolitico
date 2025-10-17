<?php
class Nota {
    private $conn;
    private $tabla = "notas";

    public $id_nota;
    public $id_materia;
    public $id_estudiante;
    public $valor;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Validar nota
    private function validarNota($nota) {
        // Validar rango (0-5) y máximo 2 decimales
        if (!is_numeric($nota) || $nota <= 0 || $nota > 5) {
            return false;
        }
        
        // Verificar máximo 2 decimales
        $partes = explode('.', $nota);
        if (isset($partes[1]) && strlen($partes[1]) > 2) {
            return false;
        }
        
        return true;
    }

    // Validar si la materia pertenece al programa del estudiante
    private function validarMateriaPrograma($id_estudiante, $id_materia) {
        $query = "SELECT e.id_programa = m.id_programa as valido
                 FROM estudiante e, materia m 
                 WHERE e.id_estudiante = :id_estudiante 
                 AND m.id_materia = :id_materia";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_estudiante", $id_estudiante);
        $stmt->bindParam(":id_materia", $id_materia);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Consultar notas por estudiante
    public function listarPorEstudiante($id_estudiante) {
        $query = "SELECT n.*, m.nombre as nombre_materia, m.codigo as codigo_materia,
                        e.nombre as nombre_estudiante, e.codigo as codigo_estudiante
                 FROM " . $this->tabla . " n
                 JOIN materia m ON n.id_materia = m.id_materia
                 JOIN estudiante e ON n.id_estudiante = e.id_estudiante
                 WHERE n.id_estudiante = :id_estudiante
                 ORDER BY m.nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_estudiante", $id_estudiante);
        $stmt->execute();
        return $stmt;
    }

    // Obtener promedio por materia
    public function obtenerPromedioPorMateria($id_estudiante, $id_materia) {
        $query = "SELECT COALESCE(ROUND(AVG(valor), 2), 0) as promedio
                 FROM " . $this->tabla . "
                 WHERE id_estudiante = :id_estudiante
                 AND id_materia = :id_materia";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_estudiante", $id_estudiante);
        $stmt->bindParam(":id_materia", $id_materia);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Registrar nota
    public function crear() {
        // Validar nota
        if (!$this->validarNota($this->valor)) {
            return ['success' => false, 'message' => 'La nota debe ser un número entre 0 y 5 con máximo 2 decimales'];
        }

        // Validar que la materia pertenezca al programa del estudiante
        if (!$this->validarMateriaPrograma($this->id_estudiante, $this->id_materia)) {
            return ['success' => false, 'message' => 'La materia no pertenece al programa del estudiante'];
        }

        $query = "INSERT INTO " . $this->tabla . " (id_estudiante, id_materia, valor)
                  VALUES (:id_estudiante, :id_materia, :valor)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_estudiante", $this->id_estudiante);
        $stmt->bindParam(":id_materia", $this->id_materia);
        $stmt->bindParam(":valor", $this->valor);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Nota registrada correctamente'];
        }
        return ['success' => false, 'message' => 'Error al registrar la nota'];
    }

    // Obtener nota por ID
    public function obtenerPorId($id) {
        $query = "SELECT n.*, m.nombre as nombre_materia, e.nombre as nombre_estudiante
                 FROM " . $this->tabla . " n
                 JOIN materia m ON n.id_materia = m.id_materia
                 JOIN estudiante e ON n.id_estudiante = e.id_estudiante
                 WHERE n.id_nota = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Modificar nota
    public function actualizar() {
        if (!$this->validarNota($this->valor)) {
            return ['success' => false, 'message' => 'La nota debe ser un número entre 0 y 5 con máximo 2 decimales'];
        }

        $query = "UPDATE " . $this->tabla . "
                  SET valor = :valor
                  WHERE id_nota = :id_nota";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":valor", $this->valor);
        $stmt->bindParam(":id_nota", $this->id_nota);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Nota actualizada correctamente'];
        }
        return ['success' => false, 'message' => 'Error al actualizar la nota'];
    }

    // Eliminar nota
    public function eliminar() {
        $query = "DELETE FROM " . $this->tabla . " WHERE id_nota = :id_nota";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_nota", $this->id_nota);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Nota eliminada correctamente'];
        }
        return ['success' => false, 'message' => 'Error al eliminar la nota'];
    }

    // Obtener materias disponibles para el estudiante
    public function obtenerMateriasDisponibles($id_estudiante) {
        $query = "SELECT m.* 
                 FROM materia m
                 JOIN estudiante e ON m.id_programa = e.id_programa
                 WHERE e.id_estudiante = :id_estudiante
                 ORDER BY m.nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_estudiante", $id_estudiante);
        $stmt->execute();
        return $stmt;
    }
}
?>
