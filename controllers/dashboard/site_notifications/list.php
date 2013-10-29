<?php 
defined('C5_EXECUTE') or die(_("Access Denied"));

/**
 * @author 		Blake Bengtson (bbeng89)
 * @copyright  	Copyright 2013 Blake Bengtson
 * @license     concrete5.org marketplace license
 */

class DashboardSiteNotificationsListController extends Controller {

	public function on_start(){
		$this->set('vth', Loader::helper('validation/token'));
	}

	public function view(){
		Loader::model('notification', 'site_notifications');
		$this->set('notifications', Notification::getAll());

		if($this->get('s') == 'a'){
			$this->set('message', t('New notification added'));
		}
		else if($this->get('s') == 'u'){
			$this->set('message', t('Notification updated'));
		}
		else if($this->get('s') == 'd'){
			$this->set('message', t('Notification deleted'));
		}
	}

	public function delete($id){
		$vth = Loader::helper('validation/token');
		$s = '';
		if($this->isPost() && $vth->validate('delete_notification')){
			$db = Loader::db();
			$db->Execute('DELETE FROM SiteNotifications WHERE notificationID = ?', array($id));
			$s = '?s=d';
		}
		$this->redirect('/dashboard/site_notifications/list' . $s);
	}
}