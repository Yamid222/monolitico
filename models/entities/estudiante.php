<?php
namespace app\models\entities;

use app\models\drivers\conexDB;

require_once __DIR__ . '/../drivers/conexDB.php';

class Estudiante {
    private $conn;
    private $tabla = "estudiantes";

    public function __construct() {
        $db = new conexDB();
        $this->conn = $db->getConexion();
    }

    // Consultar todos los estudiantes
    public function listar() {
        $sql = "SELECT e.codigo, e.nombre, e.email, p.nombre AS programa
                FROM estudiantes e
                JOIN programas p ON e.programa = p.codigo";
        return $this->conn->query($sql);
    }

    // Registrar nuevo estudiante
    public function registrar($codigo, $nombre, $email, $programa) {
        $stmt = $this->conn->prepare("INSERT INTO estudiantes (codigo, nombre, email, programa)
                                    VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $codigo, $nombre, $email, $programa);
        return $stmt->execute();
    }

    // Verificar si el estudiante tiene notas
    private function tieneNotas($codigo) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM notas WHERE estudiante = ?");
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res['total'] > 0;
    }

    // Actualizar estudiante
    public function actualizar($codigo, $nombre, $email) {
        if ($this->tieneNotas($codigo)) {
            return "❌ No se puede modificar. El estudiante tiene notas registradas.";
        }
        $stmt = $this->conn->prepare("UPDATE estudiantes SET nombre=?, email=? WHERE codigo=?");
        $stmt->bind_param("sss", $nombre, $email, $codigo);
        $stmt->execute();
        return "✅ Estudiante actualizado correctamente.";
    }

    // Eliminar estudiante
    public function eliminar($codigo) {
        if ($this->tieneNotas($codigo)) {
            return "❌ No se puede eliminar. El estudiante tiene notas registradas.";
        }
        $stmt = $this->conn->prepare("DELETE FROM estudiantes WHERE codigo=?");
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        return "✅ Estudiante eliminado correctamente.";
    }

    // Buscar estudiante por código
    public function buscarPorCodigo($codigo) {
        $stmt = $this->conn->prepare("SELECT * FROM estudiantes WHERE codigo=?");
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
