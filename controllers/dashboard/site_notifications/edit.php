<?php 
defined('C5_EXECUTE') or die(_("Access Denied"));

class DashboardSiteNotificationsEditController extends Controller {

	public function view(){
		//edit
		if($this->get('nid')){
			$db = Loader::db();
			$n = $db->GetRow('SELECT * FROM SiteNotifications WHERE notificationID=?', array($this->get('nid')));
			$this->set('notificationText', $n['notificationText']);
			$this->set('layout', $n['layout']);
			$this->set('notificationType', $n['notificationType']);
			$this->set('delay', $n['delay']);
			$this->set('modal', $n['modal']);
			$this->set('closeWith', $n['closeWith']);
			$this->set('expires', $n['expires']);
			$this->set('notificationID', $n['notificationID']);
		}
		//insert
		else{
			$this->set('modal', false);
			$this->set('delay', 0);
		}
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
		$db->Execute('INSERT INTO SiteNotifications (dateAdded, expires, notificationText, layout, notificationType, delay, modal, closeWith) VALUES(NOW(), ?, ?, ?, ?, ?, ?, ?)', $vars);
	}

	private function update($vars){
		$db = Loader::db();
		$db->Execute('UPDATE SiteNotifications SET expires=?, notificationText=?, layout=?, notificationType=?, delay=?, modal=?, closeWith=? WHERE notificationID=?', $vars);
	}

	private function getPostVars(){
		$notificationText = $this->post('notificationText');
		$layout = $this->post('layout');
		$notificationType = $this->post('notificationType');
		$delay = $this->post('delay');
		$tmp = $this->post('modal');
		$modal = !empty($tmp) ? 1 : 0;
		$closeWith = $this->post('closeWith');

		$expiresDate = $this->post('expires_dt');
		$expiresHour = $this->post('expires_h');
		$expiresMin = $this->post('expires_m');
		$expiresAMPM = $this->post('expires_a');
		$expires = $expiresDate . ' ' . $expiresHour . ':' . $expiresMin . ' ' . $expiresAMPM;
		$expiresDt = DateTime::createFromFormat('n/j/Y h:i a', $expires);
		
		return array($expiresDt->format('Y-m-d H:i:s'), $notificationText, $layout, $notificationType, $delay, $modal, $closeWith);
	}
	
}