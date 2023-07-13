<?php

class PreguntaModel{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function sugerirPregunta(){
        if (isset($_POST["sugerirPregunta"])) {
            $descripcion = $_POST["descripcion"];
            $dificultad = 1;
            $categoria = $_POST["categoria"];
            $default = 0;
            $opcion1 = $_POST["opcion_uno"];
            $opcion2 = $_POST["opcion_dos"];
            $opcion3 = $_POST["opcion_tres"];
            $opcion4 = $_POST["opcion_cuatro"];
            $opcionCorrecta = $_POST["opcionCorrecta"];
            $sql = "INSERT INTO pregunta(id_cat,id_nivel,descripcion,aciertos,cant_presentaciones,porcentaje_aciertos, aprobada, reportada)
                    VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param("iisiiiii", $categoria, $dificultad, $descripcion, $default, $default, $default, $default,$default);
            $stmt->execute();
            $idPregunta = $stmt->insert_id;
            $stmt->close();

            $sql2 = "INSERT INTO opcion(pregunta_id,descripcion,es_correcta)
                        VALUES (?,?,?),(?,?,?),(?,?,?),(?,?,?)";
            $stmt = $this->database->prepare($sql2);
            $stmt->bind_param("isiisiisiisi", $idPregunta, $opcion1, $default, $idPregunta, $opcion2, $default, $idPregunta, $opcion3, $default, $idPregunta, $opcion4, $default);
            $stmt->execute();
            $stmt->close();

            switch ($opcionCorrecta) {
                case 1:
                    $this->marcarComoCorrecta($idPregunta, $opcion1);
                    break;
                case 2:
                    $this->marcarComoCorrecta($idPregunta, $opcion2);
                    break;
                case 3:
                    $this->marcarComoCorrecta($idPregunta, $opcion3);
                    break;
                case 4:
                    $this->marcarComoCorrecta($idPregunta, $opcion4);
                    break;
            }
        }
    }

    public function marcarComoCorrecta($idPregunta, $opcion){
        $sql = "UPDATE opcion SET es_correcta=true
                    WHERE pregunta_id='$idPregunta' and descripcion='$opcion'";
        $this->database->query($sql);
    }

    public function getCategorias(){
        $sql = "SELECT id, descripcion 
                    FROM categoria";
        return $this->database->query($sql);
    }

    public function getPreguntasSugeridas(){
        $sql="SELECT pregunta.descripcion as pregunta, pregunta.id as id, 
       GROUP_CONCAT(CONCAT_WS('<br>', opcion.descripcion) SEPARATOR '<br>') AS datos_opcion,
        GROUP_CONCAT( IF(opcion.es_correcta = 1, CONCAT_WS('<br>', opcion.descripcion), NULL) SEPARATOR '<br>' ) AS correcta,
        c.descripcion as categoria
        FROM categoria c JOIN pregunta ON pregunta.id_cat=c.id
        LEFT JOIN opcion ON pregunta.id = opcion.pregunta_id
        WHERE pregunta.aprobada = 0 GROUP BY pregunta.id;";

        return $this->database->query($sql);
    }

    public function aceptarPregunta(){
        if(isset($_POST["aceptarPregunta"])){
            $idPregunta=$_POST["aceptarPregunta"];
            $fechaActual=date("Y-m-d");
            $sql="UPDATE pregunta 
                    SET aprobada=1, fecha='$fechaActual'
                        WHERE id='$idPregunta'";
            $this->database->query($sql);
        }
    }

    public function rechazarPregunta(){
        if(isset($_POST["rechazarPregunta"])){
            $idPregunta=$_POST["rechazarPregunta"];
            $sql="DELETE FROM opcion WHERE pregunta_id='$idPregunta'";
            $this->database->query($sql);
            $sql="DELETE FROM pregunta WHERE id='$idPregunta'";
            $this->database->query($sql);
        }
    }

    public function aceptarReporte(){
        if(isset($_POST["aceptarReporte"])){
            $idPregunta=$_POST["aceptarReporte"];
            $sql="DELETE FROM opcion WHERE pregunta_id='$idPregunta'";
            $this->database->query($sql);
            $sql="DELETE FROM preguntas_contestadas WHERE id_pregunta='$idPregunta'";
            $this->database->query($sql);
            $sql="DELETE FROM pregunta WHERE id='$idPregunta'";
            $this->database->query($sql);
        }
    }

    public function rechazarReporte(){
        if(isset($_POST["rechazarReporte"])){
            $idPregunta=$_POST["rechazarReporte"];
            $sql="UPDATE pregunta 
                    SET reportada=false 
                        WHERE id='$idPregunta'";
            $this->database->query($sql);
        }
    }

    public function getPreguntasReportadas(){
        $sql="SELECT pregunta.descripcion as pregunta, pregunta.id as id, 
                GROUP_CONCAT(CONCAT_WS('<br>', opcion.descripcion) SEPARATOR '<br>') AS datos_opcion
                    FROM pregunta LEFT JOIN opcion ON pregunta.id = opcion.pregunta_id
                        WHERE pregunta.reportada = true 
                                GROUP BY pregunta.id;";
        return $this->database->query($sql);
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