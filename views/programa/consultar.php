<?php
require_once "../../config/database.php";
require_once "../../controllers/ProgramaController.php";

$database = new Database();
$db = $database->conectar();
$controller = new ProgramaController($db);
$programas = $controller->consultar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consultar Programas de Formación</title>
</head>
<body>
    <h2>Programas de Formación</h2>
    <a href="registrar.php"><button>Nuevo Programa</button></a>
    <br><br>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $programas->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?php echo $row['id_programa']; ?></td>
                    <td><?php echo $row['codigo']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td>
                        <a href="modificar.php?id=<?php echo $row['id_programa']; ?>">
                            <button>Modificar</button>
                        </a>
                        <a href="eliminar.php?id=<?php echo $row['id_programa']; ?>" 
                           onclick="return confirm('¿Está seguro de eliminar este programa?')">
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