<?php
session_start();
include 'config/database.php';

// VULNERABILIDAD: Mostrar credenciales por GET
if(isset($_GET['show_creds']) && $_GET['show_creds'] == 1) {
    echo "<div style='background:yellow;padding:10px'>DEBUG - Usuario: admin | Contraseña: admin123</div>";
}

// VULNERABILIDAD: SQL Injection
$username = $_POST['username'];
$password = $_POST['password'];
$hashed_password = md5($password);

// Consulta vulnerable
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
$result = pg_query($conn, $query);

if($result && pg_num_rows($result) > 0) {
    $user = pg_fetch_assoc($result);
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    
    if(isset($_POST['remember'])) {
        setcookie('user_token', base64_encode($user['username']), time()+86400, '/');
    }
    
    header("Location: dashboard.php?welcome=" . urlencode($user['username']));
    exit();
} else {
    // User enumeration
    $check_user = pg_query($conn, "SELECT username FROM users WHERE username = '$username'");
    if(pg_num_rows($check_user) > 0) {
        echo "<script>alert('Contraseña incorrecta para: $username'); window.location='index.html';</script>";
    } else {
        echo "<script>alert('Usuario no existe: $username'); window.location='index.html';</script>";
    }
}
?>