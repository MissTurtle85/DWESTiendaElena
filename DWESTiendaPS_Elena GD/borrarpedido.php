<?php
session_start();
include "funciones.php";
include "header.php";

if (!isset($_SESSION["dni"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["idPedido"])) {
    $idPedido = $_GET["idPedido"];

    try {
        // Consulta SQL para obtener los datos
        $sql = "SELECT * FROM pedido WHERE idPedido = :idPedido";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":idPedido", $idPedido);
        $stmt->execute();

        // Guardo la fila del pedido
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            // Envío al formulario
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirmar"])) {

                // Actualizo el campo "activo" a 0 en lugar de borrar al pedido
                $sqlActualizar = "UPDATE pedido SET activo = 0 WHERE idPedido = :idPedido";
                $stmtActualizar = $con->prepare($sqlActualizar);
                $stmtActualizar->bindParam(":idPedido", $idPedido);

                if ($stmtActualizar->execute()) {
                    echo "El pedido " . $fila["idPedido"] . " ha sido desactivado.";
                    header("refresh:2;url=mostrarpedidos.php");
                    exit();
                } else {
                    echo "Error al desactivar el pedido.";
                }
            }
        } else {
            // Si el pedido no existe, redirecciona
            header("Location: index.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Desactivar Pedido</title>
</head>
<body>
    <main class="container">
    <h2>Desactivar pedido</h2>
    <p>¿Seguro que quieres desactivar al pedido <?php echo $fila["idPedido"]; ?>?</p>
    <form name="formconf" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
        <input type="hidden" class="form-control" name="idPedido" value="<?php echo $idPedido; ?>">
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