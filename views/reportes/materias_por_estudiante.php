<?php
require_once "../../config/database.php";
require_once "../../controllers/EstudianteController.php";
require_once "../../controllers/NotaController.php";
require_once "../../controllers/ProgramaController.php";

$database = new Database();
$db = $database->conectar();
$estudianteController = new EstudianteController(); // este usa su propio db interno
$notaController = new NotaController($db);
$programaController = new ProgramaController($db);

$programas = $programaController->consultar();
$programa_seleccionado = isset($_GET['programa']) ? $_GET['programa'] : null;

$estudiantes = $programa_seleccionado
    ? $estudianteController->consultarPorPrograma($programa_seleccionado)
    : $estudianteController->listar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Materias por Estudiante</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <body class="reportes-materias">
    <h1>Materias por Estudiante</h1>

    <div class="filtros">
        <form method="GET">
            <label>Filtrar por Programa:</label>
            <select name="programa" onchange="this.form.submit()">
                <option value="">Todos los programas</option>
                <?php while ($programa = $programas->fetch(PDO::FETCH_ASSOC)) : ?>
                    <option value="<?php echo $programa['codigo']; ?>"
                            <?php if($programa_seleccionado == $programa['codigo']) echo 'selected'; ?>>
                        <?php echo $programa['codigo'] . ' - ' . $programa['nombre']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>
    </div>

    <?php if ($estudiantes instanceof PDOStatement): ?>
        <?php while ($estudiante = $estudiantes->fetch(PDO::FETCH_ASSOC)) : ?>
            <?php 
                $materias_notas = $notaController->consultarPorEstudiante($estudiante['codigo']);
                $materias_disponibles = $notaController->obtenerMateriasDisponibles($estudiante['codigo']);
            ?>
            <div class="estudiante-section">
                <h2><?php echo $estudiante['codigo'] . ' - ' . $estudiante['nombre']; ?></h2>
                <?php if ($materias_disponibles && $materias_disponibles->rowCount() > 0) : ?>
                    <table class="reportes-table">
                        <thead>
                            <tr>
                                <th class="reportes-th">Código Materia</th>
                                <th class="reportes-th">Materia</th>
                                <th class="reportes-th">Promedio</th>
                                <th class="reportes-th">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($m = $materias_disponibles->fetch(PDO::FETCH_ASSOC)) : 
                            $codigo_materia = $m['codigo'];
                            $nombre_materia = $m['nombre'];
                            $promedio = isset($materias_notas[$codigo_materia]) ? $materias_notas[$codigo_materia]['promedio'] : 0;
                        ?>
                            <tr>
                                <td class="reportes-td"><?php echo $codigo_materia; ?></td>
                                <td class="reportes-td"><?php echo $nombre_materia; ?></td>
                                <td class="reportes-td promedio"><?php echo number_format($promedio, 2); ?></td>
                                <td class="reportes-td">
                                    <a href="../reportes/notas_estudiante.php?estudiante=<?php echo $estudiante['codigo']; ?>&materia=<?php echo $codigo_materia; ?>">
                                        <button class="btn btn-small btn-primary">Ver Notas</button>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Este estudiante no tiene materias registradas en su programa.</p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se pudieron obtener los estudiantes.</p>
    <?php endif; ?>

    <div class="volver">
        <a href="../../index.html"><button>Volver al Inicio</button></a>
    </div>
</body>
</html>
