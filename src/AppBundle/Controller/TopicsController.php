<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\Topic;
use AppBundle\Entity\TopicAlias;

class TopicsController extends Controller {

    public function getTopics(Request $request) : JsonResponse {
        $topicsRepository = $this->get('doctrine')->getManager()->getRepository('AppBundle:Topic');
        $serializer = $this->get('jms_serializer');

        if (count($request->query))
            $result = $topicsRepository->getFiltered($this->getFiltersForRetrievingTopics($request));
        else
            $result = $topicsRepository->findAll();

        return new JsonResponse(json_decode($serializer->serialize($result, 'json')));
    }

    public function newTopic(Request $request) : JsonResponse {
        $doctrineManager = $this->get('doctrine')->getManager();
        try {
            $decodedBody = $this->newTopicValidations($request, $doctrineManager);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        $newTopic = new Topic();
        $newTopic->setName($decodedBody->name);
        $newTopic->setPriority($decodedBody->priority);
        $doctrineManager->persist($newTopic);
        $doctrineManager->flush();

        return new JsonResponse(["success" => TRUE, "id" => $newTopic->getId()]);
    }

    public function modifyTopic(Request $request) : JsonResponse {
        $doctrineManager = $this->get('doctrine')->getManager();
        try {
            $validationResult = $this->modifyTopicValidations($request, $doctrineManager);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (isset($validationResult['decodedBody']->name))
            $validationResult['recoveredTopic']->setName($validationResult['decodedBody']->name);

        if (isset($validationResult['decodedBody']->priority))
            $validationResult['recoveredTopic']->setPriority($validationResult['decodedBody']->priority);

        $doctrineManager->persist($validationResult['recoveredTopic']);
        $doctrineManager->flush();

        return new JsonResponse(["success" => TRUE]);
    }

    public function deleteTopic(int $topicId) : JsonResponse {
        $doctrineManager = $this->get('doctrine')->getManager();
        try {
            $recoveredTopic = $this->deleteTopicValidations($topicId, $doctrineManager);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        $doctrineManager->remove($recoveredTopic);
        $doctrineManager->flush();

        return new JsonResponse();
    }
    

    private function getFiltersForRetrievingTopics(Request $request) : ?array {
        $parameters = ['id', 'name', 'alias', 'priority'];
        $filters = [];

        foreach ($parameters as $parameter)
            if ($request->get($parameter)) $filters[$parameter] = $request->get($parameter);

        return $filters;
    }

    private function newTopicValidations(Request $request, EntityManagerInterface $doctrineManager) : ?\stdClass {
        $decodedBody = json_decode($request->getContent());

        if (!isset($decodedBody->name) || !isset($decodedBody->priority))
            throw new \Exception('Missing name or priority in request');
        
        if (!is_int($decodedBody->priority))
            throw new \Exception('Priority must be an integer');
        
        $recoveredTopic = $doctrineManager->getRepository('AppBundle:Topic')
            ->findBy(['name' => $decodedBody->name]);
        
        if (count($recoveredTopic) > 0)
            throw new \Exception('Topic already exists.');
        
        return $decodedBody;
    }

    private function modifyTopicValidations(Request $request, EntityManagerInterface $doctrineManager) : array {
        $decodedBody = json_decode($request->getContent());

        if (!isset($decodedBody->id))
            throw new \Exception('Missing topic ID in request');

        if (!is_int($decodedBody->id))
            throw new \Exception('topic ID must be an integer');
        
        if (isset($decodedBody->priority) && !is_int($decodedBody->priority))
            throw new \Exception('Priority must be an integer');
        
        $recoveredTopic = $doctrineManager->getRepository('AppBundle:Topic')
            ->findBy(['id' => $decodedBody->id]);
        
        if (count($recoveredTopic) === 0)
            throw new \Exception('Unable to recover the topic with the ID: ' . $decodedBody->id);
        
        return [
            'decodedBody' => $decodedBody,
            'recoveredTopic' => $recoveredTopic[0]
        ];
    }

    private function deleteTopicValidations(int $topicId, EntityManagerInterface $doctrineManager) : ?Topic {
        $recoveredTopic = $doctrineManager->getRepository('AppBundle:Topic')
            ->findBy(['id' => $topicId]);
        
        if (count($recoveredTopic) === 0)
            throw new \Exception('Unable to recover the topic with the ID: ' . $topicId);

        return $recoveredTopic[0];
    }


}
