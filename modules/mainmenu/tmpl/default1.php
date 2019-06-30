<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
?>

<nav class="navbar navbar-default" role="navigation">

  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Espandi barra di navigazione</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="index.html">Logo</a>
  </div>
  
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav " >
      <li class="active"><a href="#">Link 1</a></li>
      <li><a href="#">Link 2</a></li>
      <li><a href="#">Link 3</a></li>
      <!-- MENU A DISCESA -->
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        Menu a discesa <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="#">Sub-Link 1</a></li>
          <li><a href="#">Sub-Link 2</a></li>
          <li><a href="#">Sub-Link 3</a></li>
          <li><a href="#">Sub-Link 4</a></li>
          <li><a href="#">Sub-Link 5</a></li>
        </ul>
      </li>
      <!-- MENU A DISCESA -->
    </ul>
    <p class="navbar-text navbar-right">creato da Alessandra</p>
  </div>
  
</nav>
