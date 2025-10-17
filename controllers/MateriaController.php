<?php
require_once "models/Materia.php";

class MateriaController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function consultar() {
        $materia = new Materia($this->db);
        return $materia->listar();
    }

    public function consultarPorPrograma($id_programa) {
        $materia = new Materia($this->db);
        return $materia->listarPorPrograma($id_programa);
    }

    public function registrar($data) {
        $materia = new Materia($this->db);
        $materia->codigo = $data['codigo'];
        $materia->nombre = $data['nombre'];
        $materia->id_programa = $data['id_programa'];

        if ($materia->crear()) {
            return ['success' => true, 'message' => 'Materia registrada correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al registrar la materia'];
        }
    }

    public function obtenerPorId($id) {
        $materia = new Materia($this->db);
        return $materia->obtenerPorId($id);
    }

    public function modificar($id, $data) {
        $materia = new Materia($this->db);
        $materia->id_materia = $id;
        
        // Verificar si la materia tiene notas antes de modificar
        if ($materia->tieneNotas()) {
            return ['success' => false, 'message' => 'No se puede modificar la materia porque tiene notas registradas'];
        }

        $materia->nombre = $data['nombre'];
        $materia->id_programa = $data['id_programa'];
        
        if ($materia->actualizar()) {
            return ['success' => true, 'message' => 'Materia actualizada correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar la materia'];
        }
    }

    public function eliminar($id) {
        $materia = new Materia($this->db);
        $materia->id_materia = $id;
        
        // Verificar si la materia tiene notas antes de eliminar
        if ($materia->tieneNotas()) {
            return ['success' => false, 'message' => 'No se puede eliminar la materia porque tiene notas registradas'];
        }
        
        if ($materia->eliminar()) {
            return ['success' => true, 'message' => 'Materia eliminada correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al eliminar la materia'];
        }
    }
}
?>
