<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión Académica</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <h1>Sistema de Gestión Académica</h1>

    <div class="menu-section">
        <h2>Programas de Formación</h2>
        <div class="menu-buttons">
            <a href="views/programa/consultar.php"><button>Ver Programas</button></a>
            <a href="views/programa/registrar.php"><button>Registrar Programa</button></a>
            <a href="views/reportes/materias_por_programa.php"><button>Ver Materias por Programa</button></a>
            <a href="views/reportes/estudiantes_por_programa.php"><button>Ver Estudiantes por Programa</button></a>
        </div>
    </div>

    <div class="menu-section">
        <h2>Materias</h2>
        <div class="menu-buttons">
            <a href="views/materia/consultar.php"><button>Ver Materias</button></a>
            <a href="views/materia/registrar.php"><button>Registrar Materia</button></a>
            <a href="views/reportes/estudiantes_por_materia.php"><button>Ver Estudiantes por Materia</button></a>
        </div>
    </div>

    <div class="menu-section">
        <h2>Estudiantes</h2>
        <div class="menu-buttons">
            <a href="views/estudiante/consultar.php"><button>Ver Estudiantes</button></a>
            <a href="views/estudiante/registrar.php"><button>Registrar Estudiante</button></a>
            <a href="views/reportes/materias_por_estudiante.php"><button>Ver Materias por Estudiante</button></a>
        </div>
    </div>

    <div class="menu-section">
        <h2>Notas</h2>
        <div class="menu-buttons">
            <a href="views/nota/consultar.php"><button>Gestionar Notas</button></a>
            <a href="views/reportes/notas_estudiante.php"><button>Ver Notas por Estudiante</button></a>
        </div>
    </div>
</body>
</html>

