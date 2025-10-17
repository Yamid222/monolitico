<?php
require_once "../../config/database.php";
require_once "../../controllers/ProgramaController.php";
require_once "../../controllers/EstudianteController.php";

$database = new Database();
$db = $database->conectar();
$programaController = new ProgramaController($db);
$estudianteController = new EstudianteController($db);

$programas = $programaController->consultar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Estudiantes por Programa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .programa-section { 
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
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h1>Estudiantes por Programa de Formación</h1>

    <?php while ($programa = $programas->fetch(PDO::FETCH_ASSOC)) : ?>
        <div class="programa-section">
            <h2><?php echo $programa['codigo'] . ' - ' . $programa['nombre']; ?></h2>
            <?php
            $estudiantes = $estudianteController->consultarPorPrograma($programa['codigo']);
            if ($estudiantes->rowCount() > 0) :
            ?>
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($estudiante = $estudiantes->fetch(PDO::FETCH_ASSOC)) : ?>
                            <tr>
                                <td><?php echo $estudiante['codigo']; ?></td>
                                <td><?php echo $estudiante['nombre']; ?></td>
                                <td><?php echo $estudiante['email']; ?></td>
                                <td>
                                    <a href="../reportes/notas_estudiante.php?estudiante=<?php echo $estudiante['codigo']; ?>">
                                        <button>Ver Notas</button>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay estudiantes registrados en este programa.</p>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

    <div class="volver">
        <a href="../../index.php"><button>Volver al Inicio</button></a>
    </div>
</body>
</html>