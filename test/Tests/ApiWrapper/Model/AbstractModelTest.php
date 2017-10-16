<?php

namespace Tests\ApiWrapper\Model;

use PHPUnit\Framework\TestCase;
use SpExt\ApiWrapper\Http\RequestObjectValidationException;

class AbstractModelTest extends TestCase
{
    const PREFIX = 'test_';
    const TEST_TITLE = 'test_title';
    const TEST_MESSAGE = 'test_message';

    /** @var  TestModel */
    private $model;

    public function setUp()
    {
        parent::setUp();

        $this->model = new TestModel();
    }

    public function tearDown()
    {
        unset($this->model);

        parent::tearDown();
    }

    public function testCreate()
    {
        $testToCreate = [
            'title' => static::TEST_TITLE,
            'message' => static::TEST_MESSAGE
        ];

        $model = TestModel::create($testToCreate);

        $this->assertEquals($model->title, static::TEST_TITLE);
        $this->assertEquals($model->message, static::TEST_MESSAGE);
    }

    public function testValidate_success()
    {
        $errors = $this->model->validate();

        $this->assertCount(0, $errors);
    }

    public function testValidate_failed()
    {
        $this->model->message = '1';

        $this->expectException(RequestObjectValidationException::class);

        $this->model->validate(true);
    }

    public function testToArray()
    {
        $array = $this->model->toArray();

        $this->assertEquals($array, $this->model->testArray());
    }

    public function testToArray_withPrefix()
    {
        $array = $this->model->toArray(static::PREFIX);

        $this->assertEquals($array, $this->model->testArrayWithPrefix(static::PREFIX));
    }
}
