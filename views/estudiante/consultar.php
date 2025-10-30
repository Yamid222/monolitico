<?php
require_once __DIR__ . '/../../controllers/estudianteController.php';
$controller = new EstudianteController();
$estudiantes = $controller->listar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar Estudiantes</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Estudiantes</h1>

        <div class="btn-group mb-2">
            <a href="registrar.php" class="btn btn-primary">➕ Registrar Estudiante</a>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Programa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $estudiantes->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['codigo']) ?></td>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['email']) ?></td>
                        <td><?= htmlspecialchars($fila['programa']) ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="modificar.php?codigo=<?= urlencode($fila['codigo']) ?>" class="btn btn-small btn-secondary">Editar</a>
                                <a href="eliminar.php?codigo=<?= urlencode($fila['codigo']) ?>" class="btn btn-small btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este estudiante?');">Eliminar</a>
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
