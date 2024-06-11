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
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index_coo.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Asistencias SJO</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Divider -->
            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Gestion de Alumnos
            </div>

            <li class="nav-item">
                <a class="nav-link" href="Faltas_coo.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Faltas</span></a>
            </li>

            

            <!-- Linea divisora -->
            <hr class="sidebar-divider">

            <!-- Sub-Menu -->
            <div class="sidebar-heading">
                Coordinador
            </div>

            <li class="nav-item">
                <a class="nav-link" href="Alumnos_coo.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Alumnos</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Materias_coo.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Materias</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

        </ul>
        <!-- End of Sidebar -->

        <!-- Barra de header -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"></nav>
                <div class="container-fluid">
                    <h1>Materias</h1>
                    <p>
                        Esta página muestra las materias.
                    </p>
                    <div class="row">
                    <?php
                    require_once '../config/config.php';

                    $sql = "SELECT m.id, m.Nombre, p.nombre
                            FROM materia m 
                            INNER JOIN personal p ON m.ID_profesor = p.dni
                            WHERE p.ROL = 'PRO'";
                    $stmt = $db->query($sql);

                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $id_materia = $row['id'];
                            $nombre_materia = $row['Nombre'];
                            $nombre_profesor = $row['nombre'];
                            echo '<div class="col-md-4 mb-4">';
                            echo '<div class="card">';
                            echo '<img src="/assets/img/'. $nombre_materia. '.jpg" class="card-img-top" alt="'. $nombre_materia. '">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">'. $nombre_materia. ' - ' . $nombre_profesor .'</h5>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "No se encontraron materias.";
                    }
                    ?>

                    </div>
                </div>

                <div id="botonplus" class="container text-center">
                    <div class="row">
                        <div class="col">
                            <div class="d-flex justify-content-between">
                                <div class="ml-auto">
                                    <a class="btn btn-secondary mr-3" href="./register_materias_coo.php" role="button">
                                        <svg id="iconoplus" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                            <path
                                                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                            <path
                                                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Inicio del Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Proyecto Escolar </span>
                        </div>
                    </div>
                </footer>
                <!-- Final del Footer -->
            </div>
        </div>
    </div>
</body>

</html>