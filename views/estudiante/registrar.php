<?php
require_once '../../config/database.php';
require_once '../../controllers/estudianteController.php';
require_once '../../controllers/programaController.php';

$db = (new Database())->conectar();
$controller = new EstudianteController();
$programaController = new ProgramaController($db);
$programas = $programaController->consultar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mensaje = $controller->registrar($_POST['codigo'], $_POST['nombre'], $_POST['email'], $_POST['programa']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Estudiante</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>➕ Registrar Estudiante</h2>
        <form method="POST" class="form-container">
            <div class="form-group">
                <label>Código:</label>
                <input type="text" name="codigo" required>
            </div>
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Programa de Formación:</label>
                <select name="programa" required>
                    <option value="">Seleccione un programa</option>
                    <?php while ($prog = $programas->fetch(PDO::FETCH_ASSOC)) : ?>
                        <option value="<?php echo $prog['codigo']; ?>"><?php echo $prog['codigo'] . ' - ' . $prog['nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="consultar.php" class="btn btn-secondary">Volver</a>
            </div>
        </form>

        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-success"><?= $mensaje ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
