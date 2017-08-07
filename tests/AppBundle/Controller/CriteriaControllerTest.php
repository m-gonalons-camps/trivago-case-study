<?php

namespace Tests\AppBundle\Controller;

class CriteriaControllerTest extends BaseHelperClass {

    private $criteriaId;

    public function testNewCriteria() {
        $response = $this->getResponse(
            'POST',
            '/api/criteria/new/',
            json_encode([
                "keyword" => 'new criteria',
                "score" => 100
            ])
        );
        $this->assertEquals(200, $response['code']);
 
        $decodedBody = json_decode($response['body']);
        $this->assertNotNull($decodedBody->criteriaId);

        $this->criteriaId = $criteriaId;
    }

    public function testGetSingleCriteria() {
        $response = $this->getResponse(
            'GET',
            '/api/criteria/' . $this->criteriaId
        );
        $this->assertEquals(200, $response['code']);

        $decodedBody = json_decode($response['body']);
        $this->assertNotNull($decodedBody->keyword);
        $this->assertNotNull($decodedBody->score);
        $this->assertEquals('new criteria', $decodedBody->keyword);
        $this->assertEquals(100, $decodedBody->score);
    }

    public function testGetAllCriteria() {
        $response = $this->getResponse(
            'GET',
            '/api/criteria'
        );
        $this->assertEquals(200, $response['code']);
        $decodedBody = json_decode($response['body']);
        $this->assertEquals(TRUE, is_array($decodedBody));
        $this->assertNotNull($decodedBody[0]->keyword);
        $this->assertNotNull($decodedBody[0]->score);
    }

    public function testModifyCriteria() {
        $response = $this->getResponse(
            'POST',
            '/api/criteria/modify/' . $this->criteriaId,
            json_encode([
                "keyword" => 'modified criteria',
                "score" => -100
            ])
        );
        $this->assertEquals(200, $response['code']);
    }

    public function testDeleteCriteria() {
        $response = $this->getResponse(
            'DELETE',
            '/api/criteria/delete/' . $this->criteriaId
        );
        $this->assertEquals(200, $response['code']);
    }
}
