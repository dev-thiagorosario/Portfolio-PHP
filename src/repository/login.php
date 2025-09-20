<?php
session_start();

function encaminharUsuario($user) {
    if ($user['id'] === 1 || $user['id'] === 2) {
        header('Location: /pages/admin.php');
        exit();
    } else {
        header('Location: /pages/home.php');
        exit();
    }
}

?>
