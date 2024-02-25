<?php
session_start();
include "funciones.php";
include "header.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Recupero los detalles del pedido desde la base de datos
$orderID = $_GET['id'];
$query = $con->query("SELECT * FROM pedido WHERE idPedido = $orderID");
$orderDetails = $query->fetch(PDO::FETCH_ASSOC);

// Verifico si se encontro el pedido
if (!$orderDetails) {
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
        <title>Pedido Realizado</title>
</head>
<body>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-body">
                <h2>Estado de tu pedido</h2>
                <p class="text-success">Tu pedido ha sido enviado con exito. La ID del pedido es #<?php echo $orderDetails['idPedido']; ?>.</p>
            </div>
        </div>
    </div>
</body>
</html>
<?php
include "footer.php";
$con = null;
?>