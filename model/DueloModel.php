<?php

class DueloModel{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function crearDuelo($idRetador, $idRival){
        $puntajeDefault = 0; // Valor inicial del puntaje_usuario1
        $aceptado = false;
        $sql = "INSERT INTO duelo (id_retador, id_rival, puntaje_retador, puntaje_rival,aceptado) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("iiiii", $idRetador, $idRival, $puntajeDefault, $puntajeDefault,$aceptado);
        $stmt->execute();
        $idDuelo = $this->database->insert_id;
        $stmt->close();
        return $idDuelo;
    }

    public function obtenerPreguntaRandom(){
        $sql="SELECT p.id, p.descripcion
FROM pregunta p
LEFT JOIN preguntas_duelo AS pd ON p.id=pd.id_pregunta
WHERE pd.id_pregunta IS NULL and p.aprobada=true
order by RAND() LIMIT 1";
        $resultado=$this->database->query($sql)->fetch_assoc();
        return $resultado;
    }

    public function obtenerOpcionCorrecta($idPregunta){
        $sql="SELECT descripcion FROM opcion WHERE pregunta_id='$idPregunta' and es_correcta=TRUE";
        $resultado=$this->database->query($sql)->fetch_assoc();
        return $resultado['descripcion'];
    }

    public function getColorIconCategoria($idPregunta){
        $sql="SELECT c.color as color, c.icon as icon
FROM categoria as c JOIN pregunta AS p ON p.id_cat=c.id
WHERE p.id='$idPregunta'";
        return $this->database->query($sql)->fetch_assoc();
    }

    public function getIDRetador($idDuelo){
        $sql="SELECT id_retador FROM duelo WHERE id='$idDuelo'";
        return $this->database->query($sql)->fetch_assoc()["id_retador"];
    }

    public function getIDRival($username){
        $sql="SELECT id FROM usuario WHERE username='$username'";
        return $this->database->query($sql)->fetch_assoc()["id"];
    }

    public function getPuntajesDuelo($idDuelo){
        $sql="SELECT d.puntaje_retador as puntajeRetador, d.puntaje_rival as puntajeRival FROM duelo d WHERE d.id='$idDuelo'";
        return $this->database->query($sql)->fetch_assoc();
    }

    public function getUsernameRetador($idDuelo){
        $sql="SELECT username FROM usuario u JOIN duelo d ON d.id_retador=u.id and d.id='$idDuelo'";
        return $this->database->query($sql)->fetch_assoc()["username"];
    }

    public function getUsernameRival($idDuelo){
        $sql="SELECT username FROM usuario u JOIN duelo d ON d.id_rival=u.id and d.id='$idDuelo'";
        return $this->database->query($sql)->fetch_assoc()["username"];
    }

    public function getGanador($idDuelo){
        if($this->getPuntajesDuelo($idDuelo)["puntajeRetador"]>$this->getPuntajesDuelo($idDuelo)["puntajeRival"]){
            $ganador=$this->getUsernameRetador($idDuelo);
            $sql="UPDATE duelo SET ganador='$ganador' WHERE id='$idDuelo'";
            $this->database->query($sql);
            return $ganador;
        }elseif($this->getPuntajesDuelo($idDuelo)["puntajeRetador"]<$this->getPuntajesDuelo($idDuelo)["puntajeRival"]){
            $ganador=$this->getUsernameRival($idDuelo);
            $sql="UPDATE duelo SET ganador='$ganador' WHERE id='$idDuelo'";
            $this->database->query($sql);
            return $ganador;
        }else{
            $ganador="Empate";
            $sql="UPDATE duelo SET ganador='$ganador' WHERE id='$idDuelo'";
            $this->database->query($sql);
            return $ganador;
        }
    }

    public function aumentarPuntaje($idUsuario, $idDuelo){
        if($idUsuario == $this->getIDRetador($idDuelo)){
            $sql="UPDATE duelo SET puntaje_retador=puntaje_retador+1 where id='$idDuelo'";
            $this->database->query($sql);
        }else{
            $sql="UPDATE duelo SET puntaje_rival=puntaje_rival+1 where id='$idDuelo'";
            $this->database->query($sql);
        }
        $sql2="UPDATE usuario SET puntaje_final=puntaje_final+1 WHERE id='$idUsuario'";
        $this->database->query($sql2);

    }

    public function aceptarDuelo($idUsuario, $idDuelo){
        if($idUsuario!=$this->getIDRetador($idDuelo)){
            $sql="UPDATE duelo SET aceptado=true WHERE id='$idDuelo'";
            $this->database->query($sql);
        }
    }

    public function getPuntaje($idUsuario, $idDuelo){
        if($idUsuario==$this->getIDRetador($idDuelo)){
            $sql="SELECT puntaje_retador FROM duelo WHERE id='$idDuelo'";
            return $this->database->query($sql)->fetch_assoc()["puntaje_retador"];
        }else{
            $sql="SELECT puntaje_rival FROM duelo WHERE id='$idDuelo'";
            return $this->database->query($sql)->fetch_assoc()["puntaje_rival"];
        }
    }
}