<?php
class Nota {
    private $conn;

    public $materia;     
    public $estudiante; 
    public $actividad;   
    public $valor;       

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    private function validarNota($nota) {
        if (!is_numeric($nota) || $nota <= 0 || $nota > 5) return false;
        $partes = explode('.', (string)$nota);
        return !(isset($partes[1]) && strlen($partes[1]) > 2);
    }

    private function validarMateriaPrograma($codigo_estudiante, $codigo_materia) {
        $sql = "SELECT COUNT(*)
                FROM estudiantes e
                JOIN materias m ON m.programa = e.programa
                WHERE e.codigo = :codigo_est AND m.codigo = :codigo_mat";
        $st = $this->conn->prepare($sql);
        $st->bindParam(':codigo_est', $codigo_estudiante);
        $st->bindParam(':codigo_mat', $codigo_materia);
        $st->execute();
        return (int)$st->fetchColumn() > 0;
    }

    public function listarPorEstudiante($codigo_estudiante) {
        $sql = "SELECT n.materia as codigo_materia, m.nombre as nombre_materia,
                       n.actividad, n.nota as valor
                FROM notas n
                JOIN materias m ON n.materia = m.codigo
                WHERE n.estudiante = :codigo_estudiante
                ORDER BY m.nombre";
        $st = $this->conn->prepare($sql);
        $st->bindParam(':codigo_estudiante', $codigo_estudiante);
        $st->execute();
        return $st;
    }

    public function obtenerPromedioPorMateria($codigo_estudiante, $codigo_materia) {
        $sql = "SELECT COALESCE(ROUND(AVG(nota), 2), 0)
                FROM notas
                WHERE estudiante = :codigo_est AND materia = :codigo_mat";
        $st = $this->conn->prepare($sql);
        $st->bindParam(':codigo_est', $codigo_estudiante);
        $st->bindParam(':codigo_mat', $codigo_materia);
        $st->execute();
        return (float)$st->fetchColumn();
    }

    public function crear() {
        if (!$this->validarNota($this->valor)) {
            return ['success' => false, 'message' => 'La nota debe ser un número entre 0 y 5 con máximo 2 decimales'];
        }
        if (!$this->validarMateriaPrograma($this->estudiante, $this->materia)) {
            return ['success' => false, 'message' => 'La materia no pertenece al programa del estudiante'];
        }
        $sql = "INSERT INTO notas (materia, estudiante, actividad, nota)
                VALUES (:materia, :estudiante, :actividad, :nota)";
        $st = $this->conn->prepare($sql);
        $st->bindParam(':materia', $this->materia);
        $st->bindParam(':estudiante', $this->estudiante);
        $st->bindParam(':actividad', $this->actividad);
        $st->bindParam(':nota', $this->valor);
        if ($st->execute()) return ['success' => true, 'message' => 'Nota registrada correctamente'];
        return ['success' => false, 'message' => 'Error al registrar la nota'];
    }

    public function actualizar($materia, $estudiante, $actividad, $nuevoValor) {
        if (!$this->validarNota($nuevoValor)) {
            return ['success' => false, 'message' => 'La nota debe ser un número entre 0 y 5 con máximo 2 decimales'];
        }
        $sql = "UPDATE notas SET nota = :nota
                WHERE materia = :materia AND estudiante = :estudiante AND actividad = :actividad";
        $st = $this->conn->prepare($sql);
        $st->bindParam(':nota', $nuevoValor);
        $st->bindParam(':materia', $materia);
        $st->bindParam(':estudiante', $estudiante);
        $st->bindParam(':actividad', $actividad);
        if ($st->execute()) return ['success' => true, 'message' => 'Nota actualizada correctamente'];
        return ['success' => false, 'message' => 'Error al actualizar la nota'];
    }

    public function eliminar($materia, $estudiante, $actividad) {
        $sql = "DELETE FROM notas WHERE materia = :materia AND estudiante = :estudiante AND actividad = :actividad";
        $st = $this->conn->prepare($sql);
        $st->bindParam(':materia', $materia);
        $st->bindParam(':estudiante', $estudiante);
        $st->bindParam(':actividad', $actividad);
        if ($st->execute()) return ['success' => true, 'message' => 'Nota eliminada correctamente'];
        return ['success' => false, 'message' => 'Error al eliminar la nota'];
    }

    public function obtenerMateriasDisponibles($codigo_estudiante) {
        $sql = "SELECT m.*
                FROM materias m
                JOIN estudiantes e ON m.programa = e.programa
                WHERE e.codigo = :codigo_est
                ORDER BY m.nombre";
        $st = $this->conn->prepare($sql);
        $st->bindParam(':codigo_est', $codigo_estudiante);
        $st->execute();
        return $st;
    }
}
?>
