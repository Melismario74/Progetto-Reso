<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

?>

<div>
	<div>
		<?php $this->displayModule('mainmenu'); ?>
	</div>
	
	<div>
		<?php $this->displaySystemMessages(); ?>
	</div>
	
	<div class="container">
		<?php $this->displayComponent(); ?>
	</div>
	
	<footer class="footer">
			MM v.<?php echo DM_APP_VERSION; ?> - ©<?php echo date('Y'); ?> 
	</footer>
</div>

<script src="<?php echo $this->getTemplateUrl(); ?>/bootstrap/js/bootstrap-transition.js"></script>
<script src="<?php echo $this->getTemplateUrl(); ?>/bootstrap/js/bootstrap-alert.js"></script>
<script src="<?php echo $this->getTemplateUrl(); ?>/bootstrap/js/bootstrap-modal.js"></script>
<script src="<?php echo $this->getTemplateUrl(); ?>/bootstrap/js/bootstrap-datepicker.js"></script>
<script src="<?php echo $this->getTemplateUrl(); ?>/bootstrap/js/bootstrap-scrollspy.js"></script>
<script src="<?php echo $this->getTemplateUrl(); ?>/bootstrap/js/bootstrap-tab.js"></script>
<script src="<?php echo $this->getTemplateUrl(); ?>/bootstrap/js/bootstrap-tooltip.js"></script>
<script src="<?php echo $this->getTemplateUrl(); ?>/bootstrap/js/bootstrap-popover.js"></script>
<script src="<?php echo $this->getTemplateUrl(); ?>/bootstrap/js/bootstrap-button.js"></script>
<script src="<?php echo $this->getTemplateUrl(); ?>/bootstrap/js/bootstrap-collapse.js"></script>
<script src="<?php echo $this->getTemplateUrl(); ?>/bootstrap/js/bootstrap-carousel.js"></script>
<script src="<?php echo $this->getTemplateUrl(); ?>/bootstrap/js/bootstrap-typeahead.js"></script>
<script>
	jQuery.ajaxSetup({
		cache: false
	});
</script>