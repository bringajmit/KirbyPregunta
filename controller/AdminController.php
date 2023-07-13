<?php

class AdminController{

    private $adminModel;
    private $renderer;

    public function __construct($adminModel, $renderer){
        $this->adminModel = $adminModel;
        $this->renderer = $renderer;
    }

    public function jugadores(){

        $data=[
            "jugadores"=>$this->adminModel->getJugadores(),
            "cantJugadores"=>$this->adminModel->getCantidadJugadores(),
            "cantJugadoresNuevos"=>$this->adminModel->getCantidadJugadoresNuevos(),
            "fechaActual"=>date("Y-m-d"),
            "paises"=>$this->adminModel->getPaises(),
            "admin"=>$this->adminModel->getRolAdmin($_SESSION["id_usuario"]),
            "perfil"=>$this->adminModel->listarDatos($_SESSION["username"]),
        ];
        $_SESSION["data"]=$data["jugadores"]->fetch_all();

        $this->renderer->render("vistaAdminJugadores",$data);
    }

    public function partidas(){
        $data=[
            "partidas"=>$this->adminModel->getPartidas(),
            "cantPartidas"=>$this->adminModel->getCantidadPartidas(),
            "fechaActual"=>date("Y-m-d"),
            "admin"=>$this->adminModel->getRolAdmin($_SESSION["id_usuario"]),
            "perfil"=>$this->adminModel->listarDatos($_SESSION["username"]),

        ];
        $_SESSION["data"]=$data["partidas"]->fetch_all();
        $this->renderer->render("vistaAdminPartidas",$data);
    }

    public function preguntas(){
        $data=[
            "preguntas"=>$this->adminModel->getPreguntas(),
            "cantPreguntas"=>$this->adminModel->getCantidadPreguntas(),
            "fechaActual"=>date("Y-m-d"),
            "admin"=>$this->adminModel->getRolAdmin($_SESSION["id_usuario"]),
            "perfil"=>$this->adminModel->listarDatos($_SESSION["username"]),
        ];
        $_SESSION["data"]=$data["preguntas"]->fetch_all();
        $this->renderer->render("vistaAdminPreguntas",$data);
    }



}