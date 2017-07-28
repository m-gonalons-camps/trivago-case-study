<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class GUIController extends Controller {

    public function indexAction() {
        // Render GUI
        return new Response('gui');
        
    }

    public function workAction() {
        
    }
}
