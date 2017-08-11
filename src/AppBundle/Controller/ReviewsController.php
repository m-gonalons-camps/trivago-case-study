<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity;
use AppBundle\Service;

class ReviewsController extends Controller {

    public function testAnalyzer(Request $request) : JsonResponse {
        $analyzer = $this->get('AppBundle.DefaultAnalyzer');
        $response = $analyzer->analyze($request->getContent());
        return new JsonResponse(json_decode($response->getFullResults(TRUE)));
    }

    public function getReviews(Request $request) : JsonResponse {
        $doctrineManager = $this->get('doctrine')->getManager();
        $reviews = $doctrineManager->getRepository('AppBundle:Review')->findAll();

        $serializer = $this->get('jms_serializer');

        return new JsonResponse(json_decode($serializer->serialize($reviews, 'json')));
    }

    public function analyzeReview(Request $request, int $reviewId) : JsonResponse {
        $doctrineManager = $this->get('doctrine')->getManager();
        $recoveredReview = $doctrineManager->getRepository('AppBundle:Review')
            ->findBy(['id' => $reviewId]);

        if (count($recoveredReview) === 0)
            return new JsonResponse(["error" => "The review with the ID " . $reviewId . " does not exist."], 400);

        $this->deletePreviousAnalysis($recoveredReview[0], $doctrineManager);

        $analyzer = $this->get('AppBundle.DefaultAnalyzer');
        $response = $analyzer->analyze($recoveredReview[0]->getText());

        $this->saveAnalysisResults($recoveredReview[0], $response, $doctrineManager);

        return new JsonResponse(json_decode($response->getFullResults(TRUE)));
    }

    public function analyzeAllReviews(Request $request) : JsonResponse {
        // Get all reviews from BD
        // Analyze them and save score in DB
        return new JsonResponse();
    }

    public function uploadReviews(Request $request) : JsonResponse {
        // Get reviews from CSV file
        // Save them in DB
        return new JsonResponse();
    }

    public function newReview(Request $request) : JsonResponse {
        return new JsonResponse();
    }

    public function deleteReview() : JsonResponse { 
        return new JsonResponse();
    }

    public function deletePreviousAnalysis(Entity\Review $review, EntityManagerInterface $doctrineManager) : void {
        $reviewAnalysis = $doctrineManager->getRepository('AppBundle:Analysis')->findBy(['review' => $review]);

        foreach ($reviewAnalysis as $analysis)
            $doctrineManager->remove($analysis);

        $doctrineManager->flush();
    }

    private function saveAnalysisResults(Entity\Review $review, Service\AnalyzerResponse $analysisResults, EntityManagerInterface $doctrineManager) : void {
        $fullResults = $analysisResults->getFullResults();
        foreach ($fullResults as $topicName => $topicResult) {
            $analysis = new Entity\Analysis;
            $analysis->setReview($review);
            $topicEntity = $doctrineManager->getRepository('AppBundle:Topic')->findBy(['name' => $topicName]);
            $analysis->setTopic($topicEntity[0]);
            $analysis->setScore($topicResult['score']);
            $doctrineManager->persist($analysis);

            foreach ($topicResult['criteria'] as $criteria) {
                $analysisCriteria = new Entity\AnalysisCriteria;
                $analysisCriteria->setAnalysis($analysis);
                $analysisCriteria->setCriteria($criteria['entity']);
                $analysisCriteria->setEmphasizer($criteria['emphasizer']);
                $analysisCriteria->setNegated($criteria['negated']);
                $doctrineManager->persist($analysisCriteria);
            }
        }

        $review->setTotalScore($analysisResults->getScore());
        $doctrineManager->persist($review);

        $doctrineManager->flush();
    }

}
