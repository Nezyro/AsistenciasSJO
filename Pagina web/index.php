<?php require_once "config/conexion.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="assets/css/styles.css" rel="stylesheet" />
</head>
<body>

    <?php

    // inicializamos la sesión
    session_start();

    // condicional para comprobar si el usuario ya está logueado
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: welcome.php");
        exit;
    }

    // si no lo está, requerimos la configuración de config
    require_once "config.php";
    // acordarse de que nuestro PDO para conectarnos se llama $db

    // variables inicizlizadas con valores vacíos
    $userdni = "";
    $password = "";
    $username = "";
    $userdni_error = "";
    $password_error = "";
    $login_error = "";

    // Procesado de los datos del formulario cuando damos submit
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // validación nombre usuario.
        // si está vacío le decimos que introduzca su dni,
        if(empty(trim($_POST["userdni"]))){
            $userdni_error = "Introduce tu DNI";
        } elseif(!preg_match('/^[a-zA-Z0-9_]+$', trim($_POST["userdni"]))){
            // comprobamos que sólo introduzca caracteres permitidos, si no
            // lanzamos este mensaje de error:
            $userdni_error = "El DNI sólo contiene letras y números";
        } else {
            
            $sql = "SELECT DNI, Nombre FROM personal WHERE DNI = :DNI";

            if($stmt = $db->prepare($sql)){

                // bindeamos las variables para el stmt como parámetros
                // del POST
                $stmt->bindParam(":DNI", $param_userdni, PDO::PARAM_STR);

                // Establecemos los parámetros
                $param_userdni = trim($_POST["userdni"]);

                // Tentativa de ejecución del statment preparado
                if($stmt->execute()){
                    // Chequea si el dni del usuario existe (si devuelve
                    // alguna fila, vamos)
                    if($stmt->rowCount() == 1){
                        if($row = $stmt->fetch()){
                            $userdni = $row["DNI"];
                            $hashed_password = $row["Password"];
                            // si el password es correcto se inicia sesión
                            if(password_verify($password, $hashed_password)){
                                // password correcto iniciamos sesión
                                session_start();

                                // guardamos los datos en las variables de sesión
                                $_SESSION["loggedin"] = true;
                                $_SESSION["DNI"] = $userdni;
                                $_SESSION["Nombre"] = $username;

                                // redirigimos al usuario a su dashboar/home
                                if($row["rol"]=="profesor"){
                                    header("location: dashboard_profesor.php");
                                } elseif ($row["rol"]=="coordinador"){
                                    header("location: dashboard_coordinador.php");                                
                                } elseif ($row["rol"]=="administrador"){
                                    header("location: dashboard_coordinador.php");                   
                                }

                            } else {
                                // si el password no es correcto mostramos el mensaje
                                $login_error = "La contraseña no es correcta";
                            }

                        } else {
                            // si el fetch no ha ido bien
                            echo "Ups, algo ha ido mal.";
                        }
                
                        // Cerramos el statement (la frase de petición)
                        unset($stmt);
                    }
                }   
                // Cerramos la conexión con la base de datos que hemos hecho mediante
                // el PDO del config
                unset($db);

            } // fin del if de preparar el statement para conectar con la base de datos

        } // fin del else del condicional de si hemos puesto bien los datos en el formulario

    } //fin del if del request method = post
    ?>
</body>
</html>