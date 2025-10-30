<?php
require_once "../../config/database.php";
require_once "../../controllers/MateriaController.php";
require_once "../../controllers/ProgramaController.php";

$database = new Database();
$db = $database->conectar();
$materiaController = new MateriaController($db);
$programaController = new ProgramaController($db);

$materias = isset($_GET['programa']) ? 
    $materiaController->consultarPorPrograma($_GET['programa']) : 
    $materiaController->consultar();

$programas = $programaController->consultar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consultar Materias</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Materias</h2>

        <form method="GET" class="form-container">
            <div class="form-group">
                <label>Filtrar por Programa:</label>
                <select name="programa" onchange="this.form.submit()">
                    <option value="">Todos los programas</option>
                    <?php while ($prog = $programas->fetch(PDO::FETCH_ASSOC)) : ?>
                        <option value="<?php echo $prog['codigo']; ?>" <?php if(isset($_GET['programa']) && $_GET['programa'] == $prog['codigo']) echo 'selected'; ?>>
                            <?php echo $prog['codigo'] . ' - ' . $prog['nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </form>

        <div class="btn-group mb-2">
            <a href="registrar.php" class="btn btn-primary">➕ Nueva Materia</a>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Programa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $materias->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?php echo $row['codigo']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['nombre_programa']; ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="modificar.php?codigo=<?php echo $row['codigo']; ?>" class="btn btn-small btn-secondary">Editar</a>
                                <a href="eliminar.php?codigo=<?php echo $row['codigo']; ?>" class="btn btn-small btn-danger" onclick="return confirm('¿Está seguro de eliminar esta materia?')">Eliminar</a>
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
