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
</head>
<body>
    <h1>Lista de Estudiantes</h1>

    <table border="1" cellpadding="10">
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Programa</th>
            <th>Acciones</th>
        </tr>

        <?php foreach ($estudiantes as $fila): ?>
            <tr>
                <td><?= $fila['codigo'] ?></td>
                <td><?= $fila['nombre'] ?></td>
                <td><?= $fila['correo'] ?></td>
                <td><?= $fila['id_programa'] ?></td>
                <td>
                    <a href="editar.php?codigo=<?= $fila['codigo'] ?>">✏️ Editar</a> |
                    <a href="eliminar.php?codigo=<?= $fila['codigo'] ?>"
                       onclick="return confirm('¿Seguro que deseas eliminar este estudiante?');">
                       🗑️ Eliminar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
