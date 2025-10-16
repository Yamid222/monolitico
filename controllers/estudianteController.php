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
        $estudiante->nombre = $data['nombre'];
        $estudiante->correo = $data['correo'];
        $estudiante->id_programa = $data['id_programa'];
        return $estudiante->actualizar();
    }

    public function eliminar($id) {
        $estudiante = new Estudiante($this->db);
        $estudiante->id_estudiante = $id;
        return $estudiante->eliminar();
    }
}
?>
