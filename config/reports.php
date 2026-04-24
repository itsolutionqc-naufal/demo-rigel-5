<?php

return [
    'quickchart_enabled' => (bool) env('REPORTS_QUICKCHART_ENABLED', true),
    'quickchart_url' => (string) env('REPORTS_QUICKCHART_URL', 'https://quickchart.io/chart'),
    'quickchart_timeout' => (int) env('REPORTS_QUICKCHART_TIMEOUT', 5),
];

