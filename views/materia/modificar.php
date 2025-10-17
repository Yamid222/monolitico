<?php
require_once "../../config/database.php";
require_once "../../controllers/MateriaController.php";
require_once "../../controllers/ProgramaController.php";

$database = new Database();
$db = $database->conectar();
$materiaController = new MateriaController($db);
$programaController = new ProgramaController($db);

if ($_POST) {
    $resultado = $materiaController->modificar($_GET['id'], $_POST);
    if ($resultado['success']) {
        echo "<script>alert('✅ " . $resultado['message'] . "'); window.location='consultar.php';</script>";
    } else {
        echo "<script>alert('❌ " . $resultado['message'] . "');</script>";
    }
}

$materia = $materiaController->obtenerPorId($_GET['id']);
$programas = $programaController->consultar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Materia</title>
</head>
<body>
    <h2>Modificar Materia</h2>
    <form method="POST">
        <label>Código:</label><br>
        <input type="text" value="<?php echo $materia['codigo']; ?>" disabled><br><br>

        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?php echo $materia['nombre']; ?>" required><br><br>

        <label>Programa:</label><br>
        <select name="id_programa" required>
            <?php while ($programa = $programas->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?php echo $programa['id_programa']; ?>"
                        <?php if($programa['id_programa'] == $materia['id_programa']) echo 'selected'; ?>>
                    <?php echo $programa['nombre']; ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit">Guardar Cambios</button>
        <a href="consultar.php"><button type="button">Cancelar</button></a>
    </form>
</body>
</html>