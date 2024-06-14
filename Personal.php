<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Personal</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="./assets/css/styles.css">
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
                Gestion de personal
            </div>

            <li class="nav-item">
                <a class="nav-link" href="Faltas.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Faltas</span></a>
            </li>

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

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <h1 class="h3 mb-2 text-gray-800">Personal</h1>
                    <p class="mb-4">La tabla de personal en esta página muestra información,
                        sobre nuestro equipo que estará encantado de ayudarte. </p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Personal</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="personal" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Apellidos</th>
                                            <th>Email</th>
                                            <th>ROL</th>
                                            <th>Telefono</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        require_once "config/config.php";

                                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_dni"])) {
                                            $dni = $_POST["delete_dni"];

                                            $sql_delete = "DELETE FROM baseasistenciassjo.personal WHERE DNI = :dni";
                                            $stmt_delete = $db->prepare($sql_delete);
                                            $stmt_delete->bindParam(":dni", $param_dni, PDO::PARAM_STR);
                                            $param_dni = trim($dni);
                                            if ($stmt_delete->execute()) {
                                                echo "<div class='alert alert-success'>Registro eliminado correctamente.</div>";
                                            } else {
                                                echo "<div class='alert alert-danger'>¡Ups! Algo salió mal. Por favor, inténtalo de nuevo más tarde.</div>";
                                            }
                                        }
                                        require_once "config/config.php";

                                        $sql = "SELECT DNI, Nombre, Apellidos, ROL, Email, Telefono FROM personal";
                                        $stmt = $db->query($sql);

                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row["DNI"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["Nombre"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["Apellidos"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["Email"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["ROL"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["Telefono"]) . "</td>";
                                            echo "<td>";
                                            echo "<a href='register_personal.php?dni=" . htmlspecialchars($row["DNI"]) . "' class='btn btn-primary btn-sm'>Editar</a>";
                                            echo " | ";
                                            echo "<form method='post' style='display:inline;'>";
                                            echo "<input type='hidden' name='delete_dni' value='" . htmlspecialchars($row["DNI"]) . "'>";
                                            echo "<input type='submit' class='btn btn-danger btn-sm' value='Eliminar'>";
                                            echo "</form>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="botonplus" class="container text-center">
                <div class="row">
                    <div class="col">
                        <div class="d-flex justify-content-between">
                            <div class="ml-auto">
                                <a class="btn btn-secondary mr-3" href="./register_personal.php" role="button">
                                    <svg id="iconoplus" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $.fn.dataTable.defaults.pageLength = 25;
            $('#personal').DataTable({
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
</body>

</html>