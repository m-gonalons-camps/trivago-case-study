<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReviewsController extends Controller {

    public function testAnalyzer(Request $request) : JsonResponse {
        return new JsonResponse(['hotel' => 'bad']);
    }

    public function analyzeReview(Request $request, int $reviewId) : JsonResponse {
        // Get review ID
        // Analyze
        // Save results in DB
        // Return results
        var_dump($request);
        return new JsonResponse();
    }

    public function getReviews(Request $request, ?int $reviewId = NULL) : JsonResponse {
        // Get all the analysis from DB
        // review, total score, score by topic with the criteria and emphasizers
        // Return in a format understood by JSGRID:
        /*
            ["value" => [[
                "ID" => 0,
                "Review" => "good hotel",
                "Total score" => 123,
                "etc..."
            ],[
                etx
            ]]]
        */
        return new JsonResponse();
    }

    public function uploadReviews(Request $request) : JsonResponse {
        // Get reviews from CSV file
        // Save them in DB
        return new JsonResponse();
    }

    public function newReview() : JsonResponse {
        return new JsonResponse();
    }

    public function deleteReview() : JsonResponse { 
        return new JsonResponse();
    }

}
