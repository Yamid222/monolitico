<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/entities/programa.php';

class ProgramaController {
    private $db;

    public function __construct($db = null) {
        $this->db = $db ?: (new Database())->conectar();
    }

    public function consultar() {
        $programa = new Programa($this->db);
        return $programa->listar();
    }

    public function registrar($data) {
        $programa = new Programa($this->db);
        $programa->codigo = $data['codigo'];
        $programa->nombre = $data['nombre'];

        if ($programa->crear()) {
            return ['success' => true, 'message' => 'Programa registrado correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al registrar el programa'];
        }
    }

    public function obtenerPorCodigo($codigo) {
        $programa = new Programa($this->db);
        return $programa->obtenerPorCodigo($codigo);
    }

    public function modificar($codigo, $data) {
        $programa = new Programa($this->db);
        $programa->codigo = $codigo;
        
        if ($programa->tieneEstudiantes() || $programa->tieneMaterias()) {
            return ['success' => false, 'message' => 'No se puede modificar el programa porque tiene estudiantes o materias relacionadas'];
        }

        $programa->nombre = $data['nombre'];
        
        if ($programa->actualizar()) {
            return ['success' => true, 'message' => 'Programa actualizado correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar el programa'];
        }
    }

    public function eliminar($codigo) {
        $programa = new Programa($this->db);
        $programa->codigo = $codigo;
        
        if ($programa->tieneEstudiantes() || $programa->tieneMaterias()) {
            return ['success' => false, 'message' => 'No se puede eliminar el programa porque tiene estudiantes o materias relacionadas'];
        }
        
        if ($programa->eliminar()) {
            return ['success' => true, 'message' => 'Programa eliminado correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al eliminar el programa'];
        }
    }
}
?>
