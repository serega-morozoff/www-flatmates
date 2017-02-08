<?php if($user->uid != 0){?>
	<div id="node-form" class="container">
		<?php
			$block = module_invoke('formblock', 'block_view', 'property');
			print render($block['content']);		
		?>
	</div>
<?php }else{ ?>
<?php
	header("Location: /user");
	/*$link = l("Authorize", "user");
	$register = t("Please "); $register.=$link; $register.= t(" to find Your accomodation");
	$output = "<div id='please-authorize' class='center'>$register</div>";*/
?>
<?php //print $output;?>
<?php } ?>
