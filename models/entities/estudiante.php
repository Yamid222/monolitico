<?php
class Estudiante {
    private $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function listar() {
        $sql = "SELECT e.codigo, e.nombre, e.email, p.nombre AS programa
                FROM estudiantes e
                JOIN programas p ON e.programa = p.codigo
                ORDER BY e.nombre";
        return $this->conn->query($sql);
    }

    public function registrar($codigo, $nombre, $email, $programa) {
        $codigo = trim($codigo);
        $nombre = trim($nombre);
        $email = trim($email);
        $programa = trim($programa);

        $check = $this->conn->prepare("SELECT COUNT(*) AS total FROM estudiantes WHERE codigo = :codigo");
        $check->bindParam(':codigo', $codigo);
        $check->execute();
        $exists = $check->fetch(PDO::FETCH_ASSOC);
        if ($exists && (int)$exists['total'] > 0) {
            return 'Ya existe un estudiante con ese código.';
        }

        try {
            $stmt = $this->conn->prepare("INSERT INTO estudiantes (codigo, nombre, email, programa)
                                      VALUES (:codigo, :nombre, :email, :programa)");
            $stmt->bindParam(':codigo', $codigo);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':programa', $programa);
            $ok = $stmt->execute();
            return $ok ? 'Estudiante registrado correctamente.' : 'Error al registrar estudiante.';
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return 'Ya existe un estudiante con ese código.';
            }
            return 'Error al registrar estudiante.';
        }
    }

    public function listarPorPrograma($codigo_programa) {
        $sql = "SELECT codigo, nombre, email FROM estudiantes WHERE programa = :programa ORDER BY nombre";
        $st = $this->conn->prepare($sql);
        $st->bindParam(':programa', $codigo_programa);
        $st->execute();
        return $st;
    }

    private function tieneNotas($codigo) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM notas WHERE estudiante = :codigo");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($res && (int)$res['total'] > 0);
    }

    public function actualizar($codigo, $nombre, $email) {
        if ($this->tieneNotas($codigo)) {
            return [ 'success' => false, 'message' => 'No se puede modificar. El estudiante tiene notas registradas.' ];
        }
        $stmt = $this->conn->prepare("UPDATE estudiantes SET nombre = :nombre, email = :email WHERE codigo = :codigo");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':codigo', $codigo);
        $ok = $stmt->execute();
        return $ok ? [ 'success' => true, 'message' => 'Estudiante actualizado correctamente.' ]
                   : [ 'success' => false, 'message' => 'Error al actualizar estudiante.' ];
    }

    public function eliminar($codigo) {
        if ($this->tieneNotas($codigo)) {
            return [ 'success' => false, 'message' => 'No se puede eliminar. El estudiante tiene notas registradas.' ];
        }
        $stmt = $this->conn->prepare("DELETE FROM estudiantes WHERE codigo = :codigo");
        $stmt->bindParam(':codigo', $codigo);
        $ok = $stmt->execute();
        return $ok ? [ 'success' => true, 'message' => 'Estudiante eliminado correctamente.' ]
                   : [ 'success' => false, 'message' => 'Error al eliminar estudiante.' ];
    }

    public function buscarPorCodigo($codigo) {
        $sql = "SELECT e.codigo, e.nombre, e.email, e.programa, p.nombre AS nombre_programa
                FROM estudiantes e
                JOIN programas p ON e.programa = p.codigo
                WHERE e.codigo = :codigo";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
