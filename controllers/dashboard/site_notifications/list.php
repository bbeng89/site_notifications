<?php 
defined('C5_EXECUTE') or die(_("Access Denied"));

/**
 * @author 		Blake Bengtson (bbeng89)
 * @copyright  	Copyright 2013 Blake Bengtson
 * @license     concrete5.org marketplace license
 */

class DashboardSiteNotificationsListController extends Controller {

	public function view(){
		$db = Loader::db();
		$notifications = $db->GetAll('SELECT * FROM SiteNotifications');
		$this->set('notifications', $notifications);
	}

	public function delete($id){
		if($this->isPost()){
			$db = Loader::db();
			$db->Execute('DELETE FROM SiteNotifications WHERE notificationID = ?', array($id));
		}
		$this->redirect('/dashboard/site_notifications/list');
	}
}