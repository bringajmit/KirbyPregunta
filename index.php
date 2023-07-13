<?php
session_start();

include_once('Configuration.php');
$configuration = new Configuration();
$router = $configuration->getRouter();

$module = $_GET['module'] ?? 'user';
$method = $_GET['action'] ?? 'login';

if(!isset($_SESSION["id_usuario"])) {
    if($method!='register' &&  ($module!='mail ' && $method!="validarMail")) {
        $module = 'user';
        $method = 'login';
   }
}
//Rol Editor
if(isset($_SESSION["idRol"])){
    if($_SESSION["idRol"]!=2 && ($method == 'preguntasSugeridas' ||
            $method == 'crearPregunta' || $method == 'preguntasReportadas' ||
                    $method=='editarPregunta' || $method=='modificarPregunta')){
        header("Location:/user/lobby");
        exit();
    }
}
//Rol Administrador
if(isset($_SESSION["idRol"])){
    if($_SESSION["idRol"]!=1 && ($method == 'jugadores' ||
            $method == 'preguntas' || $method == 'partidas' )){
        header("Location:/user/lobby");
        exit();
    }
}
if(!isset($_POST["aceptarDuelo"])) {
    if (!isset($_SESSION["idDuelo"]) && ($method == 'versus' ||
            $method == 'correcto' || $method == 'resultado')) {
        header("Location:/user/lobby");
        exit();
    }
}

if (!isset($_SESSION["idPartida"]) && ($method == 'jugar' ||
        $method == 'respuestaCorrecta' || $method == 'respuestaIncorrecta')) {
    header("Location:/user/lobby");
    exit();
}

$router->route($module, $method);



