<?php

include '../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['visto']) && is_array($_POST['visto']) && !empty($_POST['visto'])) {
    $absencesToDelete = array();

    foreach ($_POST['visto'] as $value) {
        list($alumnoID, $fecha) = explode(',', $value);
        $absencesToDelete[] = array(
            'alumno' => (int) $alumnoID,
            'date' => date('Y-m-d', strtotime($fecha))
        );

        $sql_delete = "DELETE FROM faltas WHERE ID_Alumno = :alumnoID AND Fecha = :fecha";
        $stmt_delete = $db->prepare($sql_delete);
        $stmt_delete->bindParam(':alumnoID', $alumnoID);
        $stmt_delete->bindParam(':fecha', $fecha);
        $stmt_delete->execute();
        if (!$stmt_delete->execute()) {
            $errorInfo = $stmt_delete->errorInfo();
            echo "Error: " . $errorInfo[2];
            exit;
        }
    }

    $json_absences = json_encode(array_values($absencesToDelete));
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

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Informacion de la pagina  tablas -->
                    <h1 class="h3 mb-2 text-gray-800">Faltas</h1>
                    <p class="mb-4">La tabla para quitar faltas</p>

                    <!--Informacion de las tablas-->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Faltas Registradas</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                                    <table id="faltas" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ALUMNO</th>
                                                <th>APELLIDOS</th>
                                                <th>ASIGNATURA</th>
                                                <th>FECHA</th>
                                                <th>ASISTENCIA</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            require_once '../config/config.php';

                                            if (!isset($_SESSION['DNI']) || $_SESSION['rol'] != 'PRO') {
                                                header('Location: ../login.php');
                                                exit();
                                            }

                                            $profesor_dni = $_SESSION['DNI'];

                                            try {
                                                $sql = "SELECT 
                                                alumno.ID AS id_alumno,
                                                alumno.Nombre AS nombre_alumno,
                                                alumno.Apellidos AS apellido_alumno,
                                                materia.ID AS id_materia,
                                                materia.Nombre AS nombre_materia,
                                                faltas.Fecha AS FechaFalta,
                                                (SELECT COUNT(*) FROM faltas AS f2 WHERE f2.ID_Alumno = faltas.ID_Alumno) AS cantidad_faltas
                                            FROM 
                                                faltas
                                            JOIN 
                                                alumno ON faltas.ID_Alumno = alumno.ID
                                            JOIN 
                                                materia ON faltas.ID_Materia = materia.ID
                                            WHERE 
                                                materia.ID_Profesor = :profesor_dni
                                            GROUP BY 
                                                alumno.ID, alumno.Nombre, alumno.Apellidos, materia.ID, materia.Nombre, faltas.Fecha
                                            ORDER BY 
                                                faltas.Fecha ASC";

                                                $stmt = $db->prepare($sql);
                                                $stmt->bindParam(':profesor_dni', $profesor_dni);
                                                $stmt->execute();

                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row["nombre_alumno"] . "</td>";
                                                    echo "<td>" . $row["apellido_alumno"] . "</td>";
                                                    echo "<td>" . $row["nombre_materia"] . "</td>";
                                                    echo "<td>" . $row["FechaFalta"] . "</td>";
                                                    echo "<td style='padding-left: 60px'><input type='checkbox' style='transform: scale(1.4);' name='visto[]' value='" . $row["id_alumno"] . "," . $row["FechaFalta"] . "'></td>";
                                                    echo "<td>" . $row["cantidad_faltas"] . "</td>";
                                                    echo "</tr>";
                                                }

                                            } catch (PDOException $e) {
                                                echo 'Error de base de datos: ' . $e->getMessage();
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <button type="submit">Quitar Falta</button>
                                </form>
                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
                                <script>
                                    $(document).ready(function () {
                                        $.fn.dataTable.defaults.pageLength = 25;
                                        $('#faltas').DataTable({
                                            language: {
                                                "decimal": "",
                                                "emptyTable": "No hay información",
                                                "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                                                "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                                                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                                                "infoPostFix": "",
                                                "thousands": ",",
                                                "lengthMenu": "Mostrar _MENU_ entradas",
                                                "loadingRecords": "Cargando...",
                                                "processing": "Procesando...",
                                                "search": "Buscar:",
                                                "zeroRecords": "Sin resultados encontrados",
                                                "paginate": {
                                                    "first": "Primero",
                                                    "last": "Último",
                                                    "next": "Siguiente",
                                                    "previous": "Anterior"
                                                },
                                                "aria": {
                                                    "sortAscending": ": activar para ordenar la columna en orden ascendente",
                                                    "sortDescending": ": activar para ordenar la columna en orden descendente"
                                                }
                                            },
                                            paging: {
                                                boundaryNumbers: false
                                            },

                                            ordering: false
                                        });
                                    });
                                </script>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</body>

</html>