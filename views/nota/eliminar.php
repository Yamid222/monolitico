<?php
require_once "../../config/database.php";
require_once "../../controllers/NotaController.php";

$database = new Database();
$db = $database->conectar();
$controller = new NotaController($db);

if (isset($_GET['estudiante'], $_GET['materia'], $_GET['actividad'])) {
    $id_estudiante = $_GET['estudiante'];
    $data = [
        'materia'    => $_GET['materia'],
        'estudiante' => $_GET['estudiante'],
        'actividad'  => $_GET['actividad']
    ];

    $resultado = $controller->eliminar($data);
    if ($resultado['success']) {
        echo "<script>alert('✅ " . $resultado['message'] . "'); window.location='consultar.php?estudiante=" . $id_estudiante . "';</script>";
    } else {
        echo "<script>alert('❌ " . $resultado['message'] . "'); window.location='consultar.php?estudiante=" . $id_estudiante . "';</script>";
    }
} else {
    header("Location: consultar.php");
}
?>