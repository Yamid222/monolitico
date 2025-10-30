<?php
require_once "../../config/database.php";
require_once "../../controllers/NotaController.php";
require_once "../../controllers/EstudianteController.php";

$database = new Database();
$db = $database->conectar();
$notaController = new NotaController($db);
$estudianteController = new EstudianteController($db);

if (!isset($_GET['estudiante'])) {
    header('Location: consultar.php');
    exit;
}

$estudiante = $estudianteController->obtenerPorCodigo($_GET['estudiante']);
$materias = $notaController->obtenerMateriasDisponibles($_GET['estudiante']);

$errorMessage = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $notaController->registrar([
        'estudiante' => $_GET['estudiante'],
        'materia' => $_POST['materia'] ?? null,
        'actividad' => $_POST['actividad'] ?? 'Actividad',
        'valor' => $_POST['valor'] ?? null
    ]);

    if ($resultado['success']) {
        echo "<script>alert('✅ " . $resultado['message'] . "'); window.location='consultar.php?estudiante=" . $_GET['estudiante'] . "';</script>";
        exit;
    } else {
        $errorMessage = $resultado['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Registrar Nota</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Nota</h2>
        <h3>Estudiante: <?php echo htmlspecialchars($estudiante['nombre']); ?> (<?php echo htmlspecialchars($estudiante['codigo']); ?>)</h3>

        <?php if (!empty($errorMessage)) : ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <form method="POST" class="form-container">
            <div class="form-group">
                <label>Materia:</label>
                <select name="materia" required>
                    <option value="">Seleccione una materia</option>
                    <?php while ($materia = $materias->fetch(PDO::FETCH_ASSOC)) : ?>
                        <option value="<?php echo $materia['codigo']; ?>" <?php if (isset($_POST['materia']) && $_POST['materia'] == $materia['codigo']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($materia['codigo'] . ' - ' . $materia['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Actividad:</label>
                <input type="text" name="actividad" maxlength="50" value="<?php echo isset($_POST['actividad']) ? htmlspecialchars($_POST['actividad']) : 'Actividad'; ?>">
            </div>
            <div class="form-group">
                <label>Nota (0.00 - 5.00):</label>
                <input type="number" name="valor" step="0.01" min="0.01" max="5" required value="<?php echo isset($_POST['valor']) ? htmlspecialchars($_POST['valor']) : ''; ?>">
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="consultar.php?estudiante=<?php echo $_GET['estudiante']; ?>" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>