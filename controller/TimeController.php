<?php

class TimeController{

    private $timeModel;
    private $renderer;

    public function __construct($timeModel, $view) {
        $this->timeModel = $timeModel;
        $this->renderer= $view;
    }
    function response(){
        $jsonData = file_get_contents('php://input');
        $datos = json_decode($jsonData, true);
        if (isset($datos)) {
            $_SESSION["respondio"]=true;
            if ($datos["respuesta"] == $_SESSION["opcionCorrecta"] && $this->timeModel->calcularTiempo($_SESSION["tiempo"])) {
                $_SESSION["valorV"] = true;
            } else {
                $_SESSION["valorV"] = false;
            }
        }
        var_dump($_SESSION["valorV"]);

        echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
        <script src='/public/js/timeController.js'></script>";

    }
}