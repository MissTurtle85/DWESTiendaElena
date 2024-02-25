<?php
session_start();
include "funciones.php";
include "header.php";

if (!isset($_SESSION["dni"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["codigo"])) {
    $codigo = $_GET["codigo"];

    try {
        // Consulta SQL para obtener los datos del artículo
        $sql = "SELECT * FROM articulos WHERE codigo = :codigo";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":codigo", $codigo);
        $stmt->execute();

        // Guardo la fila del artículo
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            // Envío al formulario
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirmar"])) {
                // Actualizo el campo "activo" a 0 en lugar de borrar el artículo
                $sqlActualizar = "UPDATE articulos SET activo = 0 WHERE codigo = :codigo";
                $stmtActualizar = $con->prepare($sqlActualizar);
                $stmtActualizar->bindParam(":codigo", $codigo);

                if ($stmtActualizar->execute()) {
                    echo "El artículo " . $fila["nombre"] . " ha sido desactivado.";
                    header("refresh:2;url=menuarticulos.php");
                } else {
                    echo "Error al desactivar el artículo.";
                }
            }
        } else {
            // Si el artículo no existe, redirecciona
            header("Location: mostrararticulos.php");
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: mostrararticulos.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Desactivar Artículo</title>
</head>
<body>
    <main class="container">
        <h2>Desactivar artículo:</h2>
        <p>¿Seguro que quieres desactivar el artículo <?php echo $fila["nombre"]; ?>?</p>
        <form name="formconf" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="hidden" class="form-control" name="codigo" value="<?php echo $codigo; ?>">
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