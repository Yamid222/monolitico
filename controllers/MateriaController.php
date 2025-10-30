<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/entities/materia.php';

class MateriaController {
    private $db;

    public function __construct($db = null) {
        $this->db = $db ?: (new Database())->conectar();
    }

    public function consultar() {
        $materia = new Materia($this->db);
        return $materia->listar();
    }

    public function consultarPorPrograma($codigo_programa) {
        $materia = new Materia($this->db);
        return $materia->listarPorPrograma($codigo_programa);
    }

    public function registrar($data) {
        $materia = new Materia($this->db);
        // Verificar si el código ya existe antes de crear
        $existente = $materia->obtenerPorCodigo($data['codigo']);
        if ($existente) {
            return ['success' => false, 'message' => 'El código de la materia ya existe'];
        }

        $materia->codigo = $data['codigo'];
        $materia->nombre = $data['nombre'];
        $materia->programa = $data['programa'];

        if ($materia->crear()) {
            return ['success' => true, 'message' => 'Materia registrada correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al registrar la materia'];
        }
    }

    public function obtenerPorCodigo($codigo) {
        $materia = new Materia($this->db);
        return $materia->obtenerPorCodigo($codigo);
    }

    public function modificar($codigo, $data) {
        $materia = new Materia($this->db);
        $materia->codigo = $codigo;
        
        if ($materia->bloqueadaPorRelacion()) {
            return ['success' => false, 'message' => 'No se puede modificar la materia porque tiene notas o estudiantes relacionados'];
        }

        $materia->nombre = $data['nombre'];
        $materia->programa = $data['programa'];
        
        if ($materia->actualizar()) {
            return ['success' => true, 'message' => 'Materia actualizada correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar la materia'];
        }
    }

    public function eliminar($codigo) {
        $materia = new Materia($this->db);
        $materia->codigo = $codigo;
        
        if ($materia->bloqueadaPorRelacion()) {
            return ['success' => false, 'message' => 'No se puede eliminar la materia porque tiene notas o estudiantes relacionados'];
        }
        
        if ($materia->eliminar()) {
            return ['success' => true, 'message' => 'Materia eliminada correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al eliminar la materia'];
        }
    }
}
?>
