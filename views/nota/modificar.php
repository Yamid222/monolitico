<?php
require_once "../../config/database.php";
require_once "../../controllers/NotaController.php";

$database = new Database();
$db = $database->conectar();
$controller = new NotaController($db);

if (!isset($_GET['estudiante'], $_GET['materia'], $_GET['actividad'])) {
    header("Location: consultar.php");
    exit;
}

$sql = "SELECT e.codigo AS id_estudiante, e.nombre AS nombre_estudiante,
               m.codigo AS id_materia, m.nombre AS nombre_materia,
               n.actividad, n.nota AS valor
        FROM notas n
        JOIN estudiantes e ON e.codigo = n.estudiante
        JOIN materias m ON m.codigo = n.materia
        WHERE n.estudiante = :est AND n.materia = :mat AND n.actividad = :act";
$st = $db->prepare($sql);
$st->bindParam(':est', $_GET['estudiante']);
$st->bindParam(':mat', $_GET['materia']);
$st->bindParam(':act', $_GET['actividad']);
$st->execute();
$nota = $st->fetch(PDO::FETCH_ASSOC);

if (!$nota) {
    echo "<script>alert('❌ Nota no encontrada'); window.location='consultar.php';</script>";
    exit;
}

if ($_POST) {
    $data = [
        'estudiante' => $_GET['estudiante'],
        'materia'    => $_GET['materia'],
        'actividad'  => $_GET['actividad'],
        'valor'      => $_POST['valor']
    ];
    $resultado = $controller->modificar($data);
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
        <link rel="stylesheet" href="../../assets/css/styles.css">
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