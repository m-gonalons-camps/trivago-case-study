<?php

namespace Tests\AppBundle\Controller;

class TopicsControllerTest extends BaseHelperClass {

    private $topicId;
    private $topicAliasId;

    public function testAll() {
        $this->_testNewTopic();
        $this->_testBadRequestsNewTopic();
        $this->_testGetSingleTopic();
        $this->_testGetAllTopics();
        $this->_testModifyTopic();
        $this->_testBadRequestsModifyTopic();
        $this->_testDeleteTopic();
        $this->_testBadRequestsDeleteTopic();

        // missing aliases tests
    }

    private function _testNewTopic() {
        $response = $this->getResponse(
            'POST',
            '/api/topics/new/',
            json_encode([
                "name" => 'new topic',
                "priority" => 999
            ])
        );
        $this->assertEquals(200, $response['code']);
 
        $decodedBody = json_decode($response['body']);
        $this->assertNotNull($decodedBody->id);

        $this->topicId = $decodedBody->id;
    }

    private function _testBadRequestsNewTopic() {
        $badRequests = [
            '{"bad: json": ¨',
            json_encode(["name" => 'new topic']),
            json_encode(["priority" => 123]),
            json_encode(["name" => "abctesting123", "priority" => 'priority must be int']),
            // new topic already exists
            json_encode(["name" => "new topic", "priority" => 123]),
        ];

        $this->assertBadRequests('/api/topics/new/', 'POST', $badRequests);
    }

    private function _testGetSingleTopic() {
        $response = $this->getResponse(
            'GET',
            '/api/topics?id=' . $this->topicId
        );
        $this->assertCorrectlyRecoveredTopic($response);

        $response = $this->getResponse(
            'GET',
            '/api/topics?name=new%20topic'
        );
        $this->assertCorrectlyRecoveredTopic($response);

        $response = $this->getResponse(
            'GET',
            '/api/topics?priority=999'
        );
        $this->assertCorrectlyRecoveredTopic($response);
    }

    private function _testGetAllTopics() {
        $response = $this->getResponse(
            'GET',
            '/api/topics'
        );
        $this->assertEquals(200, $response['code']);
        $decodedBody = json_decode($response['body']);
        $this->assertEquals(TRUE, is_array($decodedBody));
        $this->assertNotNull($decodedBody[0]->name);
        $this->assertNotNull($decodedBody[0]->priority);
    }

    private function _testModifyTopic() {
        $response = $this->getResponse(
            'POST',
            '/api/topics/modify/',
            json_encode([
                "id" => $this->topicId,
                "name" => 'modified topic',
                "priority" => -42
            ])
        );
        $this->assertEquals(200, $response['code']);
    }

    private function _testNewTopicAlias() {
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

    private function _testGetSingleTopicAlias() {
        $response = $this->getResponse(
            'GET',
            '/api/topics/aliases/' . $this->topicId . '/' . $this->topicAliasId
        );
        $this->assertEquals(200, $response['code']);

        $decodedBody = json_decode($response['body']);
        $this->assertNotNull($decodedBody->name);
        $this->assertEquals('new_alias', $decodedBody->name);
    }

    private function _testGetAllTopicAliases() {
        $response = $this->getResponse(
            'GET',
            '/api/topics/aliases/' . $this->topicId
        );
        $this->assertEquals(200, $response['code']);
        $decodedBody = json_decode($response['body']);
        $this->assertEquals(TRUE, is_array($decodedBody));
        $this->assertNotNull($decodedBody[0]->name);
    }

    private function _testModifyTopicAlias() {
        $response = $this->getResponse(
            'POST',
            '/api/topics/aliases/' . $this->topicId . '/modify/' . $this->topicAliasId,
            '{"new_name": "modified_alias"}'
        );
        $this->assertEquals(200, $response['code']);
    }

    private function _testDeleteTopicAlias() {
        $response = $this->getResponse(
            'DELETE',
            '/api/topics/aliases/' . $this->topicId . '/delete/' . $this->topicAliasId
        );
        $this->assertEquals(200, $response['code']);
    }

    private function _testDeleteTopic() {
        $response = $this->getResponse(
            'DELETE',
            '/api/topics/delete/' . $this->topicId
        );
        $this->assertEquals(200, $response['code']);
    }

    
    private function assertCorrectlyRecoveredTopic($response) {
        $this->assertEquals(200, $response['code']);
        $decodedBody = json_decode($response['body']);

        $this->assertNotNull($decodedBody[0]->name);
        $this->assertNotNull($decodedBody[0]->priority);
        $this->assertEquals('new topic', $decodedBody[0]->name);
        $this->assertEquals(999, $decodedBody[0]->priority);
    }

    private function _testBadRequestsModifyTopic() {
        $badRequests = [
            '{"bad: json": ¨',
            json_encode(["name" => 'new topic']),
            json_encode(["id" => "id must be an integer"]),
            json_encode(["id" => 1, "priority" => 'priority must be a number']),
            json_encode(["id" => -123, "priority" => 1]),
        ];

        $this->assertBadRequests('/api/topics/modify/', 'POST', $badRequests);
    }

    private function _testBadRequestsDeleteTopic() {
        $this->assertBadRequests('/api/topics/delete/-123', 'DELETE', ['123']);
    }
}
