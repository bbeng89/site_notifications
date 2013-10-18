<?php defined('C5_EXECUTE') or die(_("Access Denied"));
$json = Loader::helper('json');
$counter = 1;
?>

<script type="text/javascript">
	var cookie = $.parseJSON($.cookie('dismissed_site_notifications'));
	if(!cookie){
		cookie = new Array();
	}

	<?php foreach($optionObjs as $options): ?>
		var options = $.parseJSON('<?php echo $json->encode($options);?>');
		options.callback = {
			afterClose: function(){
				var nid = <?php echo $options->notificationID;?>;
				cookie.push(nid);
				//TODO: calculate expiration based on when the notification is set to expire
				$.cookie('dismissed_site_notifications', JSON.stringify(cookie), { path: '/', expires: <?php echo $expirations[$options->notificationID];?> });
			}
		}
		var noty<?php echo $counter;?> = noty(options);
	<?php endforeach; ?>
</script>