<?php

class PartidaController{
    private $partidaModel;
    private $renderer;

    public function __construct($partidaModel, $view) {
        $this->partidaModel = $partidaModel;
        $this->renderer= $view;
    }

    public function comenzandoPartida(){
        $_SESSION["idPartida"] = $this->partidaModel->crearPartida($_SESSION["id_usuario"]);
        header("Location:/partida/jugar");
        exit();
    }

    public function nuevaPregunta(){
        $data=[
            "pregunta"=>$_SESSION["pregunta"],
            "idPregunta"=>$_SESSION["idPregunta"],
            "opciones"=>$this->partidaModel->obtenerOpciones($_SESSION["idPregunta"])
        ];
        echo json_encode($data);
    }

    public function jugar(){
        unset($_SESSION["flagRC"]);
        if(!isset($_SESSION["flagPregunta"])) {
            $_SESSION["flagPregunta"]=1;
            $nivelUsuario=$this->partidaModel->getNivelUsuario($_SESSION["id_usuario"]);
            $pregunta = $this->partidaModel->obtenerPreguntaRandom($_SESSION["id_usuario"], $nivelUsuario);
            $_SESSION["idPregunta"] = $pregunta["id"];
            $_SESSION["pregunta"] = $pregunta["descripcion"];
            $_SESSION["tiempo"]=date("Y-m-d H:i:s");
            $_SESSION["opcionCorrecta"]=$this->partidaModel->obtenerOpcionCorrecta($pregunta["id"]);
            $this->partidaModel->accionesPreguntaPresentada($_SESSION["id_usuario"],$_SESSION["idPregunta"]);
        }
        $this->partidaModel->reportarPregunta();


        if(isset($_SESSION["valorV"])){
            if($_SESSION["valorV"]==false){
                header("Location:/partida/respuestaIncorrecta");
                exit();
            }else{
                header("Location:/partida/respuestaCorrecta");
                exit();
            }
        }

        $data=[
            "color"=>$this->partidaModel->getColorIconCategoria($_SESSION["idPregunta"])["color"],
            "icon"=>$this->partidaModel->getColorIconCategoria($_SESSION["idPregunta"])["icon"],
            'perfil'=>$this->partidaModel->listarDatos($_SESSION["username"]),
            "admin"=>$this->partidaModel->getRolAdmin($_SESSION["id_usuario"]),
            "editor"=>$this->partidaModel->getRolEditor($_SESSION["id_usuario"])
        ];

        $this->renderer->render("jugar", $data);

    }



    public function respuestaCorrecta() {
        if($_SESSION["respondio"]==true){
            unset($_SESSION["flagPregunta"]);
            unset($_SESSION["valorV"]);
            if (!isset($_SESSION["flagRC"])) {
                $_SESSION["flagRC"] = 1;
                $this->partidaModel->accionesRespuestaCorrecta($_SESSION["id_usuario"], $_SESSION["idPregunta"], $_SESSION["idPartida"]);
            }else{
                header("Location:/partida/jugar");
                exit();
            }

            $data=[
                "pregunta"=>$_SESSION["pregunta"],
                "puntaje"=> $this->partidaModel->getPuntajePartida($_SESSION["id_usuario"]),
                "color"=>$this->partidaModel->getColorIconCategoria($_SESSION["idPregunta"])["color"],
                "icon"=>$this->partidaModel->getColorIconCategoria($_SESSION["idPregunta"])["icon"],
                'perfil'=>$this->partidaModel->listarDatos($_SESSION["username"]),
                "admin"=>$this->partidaModel->getRolAdmin($_SESSION["id_usuario"]),
                "editor"=>$this->partidaModel->getRolEditor($_SESSION["id_usuario"])
            ];

            $this->renderer->render('respuesta_correcta', $data);
            $_SESSION["respondio"]=false;
            header("Refresh:10; url=/partida/jugar");
            exit();
        }else{
            header("Location:/partida/jugar");
            exit();
        }


    }


    public function respuestaIncorrecta() {
        unset($_SESSION["valorV"]);
        unset($_SESSION["flagPregunta"]);
        $this->partidaModel->determinarNivelUsuario($_SESSION["id_usuario"]);
        $this->partidaModel->determinarDificultadPregunta($_SESSION["idPregunta"]);

        $data=[
            "pregunta"=>$_SESSION["pregunta"],
            "opcionCorrecta"=>$this->partidaModel->obtenerOpcionCorrecta($_SESSION["idPregunta"]),
            "puntaje"=> $this->partidaModel->getPuntajePartida($_SESSION["id_usuario"]),
            "color"=>$this->partidaModel->getColorIconCategoria($_SESSION["idPregunta"])["color"],
            "icon"=>$this->partidaModel->getColorIconCategoria($_SESSION["idPregunta"])["icon"],
            'perfil'=>$this->partidaModel->listarDatos($_SESSION["username"]),
            "admin"=>$this->partidaModel->getRolAdmin($_SESSION["id_usuario"]),
            "editor"=>$this->partidaModel->getRolEditor($_SESSION["id_usuario"])
        ];

        $this->renderer->render('respuesta_incorrecta', $data);
        unset($_SESSION["idPartida"]);
    }


}