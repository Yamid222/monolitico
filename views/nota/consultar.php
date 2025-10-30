<?php
require_once "../../config/database.php";
require_once "../../controllers/NotaController.php";
require_once "../../controllers/EstudianteController.php";

$database = new Database();
$db = $database->conectar();
$notaController = new NotaController($db);
$estudianteController = new EstudianteController($db);

$estudiante = null;
$estudiantes = $estudianteController->listar();

if (isset($_GET['estudiante']) && $_GET['estudiante'] !== '') {
    $estudiante = $estudianteController->obtenerPorCodigo($_GET['estudiante']);
    $notas = $notaController->consultarPorEstudiante($_GET['estudiante']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consultar Notas</title>
        <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Notas de Estudiantes</h2>

        <form method="GET" class="form-container">
            <div class="form-group">
        <label>Seleccione un Estudiante:</label>
        <select name="estudiante" onchange="this.form.submit()">
            <option value="">Seleccione...</option>
            <?php while ($est = $estudiantes->fetch(PDO::FETCH_ASSOC)) : ?>
                        <option value="<?php echo $est['codigo']; ?>"
                                <?php if(isset($_GET['estudiante']) && $_GET['estudiante'] == $est['codigo']) echo 'selected'; ?>>
                    <?php echo $est['codigo'] . ' - ' . $est['nombre']; ?>
                </option>
            <?php endwhile; ?>
        </select>
            </div>
    </form>

    <?php if ($estudiante) : ?>
        <h3>Estudiante: <?php echo $estudiante['nombre']; ?> (<?php echo $estudiante['codigo']; ?>)</h3>
            <div class="btn-group">
                <a href="registrar.php?estudiante=<?php echo $estudiante['codigo']; ?>" class="btn btn-primary">
                    Registrar Nueva Nota
                </a>
            </div>

        <?php if (empty($notas)) : ?>
            <p>No hay notas registradas para este estudiante.</p>
        <?php else : ?>
                <table class="data-table">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Notas</th>
                        <th>Promedio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notas as $materia) : ?>
                        <tr>
                            <td><?php echo $materia['codigo_materia'] . ' - ' . $materia['materia']; ?></td>
                            <td>
                                <?php foreach ($materia['notas'] as $nota) : ?>
                                    <div class="note-item">
                                        <span><?php echo htmlspecialchars($nota['actividad']); ?>:</span>
                                        <span class="note-valor"><?php echo number_format($nota['valor'], 2); ?></span>
                                        <div class="btn-group">
                                            <a href="modificar.php?estudiante=<?php echo urlencode($estudiante['codigo']); ?>&materia=<?php echo urlencode($materia['codigo_materia']); ?>&actividad=<?php echo urlencode($nota['actividad']); ?>" class="btn btn-small btn-secondary">Editar</a>
                                            <a href="eliminar.php?estudiante=<?php echo urlencode($estudiante['codigo']); ?>&materia=<?php echo urlencode($materia['codigo_materia']); ?>&actividad=<?php echo urlencode($nota['actividad']); ?>" class="btn btn-small btn-danger" onclick="return confirm('¿Está seguro?')">Eliminar</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </td>
                            <td class="promedio"><?php echo number_format($materia['promedio'], 2); ?></td>
                            <td>
                                    <a href="registrar.php?estudiante=<?php echo $estudiante['codigo']; ?>&materia=<?php echo $materia['codigo_materia']; ?>" class="btn btn-primary">
                                        Agregar Nota
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
    <br>
        <a href="../../index.html" class="btn btn-secondary">Volver al Inicio</a>
    </div>
</body>
</html>