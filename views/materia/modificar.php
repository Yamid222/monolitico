<?php
require_once "../../config/database.php";
require_once "../../controllers/MateriaController.php";
require_once "../../controllers/ProgramaController.php";

$database = new Database();
$db = $database->conectar();
$materiaController = new MateriaController($db);
$programaController = new ProgramaController($db);

if (!isset($_GET['codigo'])) {
    header('Location: consultar.php');
    exit;
}

if ($_POST) {
    $resultado = $materiaController->modificar($_GET['codigo'], [
        'nombre' => $_POST['nombre'],
        'programa' => $_POST['programa']
    ]);
    if ($resultado['success']) {
        echo "<script>alert('✅ " . $resultado['message'] . "'); window.location='consultar.php';</script>";
    } else {
        echo "<script>alert('❌ " . $resultado['message'] . "');</script>";
    }
}

$materia = $materiaController->obtenerPorCodigo($_GET['codigo']);
$programas = $programaController->consultar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Materia</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Modificar Materia</h2>
        <form method="POST" class="form-container">
            <div class="form-group">
                <label>Código:</label>
                <input type="text" value="<?php echo $materia['codigo']; ?>" disabled>
            </div>

            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?php echo $materia['nombre']; ?>" required>
            </div>

            <div class="form-group">
                <label>Programa:</label>
                <select name="programa" required>
                    <?php while ($programa = $programas->fetch(PDO::FETCH_ASSOC)) : ?>
                        <option value="<?php echo $programa['codigo']; ?>" <?php if($programa['codigo'] == $materia['programa']) echo 'selected'; ?>>
                            <?php echo $programa['codigo'] . ' - ' . $programa['nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="consultar.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>