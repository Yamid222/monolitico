<?php
require_once '../../controllers/estudianteController.php';
$controller = new EstudianteController();

$codigo = $_GET['codigo'];
$estudiante = $controller->buscarPorCodigo($codigo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mensaje = $controller->actualizar($codigo, $_POST['nombre'], $_POST['email']);
    $estudiante = $controller->buscarPorCodigo($codigo);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>✏️ Editar Estudiante</title>
    <link rel="stylesheet" href="../../assets/estilos.css">
</head>
<body>
    <h2>✏️ Editar Estudiante</h2>

    <form method="POST">
        <label>Código:</label>
        <input type="text" name="codigo" value="<?= $estudiante['codigo'] ?>" readonly><br>

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $estudiante['nombre'] ?>" required><br>

        <label>Correo electrónico:</label>
        <input type="email" name="email" value="<?= $estudiante['email'] ?>" required><br>

        <label>Programa:</label>
        <input type="text" value="<?= $estudiante['programa'] ?>" readonly><br>

        <button type="submit">Actualizar</button>
    </form>

    <?php if (!empty($mensaje)): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>

    <a href="consultar.php">⬅ Volver</a>
</body>
</html>

