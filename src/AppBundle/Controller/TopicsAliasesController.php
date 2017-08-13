<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\TopicAlias;

class TopicsAliasesController extends Controller {

    public function getTopicAliases(Request $request) : JsonResponse {
        $topicAliasRepository = $this->get('doctrine')->getManager()->getRepository('AppBundle:TopicAlias');
        $serializer = $this->get('jms_serializer');

        if (count($request->query))
            $result = $topicAliasRepository->getFiltered($this->getFiltersForRetrievingAliases($request));
        else
            $result = $topicAliasRepository->findAll();

        return new JsonResponse(json_decode($serializer->serialize($result, 'json')));
    }

    public function newTopicAlias(Request $request) : JsonResponse {
        $doctrineManager = $this->get('doctrine')->getManager();
        try {
            [$decodedBody, $recoveredTopic] = $this->newTopicAliasValidations($request, $doctrineManager);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        $newAlias = new TopicAlias();
        $newAlias->setAlias($decodedBody->alias);
        $newAlias->setTopic($recoveredTopic);

        $doctrineManager->persist($newAlias);
        $doctrineManager->flush();

        return new JsonResponse(["success" => TRUE, "id" => $newAlias->getId()]);
    }
    
    public function modifyTopicAlias(Request $request) : JsonResponse {
        $doctrineManager = $this->get('doctrine')->getManager();
        try {
            $validationResult = $this->modifyTopicAliasValidations($request, $doctrineManager);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (isset($validationResult['decodedBody']->alias))
            $validationResult['recoveredAlias']->setAlias($validationResult['decodedBody']->alias);

        $doctrineManager->persist($validationResult['recoveredAlias']);
        $doctrineManager->flush();

        return new JsonResponse(["success" => TRUE]);
    }

    public function deleteTopicAlias(int $aliasId) : JsonResponse {
        $doctrineManager = $this->get('doctrine')->getManager();
        try {
            $recoveredAlias = $this->deleteTopicAliasValidations($aliasId, $doctrineManager);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        $doctrineManager->remove($recoveredAlias);
        $doctrineManager->flush();

        return new JsonResponse();
    }

    private function getFiltersForRetrievingAliases(Request $request) : ?array {
        $parameters = ['id', 'topic_name', 'alias'];
        $filters = [];

        foreach ($parameters as $parameter)
            if ($request->get($parameter)) $filters[$parameter] = $request->get($parameter);

        return $filters;
    }

    private function newTopicAliasValidations(Request $request, EntityManagerInterface $doctrineManager) : ?array {
        $decodedBody = json_decode($request->getContent());

        if (!isset($decodedBody->alias) || !isset($decodedBody->topic_name))
            throw new \Exception('Missing name or topic name in request');
        
        $recoveredTopic = $doctrineManager->getRepository('AppBundle:Topic')
            ->findBy(['name' => $decodedBody->topic_name]);
        
        if (count($recoveredTopic) === 0)
            throw new \Exception('Topic ' . $decodedBody->topic_name .' does not exist.');

        $recoveredAlias = $doctrineManager->getRepository('AppBundle:TopicAlias')
            ->findBy(['alias' => $decodedBody->alias]);

        if (count($recoveredAlias) > 0)
            throw new \Exception('Alias ' . $decodedBody->alias .' already exists for topic: ' . $recoveredAlias[0]->getTopic()->getName());
        
        return [$decodedBody, $recoveredTopic[0]];
    }

    private function modifyTopicAliasValidations(Request $request, EntityManagerInterface $doctrineManager) : array {
        $decodedBody = json_decode($request->getContent());

        if (!isset($decodedBody->id))
            throw new \Exception('Missing topic ID in request');

        if (!is_int($decodedBody->id))
            throw new \Exception('topic ID must be an integer');
        
        $recoveredTopicAlias = $doctrineManager->getRepository('AppBundle:TopicAlias')
            ->findBy(['id' => $decodedBody->id]);
        
        if (count($recoveredTopicAlias) === 0)
            throw new \Exception('Unable to recover the alias with the ID: ' . $decodedBody->id);
        
        return [
            'decodedBody' => $decodedBody,
            'recoveredAlias' => $recoveredTopicAlias[0]
        ];
    }

    private function deleteTopicAliasValidations(int $aliasId, EntityManagerInterface $doctrineManager) : ?TopicAlias {
        $recoveredTopic = $doctrineManager->getRepository('AppBundle:TopicAlias')
            ->findBy(['id' => $aliasId]);
        
        if (count($recoveredTopic) === 0)
            throw new \Exception('Unable to recover the alias with the ID: ' . $decodedBody->id);

        return $recoveredTopic[0];
    }

}