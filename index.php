<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>


<body id="page-top">
    
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Asistencias SJO</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Sub-Menu -->
            <div class="sidebar-heading">
                Gestion de Alumnos
            </div>

            <!-- Pagina de materias -->
            <li class="nav-item">
                <a class="nav-link" href="Faltas.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Faltas</span></a>
            </li>

            <!-- Pagina de alumnos -->
            <li class="nav-item">
                <a class="nav-link" href="Alumnos.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Alumnos</span></a>
            </li>

            <!-- Linea divisora -->
            <hr class="sidebar-divider">

            <!-- Sub-Menu -->
            <div class="sidebar-heading">
                Administrador
            </div>

            <li class="nav-item">
                <a class="nav-link" href="Materias.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Materias</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="Personal.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Personal</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

        </ul>
        <!-- End of Sidebar -->

        <!-- Barra de header -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">     
                    <form method="post" action="login.php">
                        <button type="submit" name="logout">Cerrar sesión</button>
                    </form>
                    <h3><?php echo " -" . "Usuario: " . $_SESSION["Nombre"] . " (" . $_SESSION["rol"] . ")"."<br>";?></h3>
                </nav>              
            </div>
        </div>
    </div>
</body>

</html>