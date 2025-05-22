<?php
require '../vendor/autoload.php';

use ChartComponent\Charts\BarChart;
use ChartComponent\Charts\LineChart;
use ChartComponent\Charts\PieChart;
use ChartComponent\Exporters\PdfExporter;

ob_start();

try {
    // Sample data
    $data = [
        'labels' => ['January', 'February', 'March'],
        'values' => [10, 20, 30]
    ];
    $options = [
        'label' => 'Sales',
        'color' => 'rgba(54, 162, 235, 0.2)',
        'borderColor' => 'rgba(54, 162, 235, 1)',
        'title' => 'Sales Overview'
    ];

    // Determine chart type from query parameter
    $chartType = $_GET['type'] ?? 'bar';
    $chart = null;

    switch (strtolower($chartType)) {
        case 'line':
            $chart = new LineChart($data, $options);
            break;
        case 'pie':
            $chart = new PieChart($data, array_merge($options, ['colors' => ['#ff6384', '#36a2eb', '#ffcd56']]));
            break;
        case 'bar':
        default:
            $chart = new BarChart($data, $options);
            break;
    }

    if (isset($_GET['export']) && $_GET['export'] === 'pdf') {
        ob_clean();
        $exporter = new PdfExporter();
        $exporter->export($chart, 'chart.pdf');
        exit;
    } else {
        echo $chart->render();
    }
} catch (Exception $e) {
    ob_clean();
    echo "Error: " . $e->getMessage();
}