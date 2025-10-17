<?php
require_once "../../config/database.php";
require_once "../../controllers/NotaController.php";

$database = new Database();
$db = $database->conectar();
$controller = new NotaController($db);

if (!isset($_GET['id'])) {
    header("Location: consultar.php");
    exit;
}

$nota = $controller->obtenerPorId($_GET['id']);

if ($_POST) {
    $resultado = $controller->modificar($_GET['id'], $_POST);
    if ($resultado['success']) {
        echo "<script>alert('✅ " . $resultado['message'] . "'); window.location='consultar.php?estudiante=" . $nota['id_estudiante'] . "';</script>";
    } else {
        echo "<script>alert('❌ " . $resultado['message'] . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Nota</title>
        <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Modificar Nota</h2>
        <h3>Estudiante: <?php echo $nota['nombre_estudiante']; ?></h3>
        <h4>Materia: <?php echo $nota['nombre_materia']; ?></h4>

        <form method="POST" class="form-container">
            <div class="form-group">
                <label>Nota (0.00 - 5.00):</label>
                <input type="number" name="valor" step="0.01" min="0" max="5" 
                       value="<?php echo $nota['valor']; ?>" required>
            </div>

            <div class="form-group buttons">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="consultar.php?estudiante=<?php echo $nota['id_estudiante']; ?>" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</body>
</html>