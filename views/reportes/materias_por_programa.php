<?php
require_once "../../config/database.php";
require_once "../../controllers/ProgramaController.php";
require_once "../../controllers/MateriaController.php";

$database = new Database();
$db = $database->conectar();
$programaController = new ProgramaController($db);
$materiaController = new MateriaController($db);

$programas = $programaController->consultar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Materias por Programa</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Materias por Programa de Formación</h1>

        <?php while ($programa = $programas->fetch(PDO::FETCH_ASSOC)) : ?>
            <div class="section-box">
                <h2><?php echo $programa['codigo'] . ' - ' . $programa['nombre']; ?></h2>
                <?php
                $materias = $materiaController->consultarPorPrograma($programa['codigo']);
                if ($materias->rowCount() > 0) :
                ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($materia = $materias->fetch(PDO::FETCH_ASSOC)) : ?>
                                <tr>
                                    <td><?php echo $materia['codigo']; ?></td>
                                    <td><?php echo $materia['nombre']; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a class="btn btn-small btn-secondary" href="../materia/modificar.php?codigo=<?php echo $materia['codigo']; ?>">Editar</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay materias registradas para este programa.</p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>

        <div class="btn-group mt-3">
            <a class="btn btn-secondary" href="../../index.html">Volver al Inicio</a>
        </div>
    </div>
</body>
</html>