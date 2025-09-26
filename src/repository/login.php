<?php

function encaminharUsuario($user) {
    if ($user['id'] === 1 || $user['id'] === 2) {
        header('Location: /pages/home-admin.php');
        exit();
    } else {
        header('Location: /pages/home.php');
        exit();
    }
}

?>
