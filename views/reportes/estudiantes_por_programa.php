<?php
require_once "../../config/database.php";
require_once "../../controllers/ProgramaController.php";
require_once "../../controllers/EstudianteController.php";

$database = new Database();
$db = $database->conectar();
$programaController = new ProgramaController($db);
$estudianteController = new EstudianteController();

$programas = $programaController->consultar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Estudiantes por Programa</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Estudiantes por Programa de Formación</h1>

        <?php while ($programa = $programas->fetch(PDO::FETCH_ASSOC)) : ?>
            <div class="section-box">
                <h2><?php echo $programa['codigo'] . ' - ' . $programa['nombre']; ?></h2>
                <?php
                $estudiantes = $estudianteController->consultarPorPrograma($programa['codigo']);
                if ($estudiantes->rowCount() > 0) :
                ?>
                    <table class="data-table">
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
                                        <div class="btn-group">
                                            <a href="../reportes/notas_estudiante.php?estudiante=<?php echo $estudiante['codigo']; ?>" class="btn btn-small btn-secondary">Ver Notas</a>
                                        </div>
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

        <div class="btn-group mt-3">
            <a href="../../index.html" class="btn btn-secondary">Volver al Inicio</a>
        </div>
    </div>
</body>
</html>