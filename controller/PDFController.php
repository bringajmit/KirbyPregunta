<?php

class PDFController{

    private $pdfModel;
    private $renderer;

    public function __construct($pdfModel, $view) {
        $this->pdfModel = $pdfModel;
        $this->renderer= $view;
    }

    public function pdf(){
        $data=$_SESSION["data"] ?? null;
        $this->pdfModel->generarPDFJugadores($data);
        $this->pdfModel->generarPDFPartidas($data);
        $this->pdfModel->generarPDFPreguntas($data);
    }


}