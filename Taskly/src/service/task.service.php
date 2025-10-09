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
            $query = "INSERT INTO tarefa (id_status, tarefa, data_cadastrada, responsavel) VALUES (:id_status, :tarefa, :data_cadastrada, :responsavel)";
            $stmt = $this->conexao->prepare($query);

            $idStatus = $this->tarefa->getId_status();
            if ($idStatus === null || $idStatus === '') {
            $stmt->bindValue(':id_status', null, PDO::PARAM_NULL);
            } else {
            $stmt->bindValue(':id_status', $idStatus, PDO::PARAM_INT);
            }

            $stmt->bindValue(':tarefa', $this->tarefa->getTarefa());

            $dataCadastrada = $this->tarefa->getData_cadastrada();
            if ($dataCadastrada === null || $dataCadastrada === '') {
            $stmt->bindValue(':data_cadastrada', null, PDO::PARAM_NULL);
            } else {
            $stmt->bindValue(':data_cadastrada', $dataCadastrada);
            }

            $responsavel = $this->tarefa->getResponsavel();
            if ($responsavel === null || $responsavel === '') {
            $stmt->bindValue(':responsavel', null, PDO::PARAM_NULL);
            } else {
            $stmt->bindValue(':responsavel', $responsavel);
            }
            $stmt->execute();
            $novoId = $this->conexao->lastInsertId();
            $this->tarefa->setId($novoId);
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
