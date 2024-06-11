<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Materias</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index_pro.php">
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
                Gestion de Materias
            </div>

            <li class="nav-item">
                <a class="nav-link" href="Faltas_pro.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Faltas</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="Alumnos_pro.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Alumnos</span></a>
            </li>

            <!-- Linea divisora -->
            <hr class="sidebar-divider">

            <!-- Sub-Menu -->
            <div class="sidebar-heading">
                Profesor
            </div>

            <li class="nav-item">
                <a class="nav-link" href="Materias_pro.php">
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
                        session_start();
                        require_once '../config/config.php';

                        if (!isset($_SESSION['DNI']) || $_SESSION['rol'] != 'PRO') {
                            header('Location: login.php');
                            exit();
                        }

                        $profesor_dni = $_SESSION['DNI'];

                        try {
                            $sql = "SELECT id, Nombre FROM materia WHERE ID_Profesor = :profesor_dni";
                            $stmt = $db->prepare($sql);
                            $stmt->execute(['profesor_dni' => $profesor_dni]);

                            if ($stmt->rowCount() > 0) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $id_materia = $row['id'];
                                    $nombre_materia = $row['Nombre'];
                                    echo '<div class="col-md-4 mb-4">';
                                    echo '<div class="card">';
                                    echo '<img src="/assets/img/' . htmlspecialchars($nombre_materia) . '.jpg" class="card-img-top" alt="' . htmlspecialchars($nombre_materia) . '">';
                                    echo '<div class="card-body">';
                                    echo '<h5 class="card-title">' . htmlspecialchars($nombre_materia) . '</h5>';
                                    echo '<a href="clases_pro.php?id=' . $id_materia . '" class="btn btn-primary">Ir a otra página</a>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo "No se encontraron materias.";
                            }
                        } catch (PDOException $e) {
                            echo 'Error de base de datos: ' . $e->getMessage();
                        }
                        ?>
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