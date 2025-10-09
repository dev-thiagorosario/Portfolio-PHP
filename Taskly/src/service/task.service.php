<?php

    //CRUD de tarefas

    class TaskService{

        private $conexao;
        private $tarefa;

        public function __construct(Connection $conexao, Tarefa $tarefa){
            $this->conexao = $conexao->connect();
            $this->tarefa = $tarefa;
        }

        public function inserirTarefa(){

        }
        public function listarTarefas(){
            // Implementação para listar tarefas
        }
        public function atualizarTarefa(){
            // Implementação para atualizar tarefas
        }
        public function deletarTarefa(){
            // Implementação para deletar tarefas
        }
        
    }