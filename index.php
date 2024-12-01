<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Tablas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="jumbotron mt-4">
        <h1 class="display-4">Base de Datos: examen_santiago</h1>
        <p class="lead">Listado dinámico de tablas y sus datos.</p>
        <hr class="my-4">
    </div>

    <?php
    // Conexión a la base de datos
    $host = getenv('MYSQL_HOST') ?: 'localhost';
    $user = getenv('MYSQL_USER') ?: 'root';
    $password = getenv('MYSQL_PASSWORD') ?: '';
    $dbname = 'examen_santiago';

    $conexion = new mysqli($host, $user, $password, $dbname);

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Obtener el listado de tablas
    $queryTablas = "SHOW TABLES";
    $resultadoTablas = $conexion->query($queryTablas);

    if ($resultadoTablas->num_rows > 0) {
        while ($tabla = $resultadoTablas->fetch_array()) {
            $nombreTabla = $tabla[0];
            echo "<h3 class='mt-4'>Tabla: $nombreTabla</h3>";
            
            // Consultar datos de la tabla
            $queryDatos = "SELECT * FROM $nombreTabla";
            $resultadoDatos = $conexion->query($queryDatos);

            if ($resultadoDatos->num_rows > 0) {
                echo "<table class='table table-striped table-responsive'>";
                echo "<thead><tr>";

                // Obtener nombres de las columnas
                $columnas = $resultadoDatos->fetch_fields();
                foreach ($columnas as $columna) {
                    echo "<th>" . $columna->name . "</th>";
                }
                echo "</tr></thead>";
                echo "<tbody>";

                // Listar filas de datos
                while ($fila = $resultadoDatos->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($fila as $valor) {
                        echo "<td>" . htmlspecialchars($valor) . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No hay datos en esta tabla.</p>";
            }
        }
    } else {
        echo "<p>No se encontraron tablas en la base de datos.</p>";
    }

    $conexion->close();
    ?>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</body>
</html>
