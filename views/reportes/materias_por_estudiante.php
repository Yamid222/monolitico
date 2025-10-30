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
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .filtros { margin-bottom: 20px; }
        .estudiante-section { 
            margin-bottom: 30px; 
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        table { 
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td { 
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .volver { margin-top: 20px; }
        button, select {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        select { 
            background-color: white;
            color: black;
            border: 1px solid #ddd;
        }
        button:hover { background-color: #0056b3; }
        .promedio { font-weight: bold; }
    </style>
</head>
<body>
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
                    <table>
                        <thead>
                            <tr>
                                <th>Código Materia</th>
                                <th>Materia</th>
                                <th>Promedio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($m = $materias_disponibles->fetch(PDO::FETCH_ASSOC)) : 
                            $codigo_materia = $m['codigo'];
                            $nombre_materia = $m['nombre'];
                            $promedio = isset($materias_notas[$codigo_materia]) ? $materias_notas[$codigo_materia]['promedio'] : 0;
                        ?>
                            <tr>
                                <td><?php echo $codigo_materia; ?></td>
                                <td><?php echo $nombre_materia; ?></td>
                                <td class="promedio"><?php echo number_format($promedio, 2); ?></td>
                                <td>
                                    <a href="../reportes/notas_estudiante.php?estudiante=<?php echo $estudiante['codigo']; ?>&materia=<?php echo $codigo_materia; ?>">
                                        <button>Ver Notas</button>
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
