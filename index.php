<?php
session_start();

// Si el usuario ya está autenticado, redirigir al dashboard
if (isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === true) {
    header('Location: view/usuarios/inicio.php');
    exit;
}

// Si no está autenticado, redirigir al login correcto
header('Location: view/usuarios/login.php');
exit;
?>
