<?php 
defined('C5_EXECUTE') or die(_("Access Denied"));

/**
 * @author 		Blake Bengtson (bbeng89)
 * @copyright  	Copyright 2013 Blake Bengtson
 * @license     concrete5.org marketplace license
 */
 
$dbh = Loader::helper('concrete/dashboard');
$noty = Loader::helper('noty', 'site_notifications');
$cth = Loader::helper('custom_text', 'site_notifications');
$layouts = $noty->getLayouts();
$types = $noty->getTypes();

echo $dbh->getDashboardPaneHeaderWrapper(t('All Notifications'), t('List of all site notifications'), false, false);
?>

<div class="ccm-pane-body">
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th><?php echo t('Last Modified'); ?></th>
				<th><?php echo t('Notification'); ?></th>
				<th><?php echo t('Position'); ?></th>
				<th><?php echo t('Type'); ?></th>
				<th><?php echo t('Enabled'); ?></th>
				<th><?php echo t('Expires'); ?></th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php if(empty($notifications)): ?>
			<tr>
				<td colspan="8"><?php echo t('No notifications have been added');?></td>
			</tr>
		<?php else: ?>
			<?php foreach($notifications as $n): ?>
				<tr>
					<td><?php echo date('n/j/Y g:i a', strtotime($n->lastModified)); ?></td>
					<td><?php echo $cth->shorten($n->notificationText, 50); ?></td>
					<td><?php echo $layouts[$n->layout]; ?></td>
					<td><?php echo $types[$n->notificationType]; ?></td>
					<td><?php echo $n->enabled ? t('Yes') : t('No'); ?></td>
					<td><?php echo date('n/j/Y g:i a', strtotime($n->expires)); ?></td>
					<td><a href="<?php echo $this->url('/dashboard/site_notifications/edit?nid='. $n->notificationID); ?>" class="btn"><?php echo t('Edit'); ?></td>
					<td>
						<form action="<?php echo $this->action('delete', $n->notificationID); ?>" method="POST" class="deleteForm" style="margin:0;">
							<button type="submit" class="btn btn-danger deleteBtn"><?php echo t('Delete'); ?></button>
							<?php echo $vth->output('delete_notification'); ?>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
	<div class="clearfix">
		<p class="pull-left"><?php echo count($notifications) . ' ' . $cth->pluralize(count($notifications), t('Notification'), t('Notifications')); ?></p>
		<a href="<?php echo $this->url('/dashboard/site_notifications/edit'); ?>" class="pull-right btn btn-primary"><?php echo t('Add New Notification'); ?></a>
	</div>
</div>
<div class="ccm-pane-footer">

</div>
<?php echo $dbh->getDashboardPaneFooterWrapper(false);?>

<script>
	$('.deleteForm').submit(function(){
		return confirm('<?php echo t("Are you sure you want to delete this notification?");?>');
	});
</script>