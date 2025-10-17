<?php
require_once "models/Estudiante.php";

class EstudianteController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function consultar() {
        $estudiante = new Estudiante($this->db);
        return $estudiante->listar();
    }

    public function obtenerPorId($id) {
        $estudiante = new Estudiante($this->db);
        return $estudiante->obtenerPorId($id);
    }

    public function obtenerPorCodigo($codigo) {
        $estudiante = new Estudiante($this->db);
        return $estudiante->obtenerPorCodigo($codigo);
    }

    public function consultarPorPrograma($codigo_programa) {
        $estudiante = new Estudiante($this->db);
        return $estudiante->listarPorProgramaCodigo($codigo_programa);
    }

    public function registrar($data) {
        $estudiante = new Estudiante($this->db);
        $estudiante->codigo = $data['codigo'];
        $estudiante->nombre = $data['nombre'];
        $estudiante->correo = $data['correo'];
        $estudiante->id_programa = $data['id_programa'] ?? null;

        return $estudiante->crear();
    }

    public function modificar($id, $data) {
        $estudiante = new Estudiante($this->db);
        $estudiante->id_estudiante = $id;
        
        // Verificar si el estudiante tiene notas antes de modificar
        if ($estudiante->tieneNotas()) {
            return ['success' => false, 'message' => 'No se puede modificar el estudiante porque tiene notas registradas'];
        }

        $estudiante->nombre = $data['nombre'];
        $estudiante->correo = $data['correo'];
        $estudiante->id_programa = $data['id_programa'];
        
        if ($estudiante->actualizar()) {
            return ['success' => true, 'message' => 'Estudiante actualizado correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar el estudiante'];
        }
    }

    public function eliminar($id) {
        $estudiante = new Estudiante($this->db);
        $estudiante->id_estudiante = $id;
        
        // Verificar si el estudiante tiene notas antes de eliminar
        if ($estudiante->tieneNotas()) {
            return ['success' => false, 'message' => 'No se puede eliminar el estudiante porque tiene notas registradas'];
        }
        
        if ($estudiante->eliminar()) {
            return ['success' => true, 'message' => 'Estudiante eliminado correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al eliminar el estudiante'];
        }
    }
}
?>
