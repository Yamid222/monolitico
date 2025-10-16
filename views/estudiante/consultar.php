<?php
require_once "../../config/database.php";
require_once "../../controllers/EstudianteController.php";

$database = new Database();
$db = $database->conectar();
$controller = new EstudianteController($db);
$estudiantes = $controller->consultar();
?>

<h2>Listado de Estudiantes</h2>
<a href="registrar.php">➕ Nuevo Estudiante</a>
<table border="1" cellpadding="5">
    <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Programa</th>
        <th>Acciones</th>
    </tr>
    <?php foreach($estudiantes as $e): ?>
    <tr>
        <td><?= $e['codigo'] ?></td>
        <td><?= $e['nombre'] ?></td>
        <td><?= $e['correo'] ?></td>
        <td><?= $e['id_programa'] ?></td>
        <td>
            <a href="editar.php?id=<?= $e['id_estudiante'] ?>">✏️ Editar</a> |
            <a href="eliminar.php?id=<?= $e['id_estudiante'] ?>"
               onclick="return confirm('¿Seguro que deseas eliminar este estudiante?')">🗑️ Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
