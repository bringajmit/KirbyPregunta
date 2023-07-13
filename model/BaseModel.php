<?php

class BaseModel{
    private $database;

    public function getDatabase(){
        $this->database = new mysqli("localhost","root","","juego");
    }

    public function getRolAdmin($idUsuario){
        $this->getDatabase();
        $sql="SELECT id_rol FROM usuario WHERE id='$idUsuario'";
        $resultado=$this->database->query($sql)->fetch_assoc()["id_rol"];
        if($resultado==3){
            return $resultado;
        }
    }

    public function getRolEditor($idUsuario){
        $this->getDatabase();
        $sql="SELECT id_rol FROM usuario WHERE id='$idUsuario'";
        $resultado=$this->database->query($sql)->fetch_assoc()["id_rol"];
        if($resultado==2){
            return $resultado;
        }
    }

    public function listarDatos($username){
        $this->getDatabase();
        $sql="SELECT u.*, count(p.id) as contador
            FROM usuario u JOIN partida p ON u.id=p.id_usuario
            WHERE u.username='$username'";
        return $this->database->query($sql);
    }

    public function getColorIconCategoria($idPregunta){
        $this->getDatabase();
        $sql="SELECT c.color as color, c.icon as icon
                FROM categoria as c JOIN pregunta AS p ON p.id_cat=c.id
                WHERE p.id='$idPregunta'";
        return $this->database->query($sql)->fetch_assoc();
    }

    public function getOpcionCorrecta($idPregunta){
        $this->getDatabase();
        $sql="SELECT descripcion FROM opcion WHERE pregunta_id='$idPregunta' and es_correcta=TRUE";
        $resultado=$this->database->query($sql)->fetch_assoc();
        return $resultado['descripcion'];
    }
}