<?php
namespace Nixhatter\ICMS\model;

/**
 * Test Cases for the Model Class
 */

define('_ICMS', 1);

class ModelTest extends \PHPUnit_Framework_TestCase
{
    private $model;

    protected function setUp()
    {
        $this->model = new Model();
    }


    public function testCheckIfEmpty()
    {
        $good_array = array("hello", "goodbye", "123", 4, 500);
        $bad_array = array("hello", "goodbye", "123", "", 500);

        $this->assertTrue($this->model->checkIfEmpty($good_array));
        $this->assertFalse($this->model->checkIfEmpty($bad_array));
    }
}
