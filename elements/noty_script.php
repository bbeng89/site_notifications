<?php 
defined('C5_EXECUTE') or die(_("Access Denied"));

/**
 * @author 		Blake Bengtson (bbeng89)
 * @copyright  	Copyright 2013 Blake Bengtson
 * @license     concrete5.org marketplace license
 */

$json = Loader::helper('json');
$counter = 1;
?>

<script type="text/javascript">
	var cookie = $.parseJSON($.cookie('dismissed_site_notifications'));
	if(!cookie){
		cookie = new Array();
	}

	<?php foreach($optionObjs as $options): ?>
		var options = <?php echo $json->encode($options);?>;
		options.callback = {
			afterClose: function(){
				var nid = <?php echo $options->notificationID;?>;
				cookie.push(nid);
				$.cookie('dismissed_site_notifications', JSON.stringify(cookie), { path: '/', expires: <?php echo $expiration;?> });
			}
		}
		var noty<?php echo $counter;?> = noty(options);
	<?php endforeach; ?>
</script>