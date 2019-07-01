<?php

namespace MA\Service\SpreadOffice;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use \PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf as DompdfWriter;
use \PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf as TcpdfWriter;
use \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf as MpdfWriter;
use Dompdf\Adapter\CPDF;
use Dompdf\Dompdf;
use Dompdf\Exception;

class PdfService extends ExcelService
{
    protected function response($name, Spreadsheet $spreadsheet)
    {
        set_time_limit(0);
        // $spreadsheet = new Spreadsheet;

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setShowGridLines(false);
        $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment;filename={$name}.pdf");

        IOFactory::registerWriter('Pdf', MpdfWriter::class);
        $writer = IOFactory::createWriter($spreadsheet, 'Pdf');
        $writer->writeAllSheets();
        $writer->save('php://output');
        exit;
    }
}