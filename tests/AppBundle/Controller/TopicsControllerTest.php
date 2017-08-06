<?php

namespace Tests\AppBundle\Controller;

class TopicsControllerTest extends BaseHelperClass {

    private $topicId;

    public function testNewTopic() {
        $response = $this->getResponse(
            'POST',
            '/api/topics/new/',
            'new_topic'
        );
        $this->assertEquals(200, $response['code']);
 
        $decodedBody = json_decode($response['body']);
        $this->assertNotNull($decodedBody->reviewId);

        $this->topicId = $topicId;
    }

    public function testGetSingleTopic() {
        $response = $this->getResponse(
            'GET',
            '/api/topics/' . $this->topicId
        );
        $this->assertEquals(200, $response['code']);
        $this->assertEquals('new_topic', $response['body']);
    }

    public function testGetAllTopics() {
        $response = $this->getResponse(
            'GET',
            '/api/topics'
        );
        $this->assertEquals(200, $response['code']);
        $decodedBody = json_decode($response['body']);
        $this->assertEquals(TRUE, is_array($decodedBody));
    }

    public function testModifyTopic() {
        $response = $this->getResponse(
            'POST',
            '/api/topics/modify/' . $this->topicId,
            'modified_topic'
        );
        $this->assertEquals(200, $response['code']);
    }

    public function testDeleteTopic() {
        $response = $this->getResponse(
            'DELETE',
            '/api/topics/delete/' . $this->topicId
        );
        $this->assertEquals(200, $response['code']);
    }

}
