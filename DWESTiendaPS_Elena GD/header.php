<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/x-icon" href="imgs/favicon.ico">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Header</title>
    </head>
    <body>
        <header id="header">
            <nav class="navbar navbar-expand-lg navbar-light shadow bg-black">
                <div class="container d-flex justify-content-between align-items-center">
                    <a class="navbar-brand text-success align-self-center" href="index.php">
                        <img src="imgs/logo.png" alt="" width="200">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-controls="main_nav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse flex d-lg-flex justify-content-lg-end" id="main_nav">
                        <div class="flex">
                            <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="index.php">Inicio</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="">Promociones</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="">Contacto</a>
                                </li>
                            </ul>
                        </div>
                        <form class="d-flex" method="POST" enctype="multipart/form-data" action="buscararticulo.php">
                            <input class="form-control me-2" type="search" aria-label="Search" name="busqueda">
                            <button class="btn btn-outline-success" type="submit" name="buscar">Buscar</button>
                        </form>
                    </div>
                </div>
            </nav>
        </header>
        <div class="row">
                <div class="col-md-2 bg-success text-white">

                <a class="text-decoration-none text-white" href="mostrarcategorias.php">Mostrar categorias</a><br>
                
                </div>          
                <div class="col-md-8">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>