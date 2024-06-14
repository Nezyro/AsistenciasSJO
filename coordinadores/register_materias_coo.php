<?php
require_once "../config/config.php";

$nombre = $dia = $hora = $id_profesor = "";
$nombre_err = $dia_err = $hora_err = $id_profesor_err = $imagen_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["nombre"]))) {
        $nombre_err = "Por favor, introduzca un nombre.";
    } else {
        $nombre = trim($_POST["nombre"]);
    }

    if (empty(trim($_POST["dia"]))) {
        $dia_err = "Por favor, seleccione un día.";
    } else {
        $dia = trim($_POST["dia"]);
    }

    if (empty(trim($_POST["hora"]))) {
        $hora_err = "Por favor, introduzca una hora.";
    } else {
        $hora = trim($_POST["hora"]);
    }

    if (empty($_POST["id_profesor"])) {
        $id_profesor_err = "Por favor, seleccione un profesor.";
    } else {
        $id_profesor = $_POST["id_profesor"];
    }

    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
        $target_dir = "./assets/img/";
        $imageFileType = strtolower(pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION));
        $valid_extensions = array("jpg", "jpeg", "png");

        if (in_array($imageFileType, $valid_extensions)) {
            $new_file_name = $target_dir . $nombre . ".jpg";
            if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $new_file_name)) {
                $imagen_err = "Hubo un error al subir la imagen.";
            }
        } else {
            $imagen_err = "Sólo se permiten archivos JPG, JPEG y PNG.";
        }
    } else {
        $imagen_err = "Por favor, suba una imagen.";
    }

    if (empty($nombre_err) && empty($dia_err) && empty($hora_err) && empty($id_profesor_err) && empty($imagen_err)) {
        $sql = "INSERT INTO baseasistenciassjo.materia (Nombre, Dia, Hora, Id_Profesor) VALUES (:nombre, :dia, :hora, :id_profesor)";

        if ($stmt = $db->prepare($sql)) {
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":dia", $dia, PDO::PARAM_STR);
            $stmt->bindParam(":hora", $hora, PDO::PARAM_STR);
            $stmt->bindParam(":id_profesor", $id_profesor, PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("location: register_materias.php");
                exit();
            } else {
                echo "¡Ups! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
            }
            unset($stmt);
        }
    }
    unset($db);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Materias</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font: 14px sans-serif; }
        .wrapper { width: 360px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Registro de Materias</h2>
    <p>Por favor, complete este formulario para registrar una materia.</p>
    <form action="register_materias.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nombre de Materia</label>
            <input type="text" name="nombre" class="form-control">
            <span class="text-danger"><?php echo $nombre_err; ?></span>
        </div>
        <div class="form-group">
            <label>Día</label>
            <select name="dia" class="form-control">
                <option value="LUN">Lunes</option>
                <option value="MAR">Martes</option>
                <option value="MIE">Miércoles</option>
                <option value="JUE">Jueves</option>
                <option value="VIE">Viernes</option>
            </select>
        </div>
        <div class="form-group">
            <label>Hora</label>
            <input type="time" name="hora" class="form-control" placeholder="HH:MM:SS">
        </div>
        <div class="form-group">
            <label>Profesor</label>
            <select name="id_profesor" class="form-control">
                <?php
                $sql_profesores = "SELECT DNI, Nombre, Apellidos FROM personal WHERE ROL = 'PRO'";
                $result_profesores = $db->query($sql_profesores);
                while ($row = $result_profesores->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row['DNI'] . "'>" . $row['Nombre'] . " " . $row['Apellidos'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Imagen de la Materia</label>
            <input type="file" name="imagen" class="form-control">
            <span class="text-danger"><?php echo $imagen_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Enviar">
            <input type="reset" class="btn btn-secondary ml-2" value="Limpiar">
        </div>
    </form>
</div>
</body>
</html>
