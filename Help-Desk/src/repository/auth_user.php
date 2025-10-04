<?php 
session_start();

include_once __DIR__ . '/../database/database.php';
include_once __DIR__ . '/login.php';

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? ''; 

function autenticarUsuario($email, $senha) {
    global $usuarios; // Acessa o array de usuários definido em database.php

    // Verifica se o array de usuários existe e não está vazio
    if (!isset($usuarios) || !is_array($usuarios)) {
        error_log("Erro: Array de usuários não encontrado ou inválido");
        return null;
    }

    foreach ($usuarios as $user) {
        if ($user['email'] === $email && password_verify($senha, $user['senha'])) {
            return $user; // Retorna os dados do usuário se a autenticação for bem-sucedida
        }
    }
    return null; // Retorna null se a autenticação falhar
}

// Processa a autenticação se os dados foram enviados via POST
if (!empty($email) && !empty($senha)) {
    $user = autenticarUsuario($email, $senha);
    
    if ($user !== null) {
        // Salva dados do usuário na sessão
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nome'] = $user['nome'];
        $_SESSION['usuario_email'] = $user['email'];
        $_SESSION['logado'] = true;
        
        // Usuário autenticado com sucesso, encaminha para a página apropriada
        encaminharUsuario($user);
    } else {
        // Falha na autenticação - redireciona de volta para login com erro
        header('Location: /pages/index.php?erro=1');
        exit();
    }
}

function obterNomeUsuario($id) {
    global $usuarios; // Acessa o array de usuários definido em database.php

    // Verifica se o array de usuários existe e não está vazio
    if (!isset($usuarios) || !is_array($usuarios)) {
        error_log("Erro: Array de usuários não encontrado ou inválido");
        return null;
    }

    foreach ($usuarios as $user) {
        if ($user['id'] === $id) {
            return $user['nome']; // Retorna o nome do usuário
        }
    }
    return null; // Retorna null se o usuário não for encontrado
}

?>
