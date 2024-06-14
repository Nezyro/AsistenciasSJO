<?php
include '../config/config.php';

$json_file = '../clases.json';
$json_data = file_get_contents($json_file);

$data = json_decode($json_data, true);

$clases_info = array();

foreach ($data['clases'] as $clase) {
    $info_clase = array(
        'id' => $clase['id'],
        'fecha_inicio' => $clase['fecha_inicio'],
        'fecha_final' => $clase['fecha_final']
    );
    
    $clases_info[] = $info_clase;
}

if (!isset($_GET['id'])) {
    die("Error: id no especificado en la URL.");
}

$id_materia = $_GET['id'];
$fecha_inicio = ""; 
$fecha_final = ""; 


foreach ($clases_info as $clase) {
    if ($clase['id'] == $id_materia) {
        $fecha_inicio = $clase['fecha_inicio'];
        $fecha_final = $clase['fecha_final'];
        break;
    }
}

if (empty($fecha_inicio) || empty($fecha_final)) {
    die("Error: No se encontraron fechas para el id proporcionado.");
}

$fecha_obj_inicio = new DateTime($fecha_inicio);
$fecha_obj_final = new DateTime($fecha_final);

$table_rows = "";

while ($fecha_obj_inicio <= $fecha_obj_final) {
    $fecha_actual = $fecha_obj_inicio->format('Y-m-d');

    $table_rows .= "<tr>";
    $table_rows .= "<td>" . $fecha_actual . "</td>";
    $table_rows .= "<td><a href='asistencias_pro.php?fecha=" . $fecha_actual . "&id=" . $id_materia . "' class='btn btn-primary'>Marcar Faltas</a></td>";
    $table_rows .= "</tr>";

    $fecha_obj_inicio->add(new DateInterval('P7D'));
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
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
    
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
                <div class="sidebar-brand-text mx-3">Asistencias sjo</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Divider -->
            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Gestion de Alumnos
            </div>

            <li class="nav-item">
                <a class="nav-link" href="Faltas_pro.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Faltas</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="alumnos_pro.php">
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

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <h1 class="h3 mb-2 text-gray-800">Clases</h1>
                    <p class="mb-4">La tabla muestra las clases que hay en esta materia. </p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Personal</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>DIAS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $table_rows; ?>
                                    </tbody>
                                </table>
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
        </form>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#example').DataTable({
                    paging: {
                        boundaryNumbers: false
                    }
                });
            });
        </script>
</body>

</html>