<?php

    //CRUD de tarefas
    // CRUD = Create, Read, Update, Delete

    class TaskService{

        private $conexao;
        private $tarefa;

        public function __construct(Connection $conexao, Tarefa $tarefa){
            $this->conexao = $conexao->connect();
            $this->tarefa = $tarefa;
        }

        public function inserirTarefa(){
            $query = "INSERT INTO tarefa (id_status, tarefa, data_cadastrada, responsavel, prioridade) VALUES (:id_status, :tarefa, :data_cadastrada, :responsavel, :prioridade)";
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
            $prioridade = $this->tarefa->getPrioridade();
            if ($prioridade === null || $prioridade === '') {
                $stmt->bindValue(':prioridade', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':prioridade', $prioridade);
            }
            $stmt->execute();
            $novoId = $this->conexao->lastInsertId();
            $this->tarefa->setId($novoId);
        }
        public function listarTarefas(){
            $query = "SELECT id, id_status, tarefa, data_cadastrada, responsavel FROM tarefa";
            $stmt = $this->conexao->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        public function atualizarTarefa(){
            $query = "UPDATE tarefa SET id_status = :id_status WHERE id = :id";
            $stmt = $this->conexao->prepare($query); 
            $stmt->bindValue(':id_status', $this->tarefa->getId_status());
            $stmt->bindValue(':id', $this->tarefa->getId());
            $stmt->execute();      
        }
        public function deletarTarefa(){
            $id = $this->tarefa->getId();

            if ($id === null || $id === '' || !is_numeric($id)) {
                throw new InvalidArgumentException('ID de tarefa inválido para exclusão.');
            }

            $id = (int) $id;

            if ($id <= 0) {
                throw new InvalidArgumentException('ID de tarefa inválido para exclusão.');
            }

            $query = "DELETE FROM tarefa WHERE id = :id";
            $stmt = $this->conexao->prepare($query); 
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        
    }
