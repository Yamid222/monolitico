<?php
require_once "../../config/database.php";
require_once "../../controllers/ProgramaController.php";

$database = new Database();
$db = $database->conectar();
$controller = new ProgramaController($db);

if ($_POST) {
    $resultado = $controller->modificar($_GET['id'], $_POST);
    if ($resultado['success']) {
        echo "<script>alert('✅ " . $resultado['message'] . "'); window.location='consultar.php';</script>";
    } else {
        echo "<script>alert('❌ " . $resultado['message'] . "');</script>";
    }
}

$programa = $controller->obtenerPorId($_GET['id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Programa de Formación</title>
</head>
<body>
    <h2>Modificar Programa de Formación</h2>
    <form method="POST">
        <label>Código:</label><br>
        <input type="text" value="<?php echo $programa['codigo']; ?>" disabled><br><br>

        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?php echo $programa['nombre']; ?>" required><br><br>

        <button type="submit">Guardar Cambios</button>
        <a href="consultar.php"><button type="button">Cancelar</button></a>
    </form>
</body>
</html>