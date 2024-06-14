<?php
require_once '../config/config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['DNI']) || $_SESSION['rol'] != 'PRO') {
    header('Location: ../login.php');
    exit();
}

$profesor_dni = $_SESSION['DNI'];

try {
    $sql = "SELECT a.ID, a.Nombre, a.Apellidos, m.Nombre AS nombre_materia 
            FROM alumno a 
            INNER JOIN materia m ON a.ID_materia = m.ID 
            WHERE m.ID_Profesor = :profesor_dni";
    $stmt = $db->prepare($sql);
    $stmt->execute(['profesor_dni' => $profesor_dni]);

    if ($stmt->rowCount() > 0) {
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $students = [];
        $no_students_message = "No se encontraron alumnos inscritos en las materias del profesor.";
    }
} catch (PDOException $e) {
    error_log('Error en la base de datos: ' . $e->getMessage());
    die('Error en la base de datos. Por favor, contacte al administrador.');
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
    <title>Alumnos</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        <!-- End of Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800">Alumnos</h1>
                    <p class="mb-4">La tabla de alumnos en esta página muestra información sobre los alumnos del centro.</p>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Alumnos</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="alumnos" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Apellidos</th>
                                            <th>Materia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($students)) : ?>
                                            <?php foreach ($students as $student) : ?>
                                                <tr>
                                                    <td><?= $student['ID'] ?></td>
                                                    <td><?= $student['Nombre'] ?></td>
                                                    <td><?= $student['Apellidos'] ?></td>
                                                    <td><?= $student['nombre_materia'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="4"><?= isset($no_students_message) ? $no_students_message : 'No hay alumnos disponibles.' ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <?php include 'footer.php'; ?>
            <!-- End of Footer -->
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#alumnos').DataTable({
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
