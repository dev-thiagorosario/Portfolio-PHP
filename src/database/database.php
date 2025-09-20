<?php
// Simulação de banco de dados (array de usuários)
$usuarios = [
    [
        'id' => 1,
        'nome' => 'Administrador',
        'email' => 'admin@teste.com',
        'senha' => password_hash('admin123', PASSWORD_DEFAULT) 
    ],
    [
        'id' => 2,
        'nome' => 'Thiago Rosario',
        'email' => 'thiago@teste.com',
        'senha' => password_hash('thiago123', PASSWORD_DEFAULT) 
    ],
    [
        'id' => 3,
        'nome' => 'Maria Silva',
        'email' => 'maria@teste.com',
        'senha' => password_hash('maria123', PASSWORD_DEFAULT) 
    ],
    [
        'id' => 4,
        'nome' => 'João Santos',
        'email' => 'joao@teste.com',
        'senha' => password_hash('joao123', PASSWORD_DEFAULT) 
    ],
    [
        'id' => 5,
        'nome' => 'Usuário Comum',
        'email' => 'user@teste.com',
        'senha' => password_hash('user123', PASSWORD_DEFAULT) 
    ]
];
?>
