<?php 
defined('C5_EXECUTE') or die(_("Access Denied"));

/**
 * @author 		Blake Bengtson (bbeng89)
 * @copyright  	Copyright 2013 Blake Bengtson
 * @license     concrete5.org marketplace license
 */

class DashboardSiteNotificationsEditController extends Controller {

	public function on_start(){
		$this->set('vth', Loader::helper('validation/token'));
		$this->set('vh', Loader::helper('concrete/validation'));
		$this->error = Loader::helper('validation/error');
		Loader::model('notification', 'site_notifications');
	}

	public function view(){
		$dh = Loader::helper('date');

		//edit
		if($this->get('nid')){
			$notification = Notification::getByID($this->get('nid'));
		}
		//insert
		else{
			$notification = new Notification();
		}
		$this->set('notification', $notification);
		$this->set('timezones', $dh->getTimezones());
		$this->set('groups', $this->getGroups());
	}

	public function save(){
		$vth = Loader::helper('validation/token');

		if($this->isPost() && $vth->validate('save_notification')){
			$notification = $this->getNotificationFromPostVars();
			if(!$this->error->has()){
				$new = empty($notification->notificationID);
				$renotify = $this->post('renotify');
				//If we need to renotify users, the easiest way is to clone the current notification, delete the original, then save the clone.
				//This gives the notification a new ID so it will not be in the dismissed list of notifications in the user's cookies
				if($renotify){
					$newNotification = clone $notification;
					$newNotification->notificationID = null;
					$notification->delete();
					$newNotification->save();
				}
				else{
					$notification->save();
				}
				$this->redirect('/dashboard/site_notifications/list?s=' . ($new ? 'a' : 'u'));
			}
			else{
				$this->set('error', $this->error);
				$this->view();
			}
		}
		else{
			$this->redirect('/dashboard/site_notifications/edit');
		}
	}

	//Returns a notification object created from post variables. Also handles validation
	private function getNotificationFromPostVars(){
		$json = Loader::helper('json');
		$dth = Loader::helper('form/date_time');
		$notification = new Notification();

		$notification->notificationID = $this->post('notificationID');

		$tmpEnabled = $this->post('enabled');
		$notification->enabled = !empty($tmpEnabled) ? 1 : 0;

		$notification->notificationText = $this->post('notificationText');
		if(empty($notification->notificationText)){
			$this->error->add(t("Please include notification text"));
		}

		$notification->layout = $this->post('layout');
		$notification->notificationType = $this->post('notificationType');

		$delay = $this->post('delay');
		if(!empty($delay)){
			$notification->delay = $delay;
		}

		$modalTmp = $this->post('modal');
		$notification->modal = !empty($modalTmp) ? 1 : 0;

		$notification->closeWith = $this->post('closeWith');

		$notification->expires = $dth->translate('expires');
		$notification->expiresTZ = $this->post('expiresTZ');

		$groupsTemp = $this->post('groups');
		if(empty($groupsTemp)){
			$this->error->add(t('Please select at least one group'));
		}
		$notification->groups = $json->encode($groupsTemp);
		
		return $notification;
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