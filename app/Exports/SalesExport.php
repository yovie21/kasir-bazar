<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SalesExport implements FromView, WithTitle, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function view(): View
    {
        $sales = Sale::with(['cashier', 'items.product', 'items.uom'])
            ->whereBetween('created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('exports.sales', [
            'sales'     => $sales,
            'startDate' => $this->startDate,
            'endDate'   => $this->endDate
        ]);
    }

    public function title(): string
    {
        return 'Laporan Transaksi';
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E0E0E0']
            ]
        ]);

        $lastDataRow = $sheet->getHighestDataRow() - 1;

        // Rata atas
        $sheet->getStyle('A2:C' . $lastDataRow)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        $sheet->getStyle('I2:L' . $lastDataRow)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        // Rata kanan untuk angka (kecuali Qty)
        $sheet->getStyle('G2:L' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Format angka ribuan untuk kolom harga sampai kembalian (G–L)
        $sheet->getStyle('G2:L' . $sheet->getHighestDataRow())
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        // Warna selang-seling antar transaksi
        $row = 2;
        $fillToggle = false;
        $lastTrans = null;

        while ($row <= $lastDataRow) {
            $trans = $sheet->getCell('A' . $row)->getValue();

            if ($trans !== $lastTrans) {
                $fillToggle = !$fillToggle;
                $lastTrans = $trans;
            }

            $color = $fillToggle ? 'F9F9F9' : 'FFFFFF';
            $sheet->getStyle('A' . $row . ':L' . $row)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB($color);

            $row++;
        }

        // Baris grand total
        $lastRow = $sheet->getHighestDataRow();
        $sheet->getStyle('A' . $lastRow . ':L' . $lastRow)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9EDF7']
            ]
        ]);
        $sheet->getStyle('J' . $lastRow . ':L' . $lastRow)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        return [];
    }
}
