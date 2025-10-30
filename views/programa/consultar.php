<?php
require_once "../../config/database.php";
require_once "../../controllers/ProgramaController.php";

$database = new Database();
$db = $database->conectar();
$controller = new ProgramaController($db);
$programas = $controller->consultar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consultar Programas de Formación</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Programas de Formación</h2>

        <div class="btn-group mb-2">
            <a href="registrar.php" class="btn btn-primary">➕ Nuevo Programa</a>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $programas->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['codigo']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="modificar.php?codigo=<?php echo urlencode($row['codigo']); ?>" class="btn btn-small btn-secondary">Editar</a>
                                <a href="eliminar.php?codigo=<?php echo urlencode($row['codigo']); ?>" class="btn btn-small btn-danger" onclick="return confirm('¿Está seguro de eliminar este programa?')">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="btn-group mt-3">
            <a href="../../index.html" class="btn btn-secondary">Volver al Inicio</a>
        </div>
    </div>
</body>
</html>