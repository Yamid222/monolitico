<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/entities/nota.php';

class NotaController {
    private $db;

    public function __construct($db = null) {
        $this->db = $db ?: (new Database())->conectar();
    }

    public function consultarPorEstudiante($codigo_estudiante) {
        $nota = new Nota($this->db);
        $notas = $nota->listarPorEstudiante($codigo_estudiante);

        $resultado = [];
        while ($row = $notas->fetch(PDO::FETCH_ASSOC)) {
            $cod_materia = $row['codigo_materia'];
            if (!isset($resultado[$cod_materia])) {
                $resultado[$cod_materia] = [
                    'codigo_materia' => $row['codigo_materia'],
                    'materia' => $row['nombre_materia'],
                    'notas' => [],
                    'promedio' => $nota->obtenerPromedioPorMateria($codigo_estudiante, $row['codigo_materia'])
                ];
            }
            $resultado[$cod_materia]['notas'][] = [
                'actividad' => $row['actividad'],
                'valor' => $row['valor']
            ];
        }
        return $resultado;
    }

    public function consultarEstudiantesPorMateria($codigo_materia) {
        $query = "SELECT e.codigo, e.nombre, COALESCE(ROUND(AVG(n.nota), 2), 0) AS promedio
                  FROM notas n
                  JOIN estudiantes e ON n.estudiante = e.codigo
                  WHERE n.materia = :codigo
                  GROUP BY e.codigo, e.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":codigo", $codigo_materia);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar($data) {
        $nota = new Nota($this->db);
        $nota->estudiante = $data['estudiante'];
        $nota->materia = $data['materia'];
        $nota->actividad = $data['actividad'];
        $nota->valor = $data['valor'];
        return $nota->crear();
    }

    public function modificar($data) {
        $nota = new Nota($this->db);
        return $nota->actualizar($data['materia'], $data['estudiante'], $data['actividad'], $data['valor']);
    }

    public function eliminar($data) {
        $nota = new Nota($this->db);
        return $nota->eliminar($data['materia'], $data['estudiante'], $data['actividad']);
    }

    public function obtenerMateriasDisponibles($codigo_estudiante) {
        $nota = new Nota($this->db);
        return $nota->obtenerMateriasDisponibles($codigo_estudiante);
    }

    public function obtenerPromedioPorMateria($codigo_estudiante, $codigo_materia) {
        $nota = new Nota($this->db);
        return $nota->obtenerPromedioPorMateria($codigo_estudiante, $codigo_materia);
    }
}
?>
