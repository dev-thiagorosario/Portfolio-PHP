<?php

    global $tarefa;
    require_once __DIR__ . '/../service/task.service.php';
    require_once __DIR__ . '/../core/task.php';
    require_once __DIR__ . '/../database/connection.php';

    $tarefa = new Tarefa();
    $tarefa -> setNome_tarefa($_POST['titulo'] ?? '');
    $tarefa -> setData_cadastrada($_POST['data_limite'] ?? '');
    $tarefa -> setStatus($_POST['status'] ?? '');
    $tarefa -> setPrioridade($_POST['urgencia'] ?? '');
    $tarefa -> setResponsavel($_POST['responsavel'] ?? '');

    $conexao = new Connection();

    $tarefaService = new TaskService($conexao, $tarefa);

    var_dump($tarefa);