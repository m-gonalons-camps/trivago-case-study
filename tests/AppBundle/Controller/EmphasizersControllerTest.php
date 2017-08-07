<?php

namespace Tests\AppBundle\Controller;

class EmphasizerSControllerTest extends BaseHelperClass {

    private $emphasizerId;

    public function testNewEmphasizer() {
        $response = $this->getResponse(
            'POST',
            '/api/emphasizers/new/',
            json_encode([
                "keyword" => 'new emphasizer',
                "score" => 100
            ])
        );
        $this->assertEquals(200, $response['code']);
 
        $decodedBody = json_decode($response['body']);
        $this->assertNotNull($decodedBody->emphasizerId);

        $this->emphasizerId = $emphasizerId;
    }

    public function testGetSingleEmphasizer() {
        $response = $this->getResponse(
            'GET',
            '/api/emphasizers/' . $this->emphasizerId
        );
        $this->assertEquals(200, $response['code']);

        $decodedBody = json_decode($response['body']);
        $this->assertNotNull($decodedBody->keyword);
        $this->assertNotNull($decodedBody->score);
        $this->assertEquals('new emphasizer', $decodedBody->name);
        $this->assertEquals(100, $decodedBody->score_modifier);
    }

    public function testGetAllEmphasizers() {
        $response = $this->getResponse(
            'GET',
            '/api/emphasizers'
        );
        $this->assertEquals(200, $response['code']);
        $decodedBody = json_decode($response['body']);
        $this->assertEquals(TRUE, is_array($decodedBody));
        $this->assertNotNull($decodedBody[0]->name);
        $this->assertNotNull($decodedBody[0]->score_modifier);
    }

    public function testModifyEmphasizer() {
        $response = $this->getResponse(
            'POST',
            '/api/emphasizers/modify/' . $this->emphasizerId,
            json_encode([
                "name" => 'modified emphasizer',
                "score_modifier" => 0.2
            ])
        );
        $this->assertEquals(200, $response['code']);
    }

    public function testDeleteemphasizer() {
        $response = $this->getResponse(
            'DELETE',
            '/api/emphasizers/delete/' . $this->emphasizerId
        );
        $this->assertEquals(200, $response['code']);
    }
}
