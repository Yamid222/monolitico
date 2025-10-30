<?php
class Database {
    private $host = 'localhost';
    private $db   = 'notas_app';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';

    public function conectar(): PDO {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn, $this->user, $this->pass, $options);
    }
}
