<?php
if(!defined('INSNOW'))
{
	die('Nice try...');
}
?>	<h1><?php echo api()->context['error_title']; ?></h1>
	<p><?php echo api()->context['error_message']; ?></p>