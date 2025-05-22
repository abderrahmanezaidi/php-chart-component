<?php
namespace ChartComponent\Charts;

use ChartComponent\Core\Chart;

class LineChart extends Chart {
    public function render(): string {
        $chartId = 'lineChart_' . uniqid();
        $labels = json_encode($this->data['labels'] ?? []);
        $values = json_encode($this->data['values'] ?? []);

        $label = isset($this->options['label']) ? $this->options['label'] : 'Dataset';
        $backgroundColor = isset($this->options['color']) ? $this->options['color'] : 'rgba(255, 99, 132, 0.2)';
        $borderColor = isset($this->options['borderColor']) ? $this->options['borderColor'] : 'rgba(255, 99, 132, 1)';
        $title = isset($this->options['title']) ? $this->options['title'] : 'Trend Overview';

        if (!isset($_GET['export']) || $_GET['export'] !== 'pdf') {
            $html = <<<HTML
<div style="width: 80%; margin: 20px auto; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; padding: 10px; background-color: #fff;">
    <canvas id="$chartId"></canvas>
    <div style="text-align: center; margin-top: 10px;">
        <button id="switchToBarBtn" style="padding: 8px 16px; background-color: #4bc0c0; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px;">Switch to Bar</button>
        <button id="switchToPieBtn" style="padding: 8px 16px; background-color: #ffcd56; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px;">Switch to Pie</button>
        <button id="downloadPdfBtn" style="padding: 8px 16px; background-color: #ff6384; color: white; border: none; border-radius: 4px; cursor: pointer;">Download PDF</button>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('$chartId'), {
        type: 'line',
        data: {
            labels: $labels,
            datasets: [{
                label: '$label',
                data: $values,
                backgroundColor: '$backgroundColor',
                borderColor: '$borderColor',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.5,
            plugins: {
                legend: { position: 'top', labels: { font: { size: 14, family: 'Helvetica', weight: 'bold' }, color: '#333' } },
                title: { display: true, text: '$title', font: { size: 18, weight: 'bold', family: 'Helvetica' }, color: '#2c3e50' },
                tooltip: { backgroundColor: 'rgba(44, 62, 80, 0.9)', titleFont: { size: 14, family: 'Helvetica' }, bodyFont: { size: 12, family: 'Helvetica' }, cornerRadius: 6, padding: 10 }
            },
            scales: {
                y: { beginAtZero: true, ticks: { font: { size: 12, family: 'Helvetica' }, color: '#666' }, grid: { color: 'rgba(0, 0, 0, 0.1)' } },
                x: { ticks: { font: { size: 12, family: 'Helvetica' }, color: '#666' }, grid: { display: false } }
            }
        }
    });

    document.getElementById('switchToBarBtn').addEventListener('click', function() { window.location.href = '?type=bar'; });
    document.getElementById('switchToPieBtn').addEventListener('click', function() { window.location.href = '?type=pie'; });
    document.getElementById('downloadPdfBtn').addEventListener('click', function() { window.location.href = '?export=pdf'; });
</script>
HTML;
        } else {
            $html = '<table border="1" style="font-family: Helvetica; font-size: 12px; margin: 20px auto; border-collapse: collapse; width: 80%;"><tr><th style="padding: 8px;">Label</th><th style="padding: 8px;">Value</th></tr>';
            foreach ($this->data['labels'] ?? [] as $index => $label) {
                $value = $this->data['values'][$index] ?? 0;
                $html .= "<tr><td style='padding: 8px;'>$label</td><td style='padding: 8px;'>$value</td></tr>";
            }
            $html .= '</table>';
        }

        return $html;
    }
}