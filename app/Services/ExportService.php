<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Facades\Response;

class ExportService
{
    /**
     * Export data to Excel or CSV
     *
     * @param array $data - Array of data rows
     * @param array $headers - Column headers
     * @param string $filename - Output filename (without extension)
     * @param string $format - 'xlsx' or 'csv'
     * @param string $title - Sheet title (optional)
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(array $data, array $headers, string $filename, string $format = 'xlsx', string $title = 'Export')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($title);

        // Set headers
        $columnIndex = 1;
        foreach ($headers as $header) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
            $cell = $sheet->getCell($columnLetter . '1');
            $cell->setValue($header);
            
            // Style headers (only for Excel)
            if ($format === 'xlsx') {
                $cell->getStyle()->getFont()->setBold(true);
                $cell->getStyle()->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('4CAF50'); // Green header
                $cell->getStyle()->getFont()->getColor()->setRGB('FFFFFF'); // White text
                $cell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
            
            $columnIndex++;
        }

        // Add data rows
        $rowIndex = 2;
        foreach ($data as $row) {
            $columnIndex = 1;
            foreach ($row as $value) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
                $sheet->getCell($columnLetter . $rowIndex)->setValue($value);
                $columnIndex++;
            }
            $rowIndex++;
        }

        // Auto-size columns (only for Excel)
        if ($format === 'xlsx') {
            foreach (range(1, count($headers)) as $columnIndex) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
                $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            }

            // Add borders to all cells
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $sheet->getStyle('A1:' . $highestColumn . $highestRow)
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
        }

        // Generate file
        $extension = $format === 'csv' ? 'csv' : 'xlsx';
        $fullFilename = $filename . '_' . date('Y-m-d_His') . '.' . $extension;

        if ($format === 'csv') {
            $writer = new Csv($spreadsheet);
            $writer->setDelimiter(',');
            $writer->setEnclosure('"');
            $writer->setLineEnding("\r\n");
            $writer->setSheetIndex(0);
            $contentType = 'text/csv';
        } else {
            $writer = new Xlsx($spreadsheet);
            $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        }

        // Stream the file
        return Response::streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fullFilename, [
            'Content-Type' => $contentType,
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
