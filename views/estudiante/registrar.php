<?php
require_once '../../controllers/estudianteController.php';
$controller = new EstudianteController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mensaje = $controller->registrar($_POST['codigo'], $_POST['nombre'], $_POST['email'], $_POST['programa']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Estudiante</title>
    <link rel="stylesheet" href="../../assets/estilos.css">
</head>
<body>
    <h2>➕ Registrar Estudiante</h2>
    <form method="POST">
        <label>Código:</label>
        <input type="text" name="codigo" required><br>
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Programa:</label>
        <input type="text" name="programa" required><br>
        <button type="submit">Guardar</button>
        <label>Programa de Formación:</label>
        <select name="programa" required>
        <option value="">Seleccione un programa</option>
        <?php
        require_once '../../models/drivers/conexDB.php';
        $db = new conexDB();
        $conn = $db->getConexion();
        $programas = $conn->query("SELECT codigo, nombre FROM programas");
        while ($prog = $programas->fetch_assoc()) {
            echo "<option value='{$prog['codigo']}'>{$prog['nombre']}</option>";
        }
        ?>
        </select><br>

    </form>

    <?php if (!empty($mensaje)): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>

    <a href="consultar.php">⬅ Volver</a>
</body>
</html>
