<?php

$uri = "mysql://avnadmin:AVNS_CYzssKYMusl93IJ9W5w@mysql-dawdam-alumnes-f69a.b.aivencloud.com:28440/defaultdb?ssl-mode=REQUIRED";

$fields = parse_url($uri);

// Construye el DSN incluyendo la configuración SSL
$conn = "mysql:";
$conn .= "host=" . $fields["host"];
$conn .= ";port=" . $fields["port"];
$conn .= ";dbname=baseasistenciassjo"; // Cambia este valor al nombre de tu base de datos
$conn .= ";sslmode=verify-ca;sslrootcert=ca.pem";

try {
    // Conexión a la base de datos
    $db = new PDO($conn, $fields["user"], $fields["pass"]);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $db->query("SELECT VERSION()");
    // Consulta SQL para seleccionar todos los registros de una tabla
    // $query = "SELECT * FROM baseasistenciassjo.personal"; // Cambia 'nombre_de_tabla' por el nombre de tu tabla

    // // Ejecutar la consulta
    // $stmt = $db->query($query);

    // // Obtener los resultados
    // $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // // Imprimir los resultados
    // echo "<pre>";
    // print_r($results);
    // echo "</pre>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
