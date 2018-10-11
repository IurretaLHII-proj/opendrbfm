<?php

namespace MA\Controller;

use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class ImageController extends \Base\Controller\AbstractActionController
{
    /**
     * @return ResponseInterface
     */
    public function detailAction()
    {
		$name  	  = $this->getEntity()->getName();
		$content  = file_get_contents($name);

        $response = $this->getResponse();
        $response->setContent($content);
        $response->getHeaders()
                 ->addHeaderLine('Content-Transfer-Encoding', 'binary')
                 ->addHeaderLine('Content-Length', filesize($name))
                 ->addHeaderLine('Content-Type', 'image/png')
                 ;

        return $response;
    }
}
