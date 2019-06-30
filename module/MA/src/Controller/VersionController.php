<?php

namespace MA\Controller;

use Zend\View\Model\ViewModel,
    Zend\View\Model\ModelInterface;
use Zend\Json\Json;
use DOMPDFModule\View\Model\PdfModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use BjyAuthorize\Exception\UnAuthorizedException;
use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;
use MA\Entity\ProcessInterface,
    MA\Entity\VersionInterface;

class VersionController extends \Base\Controller\AbstractActionController
{
    /**
     * @return PdfModel
     */
    public function pdfAction()
    {
        $service = new \MA\Service\SpreadOffice\PdfService($this->getEntityManager());
        $service->generateFile($this->getEntity(), (array) $this->getRequest()->getPost());
    }

    public function excelAction()
    {
        $service = new \MA\Service\SpreadOffice\ExcelService($this->getEntityManager());
        $service->generateFile($this->getEntity(), (array) $this->getRequest()->getPost());
    }
}
