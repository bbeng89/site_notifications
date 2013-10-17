<?php 
defined('C5_EXECUTE') or die(_("Access Denied"));

class DashboardSiteNotificationsListController extends Controller {

	public function view(){
		$db = Loader::db();
		$notifications = $db->GetAll('SELECT * FROM SiteNotifications');
		$this->set('notifications', $notifications);
	}
	
}