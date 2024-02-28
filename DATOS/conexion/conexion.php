<?php
$host = "srvmysql.mysql.database.azure.com";
$user = "srvadmin";
$clave = "#utilesdirectos1*";
$bd = "sis_venta";

// Inicializar la conexión mysqli
$conexion = mysqli_init();

// Configurar SSL
mysqli_ssl_set($conexion, NULL, NULL, "C:\Users\Paula\Downloads\DigiCertGlobalRootCA.crt.pem", NULL, NULL);

// Realizar la conexión utilizando SSL
mysqli_real_connect($conexion, $host, $user, $clave, $bd, 3306, MYSQLI_CLIENT_SSL);

// Verificar la conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Establecer el charset
mysqli_set_charset($conexion, "utf8");

// Ahora puedes usar la conexión $conn para realizar consultas SQL
// Por ejemplo:
//$query = "SELECT * FROM tabla";
//$result = mysqli_query($conn, $query);
//while ($row = mysqli_fetch_assoc($result)) {
//    echo $row['columna'];
//}
?>