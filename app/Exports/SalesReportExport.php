<?php

namespace App\Exports;

use App\Models\SaleTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class SalesReportExport implements WithMultipleSheets
{
    private ?string $chartImagePath = null;

    public function __construct(
        private readonly string $period,
        private readonly Carbon $startDate,
        private readonly Carbon $endDate,
        private readonly object $summary,
        private readonly array $chartLabels,
        private readonly array $chartSalesData,
        private readonly array $chartCommissionData,
        private readonly array $chartTransactionsData,
        private readonly Builder $transactionsQuery,
        ?string $chartImageBase64 = null,
    ) {
        if ($chartImageBase64) {
            $path = tempnam(sys_get_temp_dir(), 'sales_chart_');
            if ($path) {
                $pathPng = $path.'.png';
                @rename($path, $pathPng);
                @file_put_contents($pathPng, base64_decode($chartImageBase64));
                $this->chartImagePath = $pathPng;
            }
        }
    }

    public function __destruct()
    {
        if ($this->chartImagePath && file_exists($this->chartImagePath)) {
            @unlink($this->chartImagePath);
        }
    }

    public function sheets(): array
    {
        return [
            new SalesReportSummarySheet(
                period: $this->period,
                startDate: $this->startDate,
                endDate: $this->endDate,
                summary: $this->summary,
            ),
            new SalesReportChartDataSheet(
                labels: $this->chartLabels,
                salesData: $this->chartSalesData,
                commissionData: $this->chartCommissionData,
                transactionsData: $this->chartTransactionsData,
                chartImagePath: $this->chartImagePath,
            ),
            new SalesReportTransactionsSheet($this->transactionsQuery),
        ];
    }
}

class SalesReportSummarySheet implements FromArray, WithTitle, ShouldAutoSize, WithEvents
{
    public function __construct(
        private readonly string $period,
        private readonly Carbon $startDate,
        private readonly Carbon $endDate,
        private readonly object $summary,
    ) {}

    public function title(): string
    {
        return 'Summary';
    }

    public function array(): array
    {
        $avgCommissionRate = $this->summary->total_sales > 0 && $this->summary->total_commission
            ? round(($this->summary->total_commission / $this->summary->total_sales) * 100, 2)
            : 0;

        return [
            ['LAPORAN PENJUALAN & KOMISI'],
            [],
            ['INFORMASI LAPORAN'],
            ['Periode', $this->period],
            ['Range Tanggal', $this->startDate->format('d M Y') . ' s/d ' . $this->endDate->format('d M Y')],
            ['Dicetak', now()->format('d M Y H:i')],
            [],
            ['RINGKASAN'],
            ['Total Penjualan', (float) ($this->summary->total_sales ?? 0)],
            ['Total Komisi', (float) ($this->summary->total_commission ?? 0)],
            ['Total Transaksi', (int) ($this->summary->total_transactions ?? 0)],
            ['Rata-rata Komisi', $avgCommissionRate / 100],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Judul Utama
                $sheet->mergeCells('A1:B1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1F2937']],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                ]);

                // Style Header Bagian (Biru Indigo)
                $headerStyle = [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5'],
                    ],
                ];

                foreach (['A3', 'A8'] as $cell) {
                    $sheet->getStyle($cell)->applyFromArray($headerStyle);
                    $sheet->mergeCells($cell . ':B' . substr($cell, 1));
                }

                // Label Bold
                $sheet->getStyle('A4:A6')->getFont()->setBold(true);
                $sheet->getStyle('A9:A12')->getFont()->setBold(true);

                // Border untuk Tabel Data
                $borderStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'E5E7EB'],
                        ],
                    ],
                ];
                $sheet->getStyle('A4:B6')->applyFromArray($borderStyle);
                $sheet->getStyle('A9:B12')->applyFromArray($borderStyle);

                // Format Rupiah & Persentase
                $sheet->getStyle('B9:B10')->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0');

                $sheet->getStyle('B12')->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);

                // Lebar Kolom
                $sheet->getColumnDimension('A')->setWidth(25);
                $sheet->getColumnDimension('B')->setWidth(35);
            },
        ];
    }
}

class SalesReportChartDataSheet implements FromArray, WithTitle, ShouldAutoSize, WithCustomStartCell, WithDrawings, WithEvents
{
    public function __construct(
        private readonly array $labels,
        private readonly array $salesData,
        private readonly array $commissionData,
        private readonly array $transactionsData,
        private readonly ?string $chartImagePath,
    ) {}

    public function title(): string
    {
        return 'Chart Data';
    }

    public function startCell(): string
    {
        // Data tabel dimulai setelah area grafik (sekitar baris 25)
        return 'A25';
    }

    public function array(): array
    {
        $rows = [
            ['DATA TABEL GRAFIK'],
            [],
            ['Label', 'Penjualan', 'Komisi', 'Jumlah Transaksi'],
        ];

        foreach ($this->labels as $index => $label) {
            $rows[] = [
                (string) $label,
                (float) ($this->salesData[$index] ?? 0),
                (float) ($this->commissionData[$index] ?? 0),
                (int) ($this->transactionsData[$index] ?? 0),
            ];
        }

        // Baris Total
        $rows[] = [];
        $rows[] = [
            'TOTAL',
            array_sum($this->salesData),
            array_sum($this->commissionData),
            array_sum($this->transactionsData),
        ];

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = 25 + count($this->labels) + 4; // Hitung baris terakhir

                // Style Header Tabel (A27)
                $sheet->getStyle('A27:D27')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5'],
                    ],
                ]);

                // Bold untuk label "TOTAL" di baris terakhir
                $sheet->getStyle('A' . $lastRow . ':D' . $lastRow)->getFont()->setBold(true);

                // Format Rupiah untuk kolom Penjualan & Komisi
                $sheet->getStyle('B28:C' . $lastRow)->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0');

                // Border untuk seluruh tabel
                $sheet->getStyle('A27:D' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'E5E7EB'],
                        ],
                    ],
                ]);
            },
        ];
    }

    public function drawings()
    {
        if (! $this->chartImagePath || ! file_exists($this->chartImagePath)) {
            return [];
        }

        $drawing = new Drawing();
        $drawing->setName('Sales & Commission Chart');
        $drawing->setDescription('Grafik Penjualan dan Komisi');
        $drawing->setPath($this->chartImagePath);
        $drawing->setHeight(400); // Sedikit lebih tinggi
        $drawing->setWidth(900);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(10);

        return $drawing;
    }
}

class SalesReportTransactionsSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    public function __construct(private readonly Builder $query) {}

    public function title(): string
    {
        return 'Transactions';
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal',
            'Kode Transaksi',
            'Nama User',
            'Email User',
            'Role User',
            'Nama Customer',
            'No. HP Customer',
            'Layanan',
            'Jenis Transaksi',
            'Nominal',
            'Rate Komisi (%)',
            'Nominal Komisi',
            'Status',
            'Selesai Pada',
            'Deskripsi',
        ];
    }

    /**
     * @param  SaleTransaction  $row
     */
    public function map($row): array
    {
        return [
            $row->id,
            optional($row->created_at)->format('d/m/Y H:i'),
            $row->transaction_code,
            $row->user?->name,
            $row->user?->email,
            ucfirst($row->user?->role ?? '-'),
            $row->customer_name,
            $row->customer_phone,
            $row->service_name,
            strtoupper($row->transaction_type),
            (float) $row->amount,
            (float) ($row->commission_rate ?? 0),
            (float) ($row->commission_amount ?? 0),
            strtoupper($row->status),
            optional($row->completed_at)->format('d/m/Y H:i'),
            $row->description,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = Coordinate::stringFromColumnIndex(count($this->headings()));
                $lastRow = $sheet->getHighestRow();

                // Bekukan Baris Pertama & Aktifkan Filter
                $sheet->freezePane('A2');
                $sheet->setAutoFilter("A1:{$lastColumn}1");

                // Style Header (Indigo)
                $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5'],
                    ],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                ]);

                // Format Rupiah untuk Nominal (Kolom K & M)
                $sheet->getStyle("K2:K{$lastRow}")->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0');
                $sheet->getStyle("M2:M{$lastRow}")->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0');

                // Format Persentase (Kolom L)
                $sheet->getStyle("L2:L{$lastRow}")->getNumberFormat()
                    ->setFormatCode('#,##0.00"%"');

                // Zebra Striping & Borders
                for ($row = 2; $row <= $lastRow; $row++) {
                    $range = "A{$row}:{$lastColumn}{$row}";
                    
                    // Warna selang-seling (Abu-abu sangat muda)
                    if ($row % 2 == 0) {
                        $sheet->getStyle($range)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F9FAFB');
                    }

                    // Border tipis
                    $sheet->getStyle($range)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['rgb' => 'E5E7EB'],
                            ],
                        ],
                    ]);
                }

                // Perataan Tengah untuk kolom tertentu
                foreach (['A', 'B', 'C', 'F', 'J', 'N', 'O'] as $col) {
                    $sheet->getStyle("{$col}2:{$col}{$lastRow}")
                        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}
