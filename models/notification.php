<?php
defined('C5_EXECUTE') or die(_('Access Denied'));

class Notification{
	//db fields
	public $notificationID;
	public $enabled;
	public $dateAdded;
	public $lastModified;
	public $expires;
	public $expiresTZ;
	public $notificationText;
	public $layout;
	public $notificationType;
	public $delay;
	public $modal;
	public $closeWith;
	public $groups;

	//non-db fields
	public $selectedGroups;

	public function __construct(){
		$this->modal = false;
		$this->delay = 0;
		$this->selectedGroups = array('A');
		$this->enabled = false;

		$u = new User();
		$utz = $u->getUserTimeZone();
		if(defined('APP_TIMEZONE')){
			$this->expiresTZ = APP_TIMEZONE;
		}
		else if(!empty($utz)){
			$this->expiresTZ = $utz;
		}
		else{
			$this->expiresTZ = 'America/Chicago';
		}
	}

	public function save(){
		$db = Loader::db();
		$vars = array($this->enabled, $this->expires, $this->expiresTZ, $this->notificationText, $this->layout, $this->notificationType, $this->delay, $this->modal, $this->closeWith, $this->groups);
		
		if(empty($this->notificationID)){
			$query = 'INSERT INTO 
				SiteNotifications (dateAdded, lastModified, enabled, expires, expiresTZ, notificationText, layout, notificationType, delay, modal, closeWith, groups) 
				VALUES(NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		}
		else{
			$vars[] = $this->notificationID;
			$query = 'UPDATE SiteNotifications 
				SET lastModified=NOW(), enabled=?, expires=?, expiresTZ=?, notificationText=?, layout=?, notificationType=?, delay=?, modal=?, closeWith=?, groups=?  
				WHERE notificationID=?';
		}

		$db->Execute($query, $vars);
	}

	public function delete(){
		$db = Loader::db();
		$db->Execute('DELETE FROM SiteNotifications WHERE notificationID = ?', array($this->notificationID));
	}

	public static function getByID($id){
		$db = Loader::db();
		$json = Loader::helper('json');

		$n = $db->GetRow('SELECT * FROM SiteNotifications WHERE notificationID=?', array($id));
		$notification = new Notification();

		$notification->notificationID = $id;
		$notification->dateAdded = $n['dateAdded'];
		$notification->lastModified = $n['lastModified'];
		$notification->notificationText = $n['notificationText'];
		$notification->layout = $n['layout'];
		$notification->notificationType = $n['notificationType'];
		$notification->delay = $n['delay'];
		$notification->modal = $n['modal'];
		$notification->closeWith = $n['closeWith'];
		$notification->expires = $n['expires'];
		$notification->expiresTZ = $n['expiresTZ'];
		$notification->notificationID = $n['notificationID'];
		$notification->selectedGroups = $json->decode($n['groups']);
		$notification->enabled = $n['enabled'];

		return $notification;
	}

	public static function getAll(){
		$db = Loader::db();
		$json = Loader::helper('json');
		$notifications = array();
		$notificationsArr = $db->GetAll('SELECT * FROM SiteNotifications ORDER BY lastModified DESC');
		foreach($notificationsArr as $n){
			$no = new Notification();
			$no->notificationID = $n['notificationID'];
			$no->dateAdded = $n['dateAdded'];
			$no->lastModified = $n['lastModified'];
			$no->notificationText = $n['notificationText'];
			$no->layout = $n['layout'];
			$no->notificationType = $n['notificationType'];
			$no->delay = $n['delay'];
			$no->modal = $n['modal'];
			$no->closeWith = $n['closeWith'];
			$no->expires = $n['expires'];
			$no->expiresTZ = $n['expiresTZ'];
			$no->notificationID = $n['notificationID'];
			$no->selectedGroups = $json->decode($n['groups']);
			$no->enabled = $n['enabled'];
			$notifications[] = $no;
		}
		return $notifications;
	}
}