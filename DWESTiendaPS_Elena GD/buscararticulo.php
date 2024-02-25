<?php
session_start();
include "funciones.php";
include "header.php";

if (isset($_POST['buscar'])) {
    $busqueda = $_POST['busqueda'];

    try {
        // Consulta SQL para buscar artículos por nombre
        $sql = "SELECT codigo, nombre, descripcion, categoria, precio, imagen FROM articulos WHERE nombre LIKE :busqueda";

        $stmt = $con->prepare($sql);
        $stmt->bindValue(':busqueda', "%$busqueda%", PDO::PARAM_STR);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error al realizar la búsqueda: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Buscar Artículo</title>
</head>
<body>
    <main class="container">
    <h2>Artículos encontrados</h2>
    <table class="table table-responsive table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Codigo</th>
                    <th scope="col">Nombre</a></th>
                    <th scope="col">Descripcion</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Imagen</th>
                </tr>
            </thead>
                <?php
                if ($resultados) {
                    foreach ($resultados as $articulo) {
                        echo "<tr>";
                        echo "<td>{$articulo['codigo']}</td>";
                        echo "<td>{$articulo['nombre']}</td>";
                        echo "<td>{$articulo['descripcion']}</td>";
                        echo "<td>{$articulo['categoria']}</td>";
                        echo "<td>{$articulo['precio']}</td>";
                        echo "<td><img src='{$articulo['imagen']}' alt='Imagen' style='max-width: 100px; max-height: 100px;'></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No se encontraron artículos con el nombre '{$busqueda}'.</p>";
                }
                ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
<?php
include "footer.php";
$con = null;
?>