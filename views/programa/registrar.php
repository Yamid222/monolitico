<?php
require_once "../../config/database.php";
require_once "../../controllers/ProgramaController.php";

if ($_POST) {
    $database = new Database();
    $db = $database->conectar();
    $controller = new ProgramaController($db);

    $resultado = $controller->registrar($_POST);
    if ($resultado['success']) {
        echo "<script>alert('✅ " . $resultado['message'] . "'); window.location='consultar.php';</script>";
    } else {
        echo "<script>alert('❌ " . $resultado['message'] . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Programa de Formación</title>
</head>
<body>
    <h2>Registrar Programa de Formación</h2>
    <form method="POST">
        <label>Código:</label><br>
        <input type="text" name="codigo" required><br><br>

        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <button type="submit">Guardar</button>
        <a href="consultar.php"><button type="button">Volver</button></a>
    </form>
</body>
</html>