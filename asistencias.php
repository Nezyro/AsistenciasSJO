<?php
include 'config/config.php';

if (!isset($_GET['id']) || !isset($_GET['fecha'])) {
    die("Error: id o fecha no especificados en la URL.");
}

$id_materia = $_GET['id'];
$fecha = $_GET['fecha'];

$sql_materia = "SELECT Nombre FROM materia WHERE ID = :id_materia";
$stmt_materia = $db->prepare($sql_materia);
$stmt_materia->bindParam(":id_materia", $id_materia, PDO::PARAM_INT);
$stmt_materia->execute();
$materia = $stmt_materia->fetch(PDO::FETCH_ASSOC);

$nombre_materia = $materia['Nombre'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['fecha'])) {
        $alumnosVistos = isset($_POST['visto']) ? $_POST['visto'] : [];
        $fecha = $_POST['fecha'];

        foreach ($alumnosVistos as $alumnoID) {
            $sql_insert = "INSERT INTO faltas (ID_Alumno, ID_Materia, Fecha) VALUES (:alumnoID, :materiaID, :fecha)
                           ON DUPLICATE KEY UPDATE Fecha = :fecha";
            $stmt_insert = $db->prepare($sql_insert);
            $stmt_insert->bindParam(":alumnoID", $alumnoID, PDO::PARAM_INT);
            $stmt_insert->bindParam(":materiaID", $id_materia, PDO::PARAM_INT);
            $stmt_insert->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $stmt_insert->execute();
        }

        $sql_all_students = "SELECT ID FROM alumno WHERE id_materia = :id_materia";
        $stmt_all_students = $db->prepare($sql_all_students);
        $stmt_all_students->bindParam(":id_materia", $id_materia, PDO::PARAM_INT);
        $stmt_all_students->execute();
        $todosAlumnos = $stmt_all_students->fetchAll(PDO::FETCH_COLUMN, 0);

        $alumnosNoVistos = array_diff($todosAlumnos, $alumnosVistos);
        foreach ($alumnosNoVistos as $alumnoID) {
            $sql_delete = "DELETE FROM faltas WHERE ID_Alumno = :alumnoID AND ID_Materia = :materiaID AND Fecha = :fecha";
            $stmt_delete = $db->prepare($sql_delete);
            $stmt_delete->bindParam(":alumnoID", $alumnoID, PDO::PARAM_INT);
            $stmt_delete->bindParam(":materiaID", $id_materia, PDO::PARAM_INT);
            $stmt_delete->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $stmt_delete->execute();
        }
    }
}

$sql_faltas = "SELECT ID_Alumno FROM faltas WHERE ID_Materia = :id_materia AND Fecha = :fecha";
$stmt_faltas = $db->prepare($sql_faltas);
$stmt_faltas->bindParam(":id_materia", $id_materia, PDO::PARAM_INT);
$stmt_faltas->bindParam(":fecha", $fecha, PDO::PARAM_STR);
$stmt_faltas->execute();
$faltas_actuales = $stmt_faltas->fetchAll(PDO::FETCH_COLUMN, 0);
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

    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />

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
                <div class="sidebar-brand-text mx-3">Asistencias sjo</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Sub-Menu -->
            <div class="sidebar-heading">
                Gestion de Alumnos
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

                    <!-- Informacion de la pagina  tablas -->
                    <h1 class="h3 mb-2 text-gray-800">Faltas</h1>
                    <p class="mb-4">Aqui se muestran los alumnos que asisten a esta asignatura.</p>

                    <!--Informacion de las tablas-->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <?php echo htmlspecialchars($nombre_materia); ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form method="post"
                                    action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id_materia . "&fecha=" . $fecha; ?>">
                                    <div class="form-group">
                                        <label for="fecha">Fecha:</label>
                                        <strong><?php echo htmlspecialchars($fecha); ?></strong>
                                        <input type="hidden" name="fecha"
                                            value="<?php echo htmlspecialchars($fecha); ?>">
                                        <input type="hidden" name="form_submitted" value="1">
                                        <!-- Campo oculto para detectar el envío del formulario -->
                                    </div>
                                    <table id="example" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ALUMNO</th>
                                                <th>APELLIDOS</th>
                                                <th>ASISTENCIA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT alumno.ID AS AlumnoID, alumno.Nombre, alumno.Apellidos, materia.ID AS MateriaID, materia.Nombre AS Materia
                                                    FROM alumno
                                                    JOIN materia ON alumno.id_materia = materia.id
                                                    WHERE materia.id = :id_materia
                                                    ORDER BY alumno.Apellidos ASC";

                                            $stmt = $db->prepare($sql);
                                            $stmt->bindParam(":id_materia", $id_materia, PDO::PARAM_INT);
                                            $stmt->execute();

                                            $orden = 1;

                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $alumnoID = $row["AlumnoID"];
                                                $checked = in_array($alumnoID, $faltas_actuales) ? "checked" : "";
                                                echo "<tr>";
                                                echo "<td>" . $orden++ . ". " . $row["Nombre"] . "</td>";
                                                echo "<td>" . $row["Apellidos"] . "</td>";
                                                echo "<td><input type='checkbox' name='visto[]' value='" . $alumnoID . "' $checked></td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <button type="submit">Agregar/Quitar Falta</button>
                                </form>
                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
                                <script>
                                    $(document).ready(function () {
                                        $.fn.dataTable.defaults.pageLength = 25;
                                        $('#example').DataTable({
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