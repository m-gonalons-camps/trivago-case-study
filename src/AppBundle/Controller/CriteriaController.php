<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\Criteria;

class CriteriaController extends Controller {

    public function getCriteria(Request $request) : JsonResponse {
        $criteriaRepository = $this->get('doctrine')->getManager()->getRepository('AppBundle:Criteria');
        $serializer = $this->get('jms_serializer');

        if (count($request->query))
            $result = $criteriaRepository->getFiltered($this->getFiltersForRetrievingCriteria($request));
        else
            $result = $criteriaRepository->findAll();

        return new JsonResponse(json_decode($serializer->serialize($result, 'json')));
    }

    public function newCriteria(Request $request) : JsonResponse {
        $doctrineManager = $this->get('doctrine')->getManager();
        try {
            $decodedBody = $this->newCriteriaValidations($request, $doctrineManager);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        $newCriteria = new Criteria();
        $newCriteria->setKeyword($decodedBody->keyword);
        $newCriteria->setScore($decodedBody->score);
        $doctrineManager->persist($newCriteria);
        $doctrineManager->flush();

        return new JsonResponse(["success" => TRUE, "id" => $newCriteria->getId()]);
    }

    public function modifyCriteria(Request $request) : JsonResponse {
        $doctrineManager = $this->get('doctrine')->getManager();
        try {
            $validationResult = $this->modifyCriteriaValidations($request, $doctrineManager);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (isset($validationResult['decodedBody']->keyword))
            $validationResult['recoveredCriteria']->setKeyword($validationResult['decodedBody']->keyword);

        if ($validationResult['decodedBody']->score)
            $validationResult['recoveredCriteria']->setScore($validationResult['decodedBody']->score);

        $doctrineManager->persist($validationResult['recoveredCriteria']);
        $doctrineManager->flush();

        return new JsonResponse(["success" => TRUE]);
    }

    public function deleteCriteria(int $criteriaId) : JsonResponse {
        $doctrineManager = $this->get('doctrine')->getManager();
        try {
            $recoveredCriteria = $this->deleteCriteriaValidations($criteriaId, $doctrineManager);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        $doctrineManager->remove($recoveredCriteria);
        $doctrineManager->flush();

        return new JsonResponse();
    }


    private function newCriteriaValidations(Request $request, EntityManagerInterface $doctrineManager) : ?\stdClass {
        $decodedBody = json_decode($request->getContent());

        if (!isset($decodedBody->keyword) || !isset($decodedBody->score))
            throw new \Exception('Missing keyword or score in request');
        
        if (!is_int($decodedBody->score))
            throw new \Exception('Score must be an integer');
        
        $recoveredCriteria = $doctrineManager->getRepository('AppBundle:Criteria')
            ->findBy(['keyword' => $decodedBody->keyword]);
        
        if (count($recoveredCriteria) > 0)
            throw new \Exception('Criteria already exists.');
        
        return $decodedBody;
    }

    private function modifyCriteriaValidations(Request $request, EntityManagerInterface $doctrineManager) : array {
        $decodedBody = json_decode($request->getContent());

        if (!isset($decodedBody->id))
            throw new \Exception('Missing criteria ID in request');

        if (!is_int($decodedBody->id))
            throw new \Exception('Criteria ID must be an integer');
        
        if (isset($decodedBody->score) && !is_int($decodedBody->score))
            throw new \Exception('Score must be an integer');
        
        $recoveredCriteria = $doctrineManager->getRepository('AppBundle:Criteria')
            ->findBy(['id' => $decodedBody->id]);
        
        if (count($recoveredCriteria) === 0)
            throw new \Exception('Unable to recover the criteria with the ID: ' . $decodedBody->id);
        
        return [
            'decodedBody' => $decodedBody,
            'recoveredCriteria' => $recoveredCriteria[0]
        ];
    }

    private function deleteCriteriaValidations(int $criteriaId, EntityManagerInterface $doctrineManager) : ?Criteria {
        $recoveredCriteria = $doctrineManager->getRepository('AppBundle:Criteria')
            ->findBy(['id' => $criteriaId]);
        
        if (count($recoveredCriteria) === 0)
            throw new \Exception('Unable to recover the criteria with the ID: ' . $decodedBody->id);

        return $recoveredCriteria[0];
    }

    private function getFiltersForRetrievingCriteria(Request $request) : ?array {
        $parameters = ['id', 'keyword', 'score'];
        $filters = [];

        foreach ($parameters as $parameter)
            if ($request->get($parameter)) $filters[$parameter] = $request->get($parameter);

        return $filters;
    }

}
