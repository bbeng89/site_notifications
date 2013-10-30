<?php
defined('C5_EXECUTE') or die(_('Access Denied'));

Loader::helper('text');

class CustomTextHelper extends TextHelper { 

	public function pluralize($count, $singular, $plural){

		if($count == 1){
			return $singular;
		}
		
		return $plural;
	}
}