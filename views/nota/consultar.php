<?php
require_once "../../config/database.php";
require_once "../../controllers/NotaController.php";
require_once "../../controllers/EstudianteController.php";

$database = new Database();
$db = $database->conectar();
$notaController = new NotaController($db);
$estudianteController = new EstudianteController($db);

// Obtener estudiante seleccionado o lista de estudiantes
$estudiante = null;
$estudiantes = $estudianteController->consultar();

if (isset($_GET['estudiante'])) {
    $estudiante = $estudianteController->obtenerPorId($_GET['estudiante']);
    $notas = $notaController->consultarPorEstudiante($_GET['estudiante']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consultar Notas</title>
        <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Notas de Estudiantes</h2>

    <!-- Selector de estudiante -->
        <form method="GET" class="form-container">
            <div class="form-group">
        <label>Seleccione un Estudiante:</label>
        <select name="estudiante" onchange="this.form.submit()">
            <option value="">Seleccione...</option>
            <?php while ($est = $estudiantes->fetch(PDO::FETCH_ASSOC)) : ?>
                        <option value="<?php echo $est['id_estudiante']; ?>"
                                <?php if(isset($_GET['estudiante']) && $_GET['estudiante'] == $est['id_estudiante']) echo 'selected'; ?>>
                    <?php echo $est['codigo'] . ' - ' . $est['nombre']; ?>
                </option>
            <?php endwhile; ?>
        </select>
            </div>
    </form>

    <?php if ($estudiante) : ?>
        <h3>Estudiante: <?php echo $estudiante['nombre']; ?> (<?php echo $estudiante['codigo']; ?>)</h3>
            <div class="button-group">
                <a href="registrar.php?estudiante=<?php echo $estudiante['id_estudiante']; ?>" class="btn btn-primary">
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
                                <?php 
                                foreach ($materia['notas'] as $nota) {
                                    echo number_format($nota['valor'], 2) . ' ';
                                        echo "<div class='action-links'>";
                                        echo "<a href='modificar.php?id=" . $nota['id_nota'] . "' class='btn btn-small btn-secondary'>Editar</a> ";
                                        echo "<a href='eliminar.php?id=" . $nota['id_nota'] . "' class='btn btn-small btn-danger' onclick='return confirm(\"¿Está seguro?\")'>Eliminar</a>";
                                        echo "</div>";
                                }
                                ?>
                            </td>
                            <td class="promedio"><?php echo number_format($materia['promedio'], 2); ?></td>
                            <td>
                                    <a href="registrar.php?estudiante=<?php echo $estudiante['id_estudiante']; ?>&id_materia=<?php echo $materia['id_materia']; ?>" class="btn btn-primary">
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
        <a href="../../index.php" class="btn btn-secondary">Volver al Inicio</a>
    </div>
</body>
</html>