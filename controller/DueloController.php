<?php

class DueloController
{

    private $dueloModel;
    private $renderer;

    public function __construct($dueloModel, $renderer)
    {
        $this->dueloModel = $dueloModel;
        $this->renderer = $renderer;
    }

    public function comenzandoDuelo()
    {
        $idRival = $this->dueloModel->getIDRival($_SESSION["usernamePerfil"]);
        $_SESSION["idDuelo"] = $this->dueloModel->crearDuelo($_SESSION["id_usuario"], $idRival);
        header("Location:/duelo/versus");
        exit();
    }

    public function versus()
    {
        unset($_SESSION["flagRC"]);
        if (isset($_POST["aceptarDuelo"])) {
            $_SESSION["idDuelo"] = $_POST["aceptarDuelo"];
        }
        $this->dueloModel->aceptarDuelo($_SESSION["id_usuario"], $_SESSION["idDuelo"]);

        if (!isset($_SESSION["flagPregunta"])) {
            $_SESSION["flagPregunta"] = 1;
            $pregunta = $this->dueloModel->obtenerPreguntaRandom($_SESSION["id_usuario"]);
            $_SESSION["idPregunta"] = $pregunta["id"];
            $_SESSION["pregunta"] = $pregunta["descripcion"];
            $_SESSION["tiempo"] = date("Y-m-d H:i:s");
            $_SESSION["opcionCorrecta"] = $this->dueloModel->obtenerOpcionCorrecta($pregunta["id"]);
        }

        if (isset($_SESSION["valorV"])) {
            if ($_SESSION["valorV"] == false) {
                header("Location:/duelo/resultado");
                exit();
            } else {
                header("Location:/duelo/correcto");
                exit();
            }
        }

        $data = [
            "color" => $this->dueloModel->getColorIconCategoria($_SESSION["idPregunta"])["color"],
            "icon" => $this->dueloModel->getColorIconCategoria($_SESSION["idPregunta"])["icon"]
        ];

        $this->renderer->render("duelo", $data);
    }

    public function resultado()
    {
        unset($_SESSION["flagPregunta"]);
        unset($_SESSION["valorV"]);

        $data = [
            "puntajes" => $this->dueloModel->getPuntajesDuelo($_SESSION["idDuelo"]),
            "usernameRetador" => $this->dueloModel->getUsernameRetador($_SESSION["idDuelo"]),
            "usernameRival" => $this->dueloModel->getUsernameRival($_SESSION["idDuelo"]),
        ];

        if ($this->dueloModel->getIDRetador($_SESSION["idDuelo"]) == $_SESSION["id_usuario"]) {
            $this->renderer->render("resultado_parcial", $data);
        } else {
            $data += [
                "ganador" => $this->dueloModel->getGanador($_SESSION["idDuelo"])
            ];
            $this->renderer->render("resultado", $data);
        }
        unset($_SESSION["idDuelo"]);

    }

    public function correcto()
    {
        unset($_SESSION["flagPregunta"]);
        unset($_SESSION["valorV"]);

        if (!isset($_SESSION["flagRC"])) {
            $_SESSION["flagRC"] = 1;
            $this->dueloModel->aumentarPuntaje($_SESSION["id_usuario"], $_SESSION["idDuelo"]);
        } else {
            header("Location:/duelo/versus");
            exit();
        }

        $data = [
            "pregunta" => $_SESSION["pregunta"],
            "color" => $this->dueloModel->getColorIconCategoria($_SESSION["idPregunta"])["color"],
            "icon" => $this->dueloModel->getColorIconCategoria($_SESSION["idPregunta"])["icon"],
            "puntaje" => $this->dueloModel->getPuntaje($_SESSION["id_usuario"], $_SESSION["idDuelo"])
        ];
        $this->renderer->render("correcto", $data);
        header("Refresh:10.5; url=/duelo/versus");
        exit();
    }


}