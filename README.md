# PHP Chart Component

A reusable, framework-agnostic PHP library for rendering Chart.js charts (bar, line, pie) and optionally exporting them to PDF. It provides a simple, consistent PHP API that outputs ready-to-embed HTML/JavaScript, making it easy to drop charts into any PHP project and extend with custom chart types.

## Highlights
- Render bar, line, and pie charts with a unified PHP API
- Outputs self-contained HTML + Chart.js script blocks
- Optional PDF export via Browsershot (headless Chrome/Puppeteer)
- Extensible architecture: add new charts by subclassing `Chart`
- Example app included

## Requirements
- PHP 8.0+
- Composer
- Node.js + npm (only for PDF export or build/dev scripts)

## Installation

### Option 1: Clone the repository
```bash
git clone https://github.com/abderrahmanezaidi/php-chart-component.git
cd php-chart-component
composer install
```

### Option 2: Composer (library usage)
```bash
composer require zaidiabderrahmane/chart-component
```

## Quick Start (Example App)
Run the example chart page with PHP’s built-in server:
```bash
php -S localhost:8000 -t examples
```
Open:
```
http://localhost:8000/index.php
```
Use query parameters to switch chart type or export:
```
?type=bar|line|pie
?export=pdf
```

## Basic Usage
```php
require 'vendor/autoload.php';

use ChartComponent\Charts\BarChart;

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

$chart = new BarChart($data, $options);
echo $chart->render();
```

## PDF Export
```php
use ChartComponent\Exporters\PdfExporter;

$exporter = new PdfExporter();
$exporter->export($chart, 'chart.pdf');
```

Notes:
- `PdfExporter` uses Spatie Browsershot, which requires Node.js and a headless Chrome environment.
- The default Node/NPM paths in `PdfExporter` are Windows-specific; update them if needed for your system.

## Project Structure
- `src/Core/Chart.php` — base chart abstraction
- `src/Charts/*` — chart implementations (bar, line, pie)
- `src/Exporters/PdfExporter.php` — PDF export via Browsershot
- `examples/index.php` — runnable example with query controls
- `docs/api/` — generated API documentation

## Extending with a Custom Chart
Create a new class in `src/Charts/` that extends `Chart` and implements `render()`.
The existing chart classes provide a clear template for formatting data, injecting options, and outputting Chart.js config.

## License
MIT
