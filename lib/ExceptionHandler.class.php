<?php

/**
 *
 *
 * @script	ExceptionHandler.class.php
 * @author: George Russell Pruitt <pruitt.russell@gmail.com>
 * @library BareBones
 *
 * This class allows the collection of exceptions and a nice
 * way to handle them
 *
**/



class ExceptionHandler {

	public $errors = array();
	
	// constructor
	public function __construct() {
	  // code here
	}
	
	// destruct
	public function __destruct() {
	  // code here
	}
	
	// add an exception to stack
	public function add($e){
	  $this->errors[] = $e;
	}

	// returns all errors
	public function display(){
		$output = '';
		foreach($this->errors as $error){
			$output .= "<span class='error'>The following file: <strong>".$error->getFile()."</strong>";
			$output .= " produced an exception on line: <strong>".$error->getLine()."</strong>";
			$output .= " with the following message: <strong>".$error->getMessage()."</strong></span>";
		}
		return $output;
	}

	// check for errors
	public function has_errors(){
		if(sizeof($this->errors) > 0){
			return true;
		} else {
			return false;
		}
	}

}

// closing tag left off intentionally to prevent white space