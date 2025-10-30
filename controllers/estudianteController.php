<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/entities/estudiante.php';

class EstudianteController {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conectar();
    }

    public function listar() {
        $model = new Estudiante($this->db);
        return $model->listar();
    }

    public function registrar($codigo, $nombre, $email, $programa) {
        $model = new Estudiante($this->db);
        return $model->registrar($codigo, $nombre, $email, $programa);
    }

    public function actualizar($codigo, $nombre, $email) {
        $model = new Estudiante($this->db);
        return $model->actualizar($codigo, $nombre, $email);
    }

    public function eliminar($codigo) {
        $model = new Estudiante($this->db);
        return $model->eliminar($codigo);
    }

    public function buscarPorCodigo($codigo) {
        $model = new Estudiante($this->db);
        return $model->buscarPorCodigo($codigo);
    }

    public function obtenerPorCodigo($codigo) {
        return $this->buscarPorCodigo($codigo);
    }

    public function consultarPorPrograma($codigo_programa) {
        $model = new Estudiante($this->db);
        return $model->listarPorPrograma($codigo_programa);
    }
}
?>
