<?php
require_once "../config/config.php";

if (isset($_GET['ID'])) {
    $id_alumno = $_GET['ID'];
} else {
    $id_alumno = null;
}
$nombre = $apellidos = $id_materia = "";
$nombre_err = $apellidos_err = $id_materia_err = "";

if (isset($id_alumno)) {
    $sql = "SELECT Nombre, Apellidos, ID_Materia FROM baseasistenciassjo.alumno WHERE Id = :id_alumno";

    if ($stmt = $db->prepare($sql)) {
        $stmt->bindParam(":id_alumno", $id_alumno, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $nombre = $row['Nombre'];
                $apellidos = $row['Apellidos'];
                $id_materia = $row['ID_Materia'];
            } else {
                echo "No se encontraron datos.";
            }
        } else {
            echo "¡Ups! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
        }

        unset($stmt);
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_individual'])) {
        if (empty(trim($_POST["nombre"]))) {
            $nombre_err = "Por favor, introduzca un nombre.";
        } else {
            $nombre = trim($_POST["nombre"]);
        }

        if (empty(trim($_POST["apellidos"]))) {
            $apellidos_err = "Por favor, introduzca los apellidos.";
        } else {
            $apellidos = trim($_POST["apellidos"]);
        }

        if (empty($_POST["id_materia"])) {
            $id_materia_err = "Por favor, seleccione una materia.";
        } else {
            $id_materia = $_POST["id_materia"];
        }

        if (empty($nombre_err) && empty($apellidos_err) && empty($id_materia_err)) {
            if (isset($_POST['id_alumno'])) {
                $id_alumno = $_POST['id_alumno'];
                $sql = "UPDATE baseasistenciassjo.alumno SET Nombre = :nombre, Apellidos = :apellidos, ID_Materia = :id_materia WHERE Id = :id_alumno";

                if ($stmt = $db->prepare($sql)) {
                    $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                    $stmt->bindParam(":apellidos", $apellidos, PDO::PARAM_STR);
                    $stmt->bindParam(":id_materia", $id_materia, PDO::PARAM_INT);
                    $stmt->bindParam(":id_alumno", $id_alumno, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        header("location: alumnos_coo.php?id_alumno=$id_alumno");
                        exit();
                    } else {
                        echo "Error al actualizar el alumno.";
                    }
                }
            } else {
                $sql = "INSERT INTO baseasistenciassjo.alumno (Nombre, Apellidos, ID_Materia) VALUES (:nombre, :apellidos, :id_materia)";

                if ($stmt = $db->prepare($sql)) {
                    $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                    $stmt->bindParam(":apellidos", $apellidos, PDO::PARAM_STR);
                    $stmt->bindParam(":id_materia", $id_materia, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        header("location: alumnos_coo.php");
                        exit();
                    } else {
                        echo "Error al crear el alumno.";
                    }
                }
            }
        }
    }

    if (isset($_POST['submit_json'])) {
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
            $jsonFile = $_FILES['archivo']['tmp_name'];
            $jsonData = file_get_contents($jsonFile);
            $usuarios = json_decode($jsonData, true);

            if ($usuarios) {
                foreach ($usuarios as $usuario) {
                    $nombre = $usuario['nombre'];
                    $apellidos = $usuario['apellidos'];
                    $id_materia = $usuario['id_materia'];

                    $sql = "INSERT INTO baseasistenciassjo.alumno (Nombre, Apellidos, ID_Materia) VALUES (:nombre, :apellidos, :id_materia)";

                    if ($stmt = $db->prepare($sql)) {
                        $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                        $stmt->bindParam(":apellidos", $apellidos, PDO::PARAM_STR);
                        $stmt->bindParam(":id_materia", $id_materia, PDO::PARAM_INT);

                        if ($stmt->execute()) {
                            header("location: alumnos_coo.php");
                        } else {
                            echo "Error insertando usuario $nombre: " . $db->errorInfo()[2] . "<br>";
                        }
                    }
                }
            } else {
                echo "Error al decodificar el archivo JSON.";
            }
        } else {
            echo "Error al cargar el archivo.";
        }
    }

    unset($stmt);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 360px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>Registro</h2>
        <p>Por favor, complete este formulario para crear una cuenta.</p>
        <form action="register_alumnos_coo.php" method="post">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>">
                <span class="text-danger"><?php echo $nombre_err; ?></span>
            </div>
            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" name="apellidos" class="form-control" value="<?php echo $apellidos; ?>">
                <span class="text-danger"><?php echo $apellidos_err; ?></span>
            </div>
            <div class="form-group">
                <label>Materia</label>
                <select name="id_materia" class="form-control">
                    <option value="">Seleccione una materia</option>
                    <?php
                    $sql_materias = "SELECT Id, nombre FROM materia";
                    $result_materias = $db->query($sql_materias);
                    while ($row = $result_materias->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row['Id'] . "'" . (($row['Id'] == $id_materia) ? " selected" : "") . ">" . $row['nombre'] . "</option>";
                    }
                    ?>
                </select>
                <span class="text-danger"><?php echo $id_materia_err; ?></span>
            </div>
            <?php if (isset($id_alumno)):?>
                <input type="hidden" name="id_alumno" value="<?php echo $id_alumno;?>">
            <?php endif;?>
            <div class="form-group">
                <input type="submit" name="submit_individual" class="btn btn-primary" value="Enviar">
                <input type="reset" class="btn btn-secondary ml-2" value="Limpiar">
            </div>
        </form>
        </br>
        <?php if (!isset($id_alumno)): ?>
            <h2>Cargar varios estudiantes</h2>
            <form action="register_alumnoscoo.php" method="post" enctype="multipart/form-data">
                <label for="archivo">Elige un archivo JSON:</label>
                <input type="file" id="archivo" name="archivo">
                <br><br>
                <input type="submit" name="submit_json" class="btn btn-primary" value="CARGAR JSON">
            </form>
        <?php endif; ?>
    </div>
</body>

</html>