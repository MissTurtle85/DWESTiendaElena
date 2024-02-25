<?php
include 'La-carta.php';
include "funciones.php";
include "header.php";

$cart = new Cart;
?>

<!DOCTYPE html>
<html lang="es">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Ver Pedido</title>
    </head>
<body>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-body">
                <h2>Tu carrito</h2>
                <table class="table table-responsive table-bordered table-striped align-middle text-center">
                    <caption class="caption-bot">Tabla de pedido</caption>
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Producto</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($cart->total_items() > 0) {
                            // Obtengo los elementos del carrito de la sesion
                            $cartItems = $cart->contents();
                            foreach ($cartItems as $item) {
                        ?>
                        <tr>
                            <td><?php echo $item["name"]; ?></td>
                            <td><?php echo '€' . $item["price"] . ' EUR'; ?></td>
                            <td><input type="number" class="form-control text-center" value="<?php echo $item["qty"]; ?>" onchange="updateCartItem(this, '<?php echo $item["rowid"]; ?>')"></td>
                            <td><?php echo '€' . $item["subtotal"] . ' EUR'; ?></td>
                            <td>
                                <a href="AccionCarta.php?action=removeCartItem&id=<?php echo $item["rowid"]; ?>" class="btn btn-danger" onclick="return confirm('¿Confirmar eliminación?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr><td colspan="5"><p>No hay nada en el carrito</p></td></tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><a href="index.php" class="btn btn-success">Continuar Comprando</a></td>
                            <td colspan="2"></td>
                            <?php if ($cart->total_items() > 0) { ?>
                                <td class="text-center"><strong>Total <?php echo '€' . $cart->total() . ' EUR'; ?></strong></td>
                                <td><a href="Pagos.php" class="btn btn-success btn-block">Pagar</a></td>
                            <?php } ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<?php
include "footer.php";
$con = null;
?>