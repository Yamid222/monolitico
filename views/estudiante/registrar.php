<?php
require_once "../../config/database.php";
require_once "../../controllers/EstudianteController.php";

if ($_POST) {
    $database = new Database();
    $db = $database->conectar();
    $controller = new EstudianteController($db);

    if ($controller->registrar($_POST)) {
        echo "<script>alert('✅ Estudiante registrado con éxito'); window.location='listar.php';</script>";
    } else {
        echo "<script>alert('❌ Error al registrar estudiante');</script>";
    }
}
?>

<h2>Registrar Estudiante</h2>
<form method="POST">
    <label>Código:</label><br>
    <input type="text" name="codigo" required><br><br>

    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Correo:</label><br>
    <input type="email" name="correo" required><br><br>

    <label>ID Programa (opcional):</label><br>
    <input type="number" name="id_programa"><br><br>

    <button type="submit">Guardar</button>
</form>
