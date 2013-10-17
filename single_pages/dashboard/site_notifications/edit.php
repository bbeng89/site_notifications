<?php 
defined('C5_EXECUTE') or die(_("Access Denied"));
$dbh = Loader::helper('concrete/dashboard');
$nh = Loader::helper('navigation');
$fh = Loader::helper('form');
$noty = Loader::helper('noty', 'site_notifications');
$dth = Loader::helper('form/date_time');

echo $dbh->getDashboardPaneHeaderWrapper(t('Add New Notification'), t('Add a new site notification'), false, false);
?>

<form class="form-horizontal" action="<?php echo $this->action('save'); ?>" method="POST">
	<div class="ccm-pane-body">
		<legend><?php echo t('Notification Details'); ?></legend>
		
		<div class="control-group">
			<label class="control-label" for="notificationText"><?php echo t("Notification Text"); ?></label>
			<div class="controls">
				<?php echo $fh->textarea("notificationText", $notificationText, array("class" => "span8", "rows" => "2")); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="layout"><?php echo t("Position"); ?></label>
			<div class="controls">
				<?php echo $fh->select('layout', $noty->getLayouts(), $layout, array("class" => "span4")); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="notificationType"><?php echo t("Type"); ?></label>
			<div class="controls">
				<?php echo $fh->select('notificationType', $noty->getTypes(), $notificationType, array("class" => "span4")); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="delay"><?php echo t("Delay (milliseconds)"); ?></label>
			<div class="controls">
				<?php echo $fh->text('delay', $delay, array("class" => "span1")); ?>
				<span class="help-inline"><?php echo t('The amount of time to show the notification before it disappears. Set to 0 for always visible.') ?></span>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo t("Modal"); ?>
			</div>
			<div class="controls">
				<label class="checkbox">
					<?php echo $fh->checkbox('modal', 'modal', $modal); ?>
					<span class="help-inline"><?php echo t('Display the notification in a modal') ?></span>
				</label>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="closeWith"><?php echo t("Close With"); ?></label>
			<div class="controls">
				<?php echo $fh->select('closeWith', $noty->getCloseWithOptions(), $closeWith, array("class" => "span4")); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="expires"><?php echo t("Expires"); ?></label>
			<div class="controls">
				<?php echo $dth->datetime('expires', $expires, false, true); ?>
			</div>
		</div>
		<?php echo $fh->hidden('notificationID', $notificationID); ?>
		
	</div>
	<div class="ccm-pane-footer">
		<a class="btn pull-left" href="<?php echo $nh->getLinkToCollection(Page::getByPath('/dashboard')); ?>"><?php echo t('Return to Dashboard'); ?></a>
		<button type="submit" class="btn btn-primary pull-right"><?php echo t('Save Notification'); ?></button>
	</div>
</form>
<?php echo $dbh->getDashboardPaneFooterWrapper(false);?>