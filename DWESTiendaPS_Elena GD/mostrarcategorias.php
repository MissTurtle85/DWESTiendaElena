<?php
session_start();
include "conectar_db.php";
include "header.php";

// Función recursiva para obtener subcategorías de una categoría dada
function obtenerSubcategorias($idCategoria, $con) {
    $subcategorias = [];

    $query = "SELECT id_categoria, nombre FROM categorias WHERE id_super = :idCategoria";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':idCategoria', $idCategoria, PDO::PARAM_INT);
    $stmt->execute();

    while ($categoria = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categoria['subcategorias'] = obtenerSubcategorias($categoria['id_categoria'], $con);
        $subcategorias[] = $categoria;
    }

    return $subcategorias;
}

// Obtener todas las categorías principales (id_super = 0)
$queryPrincipal = "SELECT id_categoria, nombre FROM categorias WHERE id_super = 0";
$stmtPrincipal = $con->prepare($queryPrincipal);
$stmtPrincipal->execute();
$categoriasPrincipales = $stmtPrincipal->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Categorías</title>
</head>
<body>
    <h2>Todas las Categorías</h2>

    <form action="" method="post">
        <?php foreach ($categoriasPrincipales as $categoriaPrincipal): ?>
            <label for="categoria_<?php echo $categoriaPrincipal['id_categoria']; ?>">
                <?php echo $categoriaPrincipal['nombre']; ?>
            </label>
            <select name="subcategoria_<?php echo $categoriaPrincipal['id_categoria']; ?>" id="categoria_<?php echo $categoriaPrincipal['id_categoria']; ?>">
                <option value="">Selecciona una subcategoría</option>
                <?php
                $idCategoriaPrincipal = $categoriaPrincipal['id_categoria'];
                $subcategorias = obtenerSubcategorias($idCategoriaPrincipal, $con);

                foreach ($subcategorias as $subcategoria) {
                    echo "<option value=\"{$subcategoria['id_categoria']}\">{$subcategoria['nombre']}</option>";
                }
                ?>
            </select>
            <br><br>
        <?php endforeach; ?>

        <input type="submit" value="Mostrar Artículos"><br><br>
    </form>

    <?php
    // Verificar si se envió el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        foreach ($categoriasPrincipales as $categoriaPrincipal) {
            $nombreCampo = "subcategoria_" . $categoriaPrincipal['id_categoria'];
            if (isset($_POST[$nombreCampo])) {
                $idSubcategoria = $_POST[$nombreCampo];

                $queryArticulos = "SELECT * FROM articulos WHERE categoria = :idSubcategoria";
                $stmtArticulos = $con->prepare($queryArticulos);
                $stmtArticulos->bindParam(':idSubcategoria', $idSubcategoria, PDO::PARAM_INT);
                $stmtArticulos->execute();
                $articulos = $stmtArticulos->fetchAll(PDO::FETCH_ASSOC);

                if ($articulos) {
                    echo "<h3>Artículos de la subcategoría seleccionada en {$categoriaPrincipal['nombre']}:</h3>";
                    echo "<ul>";
                    foreach ($articulos as $articulo) {
                        echo "<li>{$articulo['nombre']}</li>";
                    }
                    echo "</ul>";
                }
            }
        }
    }
    ?>
</body>
</html>

<?php
include "footer.php";
$con = null;
?>