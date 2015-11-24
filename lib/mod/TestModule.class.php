<?php

/**
 * Description of BareBones::ModuleBase
 *
 * @author George Russell Pruitt
 */

// Grab all required Module Libraries
require_once($SITE->lib."ModuleBase.class.php");

class TestModule extends ModuleBase {
	
	public function __construct($name) {
		parent::__construct($name);
	}
	
	public function __destruct(){
		parent::__destruct();
	}
	
}



$TestModule = new TestModule('test');

