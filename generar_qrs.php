<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Leer el Excel
$spreadsheet = IOFactory::load("registro.xlsx");
$sheet = $spreadsheet->getActiveSheet();
$data = $sheet->toArray();

// Suponemos que la primera fila es la cabecera
array_shift($data); // Quita la cabecera

// Ruta base de los PDF en web
$baseUrl = "https://tu-dominio.com/pdfs/";

// Carpeta donde se guardarán los QR generados
$outputDir = __DIR__ . '/qrs/';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

foreach ($data as $row) {
    $nombreCompleto = trim($row[1]); // Ajusta el índice si el nombre está en otra columna

    // Construir la URL al PDF
    $pdfFileName = $nombreCompleto . ".pdf";
    $pdfUrl = $baseUrl . rawurlencode($pdfFileName); // Encode para nombres con espacios

    // Crear el QR
    $qr = new QrCode($pdfUrl);
    $writer = new PngWriter();
    $result = $writer->write($qr);

    // Guardar QR como imagen
    $qrPath = $outputDir . str_replace(' ', '_', $nombreCompleto) . '.png';
    $result->saveToFile($qrPath);

    echo "QR generado para $nombreCompleto: $pdfUrl\n";
}
