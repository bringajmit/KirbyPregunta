<?php

require './qr-code/phpqrcode/qrlib.php';

class UserModel{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function crearUsuario(){
        if (isset($_POST["registrarme"])) {
            $nombre = $_POST["nombre"];
            $fechaNacimiento = $_POST["fecha_nacimiento"];
            $sexo = $_POST["sexo"];
            $pais = $_POST["pais"];
            $ciudad = $_POST["ciudad"];
            $username = $_POST["register_username"];
            $password = $_POST["register_password"];
            $repetirPassword = $_POST["repetir_password"];
            $email = $_POST["email"];
            $foto_perfil=$_FILES["img_profile"]["name"];
            $fecha_ingreso=date('Y-m-d');
            $idRol=3;
            $estaValidado=false;
            $default=0;
            $idNivel=1;
            $passwordHash=hash('sha256',$password);


            $dir = './public/QR_CODE/' . $username . '.png';
            $filename = $dir;
            $tamanio = 10;
            $level = 'M';
            $frameSize = 3;
            $data = 'http://localhost/user/perfil?username=' . $username ;
            $qr_code = $username . '.png';

            QRcode::png($data,$filename,$level,$tamanio,$frameSize);

            if ($this->validarQueNoSeRepiteElUsername($username) &&
                $this->validarQueLasContraseñasSonIguales($password, $repetirPassword) &&
                    $this->validarQueNoSeRepiteElMail($email)) {
                    $this->moverArchivo();
                    $sql = "INSERT INTO usuario (nombre, fecha_nacimiento, sexo, pais, ciudad,
                     email, password, username, img_profile, fecha_ingreso, esta_validado, id_rol,id_nivel, puntaje_final,aciertos,cant_preguntas,porcentaje_aciertos,QR_code) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,? )";
                    $stmt = $this->database->prepare($sql);
                    $stmt->bind_param("ssssssssssssssssss", $nombre, $fechaNacimiento, $sexo, $pais, $ciudad, $email, $passwordHash, $username, $foto_perfil, $fecha_ingreso,$estaValidado, $idRol,$idNivel, $default,$default,$default,$default,$qr_code   );
                    $stmt->execute();
                    $stmt->close();
                    return true;
            }
            return false;
        }
    }


    public function editarPerfil($username)
    {
        if (isset($_POST["actualizar"])) {
            $nombre = $_POST["nombre"];
            $fechaNacimiento = $_POST["fecha_nacimiento"];
            $sexo = $_POST["sexo"];
            $pais = $_POST["pais"];
            $ciudad = $_POST["ciudad"];
            $password = $_POST["register_password"];
            $repetirPassword = $_POST["repetir_password"];
            $foto_perfil=$_FILES["img_profile"]["name"];
            $passwordHash=hash('sha256',$password);

            $dir = './public/QR_CODE/' . $username . '.png';
            $filename = $dir;
            $tamanio = 10;
            $level = 'M';
            $frameSize = 3;
            $data = 'http://localhost/user/perfil?username=' . $username ;
            $qr_code = $username . '.png';

            QRcode::png($data,$filename,$level,$tamanio,$frameSize);


            if ($this->validarQueLasContraseñasSonIguales($password, $repetirPassword)) {
                $this->moverArchivo();
                $sql = "UPDATE usuario SET nombre = ?, fecha_nacimiento = ? , sexo = ?, pais= ?, ciudad= ?, password = ?, img_profile= ?, qr_code= ?  WHERE username = ?";
                $stmt = $this->database->prepare($sql);
                $stmt->bind_param("sssssssss",$nombre, $fechaNacimiento, $sexo, $pais, $ciudad, $passwordHash, $foto_perfil , $qr_code, $username );
                $stmt->execute();
                $stmt->close();
                return true;
            }
            return false;

        }
    }

    //VALIDACIONES

    public function validarQueLasContraseñasSonIguales($password, $password2){
        if ($password == $password2) {
            return true;
        }
        return false;
    }

    public function validarQueNoSeRepiteElUsername($username){
        $consulta = "SELECT username FROM usuario ";
        $userHistorial = $this->database->query($consulta);
        foreach ($userHistorial as $u) {
            if ($username == $u["username"]) {
                return false;
            }
        }
        return true;
    }

    public function validarQueNoSeRepiteElMail($mail){
        $consulta = "SELECT email FROM usuario ";
        $userHistorial = $this->database->query($consulta);
        foreach ($userHistorial as $u){
            if($mail==$u["email"]){
                return false;
            }
        }
        return true;
    }

    public function login(){
        if(isset($_POST["login"])) {
            $username = $_POST["login_username"];
            $passwordHash =hash('sha256', $_POST["login_password"]);
            if ($this->validarQueElUsuarioExiste($username,$passwordHash)==1) {
                return true;
            }
            return false;
        }
    }

    public function validarQueElUsuarioExiste($username, $password) {
        $query = "SELECT username FROM usuario WHERE username = ? AND password = ? AND esta_validado = ?";
        $stmt = $this->database->prepare($query);
        $estaValidado = true;
        $stmt->bind_param("sss", $username, $password, $estaValidado);
        $stmt->execute();
        $stmt->store_result();
        $numRows = $stmt->num_rows;
        $stmt->close();
        return $numRows;
    }

    public function retornarMail(){
        if(isset($_POST["registrarme"])){
            $mail=$_POST["email"];
            return $mail;
        }
    }

    public function retornarUsername(){
        if(isset($_POST["login"])) {
            $username = $_POST["login_username"];
            return $username;
        }
    }

    public function retornarCiudad(){
        if(isset($_POST["ciudad"])) {
            $ciudad = $_POST["ciudad"];
            return $ciudad;
        }
    }

    public function retornarPassword(){
        if(isset($_POST["login"])) {
            $password = hash('sha256', $_POST["login_password"]);
            return $password;
        }
    }

    public function retornarID($username){
        $sql="SELECT id as id_usuario FROM usuario WHERE username='$username'";
        $id=$this->database->query($sql)->fetch_assoc();
        return $id["id_usuario"];
    }


    public function listarDatos($username){
        $sql="SELECT u.*, count(p.id) as contador
            FROM usuario u JOIN partida p ON u.id=p.id_usuario
            WHERE u.username='$username'";
        return $this->database->query($sql);
    }

    public function logout(){
        if(isset($_POST["logout"])){
            return true;
        }
        return false;
    }

    public function obtenerRanking(){
        $sql="SELECT * FROM usuario 
                WHERE esta_validado=1 
                ORDER BY puntaje_final DESC";
        $resultado=$this->database->query($sql);
        return $resultado;
    }

    public function obtenerMejoresCuatro(){
        $sql="SELECT * FROM usuario 
                WHERE esta_validado=1 
                ORDER BY puntaje_final DESC LIMIT 4";
        $resultado=$this->database->query($sql);
        return $resultado;
    }

    public function getPuntajePartida($idUsuario){
        $sql="SELECT p.puntaje 
                FROM partida p JOIN usuario u ON u.id=id_usuario
                    WHERE u.id='$idUsuario'
                 ORDER BY p.id DESC LIMIT 1;";
        $resultado=$this->database->query($sql)->fetch_assoc();
            return $resultado["puntaje"] ?? null;
    }

    public function moverArchivo(){
        if(isset($_FILES["img_profile"])){
            $nombre = $_FILES['img_profile']["name"];
            $ruta_temporal = $_FILES['img_profile']['tmp_name'];
            $ruta_destino = './public/fotosPerfil/' . $nombre;
            move_uploaded_file($ruta_temporal, $ruta_destino);
        }
    }

    public function obtenerDuelosPendientes($idUsuario){
        $sql="SELECT d.id as id, u.username as username 
                FROM duelo d, usuario u
                WHERE d.id_rival='$idUsuario' AND d.aceptado=false AND u.id!='$idUsuario'";
        return $this->database->query($sql);
    }

    public function getIDRol($idUsuario){
        $sql="SELECT id_rol FROM usuario WHERE id='$idUsuario'";
        return $this->database->query($sql)->fetch_assoc()["id_rol"];
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

    public function verificarQueElUsernameEsDiferente($usernamePerfil,$usernamePropio){
        if($usernamePerfil!=$usernamePropio){
            return "?";
        }
    }
}