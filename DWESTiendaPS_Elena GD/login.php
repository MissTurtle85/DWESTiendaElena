<?php
session_start();
include "funciones.php";
include "header.php";

// Verifica si ya hay una sesión iniciada
if (isset($_SESSION["dni"])) {
    // Si ya hay una sesión iniciada, redirige a la página de inicio
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST["dni"];
    $contrasenya = $_POST["contrasenya"];

    $funciones = new Funciones();

    //Compruebo que el DNI cumple los requisitos
    if ($funciones->validarDNI($dni)) {
        try {
            //Consulta prepare
            $stmt = $con->prepare("SELECT dni, contrasenya, rol, nombre, direccion, localidad, provincia, telefono, email, activo 
                                    FROM clientes 
                                    WHERE dni = :dni");
            $stmt->bindParam(":dni", $dni);
            $stmt->execute();

            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($fila) {
                // Almacena el valor de activo en una variable de sesión
                $_SESSION["cliente_activo"] = $fila["activo"];

                // Verifica si el cliente está activo
                if ($_SESSION["cliente_activo"] == 1) {
                    // Verifico la contraseña
                    if (password_verify($contrasenya, $fila["contrasenya"])) {
                        // Almaceno la información del cliente en la sesión
                        $_SESSION["dni"] = $fila["dni"];
                        $_SESSION["rol"] = $fila["rol"];

                        // También puedes almacenar otros detalles del cliente si es necesario
                        $_SESSION["cliente"] = [
                            'dni' => $fila["dni"],
                            'nombre' => $fila["nombre"],
                            'direccion' => $fila["direccion"],
                            'localidad' => $fila["localidad"],
                            'provincia' => $fila["provincia"],
                            'telefono' => $fila["telefono"],
                            'email' => $fila["email"],
                            'activo' => $fila["activo"]
                        ];

                        // Redirige a la página de origen si está almacenada
                        if (isset($_SESSION['url_origen'])) {
                            $url_origen = $_SESSION['url_origen'];
                            unset($_SESSION['url_origen']); // Limpia la URL de origen
                            header("Location: $url_origen");
                            exit();
                        } else {
                            header("Location: index.php");
                            exit();
                        }
                    } else {
                        echo "Error. Contraseña incorrecta.<br>";
                    }
                } else {
                    // El usuario no está activo, destruye la sesión y muestra un mensaje de error
                    session_destroy();
                    echo "Error. El usuario no está activo. Por favor, contacte al administrador.<br>";
                }
            } else {
                echo "Error. Vuelva a introducir los datos.<br>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error: DNI no válido.<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Login</title>
    </head>
    <body>
        <main class="container">
            <h2>Inicia sesion</h2>
            <form name="log" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                    <label for="dni" class="form-label">DNI</label>
                    <input type="text" class="form-control" name="dni" maxlength="9" required>
                </div>
                <div class="mb-3">
                    <label for="contrasenya" class="form-label">Contrasenya</label>
                    <input type="password" class="form-control" name="contrasenya" maxlength="9" required>
                </div>
                <div class="mb-3">
                    <input type="submit" class="btn btn-success text-black" value="Iniciar Sesion"><br><br>
                </div>
            </form>
            <p>¿No tienes cuenta? <a class="text-decoration-none text-success" href="nuevoregistro.php">Registrate ahora</a></p><br>
            <p>¿Has olvidado tu contraseña? <a class="text-decoration-none text-success" href="olvidecontrasenya.php">Recuperar contraseña</a></p>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
<?php
include "footer.php";
$con = null;
?>