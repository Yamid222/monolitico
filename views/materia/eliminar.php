<?php
require_once "../../config/database.php";
require_once "../../controllers/MateriaController.php";

$database = new Database();
$db = $database->conectar();
$controller = new MateriaController($db);

if (isset($_GET['id'])) {
    $resultado = $controller->eliminar($_GET['id']);
    if ($resultado['success']) {
        echo "<script>alert('✅ " . $resultado['message'] . "'); window.location='consultar.php';</script>";
    } else {
        echo "<script>alert('❌ " . $resultado['message'] . "'); window.location='consultar.php';</script>";
    }
} else {
    header("Location: consultar.php");
}
?>