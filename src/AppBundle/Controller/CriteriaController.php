<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CriteriaController extends Controller {

    public function getCriteria(Request $request, ?int $criteriaId = NULL) : JsonResponse {
        $criteriaRepository = $this->get('doctrine')->getManager()->getRepository('AppBundle:Criteria');
        $serializer = $this->get('jms_serializer');

        if ($criteriaId)
            $result = $criteriaRepository->findBy(['id' => $criteriaId]);
        else
            $result = $criteriaRepository->findAll();

        return new JsonResponse(json_decode($serializer->serialize($result, 'json')));
    }

    public function newCriteria(Request $request) : JsonResponse {
        return new JsonResponse();
    }

    public function modifyCriteria(Request $request) : JsonResponse {
        return new JsonResponse();
    }

    public function deleteCriteria(Request $request) : JsonResponse {
        return new JsonResponse();
    }

}
