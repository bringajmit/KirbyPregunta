<?php
require_once 'JpGraph/src/jpgraph.php';
require_once 'JpGraph/src/jpgraph_bar.php';
require_once 'JpGraph/src/jpgraph_line.php';
class PDFModel{
    private $database;
    private $pdf;

    public function __construct($database, $pdf){
        $this->database = $database;
        $this->pdf=$pdf;
    }

    public function generarPDFJugadores($listaJugadores){
        if (isset($_POST["generarPDFJugadores"])) {
            $tituloReporte = "Lista de jugadores";

            $resultado = $listaJugadores;

            $this->darDatosDefault($tituloReporte);

            $this->pdf->SetMargins(10, 10, 10);

            $this->pdf->Cell(25, 5, "Nombre", 1, 0, "C");
            $this->pdf->Cell(25, 5, "Usuario", 1, 0, "C");
            $this->pdf->Cell(25, 5, "Pais", 1, 0, "C");
            $this->pdf->Cell(25, 5, "Edad", 1, 0, "C");
            $this->pdf->Cell(25, 5, "Sexo", 1, 0, "C");
            $this->pdf->Cell(40, 5, "Porcentaje Aciertos", 1, 0, "C");
            $this->pdf->Cell(30, 5, "Fecha Ingreso", 1, 1, "C");


            foreach ($resultado as $fila){
                $this->pdf->Cell(25, 5, $fila[1], 1, 0, "C");
                $this->pdf->Cell(25, 5, $fila[0], 1, 0, "C");
                $this->pdf->Cell(25, 5, $fila[4], 1, 0, "C");
                $this->pdf->Cell(25, 5, $fila[3], 1, 0, "C");
                $this->pdf->Cell(25, 5, $fila[6], 1, 0, "C");
                $this->pdf->Cell(40, 5, $fila[5], 1, 0, "C");
                $this->pdf->Cell(30, 5, $fila[2], 1, 1, "C");
            }
            $this->pdf->addPage();
            /*Metodos de grafico*/
            $imagenGraficoBarra = './public/QR_CODE/graficoBarra.png';
            $numJugadoresPorPais = $this->contarJugadoresPorPaises($listaJugadores);
            $this->generarGraficoJugadoresPorPais($numJugadoresPorPais, $imagenGraficoBarra);

            $this->pdf->Output('I', $tituloReporte . '.pdf');
        }
    }

    public function generarPDFPartidas($listaPartidas){
        if (isset($_POST["generarPDFPartidas"])) {
            $tituloReporte = "Lista de partidas";

            $resultado = $listaPartidas;

            $this->darDatosDefault($tituloReporte);
            $this->pdf->SetMargins(16, 10, 10);


            $this->pdf->Cell(45, 5, "", 0, 1, "C");
            $this->pdf->Cell(45, 5, "Id", 1, 0, "C");
            $this->pdf->Cell(45, 5, "Usuario", 1, 0, "C");
            $this->pdf->Cell(45, 5, "Puntaje", 1, 0, "C");
            $this->pdf->Cell(45, 5, "Fecha", 1, 1, "C");


            foreach ($resultado as $fila) {
                $this->pdf->Cell(45, 5, $fila[3], 1, 0, "C");
                $this->pdf->Cell(45, 5, $fila[2], 1, 0, "C");
                $this->pdf->Cell(45, 5, $fila[0], 1, 0, "C");
                $this->pdf->Cell(45, 5, $fila[1], 1, 1, "C");
            }
            $this->pdf->addPage();
            /*Metodos de grafico*/
            $imagenGraficoBarra = './public/QR_CODE/graficoBarra.png';
            $numJugadoresPorPais = $this->contarPartidasPorPuntaje($listaPartidas);
            $this->generarGraficoPartidasPorPuntaje($numJugadoresPorPais, $imagenGraficoBarra);

            $this->pdf->Output('I', $tituloReporte . '.pdf');
        }
    }

    public function generarPDFPreguntas($listaPreguntas){
        if (isset($_POST["generarPDFPreguntas"])) {

            $tituloReporte = "Lista de preguntas";

            $resultado = $listaPreguntas;

            $this->darDatosDefault($tituloReporte);

            $this->pdf->SetMargins(4, 20, 6);
            foreach ($resultado as $fila){
                $this->pdf->Cell(40, 10,"", 0, 0, "C");
                $this->pdf->Cell(160, 10, "", 0, 1, "C");
                $this->pdf->Cell(40, 10, "Id", 1, 0, "C");
                $this->pdf->Cell(160, 10, $fila[0], 1, 1, "C");
                $this->pdf->Cell(40, 10, mb_convert_encoding("Descripción", 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
                $this->pdf->Cell(160, 10, mb_convert_encoding($fila[3], 'ISO-8859-1', 'UTF-8'), 1, 1, "C");
                $this->pdf->Cell(40, 10, mb_convert_encoding("Categoría", 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
                $this->pdf->Cell(160, 10, $fila[1], 1, 1, "C");
                $this->pdf->Cell(40, 10, "Nivel", 1, 0, "C");
                $this->pdf->Cell(160, 10, $fila[2], 1, 1, "C");
                $this->pdf->Cell(40, 10, "Aciertos", 1, 0, "C");
                $this->pdf->Cell(160, 10, $fila[4], 1, 1, "C");
                $this->pdf->Cell(40, 10, "Presentaciones", 1, 0, "C");
                $this->pdf->Cell(160, 10, $fila[5], 1, 1, "C");
                $this->pdf->Cell(40, 10, "Porcentaje aciertos", 1, 0, "C");
                $this->pdf->Cell(160, 10, $fila[6], 1, 1, "C");
                $this->pdf->Cell(40, 10, mb_convert_encoding("Fecha de creación", 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
                $this->pdf->Cell(160, 10, $fila[7], 1, 1, "C");
            }
            $this->pdf->addPage();
            /*Metodos de grafico*/
            $imagenGraficoBarra = './public/QR_CODE/graficoBarra.png';
            $numPreguntasPorCategoria = $this->contarPreguntasPorCategoria($listaPreguntas);
            $this->generarGraficoPreguntasPorCategoria($numPreguntasPorCategoria, $imagenGraficoBarra);

            $this->pdf->Output('I', $tituloReporte . '.pdf');
        }
    }

    public function darDatosDefault($tituloReporte){
        $this->pdf->SetTitle($tituloReporte);
        $this->pdf->SetAuthor('Kirby Pregunta');
        $this->pdf->AliasNbPages();
        $this->pdf->AddPage();
        $this->pdf->SetFont("Arial", "B", 9);

        $this->pdf->SetFont("Arial", "", 9);
    }

    /*Actualizan los datos del grafico*/
    private function contarPreguntasPorCategoria($listaPreguntas) {
        $categorias = [];
        $numPreguntasPorCategoria = [];

        foreach ($listaPreguntas as $fila) {
            $categoria = $fila[1];
            if (!in_array($categoria, $categorias)) {
                $categorias[] = $categoria;
                $numPreguntasPorCategoria[$categoria] = 0;
            }
            $numPreguntasPorCategoria[$categoria]++;
        }

        return $numPreguntasPorCategoria;
    }
    private function contarJugadoresPorPaises($listaJugadores) {
        $paises = [];
        $numJugadoresPorPaises = [];

        foreach ($listaJugadores as $fila) {
            $pais = $fila[4];
            if (!in_array($pais, $paises)) {
                $paises[] = $pais;
                $numJugadoresPorPaises[$pais] = 0;
            }
            $numJugadoresPorPaises[$pais]++;
        }

        return $numJugadoresPorPaises;
    }
    private function contarPartidasPorPuntaje($listaPreguntas) {
        $jugadores = [];
        $numPartidasPorPuntaje = [];

        foreach ($listaPreguntas as $fila) {
            $jugador = $fila[2];
            if (!in_array($jugador, $jugadores)) {
                $jugadores[] = $jugador;
                $numPartidasPorPuntaje[$jugador] = 0;
            }
            $numPartidasPorPuntaje[$jugador]++;
        }

        return $numPartidasPorPuntaje;
    }

    /*Genera los graficos*/
    public function generarGraficoPreguntasPorCategoria($numPreguntasPorCategoria, $imagenGrafico){
// Crear un nuevo objeto Graph
        $graph = new Graph(800, 700);
        $graph->SetScale('textlin');

// Crear un objeto de barras
        $barplot = new BarPlot(array_values($numPreguntasPorCategoria));

// Establecer estilo de las barras
        $barplot->SetFillColor('orange');

// Agregar las barras al gráfico
        $graph->Add($barplot);

// Configurar el título del gráfico
        $graph->title->Set('Gráfico de Preguntas por Categoría');
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 12);

// Configurar etiquetas del eje x
        $graph->xaxis->SetTickLabels(array_keys($numPreguntasPorCategoria));

// Configurar el título del eje y
        $graph->yaxis->title->Set('Número de preguntas');
        if (file_exists($imagenGrafico)) {
            // Eliminar el archivo existente
            unlink($imagenGrafico);
        }
// Generar el gráfico y guardar como imagen
        $graph->Stroke($imagenGrafico);
        // Generar el gráfico
        $this->pdf->Image($imagenGrafico);

    }

    public function generarGraficoJugadoresPorPais($numJugadoresPorPais, $imagenGrafico){
// Crear un nuevo objeto Graph
        $graph = new Graph(800, 700);
        $graph->SetScale('textlin');

// Crear un objeto de barras
        $barplot = new BarPlot(array_values($numJugadoresPorPais));

// Establecer estilo de las barras
        $barplot->SetFillColor('orange');

// Agregar las barras al gráfico
        $graph->Add($barplot);

// Configurar el título del gráfico
        $graph->title->Set('Gráfico de Jugadores por Pais');
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 12);

// Configurar etiquetas del eje x
        $graph->xaxis->SetTickLabels(array_keys($numJugadoresPorPais));

// Configurar el título del eje y
        $graph->yaxis->title->Set('Número de jugadores');

        if (file_exists($imagenGrafico)) {
            // Eliminar el archivo existente
            unlink($imagenGrafico);
        }
// Generar el gráfico y guardar como imagen
        $graph->Stroke($imagenGrafico);
        // Generar el gráfico
        $this->pdf->Image($imagenGrafico);
    }

    public function generarGraficoPartidasPorPuntaje($numPartidasPorPuntaje, $imagenGrafico){
// Crear un nuevo objeto Graph
        $graph = new Graph(700, 700);
        $graph->SetScale('textlin');

// Crear un objeto de barras
        $barplot = new BarPlot(array_values($numPartidasPorPuntaje));

// Establecer estilo de las barras
        $barplot->SetFillColor('orange');

// Agregar las barras al gráfico
        $graph->Add($barplot);

// Configurar el título del gráfico
        $graph->title->Set('Gráfico de Partidas por Usuario');
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 12);

// Configurar etiquetas del eje x
        $graph->xaxis->SetTickLabels(array_keys($numPartidasPorPuntaje));

// Configurar el título del eje y
        $graph->yaxis->title->Set('Número de partidas');

        if (file_exists($imagenGrafico)) {
            // Eliminar el archivo existente
            unlink($imagenGrafico);
        }
// Generar el gráfico y guardar como imagen
        $graph->Stroke($imagenGrafico);
        // Generar el gráfico
        $this->pdf->Image($imagenGrafico);

    }





}