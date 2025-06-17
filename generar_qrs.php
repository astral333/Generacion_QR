<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Rutas
$archivoExcel = "registro.xlsx";
$plantillaWord = "plantilla.docx";
$qrDir = __DIR__ . "/qrs/";
$outDir = __DIR__ . "/certificados/";
$baseUrl = "https://miuniversidad.edu.pe/pdfs/";

// Crear carpetas si no existen
if (!is_dir($qrDir)) mkdir($qrDir, 0777, true);
if (!is_dir($outDir)) mkdir($outDir, 0777, true);

// Leer Excel
$spreadsheet = IOFactory::load($archivoExcel);
$sheet = $spreadsheet->getActiveSheet();
$data = $sheet->toArray();
array_shift($data); // Quitar encabezado

foreach ($data as $row) {
    // Asumimos que el nombre está en la columna 1 (índice 1)
    $nombre = trim($row[1]);
$nombreFormateado = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nombre);
    // Generar QR
    $pdfUrl = $baseUrl . rawurlencode($nombreFormateado) . ".pdf";
    $qrPath = $qrDir . $nombreFormateado . '.png';

    if (!file_exists($qrPath)) {
        $qr = new QrCode($pdfUrl);
        $writer = new PngWriter();
        $result = $writer->write($qr);
        $result->saveToFile($qrPath);
    } else {
        echo "No se generó el QR: $qrPath\n";
    }

    // Generar certificado Word
    $template = new TemplateProcessor($plantillaWord);
    $template->setValue('NOMBRE', $nombre);
    $template->setImageValue('QR', [
        'path' => $qrPath,
        'width' => 120,
        'height' => 120,
        'ratio' => true
    ]);

    $docxFile = $outDir . $nombreFormateado . '.docx';
    $template->saveAs($docxFile);
    echo "Generado: $docxFile\n";

    // (Opcional) Convertir a PDF con LibreOffice
    $command = 'soffice --headless --convert-to pdf --outdir "' . $outDir . '" "' . $docxFile . '"';
    exec($command);
}
