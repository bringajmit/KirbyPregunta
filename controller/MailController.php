<?php

class MailController
{
    private $mailModel;
    private $renderer;
    public function __construct($mailModel, $view) {
        $this->mailModel=$mailModel;
        $this->renderer= $view;
    }

    public function validarMail(){
        if(isset($_SESSION["mail"])) {
            $validado = $_SESSION["codigo"] ?? null;
            if (!$validado) {
                $_SESSION["codigo"] = $this->mailModel->enviarMail($_SESSION["mail"]);
            }
            $validado = $this->mailModel->validarMail($_SESSION["codigo"]);
            if ($validado) {
                header("Location:/user/login");
                exit();
            }
            $this->renderer->render('validarMail');
        }else{
            header("Location:/user/login");
            exit();
        }
    }
}