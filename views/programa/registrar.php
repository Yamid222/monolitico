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
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Programa de Formación</h2>
        <form method="POST" class="form-container">
            <div class="form-group">
                <label>Código:</label>
                <input type="text" name="codigo" required>
            </div>

            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn">Guardar</button>
                <a href="consultar.php" class="btn">Volver</a>
            </div>
        </form>
    </div>
</body>
</html>