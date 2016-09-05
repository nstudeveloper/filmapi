<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class DemoController extends FOSRestController
{
    public function getDemosAction()
    {
        if($this->get('security.context')->isGranted('ROLE_ADMIN') === FALSE){
            throw new AccessDeniedException('Access denied');
        }

        $user = $this->get('security.context')->getToken()->getUser();

        $data = ["hello" => "ADMIN"];
        $view = $this->view($data);
        return $this->handleView($view);
    }
}