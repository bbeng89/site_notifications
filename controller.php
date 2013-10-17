<?php
defined('C5_EXECUTE') or die(_("Access Denied"));

class SiteNotificationsPackage extends Package {

	protected $pkgHandle = 'site_notifications';
	protected $appVersionRequired = '5.6.0';
	protected $pkgVersion = '0.9.0';

	public function getPackageDescription(){
		return t('Allows you to add notification alerts to your website');
	}

	public function getPackageName(){
		return t('Site Notifications');
	}

	public function on_start() {
        Events::extend("on_before_render", "Notification", "addNotifications", DIRNAME_PACKAGES.'/'.$this->pkgHandle.'/models/notification.php');
    }

	public function install($post = array()){
		$pkg = parent::install();
		$this->installSinglePages($pkg);
	}

	public function uninstall(){
		parent::uninstall();
		//clean up
		$db = Loader::db();
		$db->Execute('DROP TABLE IF EXISTS SiteNotifications');
	}

	public function installSinglePages($pkg){
		$dashboardIcons = array();

		//base site notifications page (just redirects to list)
		$path = '/dashboard/site_notifications';
		$cID = Page::getByPath($path)->getCollectionID();
		if (intval($cID) > 0 && $cID !== 1) {
			// the single page already exists, so we want to update it to use our package elements
			Loader::db()->execute('update Pages set pkgID = ? where cID = ?', array($pkg->pkgID, $cID));
		} else {
			// it doesn't exist, so now we add it
			$p = SinglePage::add($path, $pkg);
			if (is_object($p) && $p->isError() !== false) {
				$p->update(array('cName' => t('Site Notifications')));
			}
		}
		$dashboardIcons[$path] = "icon-exclamation-sign";

		//listing page - lists all site notifications
		$path = '/dashboard/site_notifications/list';
		$cID = Page::getByPath($path)->getCollectionID();
		if (intval($cID) > 0 && $cID !== 1) {
			// the single page already exists, so we want to update it to use our package elements
			Loader::db()->execute('update Pages set pkgID = ? where cID = ?', array($pkg->pkgID, $cID));
		} else {
			// it doesn't exist, so now we add it
			$p = SinglePage::add($path, $pkg);
			if (is_object($p) && $p->isError() !== false) {
				$p->update(array('cName' => t('View All Site Notifications')));
			}
		}
		$dashboardIcons[$path] = "icon-list";

		//add page - here you can add a new notification
		$path = '/dashboard/site_notifications/edit';
		$cID = Page::getByPath($path)->getCollectionID();
		if (intval($cID) > 0 && $cID !== 1) {
			// the single page already exists, so we want to update it to use our package elements
			Loader::db()->execute('update Pages set pkgID = ? where cID = ?', array($pkg->pkgID, $cID));
		} else {
			// it doesn't exist, so now we add it
			$p = SinglePage::add($path, $pkg);
			if (is_object($p) && $p->isError() !== false) {
				$p->update(array('cName' => t('Add New Site Notification')));
			}
		}
		$dashboardIcons[$path] = "icon-plus";

		$this->setupDashboardIcons($dashboardIcons);
	}

	private function setupDashboardIcons($iconArray) {
		$cak = CollectionAttributeKey::getByHandle('icon_dashboard');
		if (is_object($cak)) {
			foreach($iconArray as $path => $icon) {
				$sp = Page::getByPath($path);
				if (is_object($sp) && (!$sp->isError())) {
					$sp->setAttribute('icon_dashboard', $icon);
				}
			}
		}
	}
}