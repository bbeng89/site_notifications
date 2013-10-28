<?php
defined('C5_EXECUTE') or die(_("Access Denied"));

/**
 * @author 		Blake Bengtson (bbeng89)
 * @copyright  	Copyright 2013 Blake Bengtson
 * @license     concrete5.org marketplace license
 */

class NotyHelper {

	//Returns an array of possible noty layouts. Layouts are the position of the alert on the screen
	//The key is the text the noty javascript expects, and the value is the human-readable version
	public function getLayouts(){
		return array(
			'top' => t('Top'),
		    'topCenter' => t('Top Center'),
		    'topLeft' => t('Top Left'),
		    'topRight' => t('Top Right'),
		    'center' => t('Center'),
		    'centerLeft' => t('Center Left'),
		    'centerRight' => t('Center Right'),
		    'bottom' => t('Bottom'),
		    'bottomCenter' => t('Bottom Center'),
		    'bottomLeft' => t('Bottom Left'),
		    'bottomRight' => t('Bottom Right')
		);
	}

	//Returns an array of possible noty notification types. Types basically indicate the color of the notification.
	//The key is the text the noty javascript expects, and the value is the human readable form.
	public function getTypes(){
		return array(
			'alert' => t('Alert'),
		    'information' => t('Information'),
		    'error' => t('Error'),
		    'warning' => t('Warning'),
		    'notification' => t('Notification'),
		    'success' => t('Success')
		);
	}

	//Returns an array of options for closeWith. This is how the notification can be dismissed
	public function getCloseWithOptions(){
		return array(
			'click' => t('Click'),
			'hover' => t('Hover')
		);
	}
}