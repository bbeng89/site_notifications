<?php 
defined('C5_EXECUTE') or die(_("Access Denied"));

class DashboardSiteNotificationsController extends Controller {

	public function view(){
		$this->redirect('/dashboard/site_notifications/list');
	}
	
}