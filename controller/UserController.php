<?php
class UserController {
    private $userModel;
    private $renderer;
    public function __construct($userModel, $view) {
        $this->userModel = $userModel;
        $this->renderer= $view;
    }

    public function login(){
        if($this->userModel->login()){
            $_SESSION["username"]=$this->userModel->retornarUsername();
            $_SESSION["id_usuario"]=$this->userModel->retornarID($_SESSION["username"]);
            $_SESSION["idRol"]=$this->userModel->getIDRol($_SESSION["id_usuario"]);

            header("Location:/user/lobby");
            exit();
        }
        $this->renderer->render('login');
    }

    public function register(){
        unset($_SESSION["codigo"]);
        unset($_SESSION["mail"]);

        if( $this->userModel->crearUsuario()) {
            $_SESSION["mail"]=$this->userModel->retornarMail();
            header("Location:/mail/validarMail");
            exit();
        }
        $this->renderer->render('register');
    }

    public function lobby(){
        $data=[
            "ranking"=>$this->userModel->obtenerMejoresCuatro(),
            "perfil"=>$this->userModel->listarDatos($_SESSION["username"]),
            "puntaje"=>$this->userModel->getPuntajePartida($_SESSION["id_usuario"]) ?? "Aún no jugaste partidas :(",
            "flag"=>$_SESSION["idPartida"] ?? null,
            "duelos"=>$this->userModel->obtenerDuelosPendientes($_SESSION["id_usuario"]),
            "admin"=>$this->userModel->getRolAdmin($_SESSION["id_usuario"]),
            "editor"=>$this->userModel->getRolEditor($_SESSION["id_usuario"])
        ];
        $this->renderer->render("lobby",$data);
    }

    public function ranking(){
        $data=[
            "ranking"=>$this->userModel->obtenerRanking(),
            "perfil"=>$this->userModel->listarDatos($_SESSION["username"]),
            "admin"=>$this->userModel->getRolAdmin($_SESSION["id_usuario"]),
            "editor"=>$this->userModel->getRolEditor($_SESSION["id_usuario"])
        ];
        $this->renderer->render('ranking',$data);
    }

    public function perfil(){
        $_SESSION["usernamePerfil"]=$_GET["username"] ?? $_SESSION["username"];
        $data=[
            "perfil"=>$this->userModel->listarDatos($_SESSION["username"]),
            "perfilMain"=>$this->userModel->listarDatos($_SESSION["usernamePerfil"]),
            "admin"=>$this->userModel->getRolAdmin($_SESSION["id_usuario"]),
            "editor"=>$this->userModel->getRolEditor($_SESSION["id_usuario"]),
            "filtrarDuelo"=>$this->userModel->verificarQueElUsernameEsDiferente($_SESSION["usernamePerfil"],$_SESSION["username"])
        ];
        $this->renderer->render('perfil',$data);
    }

    public function modificar(){
        $data=[
            'perfil'=>$this->userModel->listarDatos($_SESSION["username"]),
            "admin"=>$this->userModel->getRolAdmin($_SESSION["id_usuario"]),
            "editor"=>$this->userModel->getRolEditor($_SESSION["id_usuario"])
        ];
        $this->renderer->render('editarPerfil', $data);
    }

    public function modificadoCompleto(){
        $username=$_SESSION['username'];
        if($this->userModel->editarPerfil($username)){
            $data['perfilMain']= $this->userModel->listarDatos($username);
            $this->renderer->render('perfil', $data);
        }
    }

    public function logout() {
        if($this->userModel->logout()){
            session_unset();
            session_destroy();
            header("Location:/user/login");
            exit();
        }
    }

}