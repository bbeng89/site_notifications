<?php 
defined('C5_EXECUTE') or die(_("Access Denied"));

/**
 * @author 		Blake Bengtson (bbeng89)
 * @copyright  	Copyright 2013 Blake Bengtson
 * @license     concrete5.org marketplace license
 */
 
$dbh = Loader::helper('concrete/dashboard');
$nh = Loader::helper('navigation');
$fh = Loader::helper('form');
$noty = Loader::helper('noty', 'site_notifications');
$dth = Loader::helper('form/date_time');
$ps = Loader::helper('form/page_selector');

echo $dbh->getDashboardPaneHeaderWrapper(t('Add New Notification'), t('Add a new site notification'), false, false);
?>

<form class="form-horizontal" action="<?php echo $this->action('save'); ?>" method="POST">
	<div class="ccm-pane-body">
		<legend><?php echo t('Notification Details'); ?></legend>
		<div class="control-group">
			<div class="control-label">
				<?php echo t("Enabled"); ?>
			</div>
			<div class="controls">
				<label class="checkbox">
					<?php echo $fh->checkbox('enabled', 'enabled', $notification->enabled); ?>
					<span class="help-inline"><?php echo t('Turn this notification on or off.') ?></span>
				</label>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="notificationText"><?php echo t("Notification Text"); ?></label>
			<div class="controls">
				<?php echo $fh->textarea("notificationText", $notification->notificationText, array("class" => "span8", "rows" => "2")); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="layout"><?php echo t("Position"); ?></label>
			<div class="controls">
				<?php echo $fh->select('layout', $noty->getLayouts(), $notification->layout, array("class" => "span4")); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="notificationType"><?php echo t("Type"); ?></label>
			<div class="controls">
				<?php echo $fh->select('notificationType', $noty->getTypes(), $notification->notificationType, array("class" => "span4")); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="delay"><?php echo t("Delay (milliseconds)"); ?></label>
			<div class="controls">
				<?php echo $fh->text('delay', $notification->delay, array("class" => "span1")); ?>
				<span class="help-inline"><?php echo t('The amount of time to show the notification before it disappears. Set to 0 for always visible.') ?></span>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo t("Modal"); ?>
			</div>
			<div class="controls">
				<label class="checkbox">
					<?php echo $fh->checkbox('modal', 'modal', $notification->modal); ?>
					<span class="help-inline"><?php echo t('Display the notification in a modal (forces user to acknowledge)') ?></span>
				</label>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="closeWith"><?php echo t("Close With"); ?></label>
			<div class="controls">
				<?php echo $fh->select('closeWith', $noty->getCloseWithOptions(), $notification->closeWith, array("class" => "span4")); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="expires"><?php echo t("Expires"); ?></label>
			<div class="controls">
				<?php echo $dth->datetime('expires', $notification->expires, false, true); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="expires"><?php echo t("Expires Timezone"); ?></label>
			<div class="controls">
				<?php echo $fh->select('expiresTZ', $timezones, $notification->expiresTZ, array("class" => "span4")); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo t("Show To"); ?></label>
			<div class="controls">
				<?php foreach($groups as $gid => $name): ?>
					<p><?php echo $fh->checkbox('groups[]', $gid, in_array($gid, $notification->selectedGroups), null) . ' ' . $name; ?></p>
				<?php endforeach;?>
			</div>
		</div>
		<?php if(!empty($notification->notificationID)): ?>
			<div class="control-group">
				<hr/>
				<div class="controls">
					<p class="muted">
						<?php echo t('Since you are updating this notification, it is possible some users have already acknowledged it. 
						Would you like users that have already seen this notification to be notified again?') ?>
					</p>
					<label class="checkbox">
						<?php echo t("Renotify Users"); ?>
						<?php echo $fh->checkbox('renotify', 'renotify', false); ?>
					</label>
				</div>
			</div>
		<?php endif; ?>
		<?php echo $vth->output('save_notification'); ?>
		<?php echo $fh->hidden('notificationID', $notification->notificationID); ?>
		
	</div>
	<div class="ccm-pane-footer">
		<a class="btn pull-left" href="<?php echo $nh->getLinkToCollection(Page::getByPath('/dashboard')); ?>"><?php echo t('Return to Dashboard'); ?></a>
		<button type="submit" class="btn btn-primary pull-right"><?php echo t('Save Notification'); ?></button>
	</div>
</form>
<?php echo $dbh->getDashboardPaneFooterWrapper(false);?>

<script>
	$(document).ready(function(){
		//initial checks
		checkCheckboxes();
		//event handlers
		$('#groups_A').change(checkCheckboxes);
	});
		//if "All Groups" is checked hide all other group CBs - otherwise show all others
	function checkCheckboxes(){
		var checkboxes = $('input[type="checkbox"][name^="groups"][id!=groups_A]');
		var allGroupsCb = $('#groups_A');

		if(allGroupsCb.is(':checked')){
			checkboxes.each(function(){
				$(this).removeAttr('checked');
				$(this).attr('disabled', 'disabled');
			});
		}
		else{
			checkboxes.each(function(){
				$(this).removeAttr('disabled');
			});
		}
	}
</script>