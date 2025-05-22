<?php
namespace ChartComponent\Charts;

use ChartComponent\Core\Chart;

class PieChart extends Chart {
    public function render(): string {
        $chartId = 'pieChart_' . uniqid();
        $labels = json_encode($this->data['labels'] ?? []);
        $values = json_encode($this->data['values'] ?? []);

        $label = isset($this->options['label']) ? $this->options['label'] : 'Dataset';
        $colors = isset($this->options['colors']) ? json_encode($this->options['colors']) : json_encode(['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0']);
        $title = isset($this->options['title']) ? $this->options['title'] : 'Category Distribution';

        if (!isset($_GET['export']) || $_GET['export'] !== 'pdf') {
            $html = <<<HTML
<div style="width: 80%; margin: 20px auto; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; padding: 10px; background-color: #fff;">
    <canvas id="$chartId"></canvas>
    <div style="text-align: center; margin-top: 10px;">
        <button id="switchToBarBtn" style="padding: 8px 16px; background-color: #4bc0c0; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px;">Switch to Bar</button>
        <button id="switchToLineBtn" style="padding: 8px 16px; background-color: #ff6384; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px;">Switch to Line</button>
        <button id="downloadPdfBtn" style="padding: 8px 16px; background-color: #ffcd56; color: white; border: none; border-radius: 4px; cursor: pointer;">Download PDF</button>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('$chartId'), {
        type: 'pie',
        data: {
            labels: $labels,
            datasets: [{
                label: '$label',
                data: $values,
                backgroundColor: $colors,
                borderWidth: 1,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.5,
            plugins: {
                legend: { position: 'right', labels: { font: { size: 14, family: 'Helvetica', weight: 'bold' }, color: '#333' } },
                title: { display: true, text: '$title', font: { size: 18, weight: 'bold', family: 'Helvetica' }, color: '#2c3e50' },
                tooltip: { backgroundColor: 'rgba(44, 62, 80, 0.9)', titleFont: { size: 14, family: 'Helvetica' }, bodyFont: { size: 12, family: 'Helvetica' }, cornerRadius: 6, padding: 10 }
            }
        }
    });

    document.getElementById('switchToBarBtn').addEventListener('click', function() { window.location.href = '?type=bar'; });
    document.getElementById('switchToLineBtn').addEventListener('click', function() { window.location.href = '?type=line'; });
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