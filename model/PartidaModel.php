<?php

class PartidaModel{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function crearPartida($id_usuario){
        $puntaje = 0;
        $fechaActual = date('Y-m-d H:i:s');
        $sql = "INSERT INTO partida (puntaje, fecha, id_usuario) VALUES (?, ?, ?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("isi", $puntaje, $fechaActual, $id_usuario);
        $stmt->execute();
        $partidaId = $stmt->insert_id;
        $stmt->close();
        return $partidaId;
    }

    public function obtenerPreguntaRandom($idUsuario, $nivelUsuario){
        do{
            $sql="SELECT p.id, p.descripcion
                FROM pregunta p
                LEFT JOIN preguntas_contestadas AS pc ON p.id = pc.id_pregunta AND pc.id_usuario = '$idUsuario'
                WHERE pc.id_pregunta IS NULL and p.id_nivel='$nivelUsuario' and p.aprobada=true
                order by RAND() LIMIT 1";
            $resultado=$this->database->query($sql)->fetch_assoc();
            $this->eliminarPreguntasContestadas($resultado,$idUsuario);
        }while($resultado==null);
        return $resultado;
    }

    public function eliminarPreguntasContestadas($resultado,$idUsuario){
        if($resultado==null) {
            $sql = "DELETE FROM preguntas_contestadas WHERE id_usuario='$idUsuario'";
            $this->database->query($sql);
        }
    }

    public function guardarPreguntaEnviada($idUsuario, $idPregunta){
        $sql="INSERT INTO preguntas_contestadas(id_usuario, id_pregunta) VALUES (?, ?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("ii", $idUsuario, $idPregunta);
        $stmt->execute();
        $stmt->close();
    }

    public function getColorIconCategoria($idPregunta){
        $sql="SELECT c.color as color, c.icon as icon
                FROM categoria as c JOIN pregunta AS p ON p.id_cat=c.id
                WHERE p.id='$idPregunta'";
        return $this->database->query($sql)->fetch_assoc();
    }

    public function obtenerOpciones($idPregunta){
        $sql="SELECT descripcion from opcion WHERE pregunta_id='$idPregunta'";
        return $this->database->query($sql)->fetch_all();
    }

    public function obtenerOpcionCorrecta($idPregunta){
        $sql="SELECT descripcion FROM opcion WHERE pregunta_id='$idPregunta' and es_correcta=TRUE";
        $resultado=$this->database->query($sql)->fetch_assoc();
        return $resultado['descripcion'];
    }

    public function aumentarCantidadPreguntasUsuario($idUsuario){
        $sql="UPDATE usuario SET cant_preguntas= cant_preguntas+1 WHERE id='$idUsuario'";
        $this->database->query($sql);
    }

    public function aumentarCantidadAciertosUsuario($idUsuario){
        $sql="UPDATE usuario SET aciertos= aciertos+1 WHERE id='$idUsuario'";
        $this->database->query($sql);
    }

    public function aumentarPuntajeFinalUsuario($idUsuario){
        $sql="UPDATE usuario SET puntaje_final= puntaje_final+1 WHERE id='$idUsuario'";
        $this->database->query($sql);
    }

    public function aumentarPuntajePartida($idPartida){
        $sql="UPDATE partida SET puntaje= puntaje+1 WHERE id='$idPartida'";
        $this->database->query($sql);
    }

    public function accionesRespuestaCorrecta($idUsuario, $idPregunta, $idPartida){
        $this->aumentarCantidadAciertosUsuario($idUsuario);
        $this->aumentarCantidadAciertosPregunta($idPregunta);
        $this->aumentarPuntajeFinalUsuario($idUsuario);
        $this->determinarNivelUsuario($idUsuario);
        $this->aumentarPuntajePartida($idPartida);
        $this->determinarDificultadPregunta($idPregunta);
    }

    public function accionesPreguntaPresentada($idUsuario, $idPregunta){
        $this->aumentarCantidadPreguntasUsuario($idUsuario);
        $this->aumentarCantidadPresentacionesPregunta($idPregunta);
        $this->guardarPreguntaEnviada($idUsuario,$idPregunta);
    }

    public function calcularPorcentajeAciertosUsuario($idUsuario){
        $sql="UPDATE usuario SET porcentaje_aciertos=aciertos/cant_preguntas*100 WHERE id='$idUsuario'";
        $this->database->query($sql);
    }

    public function determinarNivelUsuario($idUsuario){
        $this->calcularPorcentajeAciertosUsuario($idUsuario);
        $porc_aciertos=$this->getPorcentajeAciertosUsuario($idUsuario);
        if($porc_aciertos>70.0){
            $this->actualizarNivelUsuario(3, $idUsuario);
        }elseif ($porc_aciertos>=30.0 && $porc_aciertos<=70.0){
            $this->actualizarNivelUsuario(2, $idUsuario);
        }elseif ($porc_aciertos<30.0){
            $this->actualizarNivelUsuario(1, $idUsuario);
        }
    }

    public function actualizarNivelUsuario($nivel, $idUsuario){
        $sql="UPDATE usuario SET id_nivel= $nivel WHERE id='$idUsuario'";
        $this->database->query($sql);
    }

    public function getNivelUsuario($idUsuario){
        $sql="SELECT id_nivel FROM usuario WHERE id='$idUsuario'";
        $resultado=$this->database->query($sql)->fetch_assoc();
        return $resultado["id_nivel"];
    }

    public function getPorcentajeAciertosUsuario($idUsuario){
        $sql="SELECT porcentaje_aciertos FROM usuario WHERE id='$idUsuario'";
        $resultado= $this->database->query($sql)->fetch_assoc();
        return $resultado["porcentaje_aciertos"];
    }

    public function getPuntajePartida($idUsuario){
        $sql="SELECT p.puntaje 
               FROM partida p
                    WHERE p.id_usuario='$idUsuario'
                 ORDER BY p.id DESC LIMIT 1";
        $resultado=$this->database->query($sql)->fetch_assoc();
        return $resultado["puntaje"];
    }

    public function aumentarCantidadAciertosPregunta($idPregunta){
        $sql="UPDATE pregunta SET aciertos=aciertos+1 WHERE id='$idPregunta'";
        $this->database->query($sql);
    }

    public function aumentarCantidadPresentacionesPregunta($idPregunta){
        $sql="UPDATE pregunta SET cant_presentaciones=cant_presentaciones+1 WHERE id='$idPregunta'";
        $this->database->query($sql);
    }

    public function calcularPorcentajeAciertosPregunta($idPregunta){
        $sql="UPDATE pregunta SET porcentaje_aciertos=aciertos/cant_presentaciones*100 WHERE id='$idPregunta'";
        $this->database->query($sql);
    }

    public function getPorcentajeAciertosPregunta($idPregunta){
        $sql="SELECT porcentaje_aciertos FROM pregunta WHERE id='$idPregunta'";
        $resultado= $this->database->query($sql)->fetch_assoc();
        return $resultado["porcentaje_aciertos"];
    }

    public function determinarDificultadPregunta($idPregunta){
        $this->calcularPorcentajeAciertosPregunta($idPregunta);
        $porc_aciertos=$this->getPorcentajeAciertosPregunta($idPregunta);
        if($porc_aciertos>70.0){
            $this->actualizarDificultadPregunta(1, $idPregunta);
        }elseif ($porc_aciertos>=30.0 && $porc_aciertos<=70.0){
            $this->actualizarDificultadPregunta(2, $idPregunta);
        }elseif ($porc_aciertos<30.0){
            $this->actualizarDificultadPregunta(3, $idPregunta);
        }
    }

    public function actualizarDificultadPregunta($dificultad, $idPregunta){
        $sql="UPDATE pregunta SET id_nivel= $dificultad WHERE id='$idPregunta'";
        $this->database->query($sql);
    }

    public function reportarPregunta(){
        if(isset($_POST["reportarPregunta"])){
            $idPregunta=$_POST["reportarPregunta"];
            $sql="UPDATE pregunta
                    SET reportada=1 WHERE id='$idPregunta'";
            $this->database->query($sql);
        }
    }
    public function getRolAdmin($idUsuario){
        $sql="SELECT id_rol FROM usuario WHERE id='$idUsuario'";
        $resultado=$this->database->query($sql)->fetch_assoc()["id_rol"];
        if($resultado==1){
            return $resultado;
        }
    }

    public function getRolEditor($idUsuario){
        $sql="SELECT id_rol FROM usuario WHERE id='$idUsuario'";
        $resultado=$this->database->query($sql)->fetch_assoc()["id_rol"];
        if($resultado==2){
            return $resultado;
        }
    }
    public function listarDatos($username){
        $sql="SELECT u.*, count(p.id) as contador
            FROM usuario u JOIN partida p ON u.id=p.id_usuario
            WHERE u.username='$username'";
        return $this->database->query($sql);
    }


}