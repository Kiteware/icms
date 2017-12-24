<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/3/2016
 * Time: 11:41 PM
 */

namespace Nixhatter\ICMS\model;

class UserModelTest extends \PHPUnit_Framework_TestCase
{
    protected $usermodel;

    protected function setUp()
    {
        /**
         * Create a DI Container
         */
        $container = new \Pimple\Container();

        $container['settings'] = function ($c) {
            return "";
        };

        $container['db'] = function ($c) {
            return "";
        };

        $this->usermodel = new UserModel($container);
    }

    public function testFuncExists()
    {
        $this->assertTrue(function_exists('password_hash'));
        $this->assertTrue(function_exists('password_verify'));
    }

    public function testHash()
    {
        $hash = password_hash('password', PASSWORD_DEFAULT);
        $this->assertEquals($hash, crypt('password', $hash));
    }

    public function testGenHash()
    {
        $actualHash = $this->usermodel->genHash('password');

        $true_result = $this->usermodel->compare('password', $actualHash);
        $false_result = $this->usermodel->compare('passw0rd', $actualHash);

        $this->assertTrue($true_result);
        $this->assertFalse($false_result);
    }
}
