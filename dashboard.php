<?php
session_start();
include 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.html';
    header("Location: $redirect");
    exit();
}

$welcome = isset($_GET['welcome']) ? $_GET['welcome'] : $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- XSS vulnerable -->
        <h1>Bienvenido, <?php echo $welcome; ?></h1>
        
        <div class="settings-panel">
            <h3>Configuración</h3>
            <form method="GET">
                <select name="page">
                    <option value="profile">Perfil</option>
                    <option value="settings">Ajustes</option>
                    <option value="../../config/database">Config DB</option>
                </select>
                <button type="submit">Cargar</button>
            </form>
            
            <?php
            // LFI vulnerable
            if(isset($_GET['page'])) {
                include($_GET['page'] . '.php');
            }
            ?>
        </div>
        
        <a href="logout.php">Cerrar sesión</a>
    </div>
</body>
</html> 