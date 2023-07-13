<?php

class EditorController{
    private $editorModel;
    private $renderer;
    public function __construct($editorModel, $view) {
        $this->editorModel=$editorModel;
        $this->renderer= $view;
    }

    public function crearPregunta(){
        $this->editorModel->crearPregunta();
        $data=[
            "categorias"=>$this->editorModel->getCategorias(),
            "dificultad"=>$this->editorModel->getDificultad(),
            "perfil"=>$this->editorModel->listarDatos($_SESSION["username"]),
            "editor"=>$this->editorModel->getRolEditor($_SESSION["id_usuario"])
        ];
        $this->renderer->render("altaPreguntaEditor",$data);
    }

    public function editarPreguntas(){
        $this->editorModel->eliminarPreguntas();
        $this->editorModel->modificarPregunta($_SESSION["idPreg"]);
        $data=[
            "preguntas"=>$this->editorModel->getPreguntas(),
            "perfil"=>$this->editorModel->listarDatos($_SESSION["username"]),
            "editor"=>$this->editorModel->getRolEditor($_SESSION["id_usuario"])
        ];
        $this->renderer->render("bajaPreguntaEditor", $data);
    }

    public function modificarPregunta(){
        if(isset($_POST["modificarPregunta"])){
            $_SESSION["idPreg"]=$_POST["modificarPregunta"];
            $data=[
                "pregunta"=>$this->editorModel->getPreguntaPorID($_SESSION["idPreg"]),
                "opcionesIncorrectas"=>$this->editorModel->getOpcionesIncorrectas($_SESSION["idPreg"]),
                "opcionCorrecta"=>$this->editorModel->getOpcionCorrecta($_SESSION["idPreg"]),
                "opciones"=>$this->editorModel->getOpcionesPorID($_SESSION["idPreg"]),
                "categoriasIncorrectas"=>$this->editorModel->getCategoriasIncorrectas($_SESSION["idPreg"]),
                "dificultadesIncorrectas"=>$this->editorModel->getDificultadesIncorrectas($_SESSION["idPreg"]),
                "perfil"=>$this->editorModel->listarDatos($_SESSION["username"]),
                "editor"=>$this->editorModel->getRolEditor($_SESSION["id_usuario"])
            ];

            $this->renderer->render("modificarPreguntaEditor",$data);
        }else{
            header("Location: /editor/editarPreguntas");
            exit();
        }
    }

    public function crearCategoria(){
        $this->editorModel->crearCategoria();
        $data=[
            "perfil"=>$this->editorModel->listarDatos($_SESSION["username"]),
            "editor"=>$this->editorModel->getRolEditor($_SESSION["id_usuario"])
        ];
        $this->renderer->render("altaCategoriaEditor",$data);

    }


}