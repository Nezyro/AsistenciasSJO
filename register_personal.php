<?php
require_once "config/config.php";

$dni = $nombre = $apellidos = $email = $rol = $password = $telefono = "";
$dni_err = $nombre_err = $apellidos_err = $email_err = $rol_err = $password_err = $telefono_err = "";
$dni_actual = ""; 

if (isset($_GET['dni'])) {
    $dni_actual = $_GET['dni'];

    $sql = "SELECT Nombre, Apellidos, Email, ROL, Password, Telefono FROM baseasistenciassjo.personal WHERE DNI = :dni_actual";

    if ($stmt = $db->prepare($sql)) {
        $stmt->bindParam(":dni_actual", $dni_actual, PDO::PARAM_STR);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $nombre = $row['Nombre'];
                $apellidos = $row['Apellidos'];
                $email = $row['Email'];
                $rol = $row['ROL'];
                $password = $row['Password'];
                $telefono = $row['Telefono'];
            } else {
                echo "No se encontraron datos.";
            }
        } else {
            echo "¡Ups! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
        }

        unset($stmt);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["dni_nuevo"])) {
    $nombre = trim($_POST["nombre"]);
    $apellidos = trim($_POST["apellidos"]);
    $email = trim($_POST["email"]);
    $rol = trim($_POST["rol"]);
    $password = trim($_POST["password"]);
    $telefono = trim($_POST["telefono"]);
    $nuevo_dni = trim($_POST["dni_nuevo"]);

    echo "Datos recibidos: ";
    echo "Nombre: $nombre, Apellidos: $apellidos, Email: $email, Rol: $rol, Password: $password, Telefono: $telefono, Nuevo DNI: $nuevo_dni, DNI Actual: $dni_actual";

    $sql = "UPDATE baseasistenciassjo.personal SET DNI = :nuevo_dni, Nombre = :nombre, Apellidos = :apellidos, Email = :email, ROL = :rol, Password = :password, Telefono = :telefono WHERE DNI = :dni_actual";

    if ($stmt = $db->prepare($sql)) {
        $stmt->bindParam(":nuevo_dni", $nuevo_dni, PDO::PARAM_STR);
        $stmt->bindParam(":dni_actual", $dni_actual, PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $stmt->bindParam(":apellidos", $apellidos, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":rol", $rol, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $telefono, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header("Location: personal.php");
            exit();
        } else {
            echo "Error en la ejecución de la consulta: ";
            print_r($stmt->errorInfo());
        }

        unset($stmt);
    }
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font: 14px sans-serif; }
        .wrapper { width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Registro</h2>
        <p>Por favor, complete este formulario para crear una cuenta.</p>
        <form action="register_personal.php?dni=<?php echo htmlspecialchars($dni_actual); ?>" method="post">
            <div class="form-group">
                <label>DNI</label>
                <input type="text" name="dni_nuevo" class="form-control" value="<?php echo htmlspecialchars($dni_actual); ?>">
            </div>    
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($nombre); ?>">
            </div>
            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" name="apellidos" class="form-control" value="<?php echo htmlspecialchars($apellidos); ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="form-group">
                <label>Rol</label>
                <select name="rol" class="form-control">
                    <option value="PRO" <?php echo ($rol == "PRO") ? "selected" : ""; ?>>Profesor</option>
                    <option value="COO" <?php echo ($rol == "COO") ? "selected" : ""; ?>>Colaborador</option>
                    <option value="ADM" <?php echo ($rol == "ADM") ? "selected" : ""; ?>>Administrador</option>
                </select>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo htmlspecialchars($password); ?>">
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($telefono); ?>">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Enviar">
                <input type="reset" class="btn btn-secondary ml-2" value="Limpiar">
            </div>
        </form>
    </div>    
</body>
</html>