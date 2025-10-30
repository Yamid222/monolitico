<?php
require_once '../../controllers/estudianteController.php';
$controller = new EstudianteController();

$codigo = $_GET['codigo'];
$resultado = $controller->eliminar($codigo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>🗑️ Eliminar Estudiante</title>
    <link rel="stylesheet" href="../../assets/estilos.css">
</head>
<body>
    <h2>🗑️ Eliminar Estudiante</h2>

    <?php if (!empty($resultado['success']) && $resultado['success'] === true): ?>
        <p><?= htmlspecialchars($resultado['message']) ?></p>
        <script>
            alert('✅ <?= addslashes($resultado['message']) ?>');
            window.location = 'consultar.php';
        </script>
    <?php else: ?>
        <p><?= htmlspecialchars($resultado['message'] ?? 'No se pudo eliminar el estudiante.') ?></p>
        <script>
            alert('❌ <?= addslashes($resultado['message'] ?? 'No se pudo eliminar el estudiante.') ?>');
            window.location = 'consultar.php';
        </script>
    <?php endif; ?>

    <a href="consultar.php">⬅ Volver</a>
</body>
</html>
