<?php
defined('C5_EXECUTE') or die(_('Access Denied'));

/**
 * @author 		Blake Bengtson (bbeng89)
 * @copyright  	Copyright 2013 Blake Bengtson
 * @license     concrete5.org marketplace license
 */

class CustomTextHelper extends TextHelper { 

	public function pluralize($count, $singular, $plural){

		if($count == 1){
			return $singular;
		}
		
		return $plural;
	}
}