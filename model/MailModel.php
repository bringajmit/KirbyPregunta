<?php


class MailModel
{
    private $database;
    private $phpMailer;

    public function __construct($database, $phpMailer){
        $this->database = $database;
        $this->phpMailer=$phpMailer;
    }

    public function validarMail($codigoEnviado){
        if(isset($_POST["enviarCodigo"])){
            $codigo=$_POST["codigoEnviado"];
            if($codigo==$codigoEnviado) {
                $sql = "UPDATE usuario SET esta_validado=true WHERE codigo_validacion='$codigoEnviado'";
                $this->database->query($sql);
                return true;
            }
            return false;
        }
    }

    public function configurarMail($mailDestino){
        $this->phpMailer->isSMTP();                                            //Send using SMTP
        $this->phpMailer->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $this->phpMailer->SMTPAuth   = true;                                   //Enable SMTP authentication
        $this->phpMailer->Username   = 'kirbypregunta@gmail.com';                     //SMTP username
        $this->phpMailer->Password   = 'upbivxhsuebilqtz';                               //SMTP password
        $this->phpMailer->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $this->phpMailer->setFrom('kirbypregunta@gmail.com', 'Kirby Pregunta');
        $this->phpMailer->addAddress($mailDestino, 'Joe User');     //Add a recipient
    }

    public function generarContenidoMail($codigo){
        $this->phpMailer->isHTML(true);                                  //Set email format to HTML
        $this->phpMailer->Subject = 'Activar Cuenta en Kirby Pregunta';
        $this->phpMailer->Body    = 'Código de activación de cuenta para Kirby Pregunta: <b>' . $codigo .'</b> <br>Link para ingresar el código: http://localhost/user/validarMail ';
        $this->phpMailer->AltBody = 'This is the body in plain text for non-HTML mail clients';
    }

    public function generarCodigo($strength = 16) {
        $codigoRandom = '';
        $caracteresPermitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for($i = 0; $i < $strength; $i++) {
            $caracterRandom = $caracteresPermitidos[mt_rand(0, strlen($caracteresPermitidos) - 1)];
            $codigoRandom .= $caracterRandom;
        }
        return $codigoRandom;
    }

    public function enviarMail($mailDestino){
        $this->configurarMail($mailDestino);
        $codigo = $this->generarCodigo(5);
        $sql="UPDATE usuario SET codigo_validacion='$codigo' WHERE email='$mailDestino'";
        $this->database->query($sql);
        $this->generarContenidoMail( $codigo);
        $this->phpMailer->send();
        return $codigo;
    }



}