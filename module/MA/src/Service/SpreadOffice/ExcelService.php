<?php

namespace MA\Service\SpreadOffice;

class ExcelService
{
    /**
     * @var array
     */
    protected $defaultStyle = [
        /*'font' => [
            'bold' => true,
        ],*/
        'alignment' => [
            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
        /*'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
            'rotation' => 90,
            'startColor' => [
                'argb' => 'FFA0A0A0',
            ],
            'endColor' => [
                'argb' => 'FFFFFFFF',
            ],
        ],*/
    ];

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @param Doctrine\ORM\EntityManager $em
     */
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param \MA\Entity\VersionInterface $version
     * @param array $values
     */
    public function generateFile(\MA\Entity\Version $version, array $values)
    {
        $spreadsheet = $this->generateSpreadSheet($version, $values);
        $this->response("{$version->getProcess()->getTitle()}-{$version->getName()}-report", $spreadsheet);
        exit;
    }

    /**
     * @param string $name
     * @param \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet
     */
    protected function response($name, \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet)
    {
        // Redirect output to a client’s web browser (Xlsx)
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename={$name}.xlsx");

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    /**
     * @param \MA\Entity\VersionInterface $version
     * @param array $values
     */
    protected function filterVersion(\MA\Entity\Version $version, array $values)
    {
        foreach ($version->getStages() as $stage) {
            if (!in_array((string) $stage->getId(), $values['stages'])) {
                $version->removeStage($stage);
            }
            else {
                foreach ($stage->getHints() as $error) {
                    if (!in_array((string) $error->getId(), $values['hints'])) {
                        $stage->removeHint($error);
                    }
                    else {
                        foreach ($error->getReasons() as $r) {
                            if (!in_array((string) $r->getId(), $values['reasons'])) {
                                $error->removeReason($r);
                            }
                            else {
                                foreach ($r->getNotes() as $note) {
                                    if (!in_array((string) $note->getId(), $values['notes'])) {
                                        $r->removeNote($note);
                                    } 
                                }
                                foreach ($r->getInfluences() as $i) {
                                    if (!in_array((string) $i->getId(), $values['influences'])) {
                                        $r->removeInfluence($i);
                                    }
                                    else {
                                        foreach ($i->getNotes() as $note) {
                                            if (!in_array((string) $note->getId(), $values['notes'])) {
                                                $i->removeNote($note);
                                            } 
                                        }
                                        foreach ($i->getSimulations() as $s) {
                                            if (!in_array((string) $s->getId(), $values['simulations'])) {
                                                $i->removeSimulation($s);
                                            }
                                            else {
                                                foreach ($s->getSuggestions() as $note) {
                                                    if (!in_array((string) $note->getId(), $values['notes'])) {
                                                        $s->removeSuggestion($note);
                                                    }       
                                                }
                                                foreach ($s->getEffects() as $note) {
                                                    if (!in_array((string) $note->getId(), $values['notes'])) {
                                                        $s->removeEffect($note);
                                                    }       
                                                }
                                                foreach ($s->getPreventions() as $note) {
                                                    if (!in_array((string) $note->getId(), $values['notes'])) {
                                                        $s->removePrevention($note);
                                                    }       
                                                }
                                            }     
                                        }
                                    }
                                }
                            }    
                        }
                    }    
                }
            }
        }
        return $version;
    }

    /**
     * @param \MA\Entity\VersionInterface $version
     * @param array $values
     */
    protected function generateSpreadSheet(\MA\Entity\Version $version, array $values)
    {
        $complexities = $this->em->getRepository(\MA\Entity\Complexity::class)->findAll();

        $version = $this->filterVersion($version, $values);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(getcwd() . "/public/xlsx/DRBFM.xlsx");
    
        $sheet = $spreadsheet->getActiveSheet();

        $this->generateProcessSheet($version, $sheet);
    
        foreach ($version->getStages() as $index => $stage) {

            $cloned = clone $spreadsheet->getSheet(1);
            $cloned->setTitle(sprintf("Stage %s", $stage->getOrder()));
            $spreadsheet->addSheet($cloned);
            
            $sheet = $spreadsheet->setActiveSheetIndex($index+2);
            
            $this->generateStageSheet($stage, $sheet);
        }

        // Remove default Stage worksheet
        $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheet(1));
        //$spreadsheet->removeSheetByIndex($sheetIndex);
        
        return $spreadsheet;
    }

    /**
     * @param \MA\Entity\VersionInterface $version
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    protected function generateProcessSheet(\MA\Entity\Version $version, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        $process = $version->getProcess();
        $sheet->setTitle($process->getTitle());
        $sheet->getCell("D2")->setValue($process->getNumber());
        $sheet->getCell("D3")->setValue($process->getTitle());
        $sheet->getCell("D4")->setValue("{$process->getMachine()->getname()}/{$process->getLine()}");
        $sheet->getCell("D5")->setValue($process->getCode());
        $sheet->getCell("D6")->setValue($process->getCustomer()->getName());
        $sheet->getCell("H6")->setValue($process->getCustomer()->getCode());
        $sheet->getCell("H7")->setValue($process->getCustomer()->getContact());
        $sheet->getCell("M6")->setValue($process->getCustomer()->getEmail());
        $sheet->getCell("M7")->setValue($process->getCustomer()->getPhone());
        $sheet->getCell("D7")->setValue($process->getPlant()->getName());
        $sheet->getCell("D8")->setValue($process->getPieceName());
        $sheet->getCell("D9")->setValue($process->getPieceNumber());
        $sheet->getCell("D10")->setValue($process->getBody());
        foreach ($version->getStages() as $i => $stg) {
            if ($stg->hasImage()) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName(sprintf("Stage %s", $i));
                $drawing->setDescription($stg->getImage()->getDescription());
                $drawing->setPath($stg->getImage()->getName());
                $drawing->setWidth(150);
                $drawing->setHeight(250);
                $drawing->setCoordinates("B11");
                $drawing->setOffsetX(150*$i);
                $drawing->setOffsetY(100);
                $drawing->setWorksheet($sheet);
            }     
        }         
    }

    /**
     * @param \MA\Entity\StageInterface $stage
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    protected function generateStageSheet(\MA\Entity\Stage $stage, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        //Stage info
        $gdImage = @imagecreatetruecolor(200, 300) or die('Cannot Initialize new GD image stream');
        //Apply transparency
        imagealphablending($gdImage, false);
        $transparency = imagecolorallocatealpha($gdImage, 0, 0, 0, 127);
        imagefill($gdImage, 0, 0, $transparency);
        imagesavealpha($gdImage, true);
        //Red rectangle
        $red = imagecolorallocate($gdImage, 255, 0, 0);
        imagerectangle($gdImage, 0, 0, 199, 299, $red);
        
        //  Add the In-Memory image to a worksheet
        $border = new \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing();
        $border->setName('Border');
        $border->setDescription('Active Stage');
        $border->setCoordinates("BH12");
        $border->setImageResource($gdImage);
        $border->setRenderingFunction(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::RENDERING_PNG);
        $border->setMimeType(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_DEFAULT);
        //$border->setWidth(200);
        //$border->setHeight(300);           

        if ($stage->hasImage()) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName(sprintf("Stage %s", $index));
            $drawing->setDescription($stage->getImage()->getDescription());
            $drawing->setPath($stage->getImage()->getName());
            $drawing->setWidth(200);
            $drawing->setHeight(300);
            $drawing->setCoordinates("O12");
            $drawing->setOffsetX(100);
            $drawing->setWorksheet($sheet);
            //$sheet->getCell("O12")->getStyle()->getAlignment()->setHorizontal('center');
        }

        if ($index && $version->getStage($index-1)->hasImage()) {
            $img = $version->getStage($index-1)->getImage();
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName(sprintf("Stage %s", $index-1));
            $drawing->setDescription($img->getDescription());
            $drawing->setPath($img->getName());
            $drawing->setWidth(200);
            $drawing->setHeight(300);
            $drawing->setCoordinates("AR12");
            $drawing->setOffsetX(100);
            $drawing->setWorksheet($sheet);
        }

        foreach ($stage->getVersion()->getStages() as $i => $stg) {
            if ((true || $stg === $stage) && $stg->hasImage()) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName(sprintf("Stage %s", $i));
                $drawing->setDescription($stg->getImage()->getDescription());
                $drawing->setPath($stg->getImage()->getName());
                $drawing->setWidth(200);
                $drawing->setHeight(300);
                $drawing->setCoordinates("BH12");
                $drawing->setOffsetX(200*$i);
                $drawing->setWorksheet($sheet);
                if ($stg === $stage) {
                    $border->setOffsetX(200*$i);
                    $border->setWorksheet($sheet);
                }
                /*$drawing->getShadow()
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK))
                    ->setAlpha(100)
                    ->setVisible($stg === $stage);*/
            }     
        }          
        
        $wizard = new \PhpOffice\PhpSpreadsheet\Helper\Html();

        $sheet->getCell("I9")->setValue($stage->getOrder());
        $ops = $stage->getOperations()->map(function($e){return $e->getName();})->toArray();
        $sheet->getCell("I10")->setValue(implode(", ", $ops));
        $col = "AF";
        foreach ($complexities as $complexity) {
            $cell = $sheet->getCell("{$col}10");
            $cell->setValue($complexity->getName());
            if ($version->getProcess()->getComplexity() === $complexity) {
                $cell->getStyle()->getFont()->setBold(true);
            }
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        $row = 33;
        $col = "B";
        foreach ($stage->getOperations() as $operation) {

            $errors = $stage->getHints()->filter(function($e) use ($operation) {return $e->getOperation() === $operation;});
            if ($errors->isEmpty()) {
                continue;
            }
            $e = $row;         
            foreach ($errors as $error) {
                $r = $e;
                $reasons = $error->getReasons();
                if ($reasons->count()) {
                    foreach ($reasons as $reason) {
                        $i = $r;
                        $influences = $reason->getInfluences();
                        if ($influences->count()) {
                            foreach ($influences as $influence) {
                                $s = $i;
                                $simulations = $influence->getSimulations();
                                if ($simulations->count()) {
                                    foreach ($simulations as $simulation) {
                                        $sheet->mergeCells("AR{$s}:BN{$s}");
                                        $sheet->mergeCells("BO{$s}:BT{$s}");
                                        $sheet->mergeCells("BU{$s}:BX{$s}");
                                        $sheet->mergeCells("BY{$s}:CB{$s}");
                                        $sheet->mergeCells("CC{$s}:CP{$s}");
                                        $sheet->mergeCells("CQ{$s}:DI{$s}");
                                        $sheet->mergeCells("DJ{$s}:DO{$s}");
                                        
                                        switch ($simulation->getState()) {
                                            case \Ma\Entity\Simulation::STATE_CANCELED:
                                                $value = "Cancelled"; $color = "FFFF0000";
                                                break;
                                            case \Ma\Entity\Simulation::STATE_IN_PROGRESS:
                                                $value = "In Progress"; $color = "FFCCCCCC";
                                                break;
                                            case \Ma\Entity\Simulation::STATE_FINISHED:
                                                $value = "Finished"; $color = "92D14F";
                                                break;
                                            case \Ma\Entity\Simulation::STATE_NOT_NECESSARY:
                                                $value = "Not necessary"; $color = "FF666666";
                                                break;
                                            case \Ma\Entity\Simulation::STATE_NOT_PROCESSED:
                                            case \Ma\Entity\Simulation::STATE_CREATED:
                                                $value = "Not processed"; $color = "FFDDDDDD";
                                            default:
                                        }
                                        $cell = $sheet->getCell("DJ{$s}");
                                        $cell->setValue($value);
                                        $cell->getStyle()->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()->setARGB($color);
                                        $cell->getStyle()->getAlignment()
                                            ->setHorizontal('center')
                                            ->setVertical('center');

                                        if (null !== ($who = $simulation->getWho())) {
                                            $sheet->getCell("BU{$s}")->setValue($who->getName())
                                                ->getStyle()->applyFromArray($this->defaultStyle);
                                        }
                                        if (null !== ($when = $simulation->getWhen())) {
                                            $sheet->getCell("BY{$s}")
                                                ->setValue(\PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($when))
                                                ->getStyle()->applyFromArray($this->defaultStyle)
                                                ->getNumberFormat()
                                                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
                                        }
                                        $str = "";
                                        foreach ($simulation->getSuggestions() as $note) {
                                            $str .= "{$wizard->toRichTextObject($note->getText())}\n";
                                        }
                                        $sheet->getCell("AR{$s}")->setValue($str)->getStyle()->applyFromArray($this->defaultStyle);
                                        $str = "";
                                        foreach ($simulation->getEffects() as $note) {
                                            $str .= "{$wizard->toRichTextObject($note->getText())}\n";
                                        }
                                        $sheet->getCell("CC{$s}")->setValue($str)->getStyle()->applyFromArray($this->defaultStyle);
                                        $str = "";
                                        foreach ($simulation->getPreventions() as $note) {
                                            $str .= "{$wizard->toRichTextObject($note->getText())}\n";
                                        }
                                        $sheet->getCell("CQ{$s}")->setValue($str)->getStyle()->applyFromArray($this->defaultStyle);
                                        $sheet->getRowDimension($s)->setRowHeight(100);
                                        $s++;
                                    }   
                                }
                                else {
                                    $sheet->mergeCells("AR{$s}:DO{$s}");
                                    $sheet->getCell("AR{$s}")->getStyle()->applyFromArray($this->defaultStyle);
                                    $s++;
                                }
                                $sheet->mergeCells("AH{$i}:AN{$i}");
                                if ($s > 1) $sheet->mergeCells("AH{$r}:AN".($s-1));

                                $str = "";
                                foreach ($influence->getNotes() as $note) $str .= "{$wizard->toRichTextObject($note->getText())}\n";
                                $sheet->getCell("AH{$i}")->setValue($str)->getStyle()->applyFromArray($this->defaultStyle);
                                $sheet->getRowDimension($i)->setRowHeight(100);
                                $i = $s;
                            }
                        }
                        else {
                            $sheet->mergeCells("AH{$i}:AN{$i}");
                            $sheet->getCell("AH{$i}")->getStyle()->applyFromArray($this->defaultStyle);
                            $sheet->mergeCells("AR{$i}:DO{$i}");
                            $sheet->getCell("AR{$i}")->getStyle()->applyFromArray($this->defaultStyle);
                            $i++;
                        }
                        
                        $sheet->mergeCells("AA{$r}:AG{$r}");
                        if ($i > 1) $sheet->mergeCells("AA{$r}:AG".($i-1));

                        $str = "";
                        foreach ($reason->getNotes() as $note) $str .= "{$wizard->toRichTextObject($note->getText())}\n";
                        $sheet->getCell("AA{$r}")->setValue($str)->getStyle()->applyFromArray($this->defaultStyle);
                        $sheet->getRowDimension($r)->setRowHeight(100);
                        $r = $i;
                    }
                }
                else {
                    $sheet->mergeCells("AA{$r}:AG{$r}");
                    $sheet->getCell("AA{$r}")->getStyle()->applyFromArray($this->defaultStyle);
                    $sheet->mergeCells("AH{$r}:AN{$r}");
                    $sheet->getCell("AH{$r}")->getStyle()->applyFromArray($this->defaultStyle);
                    $sheet->mergeCells("AR{$r}:DO{$r}");
                    $sheet->getCell("AR{$r}")->getStyle()->applyFromArray($this->defaultStyle);
                    $r++;
                }
                
                $sheet->mergeCells("T{$e}:Z{$e}");
                $sheet->mergeCells("AO{$e}:AQ{$e}");
                if ($r > 1) {
                    $sheet->mergeCells("T{$e}:Z".($r-1));
                    $sheet->mergeCells("AO{$e}:AQ".($r-1));
                }
                $sheet->getCell("T{$e}")->setValue($error->getName())->getStyle()->applyFromArray($this->defaultStyle);
                $sheet->getCell("AO{$e}")->setValue($error->getPriority())->getStyle()->applyFromArray($this->defaultStyle);
                $sheet->getRowDimension($e)->setRowHeight(100);
                $e = $r;
            }

            $sheet->mergeCells("B{$row}:S{$row}");
            if ($e > 1) $sheet->mergeCells("B{$row}:S".($e-1));  
            $sheet->getCell("B{$row}")->setValue($operation->getName())->getStyle()->applyFromArray($this->defaultStyle);
            $row = $e;
        }
    }
}
