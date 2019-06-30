<?php

namespace MA\Service\SpreadOffice;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

use Dompdf\Adapter\CPDF;
use Dompdf\Dompdf;
use Dompdf\Exception;

class PdfService extends ExcelService
{
    protected function response($name, Spreadsheet $spreadsheet)
    {
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setShowGridLines(false);
        $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment;filename="01simple.pdf"');

        IOFactory::registerWriter('Pdf', \PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf::class);
        $writer = IOFactory::createWriter($spreadsheet, 'Pdf');
        $writer->writeAllSheets();
        $writer->save('php://output');
        exit;
    }
}