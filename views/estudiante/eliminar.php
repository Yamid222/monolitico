<?php
require_once '../../controllers/estudianteController.php';
$controller = new EstudianteController();

$codigo = $_GET['codigo'];
$mensaje = $controller->eliminar($codigo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>🗑️ Eliminar Estudiante</title>
    <link rel="stylesheet" href="../../assets/estilos.css">
</head>
<body>
    <h2>🗑️ Eliminar Estudiante</h2>

    <p><?= $mensaje ?></p>

    <a href="consultar.php">⬅ Volver</a>
</body>
</html>
