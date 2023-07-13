<?php

class HistorialModel{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function listarPartidas($idUsuario){
        $sql="SELECT p.puntaje as puntaje
            FROM partida p 
                WHERE p.id_usuario='$idUsuario'";
        return $this->database->query($sql);
    }

    public function listarDuelos($username){
        $sql="SELECT u.username as username, d.ganador as ganador,
                d.puntaje_retador as puntajeRetador, d.puntaje_rival as puntajeRival
            FROM usuario u JOIN duelo d ON d.id_retador=u.id or d.id_rival=u.id
            WHERE u.username!='$username' ";
        return $this->database->query($sql);
    }

    public function getCantidadDuelos($idUsuario){
        $sql="SELECT count(id) as contador
            FROM duelo
            WHERE id_retador='$idUsuario' or id_rival='$idUsuario' and aceptado= true";
        return $this->database->query($sql)->fetch_assoc()["contador"];
    }

    public function listarDatos($username){
        $sql="SELECT u.*, count(p.id) as contador
            FROM usuario u JOIN partida p ON u.id=p.id_usuario
            WHERE u.username='$username'";
        return $this->database->query($sql);
    }

    public function getRolEditor($idUsuario){
        $sql="SELECT id_rol FROM usuario WHERE id='$idUsuario'";
        $resultado=$this->database->query($sql)->fetch_assoc()["id_rol"];
        if($resultado==2){
            return $resultado;
        }
    }

    public function getRolAdmin($idUsuario){
        $sql="SELECT id_rol FROM usuario WHERE id='$idUsuario'";
        $resultado=$this->database->query($sql)->fetch_assoc()["id_rol"];
        if($resultado==3){
            return $resultado;
        }
    }

}