<?php
require_once "../../config/database.php";
require_once "../../controllers/MateriaController.php";
require_once "../../controllers/ProgramaController.php";

$database = new Database();
$db = $database->conectar();

if ($_POST) {
    $controller = new MateriaController($db);
    $resultado = $controller->registrar($_POST);
    if ($resultado['success']) {
        echo "<script>alert('✅ " . $resultado['message'] . "'); window.location='consultar.php';</script>";
    } else {
        echo "<script>alert('❌ " . $resultado['message'] . "');</script>";
    }
}

$programaController = new ProgramaController($db);
$programas = $programaController->consultar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Materia</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Materia</h2>
        <form method="POST" class="form-container">
            <div class="form-group">
                <label>Código:</label>
                <input type="text" name="codigo" required>
            </div>

            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Programa:</label>
                <select name="programa" required>
                    <option value="">Seleccione un programa</option>
                    <?php while ($programa = $programas->fetch(PDO::FETCH_ASSOC)) : ?>
                        <option value="<?php echo $programa['codigo']; ?>"><?php echo $programa['codigo'] . ' - ' . $programa['nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn">Guardar</button>
                <a href="consultar.php"><button type="button" class="btn">Volver</button></a>
            </div>
        </form>
    </div>
</body>
</html>