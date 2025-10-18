<?php
namespace app\controllers;

use app\models\entities\Estudiante;   // 🔹 Importa la clase Estudiante
use app\models\drivers\conexDB;       // 🔹 (Opcional si también usas la conexión aquí)

require_once __DIR__ . '/../models/entities/estudiante.php';  // 🔹 Incluye la clase
require_once __DIR__ . '/../models/drivers/conexDB.php';
       // 🔹 Incluye la conexión
       
class EstudianteController {
    private $model;

    public function __construct() {
        $this->model = new Estudiante();
    }

    public function listar() {
        return $this->model->listar();
    }

    public function registrar($codigo, $nombre, $email, $programa) {
        return $this->model->registrar($codigo, $nombre, $email, $programa);
    }

    public function actualizar($codigo, $nombre, $email) {
        return $this->model->actualizar($codigo, $nombre, $email);
    }

    public function eliminar($codigo) {
        return $this->model->eliminar($codigo);
    }

    public function buscarPorCodigo($codigo) {
        return $this->model->buscarPorCodigo($codigo);
    }
}
?>
