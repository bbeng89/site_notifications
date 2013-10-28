<?php 
defined('C5_EXECUTE') or die(_("Access Denied"));

/**
 * @author 		Blake Bengtson (bbeng89)
 * @copyright  	Copyright 2013 Blake Bengtson
 * @license     concrete5.org marketplace license
 */

class DashboardSiteNotificationsEditController extends Controller {

	public function view(){
		//edit
		if($this->get('nid')){
			$db = Loader::db();
			$json = Loader::helper('json');
			$n = $db->GetRow('SELECT * FROM SiteNotifications WHERE notificationID=?', array($this->get('nid')));
			$this->set('notificationText', $n['notificationText']);
			$this->set('layout', $n['layout']);
			$this->set('notificationType', $n['notificationType']);
			$this->set('delay', $n['delay']);
			$this->set('modal', $n['modal']);
			$this->set('closeWith', $n['closeWith']);
			$this->set('expires', $n['expires']);
			$this->set('expiresTZ', $n['expiresTZ']);
			$this->set('notificationID', $n['notificationID']);
			$this->set('selectedGroups', $json->decode($n['groups']));
			$this->set('enabled', $n['enabled']);
		}
		//insert
		else{
			$this->set('modal', false);
			$this->set('delay', 0);
			$this->set('selectedGroups', array('A'));
			$this->set('enabled', false);
			$u = new User();
			$utz = $u->getUserTimeZone();
			if(defined('APP_TIMEZONE')){
				$this->set('expiresTZ', APP_TIMEZONE);
			}
			else if(!empty($utz)){
				$this->set('expiresTZ', $utz);
			}
			else{
				$this->set('expiresTZ', 'America/Chicago');
			}
		}
		$dh = Loader::helper('date');
		$this->set('timezones', $dh->getTimezones());
		$this->set('groups', $this->getGroups());
	}

	public function save(){
		if($this->isPost()){
			$vars = $this->getPostVars();
			$notificationID = $this->post('notificationID');
			if(!empty($notificationID)){
				$vars[] = $notificationID;
				$this->update($vars);
			}
			else{
				$this->add($vars);
			}
			$this->redirect('/dashboard/site_notifications/list');
		}
	}

	private function add($vars){
		$db = Loader::db();
		$db->Execute('INSERT INTO SiteNotifications (dateAdded, enabled, expires, expiresTZ, notificationText, layout, notificationType, delay, modal, closeWith, groups) VALUES(NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $vars);
	}

	private function update($vars){
		$db = Loader::db();
		$db->Execute('UPDATE SiteNotifications SET enabled=?, expires=?, expiresTZ=?, notificationText=?, layout=?, notificationType=?, delay=?, modal=?, closeWith=?, groups=? WHERE notificationID=?', $vars);
	}

	private function getPostVars(){
		$json = Loader::helper('json');
		$dth = Loader::helper('form/date_time');
		$tmpEnabled = $this->post('enabled');
		$enabled = !empty($tmpEnabled) ? 1 : 0;
		$notificationText = $this->post('notificationText');
		$layout = $this->post('layout');
		$notificationType = $this->post('notificationType');
		$delay = $this->post('delay');
		$tmp = $this->post('modal');
		$modal = !empty($tmp) ? 1 : 0;
		$closeWith = $this->post('closeWith');
		$expires = $dth->translate('expires');
		$expiresTZ = $this->post('expiresTZ');
		$groups = $json->encode($this->post('groups'));
		
		return array($enabled, $expires, $expiresTZ, $notificationText, $layout, $notificationType, $delay, $modal, $closeWith, $groups);
	}

	//helper function - returns array of user groups
	private function getGroups(){
		Loader::model('search/group');
		$gs = new GroupSearch();
		$groupArr = $gs->get(9999, 0);
		$groups = array("A" => t("All Groups"));
		foreach($groupArr as $ga){
			$groups[$ga['gID']] = $ga['gName'];
		}
		return $groups;
	}
}