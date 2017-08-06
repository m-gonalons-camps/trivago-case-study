<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class EmphasizersController extends Controller {

    public function getEmphasizers(Request $request, ?int $emphasizerId = NULL) : JsonResponse {
        return new JsonResponse();
    }

    public function newEmphasizer(Request $request) : JsonResponse {
        return new JsonResponse();
    }

    public function modifyEmphasizer(Request $request) : JsonResponse {
        return new JsonResponse();
    }

    public function deleteEmphasizer(Request $request) : JsonResponse {
        return new JsonResponse();
    }

}
