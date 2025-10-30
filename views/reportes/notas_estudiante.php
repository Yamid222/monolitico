<?php
require_once "../../config/database.php";
require_once "../../controllers/EstudianteController.php";
require_once "../../controllers/NotaController.php";

$database = new Database();
$db = $database->conectar();
$estudianteController = new EstudianteController();
$notaController = new NotaController($db);

if (isset($_GET['estudiante']) && $_GET['estudiante'] !== '') {
    $estudiante = $estudianteController->obtenerPorCodigo($_GET['estudiante']);
    $materias_notas = $notaController->consultarPorEstudiante($_GET['estudiante']);
    $materias_disponibles = $notaController->obtenerMateriasDisponibles($_GET['estudiante']);
} else {
    $todos_estudiantes = $estudianteController->listar();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notas del Estudiante</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Notas por Estudiante</h1>

        <?php if (isset($estudiante)) : ?>
            <div class="section-box">
                <h2><?php echo $estudiante['codigo'] . ' - ' . $estudiante['nombre']; ?></h2>
                <p>Programa: <?php echo $estudiante['nombre_programa']; ?></p>
            </div>

            <?php while ($m = $materias_disponibles->fetch(PDO::FETCH_ASSOC)) : 
                $codigo_materia = $m['codigo'];
                $nombre_materia = $m['nombre'];
                $datos = isset($materias_notas[$codigo_materia]) ? $materias_notas[$codigo_materia] : [
                    'materia' => $nombre_materia,
                    'promedio' => 0,
                    'notas' => []
                ];
            ?>
                <div class="section-box">
                    <h3><?php echo $datos['materia']; ?></h3>
                    <p class="promedio">Promedio: <?php echo number_format($datos['promedio'], 2); ?></p>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Actividad</th>
                                <th>Nota</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($datos['notas'])) : ?>
                                <?php foreach ($datos['notas'] as $nota) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($nota['actividad']); ?></td>
                                        <td><?php echo number_format($nota['valor'], 2); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a class="btn btn-small btn-secondary" href="../nota/modificar.php?estudiante=<?php echo $estudiante['codigo']; ?>&materia=<?php echo $codigo_materia; ?>&actividad=<?php echo urlencode($nota['actividad']); ?>">Editar</a>
                                                <a class="btn btn-small btn-danger" href="../nota/eliminar.php?estudiante=<?php echo $estudiante['codigo']; ?>&materia=<?php echo $codigo_materia; ?>&actividad=<?php echo urlencode($nota['actividad']); ?>" onclick="return confirm('¿Está seguro de eliminar esta nota?')">Eliminar</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td>—</td>
                                    <td>0.00</td>
                                    <td>
                                        <div class="btn-group">
                                            <a class="btn btn-small btn-primary" href="../nota/registrar.php?estudiante=<?php echo $estudiante['codigo']; ?>&materia=<?php echo $codigo_materia; ?>">Agregar Nota</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endwhile; ?>

        <?php else: ?>
            <?php while ($est = $todos_estudiantes->fetch(PDO::FETCH_ASSOC)) : ?>
                <?php 
                    $materias_notas = $notaController->consultarPorEstudiante($est['codigo']);
                    $materias_disponibles = $notaController->obtenerMateriasDisponibles($est['codigo']);
                ?>
                <div class="section-box">
                    <h2><?php echo $est['codigo'] . ' - ' . $est['nombre']; ?></h2>
                    <?php while ($m = $materias_disponibles->fetch(PDO::FETCH_ASSOC)) : 
                        $codigo_materia = $m['codigo'];
                        $nombre_materia = $m['nombre'];
                        $datos = isset($materias_notas[$codigo_materia]) ? $materias_notas[$codigo_materia] : [
                            'materia' => $nombre_materia,
                            'promedio' => 0,
                            'notas' => []
                        ];
                    ?>
                        <div class="section-box" style="background:#fff;">
                            <h3><?php echo $datos['materia']; ?></h3>
                            <p class="promedio">Promedio: <?php echo number_format($datos['promedio'], 2); ?></p>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Actividad</th>
                                        <th>Nota</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($datos['notas'])) : ?>
                                        <?php foreach ($datos['notas'] as $nota) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($nota['actividad']); ?></td>
                                                <td><?php echo number_format($nota['valor'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td>—</td>
                                            <td>0.00</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

        <div class="btn-group mt-3">
            <a class="btn btn-secondary" href="../../index.html">Volver al Inicio</a>
            <a class="btn" href="../estudiante/consultar.php">Volver a Estudiantes</a>
        </div>
    </div>
</body>
</html>