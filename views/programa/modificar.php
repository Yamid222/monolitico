<?php
require_once "../../config/database.php";
require_once "../../controllers/ProgramaController.php";

$database = new Database();
$db = $database->conectar();
$controller = new ProgramaController($db);

if ($_POST) {
    $codigo = $_GET['codigo'] ?? '';
    $resultado = $controller->modificar($codigo, $_POST);
    if ($resultado['success']) {
        echo "<script>alert('✅ " . $resultado['message'] . "'); window.location='consultar.php';</script>";
    } else {
        echo "<script>alert('❌ " . $resultado['message'] . "');</script>";
    }
}

$codigo = $_GET['codigo'] ?? '';
$programa = $controller->obtenerPorCodigo($codigo);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Programa de Formación</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Modificar Programa de Formación</h2>
        <form method="POST" class="form-container">
            <div class="form-group">
                <label>Código:</label>
                <input type="text" value="<?php echo $programa['codigo']; ?>" disabled>
            </div>

            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?php echo $programa['nombre']; ?>" required>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="consultar.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>