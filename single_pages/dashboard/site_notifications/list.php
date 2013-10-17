<?php 
defined('C5_EXECUTE') or die(_("Access Denied"));
$dbh = Loader::helper('concrete/dashboard');
$noty = Loader::helper('noty', 'site_notifications');
$th = Loader::helper('text');

echo $dbh->getDashboardPaneHeaderWrapper(t('All Notifications'), t('List of all site notifications'), false, false);
?>

<div class="ccm-pane-body">
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th><?php echo t('Added'); ?></th>
				<th><?php echo t('Notification'); ?></th>
				<th><?php echo t('Position'); ?></th>
				<th><?php echo t('Type'); ?></th>
				<th><?php echo t('Expires'); ?></th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($notifications as $n): ?>
			<tr>
				<td><?php echo $n['dateAdded']; ?></td>
				<td><?php echo $th->shorten($n['notificationText'], 50); ?></td>
				<td><?php echo $noty->getLayouts()[$n['layout']]; ?></td>
				<td><?php echo $noty->getTypes()[$n['notificationType']]; ?></td>
				<td><?php echo $n['expires']; ?></td>
				<td><a href="<?php echo $this->url('/dashboard/site_notifications/edit?nid='. $n['notificationID']); ?>" class="btn"><?php echo t('Edit'); ?></td>
				<td><a href="#" class="btn btn-danger"><?php echo t('Delete'); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<div class="ccm-pane-footer">

</div>
<?php echo $dbh->getDashboardPaneFooterWrapper(false);?>