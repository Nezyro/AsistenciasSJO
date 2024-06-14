<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST["logout"])) {
    $_SESSION = array(); 

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();

    header("Location: login.php");
    exit;
}

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if($_SESSION["rol"] == "PRO"){
        header("Location: ./profesores/index_pro.php");
    } elseif ($_SESSION["rol"] == "COO"){
        header("Location: ./coordinadores/index_coo.php");                                
    } elseif ($_SESSION["rol"] == "ADM"){
        header("Location: index.php");                   
    }
    exit;
}

require_once "config/config.php";
$userdni = "";
$password = "";
$userdni_error = "";
$password_error = "";
$login_error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["userdni"]))){
        $userdni_error = "Introduce tu DNI o nombre de usuario";
    } else {
        $sql = "SELECT DNI, Nombre, Password, rol FROM personal WHERE DNI = :DNI OR Nombre = :Nombre";

        if($stmt = $db->prepare($sql)){
            $stmt->bindParam(":DNI", $param_userdni, PDO::PARAM_STR);
            $stmt->bindParam(":Nombre", $param_username, PDO::PARAM_STR);

            $param_userdni = trim($_POST["userdni"]);
            $param_username = trim($_POST["userdni"]);

            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $userdni = $row["DNI"];
                    $db_password = $row["Password"];

                    if($_POST["password"] === $db_password){
                        
                        $_SESSION["loggedin"] = true;
                        $_SESSION["DNI"] = $userdni;
                        $_SESSION["Nombre"] = $row["Nombre"];
                        $_SESSION["rol"] = $row["rol"];

                        if($row["rol"] == "PRO"){
                            header("Location: ./profesores/index_pro.php");
                        } elseif ($row["rol"] == "COO"){
                            header("Location: ./coordinadores/index_coo.php");                                
                        } elseif ($row["rol"] == "ADM"){
                            header("Location: index.php");                   
                        }
                        exit;
                    } else {
                        $login_error = "La contraseña no es correcta";
                    }
                } else {
                    $login_error = "El usuario no existe";
                }
            } else {
                $login_error = "Error al intentar iniciar sesión. Por favor, inténtalo de nuevo más tarde.";
            }
            unset($stmt);
        } else {
            $login_error = "Ups, algo ha ido mal.";
        }
    }
    unset($db);
}
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>

    <section class="vh-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 text-black">

                    <div class="px-5 ms-xl-4">
                        <i class="fas fa-crow fa-2x me-3 pt-5 mt-xl-4" style="color: #709085;"></i>
                        <span class="h1 fw-bold mb-0">ASISTENCIAS SJO</span>
                    </div>

                    <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">

                        <form style="width: 23rem;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                            method="post">

                            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Log in</h3>

                            <div class="form-outline mb-4">
                                <input type="text" id="userdni" name="userdni"
                                    class="form-control form-control-lg <?php echo (!empty($userdni_error)) ? 'is-invalid' : ''; ?>"
                                    value="<?php echo $userdni; ?>" />
                                <label class="form-label" for="userdni">DNI/Nombre</label>
                                <span class="invalid-feedback"><?php echo $userdni_error; ?></span>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="password" id="password" name="password"
                                    class="form-control form-control-lg <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>" />
                                <label class="form-label" for="password">Password</label>
                                <span class="invalid-feedback"><?php echo $password_error; ?></span>
                            </div>

                            <div class="pt-1 mb-4">
                                <button class="btn btn-info btn-lg btn-block" type="submit">Login</button>
                            </div>


                        </form>

                    </div>

                </div>
                <div class="col-sm-6 px-0 d-none d-sm-block">
                    <img src="assets/img/imagen_login.jpeg" alt="Login image" class="w-100 vh-100"
                        style="object-fit: cover; object-position: left;">
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>