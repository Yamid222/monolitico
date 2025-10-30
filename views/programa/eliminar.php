<?php
require_once "../../config/database.php";
require_once "../../controllers/ProgramaController.php";

$database = new Database();
$db = $database->conectar();
$controller = new ProgramaController($db);

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $resultado = $controller->eliminar($codigo);
    if (!empty($resultado['success']) && $resultado['success'] === true) {
        echo "<script>alert('✅ " . $resultado['message'] . "'); window.location='consultar.php';</script>";
    } else {
        $msg = $resultado['message'] ?? 'No se pudo eliminar el programa.';
        echo "<script>alert('❌ " . $msg . "'); window.location='consultar.php';</script>";
    }
} else {
    header("Location: consultar.php");
}
?>