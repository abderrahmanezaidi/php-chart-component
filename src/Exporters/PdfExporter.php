<?php
namespace ChartComponent\Exporters;

use ChartComponent\Core\Chart;
use Spatie\Browsershot\Browsershot;

class PdfExporter {
    public function export(Chart $chart, string $filename = 'chart.pdf'): void {
        // Get the full HTML from the chart's render method
        $html = $chart->render();

        // Use Browsershot to generate PDF from the HTML
        $pdf = Browsershot::html($html)
    ->setOption('args', ['--no-sandbox'])
    ->setNodeBinary('C:\\Program Files\\nodejs\\node.exe') // Adjust path if needed
    ->setNpmBinary('C:\\Program Files\\nodejs\\npm.cmd') // Adjust path if needed
    ->pdf();

        // Set headers for file download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');

        // Output the PDF
        echo $pdf;
    }
}