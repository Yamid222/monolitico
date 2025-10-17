<?php
require_once "../../config/database.php";
require_once "../../controllers/MateriaController.php";
require_once "../../controllers/ProgramaController.php";

$database = new Database();
$db = $database->conectar();
$materiaController = new MateriaController($db);
$programaController = new ProgramaController($db);

// Filtrar por programa si se proporciona un ID
$materias = isset($_GET['programa']) ? 
    $materiaController->consultarPorPrograma($_GET['programa']) : 
    $materiaController->consultar();

// Obtener lista de programas para el filtro
$programas = $programaController->consultar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consultar Materias</title>
</head>
<body>
    <h2>Materias</h2>

    <!-- Filtro por programa -->
    <form method="GET">
        <label>Filtrar por Programa:</label>
        <select name="programa" onchange="this.form.submit()">
            <option value="">Todos los programas</option>
            <?php while ($prog = $programas->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?php echo $prog['id_programa']; ?>"
                        <?php if(isset($_GET['programa']) && $_GET['programa'] == $prog['id_programa']) echo 'selected'; ?>>
                    <?php echo $prog['nombre']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>
    <br>

    <a href="registrar.php"><button>Nueva Materia</button></a>
    <br><br>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Programa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $materias->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?php echo $row['id_materia']; ?></td>
                    <td><?php echo $row['codigo']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['nombre_programa']; ?></td>
                    <td>
                        <a href="modificar.php?id=<?php echo $row['id_materia']; ?>">
                            <button>Modificar</button>
                        </a>
                        <a href="eliminar.php?id=<?php echo $row['id_materia']; ?>" 
                           onclick="return confirm('¿Está seguro de eliminar esta materia?')">
                            <button>Eliminar</button>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br>
    <a href="../../index.php"><button>Volver al Inicio</button></a>
</body>
</html>