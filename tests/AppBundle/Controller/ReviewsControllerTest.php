<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ReviewsControllerTest extends BaseHelperClass {

    private $reviewId;

    public function testTestAnalyzer() {
        $response = $this->getResponse(
            'POST',
            '/api/reviews/testAnalyzer/',
            $this->getReviewForTestingAnalyzer()
        );
        $this->assertEquals(200, $response['code']);

        $decodedBody = json_decode($response['body']);
        $this->assertNotNull($decodedBody->hotel);
    }

    public function testNewReview() {
        $response = $this->getResponse(
            'POST',
            '/api/reviews/new/',
            $this->getReviewForTestingNewReview()
        );
        $this->assertEquals(200, $response['code']);
 
        $decodedBody = json_decode($response['body']);
        $this->assertNotNull($decodedBody->reviewId);

        $this->reviewId = $reviewId;
    }

    public function testAnalyzeReview() {
        $response = $this->getResponse(
            'POST',
            '/api/reviews/analyze/' . $this->reviewId
        );
        $this->assertEquals(200, $response['code']);

        $decodedBody = json_decode($response['body']);
        $this->assertNotNull($decodedBody->staff);
        $this->assertNotNull($decodedBody->pool);
    }

    public function testGetSingleReview() {
        $response = $this->getResponse(
            'GET',
            '/api/reviews/' . $this->reviewId
        );
        $this->assertEquals(200, $response['code']);
    }

    public function testGetAllReviews() {
        $response = $this->getResponse(
            'GET',
            '/api/reviews'
        );
        $this->assertEquals(200, $response['code']);
        $decodedBody = json_decode($response['body']);
        $this->assertEquals(TRUE, is_array($decodedBody));
    }

    public function testUploadReviews() {
        $response = $this->getResponse(
            'PUT',
            '/api/reviews/upload',
            '',
            [],
            $this->getCSVFile()
        );
        $this->assertEquals(200, $response['code']);
    }

    public function testDeleteReview() {
        $response = $this->getResponse(
            'DELETE',
            '/api/reviews/delete/' . $this->reviewId
        );
        $this->assertEquals(200, $response['code']);
    }

    private function getReviewForTestingAnalyzer() : string {
        return 'Hotel is very bad. Restaurant is not good.';
    }

    private function getReviewForTestingNewReview() : string {
        return 'Staff is so helpful and very friendly. Pool is so dirty though.';
    }
    
    private function getCSVFile() : array {
        $filepath = __DIR__ . '/test_reviews.csv';
        $csv = new UploadedFile($filepath, $filepath, 'text/csv', filesize($filepath));
        return ['csv' => $csv];
    }
}
