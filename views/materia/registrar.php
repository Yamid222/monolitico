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

// Obtener lista de programas para el select
$programaController = new ProgramaController($db);
$programas = $programaController->consultar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Materia</title>
</head>
<body>
    <h2>Registrar Materia</h2>
    <form method="POST">
        <label>Código:</label><br>
        <input type="text" name="codigo" required><br><br>

        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Programa:</label><br>
        <select name="id_programa" required>
            <option value="">Seleccione un programa</option>
            <?php while ($programa = $programas->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?php echo $programa['id_programa']; ?>">
                    <?php echo $programa['nombre']; ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit">Guardar</button>
        <a href="consultar.php"><button type="button">Volver</button></a>
    </form>
</body>
</html>