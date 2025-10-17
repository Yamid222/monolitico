<?php
require_once "../../config/database.php";
require_once "../../controllers/MateriaController.php";
require_once "../../controllers/NotaController.php";
require_once "../../controllers/ProgramaController.php";

$database = new Database();
$db = $database->conectar();
$materiaController = new MateriaController($db);
$notaController = new NotaController($db);
$programaController = new ProgramaController($db);

// Filtrar por programa si se selecciona
$programas = $programaController->consultar();
$programa_seleccionado = isset($_GET['programa']) ? $_GET['programa'] : null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Estudiantes por Materia</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .filtros { margin-bottom: 20px; }
        .materia-section { 
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
    <h1>Estudiantes por Materia</h1>

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

    <?php 
    $materias = $programa_seleccionado ? 
        $materiaController->consultarPorPrograma($programa_seleccionado) : 
        $materiaController->consultar();
    
    while ($materia = $materias->fetch(PDO::FETCH_ASSOC)) : 
        $estudiantes = $notaController->consultarEstudiantesPorMateria($materia['codigo']);
    ?>
        <div class="materia-section">
            <h2><?php echo $materia['codigo'] . ' - ' . $materia['nombre']; ?></h2>
            <?php if ($estudiantes && count($estudiantes) > 0) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Promedio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes as $estudiante) : ?>
                            <tr>
                                <td><?php echo $estudiante['codigo']; ?></td>
                                <td><?php echo $estudiante['nombre']; ?></td>
                                <td class="promedio"><?php echo number_format($estudiante['promedio'], 2); ?></td>
                                <td>
                                    <a href="../reportes/notas_estudiante.php?estudiante=<?php echo $estudiante['codigo']; ?>&materia=<?php echo $materia['codigo']; ?>">
                                        <button>Ver Notas</button>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay estudiantes registrados en esta materia.</p>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

    <div class="volver">
        <a href="../../index.php"><button>Volver al Inicio</button></a>
    </div>
</body>
</html>