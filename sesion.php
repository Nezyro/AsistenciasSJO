<?php
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    echo "Usuario: " . $_SESSION["Nombre"] . "<br>";
} else {
    echo "Ninguna sesión activa.";
}
?>
