<?php

class Tarefa {
    private $id;
    private $id_status;
    private $tarefa;
    private $data_cadastrada;
    private $responsavel;
    private $prioridade;

    public function __construct(
        $id = null,
        $id_status = null,
        $tarefa = '',
        $data_cadastrada = '',
        $responsavel = '',
        $prioridade = ''
    ) {
        $this->id = $id;
        $this->id_status = $id_status;
        $this->tarefa = $tarefa;
        $this->data_cadastrada = $data_cadastrada;
        $this->responsavel = $responsavel;
        $this->prioridade = $prioridade;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId_status() {
        return $this->id_status;
    }

    public function setId_status($id_status) {
        $this->id_status = $id_status;
    }

    public function getTarefa() {
        return $this->tarefa;
    }

    public function setTarefa($tarefa) {
        $this->tarefa = $tarefa;
    }

    public function getData_cadastrada() {
        return $this->data_cadastrada;
    }

    public function setData_cadastrada($data_cadastrada) {
        $this->data_cadastrada = $data_cadastrada;
    }

    public function getResponsavel() {
        return $this->responsavel;
    }

    public function setResponsavel($responsavel) {
        $this->responsavel = $responsavel;
    }

    public function getPrioridade() {
        return $this->prioridade;
    }

    public function setPrioridade($prioridade) {
        $this->prioridade = $prioridade;
    }
}
