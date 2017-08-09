<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TopicsController extends Controller {

    public function getTopics(Request $request, ?int $topicId = NULL) : JsonResponse {
        // Get all topics with their aliases
        // Return correct format
        /*
            ["value" => [[
                "ID" => 0,
                "Name" => "Hotel",
                "Aliases" => "Building,Property",
                "etc..."
            ],[
                etc
            ]]]
        */
        return new JsonResponse();
    }

    public function newTopic(Request $request) : JsonResponse {
        return new JsonResponse();
    }

    public function modifyTopic(Request $request) : JsonResponse {
        return new JsonResponse();
    }

    public function deleteTopic(Request $request) : JsonResponse {
        return new JsonResponse();
    }
    
    public function getTopicAliases(Request $request, int $topicId, ?int $aliasId = NULL) : JsonResponse {
        // Get all aliases from the topic ID
        return new JsonResponse();
    }

    public function newTopicAlias(Request $request, int $topicId) : JsonResponse {
        echo $topicId;
        return new JsonResponse();
    }
    
    public function modifyTopicAlias(Request $request, int $topicId, int $aliasId) : JsonResponse {
        return new JsonResponse();
    }

    public function deleteTopicAlias(Request $request) : JsonResponse {
        return new JsonResponse();
    }

}
