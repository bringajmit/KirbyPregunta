<?php

class TimeModel
{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function calcularTiempo($tiempo){
            // Obtén la fecha y hora actual
            $now = time();

            // Convierte el tiempo pasado como parámetro a un objeto DateTime
            $tiempoObj = DateTime::createFromFormat("Y-m-d H:i:s", $tiempo);

            // Obtén el timestamp Unix del objeto DateTime
            $tiempoUnix = $tiempoObj->getTimestamp();

            // Calcula la diferencia en segundos
            $diferencia = $now - $tiempoUnix;

            if($diferencia < 11){
                return true;
            } else {
                return false;

        }
    }

}