<?php

App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ClearController', 'Cache.Controller');

class ClearControllerTest extends CakeTestCase
{

	public $Controller = null;

	public function setUp()
	{
		$this->Controller = new ClearController(new CakeRequest(), new CakeResponse());
	}

	public function tearDown()
	{
		unset($this->Contorller);
	}

	public function testClear()
	{
		$file = APP . 'tmp' . DS . 'cache' . DS . 'persistent' . DS . '---temporaly-file-for-cache-clear-test---';
		fopen($file, 'a+');

		$expected = true;
		$result = file_exists($file);
		$this->assertEquals($expected, $result);

		@$this->Controller->admin_index('app', false);

		$expected = false;
		$result = file_exists($file);
		$this->assertEquals($expected, $result);
	}

}
