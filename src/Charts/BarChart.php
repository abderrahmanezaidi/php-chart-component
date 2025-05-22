<?php
namespace ChartComponent\Charts;

use ChartComponent\Core\Chart;

class BarChart extends Chart {
    public function render(): string {
        $chartId = 'barChart_' . uniqid();
        $labels = json_encode($this->data['labels'] ?? []);
        $values = json_encode($this->data['values'] ?? []);

        // Fallbacks for options using isset()
        $label = isset($this->options['label']) ? $this->options['label'] : 'Dataset';
        $backgroundColor = isset($this->options['color']) ? $this->options['color'] : 'rgba(75, 192, 192, 0.8)'; // Teal base
        $borderColor = isset($this->options['borderColor']) ? $this->options['borderColor'] : 'rgba(75, 192, 192, 1)';
        $title = isset($this->options['title']) ? $this->options['title'] : 'Sales Overview';

        if (!isset($_GET['export']) || $_GET['export'] !== 'pdf') {
            // Chart.js CDN for browser with enhanced styling and buttons
            $html = <<<HTML
<div style="width: 80%; margin: 20px auto; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; padding: 10px; background-color: #fff;">
    <canvas id="$chartId"></canvas>
    <div style="text-align: center; margin-top: 10px;">
        <button id="switchToLineBtn" style="padding: 8px 16px; background-color: #ff6384; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px; transition: background-color 0.3s;">Switch to Line</button>
        <button id="switchToPieBtn" style="padding: 8px 16px; background-color: #ffcd56; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px; transition: background-color 0.3s;">Switch to Pie</button>
        <button id="downloadPdfBtn" style="padding: 8px 16px; background-color: #4bc0c0; color: white; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s;">Download PDF</button>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('$chartId').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 200);
    gradient.addColorStop(0, 'rgba(75, 192, 192, 0.9)');
    gradient.addColorStop(1, 'rgba(75, 192, 192, 0.4)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: $labels,
            datasets: [{
                label: '$label',
                data: $values,
                backgroundColor: gradient,
                borderColor: '$borderColor',
                borderWidth: 1,
                borderRadius: 8,
                barPercentage: 0.6,
                categoryPercentage: 0.7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.5,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: { size: 14, family: 'Helvetica', weight: 'bold' },
                        color: '#333'
                    }
                },
                title: {
                    display: true,
                    text: '$title',
                    font: { size: 18, weight: 'bold', family: 'Helvetica' },
                    color: '#2c3e50'
                },
                tooltip: {
                    backgroundColor: 'rgba(44, 62, 80, 0.9)',
                    titleFont: { size: 14, family: 'Helvetica' },
                    bodyFont: { size: 12, family: 'Helvetica' },
                    cornerRadius: 6,
                    padding: 10
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { font: { size: 12, family: 'Helvetica' }, color: '#666' },
                    grid: { color: 'rgba(0, 0, 0, 0.1)' }
                },
                x: {
                    ticks: { font: { size: 12, family: 'Helvetica' }, color: '#666' },
                    grid: { display: false }
                }
            }
        }
    });

    // Add button event listeners
    document.getElementById('switchToLineBtn').addEventListener('click', function() {
        window.location.href = '?type=line';
    });
    document.getElementById('switchToPieBtn').addEventListener('click', function() {
        window.location.href = '?type=pie';
    });
    document.getElementById('downloadPdfBtn').addEventListener('click', function() {
        window.location.href = '?export=pdf';
    });

    // Add hover effects
    document.getElementById('switchToLineBtn').addEventListener('mouseover', function() {
        this.style.backgroundColor = '#ff4d6d'; /* Darker on hover */
    });
    document.getElementById('switchToLineBtn').addEventListener('mouseout', function() {
        this.style.backgroundColor = '#ff6384'; /* Restore original */
    });
    document.getElementById('switchToPieBtn').addEventListener('mouseover', function() {
        this.style.backgroundColor = '#ffaa33'; /* Darker on hover */
    });
    document.getElementById('switchToPieBtn').addEventListener('mouseout', function() {
        this.style.backgroundColor = '#ffcd56'; /* Restore original */
    });
    document.getElementById('downloadPdfBtn').addEventListener('mouseover', function() {
        this.style.backgroundColor = '#3da8a8'; /* Darker on hover */
    });
    document.getElementById('downloadPdfBtn').addEventListener('mouseout', function() {
        this.style.backgroundColor = '#4bc0c0'; /* Restore original */
    });
</script>
HTML;
        } else {
            // Simplified table for fallback (optional, Browsershot handles graphics)
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