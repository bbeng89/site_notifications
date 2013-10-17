<?php
defined('C5_EXECUTE') or die(_("Access Denied"));

class Notification {

	public function addNotifications($c){
		$db = Loader::db();
		$v = View::getInstance();
		$notifications = $db->GetAll("SELECT * FROM SiteNotifications");

		if(count($notifications) != 0){
			$html = Loader::helper('html');
			$json = Loader::helper('json');
			$nOptions = array();
			$nLayouts = array();
			$dismissed = !empty($_COOKIE['dismissed_site_notifications']) ? $json->decode($_COOKIE['dismissed_site_notifications']) : array();

			$v->addFooterItem($html->javascript('noty-2.1.0/js/noty/jquery.noty.js', 'site_notifications'));
			
			//create options objects for each notification
			foreach($notifications as $n){
				if(in_array($n['notificationID'], $dismissed)){
					continue;
				}
				//these must match up with noty options (http://needim.github.io/noty/#options)
				$settings = new StdClass();
				$settings->notificationID = $n['notificationID'];
				$settings->text = $n['notificationText'];
				$settings->layout = $n['layout'];
				$settings->theme = 'defaultTheme';
				$settings->type = $n['notificationType'];
				$settings->dismissQueue = true;
				$settings->timeout = empty($n['delay'])? false : $n['delay'];
				$settings->modal = $n['modal'] === 1 ? true : false;
				$settings->closeWith = array($n['closeWith']);
				$nOptions[] = $settings;

				if(!in_array($n['layout'], $nLayouts)){
					$nLayouts[] = $n['layout'];
				}
			}

			foreach($nLayouts as $layout){
				$v->addFooterItem($html->javascript('noty-2.1.0/js/noty/layouts/' . $layout . '.js', 'site_notifications'));
			}

			$v->addFooterItem($html->javascript('noty-2.1.0/js/noty/themes/default.js', 'site_notifications'));

			ob_start();
			Loader::packageElement('noty_script', 'site_notifications', array('optionObjs' => $nOptions));
			$script = ob_get_contents();
			ob_end_clean();

			$v->addFooterItem($html->javascript('jquery-cookie/jquery.cookie.js', 'site_notifications'));
			$v->addFooterItem($script);
		}
	}
}