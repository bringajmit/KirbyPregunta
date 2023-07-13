<?php

class EditorModel{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }


    public function crearPregunta()
    {
        if (isset($_POST["crearPregunta"])) {
            $descripcion = $_POST["descripcion"];
            $dificultad = $_POST["dificultad"];
            $categoria = $_POST["categoria"];
            $default = 0;
            $opcion1 = $_POST["opcion_uno"];
            $opcion2 = $_POST["opcion_dos"];
            $opcion3 = $_POST["opcion_tres"];
            $opcion4 = $_POST["opcion_cuatro"];
            $opcionCorrecta = $_POST["opcionCorrecta"];
            $aprobada = true;
            $reportada = false;
            $sugerida = false;
            $fecha = date("Y-m-d");
            $sql = "INSERT INTO pregunta(id_cat,id_nivel,descripcion,aciertos,cant_presentaciones,porcentaje_aciertos,aprobada,reportada,fecha,sugerida)
                VALUES (?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param("iisiiiiisi", $categoria, $dificultad, $descripcion, $default, $default, $default,
                $aprobada,$reportada,$fecha,$sugerida);
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

    public function getDificultad()
    {
        $sql = "SELECT id as id, descripcion as descripcion 
                    FROM nivel";
        return $this->database->query($sql);
    }

    public function getPreguntas()
    {
        $sql = "SELECT id, descripcion
                    FROM pregunta";
        return $this->database->query($sql);
    }

    public function eliminarPreguntas(){
        if (isset($_POST["eliminarPregunta"])) {
            $idPregunta = $_POST["eliminarPregunta"];
            $sql2 = "DELETE FROM preguntas_contestadas WHERE id_pregunta='$idPregunta'";
            $this->database->query($sql2);
            $sql="DELETE FROM opcion WHERE pregunta_id='$idPregunta'";
            $this->database->query($sql);
            $sql2 = "DELETE FROM pregunta WHERE id='$idPregunta'";
            $this->database->query($sql2);

        }
    }

    public function modificarPregunta($idPregunta){
        if(isset($_POST["modificar"])){
            $pregunta=$_POST["descripcion"];
            $opcion1=$_POST["opcion_1"];
            $opcion2=$_POST["opcion_2"];
            $opcion3=$_POST["opcion_3"];
            $opcion4=$_POST["opcion_4"];
            $categoria=$_POST["categoria"];
            $dificultad=$_POST["dificultad"];
            $opcionCorrecta=$_POST["opcionCorrecta"];
            $idOpciones=$this->getOpcionesPorID($idPregunta)->fetch_all();
            $idOpcion1=$idOpciones[0][1];
            $idOpcion2=$idOpciones[1][1];
            $idOpcion3=$idOpciones[2][1];
            $idOpcion4=$idOpciones[3][1];
            $sql="UPDATE pregunta 
                    SET descripcion='$pregunta', id_cat='$categoria',id_nivel='$dificultad'
                    WHERE id='$idPregunta' ";
            $this->database->query($sql);
            $sql2="UPDATE opcion
                    SET descripcion='$opcion1', es_correcta=false 
                    WHERE id='$idOpcion1'";
            $this->database->query($sql2);
            $sql2="UPDATE opcion
                    SET descripcion='$opcion2' , es_correcta=false
                    WHERE id='$idOpcion2'";
            $this->database->query($sql2);
            $sql2="UPDATE opcion
                    SET descripcion='$opcion3' , es_correcta=false
                    WHERE id='$idOpcion3'";
            $this->database->query($sql2);
            $sql2="UPDATE opcion
                    SET descripcion='$opcion4' , es_correcta=false
                    WHERE id='$idOpcion4'";
            $this->database->query($sql2);
            $sql2="UPDATE opcion
                    SET es_correcta=true 
                    WHERE id='$opcionCorrecta'";
            $this->database->query($sql2);
        }
    }

    public function getPreguntaPorID($idPregunta){
            $sql = "SELECT p.descripcion as preguntaD,c.id as idCat,
                        c.descripcion as categoriaD,n.id as idDificultad, n.descripcion as dificultadD 
                    FROM pregunta p JOIN categoria c ON p.id_cat=c.id
                        JOIN nivel n ON p.id_nivel=n.id
                        WHERE p.id='$idPregunta'";
            return $this->database->query($sql)->fetch_assoc();
    }

    public function getOpcionesIncorrectas($idPregunta){
            $sql="SELECT descripcion, id 
                    FROM opcion 
                    WHERE pregunta_id='$idPregunta' and es_correcta=false";
            return $this->database->query($sql);

    }

    public function getCategoriasIncorrectas(){
            $sql="SELECT DISTINCT c.descripcion, c.id 
                    FROM categoria c JOIN pregunta p ON p.id_cat!=c.id";
            return $this->database->query($sql);

    }

    public function getDificultadesIncorrectas(){
            $sql="SELECT DISTINCT n.descripcion, n.id 
                    FROM nivel n JOIN pregunta p ON p.id_nivel!=n.id";
            return $this->database->query($sql);

    }

    public function getOpcionesPorID($idPregunta){
            $sql="SELECT descripcion, id 
                    FROM opcion 
                    WHERE pregunta_id='$idPregunta'";
            return $this->database->query($sql);

    }



    public function getCategoriaCorrecta(){
        if(isset($_POST["modificarPregunta"])) {
            $idPregunta = $_POST["modificarPregunta"];
            $sql="SELECT descripcion, id 
                    FROM categoria WHERE pregunta_id='$idPregunta' 
                                 and  es_correcta = true";
            return $this->database->query($sql);
        }
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
    public function crearCategoria()
    {
        if (isset($_POST["crearCategoria"])) {
            if(is_null($this->validarCategoria($_POST["descripcion"]))){
                $descripcion = $_POST["descripcion"];
                $icon = $_POST["icon"];
                $color = $_POST["color"];
                $color = ltrim($color, '#');
                $decimal = hexdec($color);
                $hexadecimal = dechex($decimal);

                $sql = "INSERT INTO categoria(descripcion,icon,color)
                VALUES (?,?,?)";
                $stmt = $this->database->prepare($sql);
                $stmt->bind_param("sss", $descripcion, $icon, $hexadecimal);
                $stmt->execute();
                $stmt->close();
            }

        }
    }

    public function validarCategoria($crearCategoria){
        $sql = " SELECT descripcion FROM categoria WHERE descripcion='$crearCategoria'";
        $resultado=$this->database->query($sql)->fetch_assoc();

        return $resultado;
    }


}

