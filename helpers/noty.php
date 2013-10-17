<?php
defined('C5_EXECUTE') or die(_("Access Denied"));

class NotyHelper {

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

	public function getCloseWithOptions(){
		return array(
			'click' => t('Click'),
			'hover' => t('Hover')
		);
	}
}