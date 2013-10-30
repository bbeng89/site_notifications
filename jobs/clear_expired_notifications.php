<?php
defined('C5_EXECUTE') or die(_('Access Denied'));

class ClearExpiredNotifications extends Job {

	public function getJobName(){
		return t('Clear Expired Site Notifications');
	}

	public function getJobDescription(){
		return t('Deletes all site notifications from the database that have expired');
	}

	public function run(){
		$db = Loader::db();
		$cth = Loader::helper('custom_text', 'site_notifications');

		$expiredCount = $db->GetOne('SELECT COUNT(*) FROM SiteNotifications WHERE expires < NOW()');
		if($expiredCount > 0){
			$db->Execute('DELETE FROM SiteNotifications WHERE expires < NOW()');
			return t('Job complete. ' . $expiredCount . $cth->pluralize($expiredCount, ' notification was deleted.', ' notifications were deleted.'));
		}
		return t('Job complete. No notifications were deleted.');
	}
}