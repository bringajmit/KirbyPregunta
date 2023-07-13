<?php

class PreguntaController{
    private $preguntaModel;
    private $renderer;

    public function __construct($preguntaModel, $view) {
        $this->preguntaModel=$preguntaModel;
        $this->renderer= $view;
    }

    public function sugerir(){
        $this->preguntaModel->sugerirPregunta();
        $data=[
            "categorias"=>$this->preguntaModel->getCategorias(),
            "perfil"=> $this->preguntaModel->listarDatos($_SESSION["username"]),
            "admin"=>$this->preguntaModel->getRolAdmin($_SESSION["id_usuario"]),
            "editor"=>$this->preguntaModel->getRolEditor($_SESSION["id_usuario"])
        ];
        $this->renderer->render("sugerirPregunta",$data);
    }

    public function preguntasSugeridas(){
        $this->preguntaModel->aceptarPregunta();
        $this->preguntaModel->rechazarPregunta();
        $data=[
            "preguntasSugeridas"=>$this->preguntaModel->getPreguntasSugeridas(),
            "perfil"=> $this->preguntaModel->listarDatos($_SESSION["username"]),
            "admin"=>$this->preguntaModel->getRolAdmin($_SESSION["id_usuario"]),
            "editor"=>$this->preguntaModel->getRolEditor($_SESSION["id_usuario"])
        ];
        $this->renderer->render("preguntasSugeridas",$data);
    }

    public function preguntasReportadas(){
        $this->preguntaModel->aceptarReporte();
        $this->preguntaModel->rechazarReporte();
        $data=[
            "preguntasReportadas"=>$this->preguntaModel->getPreguntasReportadas(),
            "perfil"=> $this->preguntaModel->listarDatos($_SESSION["username"]),
            "editor"=>$this->preguntaModel->getRolEditor($_SESSION["id_usuario"])
        ];
        $this->renderer->render("preguntasReportadas",$data);
    }


}