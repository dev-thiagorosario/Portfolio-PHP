<?php
include_once '../repository/authenticatedUser.php';
$user = getAuthenticatedUser();

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: /pages/index.php');
    exit();
}

// Pega o nome do usuário da sessão
$usuario = $_SESSION['usuario_nome'] ?? 'Usuário';
?>
