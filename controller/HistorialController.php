<?php

class HistorialController{

    private $historialModel;
    private $renderer;

    public function __construct($historialModel, $renderer){
        $this->historialModel = $historialModel;
        $this->renderer = $renderer;
    }

    public function partidasJugadas(){
        $data=[
            "historialPartidas"=>$this->historialModel->listarPartidas($_SESSION["id_usuario"]),
            "perfil"=>$this->historialModel->listarDatos($_SESSION["username"]),
            "admin"=>$this->historialModel->getRolAdmin($_SESSION["id_usuario"]),
            "editor"=>$this->historialModel->getRolEditor($_SESSION["id_usuario"])
        ];
        $this->renderer->render("historialPartidas", $data);
    }

    public function duelosJugados(){
        $data=[
            "historialDuelos"=>$this->historialModel->listarDuelos($_SESSION["username"]),
            "perfil"=>$this->historialModel->listarDatos($_SESSION["username"]),
            "username"=>$_SESSION["username"],
            "contadorDuelos"=>$this->historialModel->getCantidadDuelos($_SESSION["id_usuario"]),
            "admin"=>$this->historialModel->getRolAdmin($_SESSION["id_usuario"]),
            "editor"=>$this->historialModel->getRolEditor($_SESSION["id_usuario"])
        ];
        $this->renderer->render('historialDuelos',$data);
    }

}