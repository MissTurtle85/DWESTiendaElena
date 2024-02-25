<?php
session_start();
include "funciones.php";
include "header.php";

if (!isset($_SESSION["dni"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idCategoria = $_POST["id_categoria"];
    $nombre = $_POST["nombre"];
    $idSuper = $_POST["id_super"];
    $activo = isset($_POST["activo"]) ? 1 : 0;

    try {
        // Consulta SQL para actualizar la categoría
        $sqlCategoria = "UPDATE categorias
                         SET nombre = :nombre, id_super = :id_super, activo = :activo
                         WHERE id_categoria = :id_categoria";

        $stmtCategoria = $con->prepare($sqlCategoria);
        $stmtCategoria->bindParam(":id_categoria", $idCategoria);
        $stmtCategoria->bindParam(":nombre", $nombre);
        $stmtCategoria->bindParam(":id_super", $idSuper);
        $stmtCategoria->bindParam(":activo", $activo);
        $stmtCategoria->execute();

        header("Location: vercategorias.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_GET["id_categoria"])) {
    $idCategoria = $_GET["id_categoria"];

    try {
        // Obtener información de la categoría
        $sqlCategoria = "SELECT * FROM categorias WHERE id_categoria = :id_categoria";
        $stmtCategoria = $con->prepare($sqlCategoria);
        $stmtCategoria->bindParam(":id_categoria", $idCategoria);
        $stmtCategoria->execute();
        $categoria = $stmtCategoria->fetch(PDO::FETCH_ASSOC);

        // Obtener todas las supercategorías disponibles
        $sqlSuperCategorias = "SELECT id_categoria, nombre FROM categorias WHERE id_super = 0 OR id_categoria = :id_super";
        $stmtSuperCategorias = $con->prepare($sqlSuperCategorias);
        $stmtSuperCategorias->bindParam(":id_super", $categoria['id_super']);
        $stmtSuperCategorias->execute();
        $superCategorias = $stmtSuperCategorias->fetchAll(PDO::FETCH_ASSOC);
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
        <title>Editar Categoría</title>
    </head>
    <body>
        <main class="container">
            <h2>Introduce los nuevos datos de la categoría</h2>
            <form name="formedi" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <input type="hidden" class="form-control" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" value="<?php echo $categoria['nombre']; ?>" maxlength="30" required>
                </div>
                <div class="mb-3">
                    <label for="id_super" class="form-label">Super Categoría:</label>
                    <?php if ($categoria['id_super'] != 0): ?>
                        <select name="id_super" class="form-control">
                            <?php foreach ($superCategorias as $superCategoria): ?>
                                <option value="<?php echo $superCategoria['id_categoria']; ?>" <?php echo ($categoria['id_super'] == $superCategoria['id_categoria']) ? "selected" : ""; ?>>
                                    <?php echo $superCategoria['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input type="hidden" name="id_super" value="0">
                        <p>Esta categoría no tiene una supercategoría asociada.</p>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="activo" class="form-check-label">Activo:</label>
                    <input type="checkbox" class="form-check-input" name="activo" <?php echo $categoria["activo"] ? "checked" : ""; ?>>
                </div>
                <input type="submit" class="btn btn-success text-black" name="actualizar" value="Actualizar Datos"><br><br>
            </form>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
<?php
include "footer.php";
$con = null;
?>