<?php

include_once ('FPDF/plantilla.php');
class AdminModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function getJugadores(){
        if (isset($_POST["filtrar"])) {
            $fechaDesde = $_POST["fechaDesde"];
            $fechaHasta = $_POST["fechaHasta"];
            return $this->getJugadoresFiltrados($fechaDesde,$fechaHasta);
        } else {
            $sql = "SELECT username, nombre, fecha_ingreso ,
                  FLOOR(DATEDIFF(CURDATE(), fecha_nacimiento) / 365) AS edad, pais, porcentaje_aciertos, sexo
                    FROM usuario";
            return $this->database->query($sql);
        }
    }

    public function filtrar($fechaDesde, $fechaHasta){
        $conditions[]="fecha_ingreso BETWEEN '".$fechaDesde."' and '". $fechaHasta."'";

        if (isset($_POST["pais"])) {
            $pais = $_POST["pais"];
            $conditions[] = "pais ='".$pais."'";
        }

        if (isset($_POST["sexo"])) {
            $sexo=$_POST["sexo"];
            $conditions[] = "sexo = '".$sexo."'";
        }

        if (isset($_POST["edad"])) {
           switch ($_POST["edad"]){
               case 'Medio':
                   $conditions[] = "FLOOR(DATEDIFF(CURDATE(), fecha_nacimiento) / 365) >= ".'18'." and FLOOR(DATEDIFF(CURDATE(), fecha_nacimiento) / 365)<".'65';
                   break;
               case 'Jubilados':
                   $conditions[] = "FLOOR(DATEDIFF(CURDATE(), fecha_nacimiento) / 365) >=".'65';
                   break;
               case 'Menores':
                   $conditions[] = "FLOOR(DATEDIFF(CURDATE(), fecha_nacimiento) / 365) <".'18';
                   break;
           }
        }

        if(isset($_POST["nuevos"])){
            $conditions[] ="datediff(CURDATE(),fecha_ingreso)<32";
        }

        $whereClause = "";
        if (!empty($conditions)) {
            $whereClause = "WHERE " . implode(" AND ", $conditions);
        }
        return $whereClause;
    }

    public function getJugadoresFiltrados($fechaDesde, $fechaHasta){
        $whereClause=$this->filtrar($fechaDesde,$fechaHasta);
        $sql = "SELECT username, nombre, fecha_ingreso ,
                  FLOOR(DATEDIFF(CURDATE(), fecha_nacimiento) / 365) AS edad, pais, porcentaje_aciertos, sexo
                    FROM usuario " . $whereClause;
        return $this->database->query($sql);
    }

    public function getCantidadJugadores(){
        if (isset($_POST["filtrar"])) {
            $fechaDesde = $_POST["fechaDesde"];
            $fechaHasta = $_POST["fechaHasta"];
            return $this->getCantidadJugadoresFiltrados($fechaDesde,$fechaHasta)->fetch_assoc()["contador"];
        } else {
            $sql = "SELECT COUNT(id) as contador
                    FROM usuario";
            return $this->database->query($sql)->fetch_assoc()["contador"];
        }
    }

    public function getCantidadJugadoresNuevos(){
            $sql = "SELECT COUNT(id) as contador
                    FROM usuario
                    WHERE datediff(CURDATE(),fecha_ingreso)<32 ";
            return $this->database->query($sql)->fetch_assoc()["contador"];
    }

    public function getCantidadJugadoresFiltrados($fechaDesde, $fechaHasta){
        $whereClause=$this->filtrar($fechaDesde,$fechaHasta);
        $sql = "SELECT COUNT(id) as contador
                FROM usuario " . $whereClause;
        return $this->database->query($sql);
    }

    public function getPaises(){
        $sql="SELECT DISTINCT pais
                FROM usuario";
        return $this->database->query($sql);
    }



    public function getPartidas(){
        if (isset($_POST["filtrar"])) {
            $fechaDesde = $_POST["fechaDesde"];
            $fechaHasta = $_POST["fechaHasta"];
            $sql = "SELECT p.puntaje as puntaje, p.fecha as fecha, u.username as username, p.id as id
                        FROM partida p JOIN usuario u ON p.id_usuario=u.id
                        WHERE fecha BETWEEN '$fechaDesde' and  '$fechaHasta' ";
            return $this->database->query($sql);
        }else {
            $sql = "SELECT p.puntaje as puntaje, p.fecha as fecha, u.username as username, p.id as id
                FROM partida p JOIN usuario u ON p.id_usuario=u.id";
            return $this->database->query($sql);
        }
    }

    public function getCantidadPartidas(){
        if (isset($_POST["filtrar"])) {
            $fechaDesde = $_POST["fechaDesde"];
            $fechaHasta = $_POST["fechaHasta"];
            $sql = "SELECT COUNT(*) as contador
                        FROM partida
                        WHERE fecha BETWEEN '$fechaDesde' and  '$fechaHasta' ";
            return $this->database->query($sql)->fetch_assoc()["contador"];
        }else {
            $sql = "SELECT COUNT(*) as contador
                FROM partida";
            return $this->database->query($sql)->fetch_assoc()["contador"];
        }
    }

    public function getPreguntas(){
        if (isset($_POST["filtrar"])) {
            if(isset($creadas)){
                $whereClause=" and ";
            }
            $fechaDesde = $_POST["fechaDesde"];
            $fechaHasta = $_POST["fechaHasta"];
            $sql="SELECT p.id as id, c.descripcion as cDescripcion, n.descripcion as nDescripcion, 
                    p.descripcion as pDescripcion, p.aciertos as aciertos, p.cant_presentaciones as cantPresentaciones, 
                    p.porcentaje_aciertos as porcentajeAciertos, p.fecha as fecha 
                    FROM pregunta p JOIN categoria C ON p.id_cat=c.id
                    JOIN nivel n on p.id_nivel=n.id
                    WHERE fecha BETWEEN '$fechaDesde' and  '$fechaHasta' and aprobada=1
                    ORDER BY p.id";
            return $this->database->query($sql);
        }else {
            $sql = "SELECT p.id as id, c.descripcion as cDescripcion, n.descripcion as nDescripcion, 
                p.descripcion as pDescripcion, p.aciertos as aciertos, p.cant_presentaciones as cantPresentaciones, 
                    p.porcentaje_aciertos as porcentajeAciertos, p.fecha as fecha 
                FROM pregunta p JOIN categoria C ON p.id_cat=c.id
                    JOIN nivel n on p.id_nivel=n.id
                where aprobada=1
                    ORDER BY p.id";
            return $this->database->query($sql);
        }
    }

    public function getCantidadPreguntas(){
        if (isset($_POST["filtrar"])) {
            $fechaDesde = $_POST["fechaDesde"];
            $fechaHasta = $_POST["fechaHasta"];
            $sql="SELECT count(*) as contador
                    FROM pregunta p 
                    WHERE fecha BETWEEN '$fechaDesde' and  '$fechaHasta' and aprobada=1";
            return $this->database->query($sql)->fetch_assoc()["contador"];
        }else {
            $sql="SELECT count(*) as contador
                    FROM pregunta p
                        WHERE aprobada=1";
            return $this->database->query($sql)->fetch_assoc()["contador"];
        }
    }

    public function listarDatos($username){
        $sql="SELECT u.*, count(p.id) as contador
            FROM usuario u JOIN partida p ON u.id=p.id_usuario
            WHERE u.username='$username'";
        return $this->database->query($sql);
    }

    public function getRolAdmin($idUsuario){
        $sql="SELECT id_rol FROM usuario WHERE id='$idUsuario'";
        $resultado=$this->database->query($sql)->fetch_assoc()["id_rol"];
        if($resultado==1){
            return $resultado;
        }
    }


}