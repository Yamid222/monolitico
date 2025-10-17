<?php
require_once "../../config/database.php";
require_once "../../controllers/NotaController.php";

$database = new Database();
$db = $database->conectar();
$controller = new NotaController($db);

if (isset($_GET['id'])) {
    // Obtener la información de la nota antes de eliminarla
    $nota = $controller->obtenerPorId($_GET['id']);
    $id_estudiante = $nota['id_estudiante'];
    
    $resultado = $controller->eliminar($_GET['id']);
    if ($resultado['success']) {
        echo "<script>alert('✅ " . $resultado['message'] . "'); window.location='consultar.php?estudiante=" . $id_estudiante . "';</script>";
    } else {
        echo "<script>alert('❌ " . $resultado['message'] . "'); window.location='consultar.php?estudiante=" . $id_estudiante . "';</script>";
    }
} else {
    header("Location: consultar.php");
}
?>