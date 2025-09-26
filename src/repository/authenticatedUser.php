<?php
session_start();

function getAuthenticatedUser() {
    // Verifica se o usuário está logado usando a flag principal do sistema
    if (isset($_SESSION['logado']) && $_SESSION['logado'] === true && isset($_SESSION['usuario_id'])) {
        return [
            'authenticated' => true,
            'user_id' => $_SESSION['usuario_id'],
            'user_name' => $_SESSION['usuario_nome'] ?? null,
            'user_email' => $_SESSION['usuario_email'] ?? null
        ];
    } else {
        // Se não está autenticado, redireciona para login
        header('Location: /pages/index.php?erro=2');
        exit();
    }
}

