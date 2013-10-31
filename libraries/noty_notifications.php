<?php
defined('C5_EXECUTE') or die(_("Access Denied"));

/**
 * @author 		Blake Bengtson (bbeng89)
 * @copyright  	Copyright 2013 Blake Bengtson
 * @license     concrete5.org marketplace license
 */

class NotyNotifications {

	public function addNotifications($v){
		Loader::model('notification', 'site_notifications');
		$notifications = Notification::getAll();

		if(count($notifications) != 0){
			$html = Loader::helper('html');
			$json = Loader::helper('json');
			$nOptions = array();
			$nLayouts = array();
			$dismissed = !empty($_COOKIE['dismissed_site_notifications']) ? $json->decode($_COOKIE['dismissed_site_notifications']) : array();
			$u = new User();

			//load the noty js first
			$v->addFooterItem($html->javascript('noty-2.1.0/js/noty/jquery.noty.js', 'site_notifications'));

			//create options objects for each notification
			foreach($notifications as $n){

				//if this notification doesn't meet the criteria to be visible, skip it
				if(!NotyNotifications::isVisible($n, $u, $dismissed)){
					continue;
				}
				//these must match up with noty options (http://needim.github.io/noty/#options)
				$settings = new StdClass();
				$settings->notificationID = $n->notificationID;
				$settings->text = $n->notificationText;
				$settings->layout = $n->layout;
				$settings->theme = 'defaultTheme';
				$settings->type = $n->notificationType;
				$settings->dismissQueue = true;
				$settings->timeout = empty($n->delay)? false : $n->delay;
				$settings->modal = $n->modal == 1 ? true : false;
				$settings->closeWith = array($n->closeWith);
				$nOptions[] = $settings;

				//each layout has a separate js file - add unique layouts to load later
				if(!in_array($n->layout, $nLayouts)){
					$nLayouts[] = $n->layout;
				}
			}

			$expiration = NotyNotifications::calculateCookieExpiration($notifications);
			
			//load the correct js layout file for each layout
			foreach($nLayouts as $layout){
				$v->addFooterItem($html->javascript('noty-2.1.0/js/noty/layouts/' . $layout . '.js', 'site_notifications'));
			}

			//load the theme js
			$v->addFooterItem($html->javascript('noty-2.1.0/js/noty/themes/default.js', 'site_notifications'));

			//get the content of the noty_script element
			ob_start();
			Loader::packageElement('noty_script', 'site_notifications', array('optionObjs' => $nOptions, 'expiration' => $expiration));
			$script = ob_get_contents();
			ob_end_clean();

			$v->addFooterItem($html->javascript('jquery-cookie/jquery.cookie.js', 'site_notifications'));
			$v->addFooterItem($script);
		}
	}

	//helper function - checks if the specified notification should be displayed or not
	//$n - notifaction array from database
	//$u - current user
	//$dismissed - array of NotificationIDs that have been dismissed (pulled from cookies)
	//returns - bool
	private function isVisible($n, $u, $dismissed){
		//first make sure the notification is enabled
		if(!(bool)$n->enabled){
			return false;
		}
		//if its enabled, check if its been dismissed
		if(in_array($n->notificationID, $dismissed)){
			return false;
		}
		//check if the notification has expired - if it has immediately return false
		$tz = new DateTimeZone($n->expiresTZ);
		$expires = new DateTime($n->expires, $tz);
		$now = new DateTime('now', $tz);
		if($expires <= $now){
			return false;
		}
		//if its enabled, active, and hasn't been dismissed, check if it applies to this user
		$groups = $n->selectedGroups;
		$inGroup = false;
		if(in_array('A', $groups)){
			$inGroup = true;
		}
		else{
			foreach($groups as $gid){
				//check if guest was selected (apparently inGroup doesn't work for guest or registered user)
				if($gid == 1 && !$u->isRegistered()){
					$inGroup = true;
					break;
				}
				//check if regeistered user was selected
				else if($gid == 2 && $u->isRegistered()){
					$inGroup = true;
					break;
				}
				//check any other group
				else if($u->inGroup(Group::getByID($gid))){
					$inGroup = true;
					break;
				}
			}
		}
		return $inGroup;
	}

	//finds the latest expiration date of the notifications, then returns the number of days between now and then
	private function calculateCookieExpiration($notifications){
		$expiration = null;
		foreach($notifications as $n){
			$tz = new DateTimeZone($n->expiresTZ);
			$now = new DateTime('now', $tz);
			$exp = $n->getExpiresObj();
			$datediff = $exp->diff($now)->format("%a");
			if(empty($expiration) || $datediff > $expiration){
				$expiration = $datediff;
			}
		}
		return $expiration + 2;
	}
}