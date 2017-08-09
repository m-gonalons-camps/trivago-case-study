<?php

namespace Tests\AppBundle\Controller;

class TopicsControllerTest extends BaseHelperClass {

    private $topicId;
    private $topicAliasId;

    public function testNewTopic() {
        $response = $this->getResponse(
            'POST',
            '/api/topics/new/',
            '{"name": "new_topic"}'
        );
        $this->assertEquals(200, $response['code']);
 
        $decodedBody = json_decode($response['body']);
        $this->assertNotNull($decodedBody->topicId);

        $this->topicId = $topicId;
    }

    public function testGetSingleTopic() {
        $response = $this->getResponse(
            'GET',
            '/api/topics/' . $this->topicId
        );
        $this->assertEquals(200, $response['code']);

        $decodedBody = json_decode($response['body']);
        $this->assertNotNull($decodedBody->name);
        $this->assertEquals('new_topic', $decodedBody->name);
    }

    public function testGetAllTopics() {
        $response = $this->getResponse(
            'GET',
            '/api/topics'
        );
        $this->assertEquals(200, $response['code']);
        $decodedBody = json_decode($response['body']);
        $this->assertEquals(TRUE, is_array($decodedBody));
        $this->assertNotNull($decodedBody[0]->name);
    }

    public function testModifyTopic() {
        $response = $this->getResponse(
            'POST',
            '/api/topics/modify/' . $this->topicId,
            '{"new_name": "modified_topic"}'
        );
        $this->assertEquals(200, $response['code']);
    }

    public function testNewTopicAlias() {
        $response = $this->getResponse(
            'POST',
            '/api/topics/aliases/' . $this->topicId . '/new',
            '{"name": "new_alias"}'
        );
        $this->assertEquals(200, $response['code']);
 
        $decodedBody = json_decode($response['body']);
        $this->assertNotNull($decodedBody->topicAliasId);

        $this->topicAliasId = $topicAliasId;
    }

    public function testGetSingleTopicAlias() {
        $response = $this->getResponse(
            'GET',
            '/api/topics/aliases/' . $this->topicId . '/' . $this->topicAliasId
        );
        $this->assertEquals(200, $response['code']);

        $decodedBody = json_decode($response['body']);
        $this->assertNotNull($decodedBody->name);
        $this->assertEquals('new_alias', $decodedBody->name);
    }

    public function testGetAllTopicAliases() {
        $response = $this->getResponse(
            'GET',
            '/api/topics/aliases/' . $this->topicId
        );
        $this->assertEquals(200, $response['code']);
        $decodedBody = json_decode($response['body']);
        $this->assertEquals(TRUE, is_array($decodedBody));
        $this->assertNotNull($decodedBody[0]->name);
    }

    public function testModifyTopicAlias() {
        $response = $this->getResponse(
            'POST',
            '/api/topics/aliases/' . $this->topicId . '/modify/' . $this->topicAliasId,
            '{"new_name": "modified_alias"}'
        );
        $this->assertEquals(200, $response['code']);
    }

    public function testDeleteTopicAlias() {
        $response = $this->getResponse(
            'DELETE',
            '/api/topics/aliases/' . $this->topicId . '/delete/' . $this->topicAliasId
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
