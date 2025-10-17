<?php
require_once "models/Nota.php";

class NotaController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function consultarPorEstudiante($id_estudiante) {
        $nota = new Nota($this->db);
        $notas = $nota->listarPorEstudiante($id_estudiante);
        
        // Agrupar notas por materia y calcular promedios
        $resultado = [];
        while ($row = $notas->fetch(PDO::FETCH_ASSOC)) {
            $id_materia = $row['id_materia'];
            if (!isset($resultado[$id_materia])) {
                $resultado[$id_materia] = [
                    'codigo_materia' => $row['codigo_materia'],
                    'materia' => $row['nombre_materia'],
                    'notas' => [],
                    'promedio' => $nota->obtenerPromedioPorMateria($id_estudiante, $id_materia)
                ];
            }
            $resultado[$id_materia]['notas'][] = [
                'id_nota' => $row['id_nota'],
                'valor' => $row['valor']
            ];
        }
        
        return $resultado;
    }

    public function obtenerPorId($id_nota) {
        $nota = new Nota($this->db);
        return $nota->obtenerPorId($id_nota);
    }

    // Consultar estudiantes inscritos en una materia (por código de materia)
    public function consultarEstudiantesPorMateria($codigo_materia) {
        // Retornará una lista de estudiantes que tienen notas en la materia
        $query = "SELECT e.* FROM notas n
                  JOIN estudiante e ON n.id_estudiante = e.id_estudiante
                  JOIN materia m ON n.id_materia = m.id_materia
                  WHERE m.codigo = :codigo
                  GROUP BY e.id_estudiante";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":codigo", $codigo_materia);
        $stmt->execute();
        return $stmt;
    }

    public function registrar($data) {
        $nota = new Nota($this->db);
            $nota->id_estudiante = $data['id_estudiante'];
            $nota->id_materia = $data['id_materia'];
            $nota->valor = $data['valor'];

        return $nota->crear();
    }

        public function obtenerNota($id_nota) {
        $nota = new Nota($this->db);
            return $nota->obtenerPorId($id_nota);
    }

    public function modificar($data) {
        $nota = new Nota($this->db);
            $nota->id_nota = $data['id_nota'];
            $nota->valor = $data['valor'];
        
        return $nota->actualizar();
    }

        public function eliminar($id_nota) {
        $nota = new Nota($this->db);
            $nota->id_nota = $id_nota;
            return $nota->eliminar();
    }

    public function obtenerMateriasDisponibles($id_estudiante) {
        $nota = new Nota($this->db);
        return $nota->obtenerMateriasDisponibles($id_estudiante);
    }
}
?>
