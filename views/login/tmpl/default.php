<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

?>

<div class="container">

	<form class="form-signin" action="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php" method="post">
		<h2 class="form-signin-heading">Effettua l'accesso</h2>
		<input type="text" name="username" class="input-block-level" placeholder="Nome utente">
		<input type="password" name="password" class="input-block-level" placeholder="Password">
		<button class="btn btn-large btn-primary" type="submit">Accedi</button>
		
		<input type="hidden" name="controller" value="login" />
		<input type="hidden" name="task" value="login" />
	</form>

</div>