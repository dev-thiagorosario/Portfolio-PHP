<?php

class Tarefa{
    private $id;
    private $id_status;
    private $tarefa;
    private $data_cadastrada;
    private $responsavel;
    private $prioridade;

    public function __construct($id, $id_status, $tarefa, $data_cadastrada) {
        $this->id = $id;
        $this->id_status = $id_status;
        $this->tarefa = $tarefa;
        $this->data_cadastrada = $data_cadastrada;
        $this->responsavel = $responsavel;
        $this->prioridade = $prioridade;
    }

}