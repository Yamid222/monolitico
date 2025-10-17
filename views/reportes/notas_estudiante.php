<?php
require_once "../../config/database.php";
require_once "../../controllers/EstudianteController.php";
require_once "../../controllers/NotaController.php";

$database = new Database();
$db = $database->conectar();
$estudianteController = new EstudianteController($db);
$notaController = new NotaController($db);

if (!isset($_GET['estudiante'])) {
    header("Location: ../estudiante/consultar.php");
    exit;
}

$estudiante = $estudianteController->obtenerPorCodigo($_GET['estudiante']);
$materias_notas = $notaController->consultarPorEstudiante($_GET['estudiante']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notas del Estudiante</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="info-estudiante">
        <h1>Notas del Estudiante</h1>
        <h2><?php echo $estudiante['codigo'] . ' - ' . $estudiante['nombre']; ?></h2>
        <h3>Programa: <?php echo $estudiante['nombre_programa']; ?></h3>
    </div>

    <?php foreach ($materias_notas as $codigo_materia => $materia) : ?>
        <div class="materia-section">
            <h3><?php echo $materia['materia']; ?></h3>
            <p class="promedio">Promedio: <?php echo number_format($materia['promedio'], 2); ?></p>
            
            <table>
                <thead>
                    <tr>
                        <th>Actividad</th>
                        <th>Nota</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materia['notas'] as $nota) : ?>
                        <tr>
                            <td><?php echo $nota['actividad']; ?></td>
                            <td><?php echo number_format($nota['nota'], 2); ?></td>
                            <td>
                                <a href="../nota/modificar.php?estudiante=<?php echo $estudiante['codigo']; ?>&materia=<?php echo $codigo_materia; ?>&actividad=<?php echo urlencode($nota['actividad']); ?>">
                                    <button>Modificar</button>
                                </a>
                                <a href="../nota/eliminar.php?estudiante=<?php echo $estudiante['codigo']; ?>&materia=<?php echo $codigo_materia; ?>&actividad=<?php echo urlencode($nota['actividad']); ?>"
                                   onclick="return confirm('¿Está seguro de eliminar esta nota?')">
                                    <button>Eliminar</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="margin-top: 10px;">
                <a href="../nota/registrar.php?estudiante=<?php echo $estudiante['codigo']; ?>&materia=<?php echo $codigo_materia; ?>">
                    <button>Agregar Nota</button>
                </a>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="volver">
        <a href="../../index.php"><button>Volver al Inicio</button></a>
        <a href="../estudiante/consultar.php"><button>Volver a Estudiantes</button></a>
    </div>
</body>
</html>