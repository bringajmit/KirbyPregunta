<?php
include_once ("./controller/DueloController.php");
include_once ("./controller/UserController.php");
include_once ("./controller/PartidaController.php");
include_once ("./controller/PreguntaController.php");
include_once ("./controller/MailController.php");
include_once ("./controller/EditorController.php");
include_once ("./controller/HistorialController.php");
include_once ("./controller/AdminController.php");
include_once ("./controller/PDFController.php");
include_once ("./controller/TimeController.php");


include_once ("./model/UserModel.php");
include_once ("./model/DueloModel.php");
include_once ("./model/MailModel.php");
include_once ("./model/PartidaModel.php");
include_once ("./model/PreguntaModel.php");
include_once ("./model/EditorModel.php");
include_once ("./model/HistorialModel.php");
include_once ("./model/AdminModel.php");
include_once ("./model/PDFModel.php");
include_once ("./model/TimeModel.php");

include_once ("./helpers/MustacheRender.php");
include_once ("./helpers/MySqlDatabase.php");
include_once ("./helpers/Router.php");
include_once ("./helpers/Logger.php");

include_once('third-party/mustache/src/Mustache/Autoloader.php');

use PHPMailer\PHPMailer\PHPMailer;
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';

include_once ('FPDF/plantilla.php');


class Configuration {
    private $configFile = 'config/config.ini';

    public function __construct()
    {
    }

    public function getPhpMailer(){
        $phpMailer= new PHPMailer(true);
        $phpMailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption

        return $phpMailer;
    }

    public function getUserController(){
        return new UserController(
            new UserModel($this->getDatabase()),
            $this->getRenderer());
    }

    public function getPartidaController(){
        return new PartidaController(
            new PartidaModel($this->getDatabase()),
            $this->getRenderer());
    }

    public function getPreguntaController(){
        return new PreguntaController(
            new PreguntaModel($this->getDatabase()),$this->getRenderer());
    }

    public function getTimeController(){
        return new TimeController(new TimeModel($this->getDatabase()),$this->getRenderer());
    }

    public function getMailController(){
        return new MailController(new MailModel($this->getDatabase(),$this->getPhpMailer()),$this->getRenderer());
    }

    public function getDueloController(){
        return new DueloController(new DueloModel($this->getDatabase()),$this->getRenderer());
    }

    public function getEditorController(){
        return new EditorController(new EditorModel($this->getDatabase()),$this->getRenderer());
    }

    public function getHistorialController(){
        return new HistorialController(new HistorialModel($this->getDatabase()),$this->getRenderer());
    }

    public function getAdminController(){
        return new AdminController(new AdminModel($this->getDatabase()),$this->getRenderer());
    }
    public function getPDFController(){
        return new PDFController(new PDFModel($this->getDatabase(),new PDF("P", "mm", "letter")),$this->getRenderer());
    }

    private function getArrayConfig() {
        return parse_ini_file($this->configFile);
    }

    private function getRenderer() {
        return new MustacheRender('view/partial');
    }

    public function getDatabase() {
        $config = $this->getArrayConfig();
        return new MySqlDatabase(
            $config['servername'],
            $config['username'],
            $config['password'],
            $config['database']);
    }

    public function getRouter() {
        return new Router(
            $this,
            "getUserController",
            "login");
    }
}