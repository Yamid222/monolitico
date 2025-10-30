<?php
require_once '../../controllers/estudianteController.php';
$controller = new EstudianteController();

$codigo = $_GET['codigo'];
$estudiante = $controller->buscarPorCodigo($codigo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resultado = $controller->actualizar($codigo, $_POST['nombre'], $_POST['email']);
    $estudiante = $controller->buscarPorCodigo($codigo);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>✏️ Editar Estudiante</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>✏️ Editar Estudiante</h2>

        <form method="POST" class="form-container">
            <div class="form-group">
                <label>Código:</label>
                <input type="text" name="codigo" value="<?= $estudiante['codigo'] ?>" readonly>
            </div>

            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?= $estudiante['nombre'] ?>" required>
            </div>

            <div class="form-group">
                <label>Correo electrónico:</label>
                <input type="email" name="email" value="<?= $estudiante['email'] ?>" required>
            </div>

            <div class="form-group">
                <label>Programa:</label>
                <input type="text" value="<?= isset($estudiante['nombre_programa']) ? $estudiante['nombre_programa'] : $estudiante['programa'] ?>" readonly>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="consultar.php" class="btn btn-secondary">Volver</a>
            </div>
        </form>

        <?php if (!empty($resultado)): ?>
            <div class="alert <?= (is_array($resultado) && !empty($resultado['success'])) ? 'alert-success' : 'alert-danger' ?>">
                <?= htmlspecialchars(is_array($resultado) ? ($resultado['message'] ?? '') : (string)$resultado) ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

