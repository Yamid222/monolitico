<?php
require_once "../../config/database.php";
require_once "../../controllers/MateriaController.php";
require_once "../../controllers/NotaController.php";
require_once "../../controllers/ProgramaController.php";
require_once "../../controllers/EstudianteController.php";

$database = new Database();
$db = $database->conectar();
$materiaController = new MateriaController($db);
$notaController = new NotaController($db);
$programaController = new ProgramaController($db);
$estudianteController = new EstudianteController();

$programas = $programaController->consultar();
$programa_seleccionado = isset($_GET['programa']) ? $_GET['programa'] : null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Estudiantes por Materia</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Estudiantes por Materia</h1>

        <div class="filtros">
            <form method="GET" class="form-container">
                <div class="form-group">
                    <label>Filtrar por Programa:</label>
                    <select name="programa" onchange="this.form.submit()">
                        <option value="">Todos los programas</option>
                        <?php while ($programa = $programas->fetch(PDO::FETCH_ASSOC)) : ?>
                            <option value="<?php echo $programa['codigo']; ?>" <?php if($programa_seleccionado == $programa['codigo']) echo 'selected'; ?>>
                                <?php echo $programa['codigo'] . ' - ' . $programa['nombre']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </form>
        </div>

    <?php 
    $materias = $programa_seleccionado ? 
        $materiaController->consultarPorPrograma($programa_seleccionado) : 
        $materiaController->consultar();
    
    while ($materia = $materias->fetch(PDO::FETCH_ASSOC)) : 
        $estudiantesPrograma = $estudianteController->consultarPorPrograma($materia['programa']);
    ?>
        <div class="section-box">
            <h2><?php echo $materia['codigo'] . ' - ' . $materia['nombre']; ?></h2>
            <?php if ($estudiantesPrograma && $estudiantesPrograma->rowCount() > 0) : ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Promedio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($estudiante = $estudiantesPrograma->fetch(PDO::FETCH_ASSOC)) : 
                            $promedio = $notaController->obtenerPromedioPorMateria($estudiante['codigo'], $materia['codigo']);
                        ?>
                            <tr>
                                <td><?php echo $estudiante['codigo']; ?></td>
                                <td><?php echo $estudiante['nombre']; ?></td>
                                <td class="promedio"><?php echo number_format($promedio, 2); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-small btn-secondary" href="../reportes/notas_estudiante.php?estudiante=<?php echo $estudiante['codigo']; ?>&materia=<?php echo $materia['codigo']; ?>">Ver Notas</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay estudiantes registrados en el programa de esta materia.</p>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

        <div class="btn-group mt-3">
            <a class="btn btn-secondary" href="../../index.html">Volver al Inicio</a>
        </div>
    </div>
</body>
</html>