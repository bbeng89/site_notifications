<?php
defined('C5_EXECUTE') or die(_('Access Denied'));

/**
 * @author 		Blake Bengtson (bbeng89)
 * @copyright  	Copyright 2013 Blake Bengtson
 * @license     concrete5.org marketplace license
 */

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