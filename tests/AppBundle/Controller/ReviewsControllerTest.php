<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ReviewsControllerTest extends BaseHelperClass {

    private $reviewId;

    public function testTestAnalyzer() {
        $response = $this->getResponse(
            'POST',
            '/api/reviews/testAnalyzer/',
            'Hotel is very bad. Restaurant is not good.'
        );
        $this->assertEquals(200, $response['code']);

        $decodedBody = json_decode($response['body']);

        // since the analyzer is fully tested in tests/AppBundle/Service/DefaultAnalyzerTest.php,
        // there is no need to test any more than this here.
        $this->assertNotNull($decodedBody->hotel);
        $this->assertNotNull($decodedBody->restaurant);
    }

    public function testNewReview() {
        $response = $this->getResponse(
            'POST',
            '/api/reviews/new/',
            $this->getNewReview()
        );
        $this->assertEquals(200, $response['code']);
 
        $decodedBody = json_decode($response['body']);
        $this->assertGreaterThan(0, $decodedBody->reviewId);

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
        $decodedBody = json_decode($response['body']);

        $this->assertNotNull($decodedBody->review);
        $this->assertEquals($this->getNewReview(), $decodedBody->review);
        $this->assertGreaterThan(0, $decodedBody->totalScore);
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

    private function getCSVFile() : array {
        $filepath = __DIR__ . '/test_reviews.csv';
        $csv = new UploadedFile($filepath, $filepath, 'text/csv', filesize($filepath));
        return ['csv' => $csv];
    }

    private function getNewReview() : string {
        return 'Staff is so helpful and very friendly. Pool is so dirty though.';
    }
}
