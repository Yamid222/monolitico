<?php
class Database {
    private $host = "localhost";
    private $db_name = "notas_app";
    private $username = "root";
    private $password = "";
    private $charset = "utf8mb4";
    public $conn;

    public function conectar() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . 
                ";dbname=" . $this->db_name . 
                ";charset=" . $this->charset,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "❌ Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
