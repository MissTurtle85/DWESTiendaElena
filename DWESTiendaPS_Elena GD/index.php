<?php
include 'La-carta.php';
include "funciones.php";
include "header.php";

$cart = new Cart;

// Configuro la paginación
$PAGS = 4;
$pagina = 1;
$inicio = 0;

if(isset($_GET["pagina"])){
    $pagina = $_GET["pagina"];
    $inicio = ($pagina - 1) * $PAGS;
}

// Indica que deseas ordenar por nombre, luego las direcciones
$ordenarPor = isset($_GET["ordenarPor"]) ? $_GET["ordenarPor"] : "nombre";
$orden = isset($_GET["orden"]) ? $_GET["orden"] : "asc";

try {
    // Consulta SQL para obtener los artículos con paginación y ordenar
    $sql = "SELECT codigo, nombre, descripcion, categoria, precio, imagen 
            FROM articulos 
            WHERE activo = 1
            ORDER BY $ordenarPor $orden
            LIMIT :inicio, :PAGS";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":inicio", $inicio, PDO::PARAM_INT);
    $stmt->bindParam(":PAGS", $PAGS, PDO::PARAM_INT);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Tienda</title>
    </head>
    <body>
        <main class="container">
        <h2>Selecciona tus artículos</h2>
        <div class="container-fluid">
        <div class="float-end">
            <a class="nav-link text-white" href="VerCarta.php">
                <img src="imgs/carrito.png" alt="Carrito" width="50" height="50">
                <?php
                // Obtener la cantidad de artículos en el carrito
                $cartItemCount = ($cart->total_items() > 0) ? $cart->total_items() : 0;

                // Obtener el precio total de los artículos en el carrito
                $totalPrice = ($cart->total() > 0) ? '€' . number_format($cart->total(), 2) : '€0.00';

                // Mostrar la cantidad de artículos en el carrito
                echo '<span class="badge bg-success">' . $cartItemCount . '</span>';

                // Mostrar el precio total al lado del carrito
                echo '<span class="badge bg-success">' . $totalPrice . '</span>';
                ?>
            </a>
        </div>
        </div>
        <table class="table table-responsive table-bordered table-striped align-middle text-center">
            <caption class="caption-bot">Tabla de compras</caption>
            <thead class="table-dark">
                <tr>
                    <th scope="col">Codigo</th>
                    <th scope="col">Nombre</a></th>
                    <th scope="col">Descripcion</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Imagen</th>
                    <th scope="col">Comprar</th>
                </tr>
            </thead>

            <?php
            if ($stmt->rowCount() > 0) {
                foreach ($resultado as $fila) {
                    echo "<tr>";
                    echo "<td>{$fila['codigo']}</td>";
                    echo "<td>{$fila['nombre']}</td>";
                    echo "<td>{$fila['descripcion']}</td>";
                    echo "<td>{$fila['categoria']}</td>";
                    echo "<td>{$fila['precio']}</td>";
                    echo "<td><img src='{$fila['imagen']}' alt='Imagen' style='max-width: 100px; max-height: 100px;'></td>";
                    echo "<td><a class='btn btn-success' href='AccionCarta.php?action=addToCart&id={$fila['codigo']}'>Al carrito</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'><p>No hay nada en el carrito</p></td></tr>";
            }
        echo "</table>";

        // Separador para cada enlace de paginación
                $sqlTotalArticulos = "SELECT COUNT(*) FROM articulos";
                $stmtTotalArticulos = $con->prepare($sqlTotalArticulos);
                $stmtTotalArticulos->execute();

                $totalArticulos = $stmtTotalArticulos->fetchColumn();
                $totalPaginas = ceil($totalArticulos / $PAGS);
            ?>
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                        <li class="page-item">
                            <a class="page-link text-success" href="?pagina=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            <a href="?orden=asc" class="text-decoration-none text-success ' . ($orden == 'asc' ? 'selected' : '') . '">Nombre Asc | </a>
            <a href="?orden=desc" class="text-decoration-none text-success ' . ($orden == 'desc' ? 'selected' : '') . '">Nombre Desc</a><br><br>

        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
<?php
include "footer.php";
$con = null;
?>