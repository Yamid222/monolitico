<?php
require_once "models/Programa.php";

class ProgramaController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
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

    public function obtenerPorId($id) {
        $programa = new Programa($this->db);
        return $programa->obtenerPorId($id);
    }

    public function modificar($id, $data) {
        $programa = new Programa($this->db);
        $programa->id_programa = $id;
        
        // Verificar si el programa tiene relaciones antes de modificar
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

    public function eliminar($id) {
        $programa = new Programa($this->db);
        $programa->id_programa = $id;
        
        // Verificar si el programa tiene relaciones antes de eliminar
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
