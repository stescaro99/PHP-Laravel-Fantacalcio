<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PlayersTemplateExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Restituisce alcune righe di esempio per mostrare il formato
        return [
            [
                'Mario Rossi',
                'A',
                'Juventus',
                25,
                20,
                5,
                15,
                'A',
                30,
                25,
                5,
                20
            ],
            [
                'Luca Bianchi',
                'C',
                'Milan',
                18,
                15,
                3,
                12,
                'C',
                20,
                18,
                2,
                14
            ],
            [
                'Giuseppe Verdi',
                'D',
                'Inter',
                12,
                10,
                2,
                8,
                'D',
                15,
                12,
                3,
                10
            ]
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'name',
            'position',
            'team',
            'quotation',
            'initial_quotation',
            'difference',
            'value',
            'mantra_position',
            'mantra_quotation',
            'initial_mantra_quotation',
            'mantra_difference',
            'mantra_value'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style per l'header (prima riga)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '4472C4'],
                ],
            ],
        ];
    }
}
