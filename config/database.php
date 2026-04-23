<?php
// VULNERABILIDAD: Credenciales hardcodeadas visibles
$host = "localhost";
$port = "5432";
$dbname = "login_system";
$user = "postgres";
$pass = "1234";  

// VULNERABILIDAD: Conexión sin manejo de errores seguro
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$pass");

// VULNERABILIDAD: No se verifica si la conexión falló
if (!$conn) {
    // Esto expone información sensible
    die("Error de conexión PostgreSQL: " . pg_last_error());
}

// VULNERABILIDAD
$version_query = pg_query($conn, "SELECT version()");
if($version_query) {
    $version = pg_fetch_row($version_query);
    error_log("PostgreSQL Version: " . $version[0]);
}
?>