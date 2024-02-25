<?php
session_start();
include "funciones.php";
include "header.php";

if (!isset($_SESSION["dni"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id_categoria"])) {
    $idCategoria = $_GET["id_categoria"];

    try {
        // Consulta SQL para obtener los datos de la categoría
        $sql = "SELECT * FROM categorias WHERE id_categoria = :id_categoria";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":id_categoria", $idCategoria);
        $stmt->execute();

        // Guardo la fila de la categoría
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            // Envío al formulario
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirmar"])) {
                // Actualizo el campo "activo" a 0 en lugar de borrar la categoría
                $sqlActualizar = "UPDATE categorias SET activo = 0 WHERE id_categoria = :id_categoria";
                $stmtActualizar = $con->prepare($sqlActualizar);
                $stmtActualizar->bindParam(":id_categoria", $idCategoria);

                if ($stmtActualizar->execute()) {
                    echo "La categoría " . $fila["nombre"] . " ha sido desactivada.";

                    // Desactivar subcategorías
                    $sqlDesactivarSubcategorias = "UPDATE categorias SET activo = 0 WHERE id_super = :id_categoria";
                    $stmtDesactivarSubcategorias = $con->prepare($sqlDesactivarSubcategorias);
                    $stmtDesactivarSubcategorias->bindParam(":id_categoria", $idCategoria);
                    $stmtDesactivarSubcategorias->execute();

                    header("refresh:2;url=vercategorias.php");
                } else {
                    echo "Error al desactivar la categoría.";
                }
            }
        } else {
            // Si la categoría no existe, redirecciona
            header("Location: vercategorias.php");
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: vercategorias.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Desactivar Categoría</title>
</head>
<body>
    <main class="container">
        <h2>Desactivar categoría:</h2>
        <p>¿Seguro que quieres desactivar la categoría <?php echo $fila["nombre"]; ?>?</p>
        <form name="formconf" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="hidden" class="form-control" name="id_categoria" value="<?php echo $idCategoria; ?>">
            </div>
            <div class="mb-3">
                <input type="submit" class="btn btn-success text-black" name="confirmar" value="Confirmar"><br>
            </div>
        </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include "footer.php";
$con = null;
?>