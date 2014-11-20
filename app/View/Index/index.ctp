<?php if ($authenticated) {?>
<form id="main-form" action="<?php echo Router::url("/api/createlist"); ?>" method="post" target="progress">
<input type="hidden" name="user_id" value="<?php echo $user['User']['user_id']; ?>">
<?php } else {?>
<form id="main-form" action="<?php echo Router::url("/api/authorize"); ?>" method="post">
<?php }?>
	<div class="form-container">
		<div class="input-container">
			<input type="text" id="username" name="username" placeholder="@influencer" value="<?php echo (isset($viewInfluencer))? "@".$viewInfluencer : "";?>">
			<img id="to-settings" data-active="0" class="settings-icon" src="<?php echo Router::url("/img/settings.png"); ?>" />
		</div>
		<input type="hidden" name="visibility" id="form-visibility" value="<?php echo (isset($visibility)) ? $visibility : 1; ?>">
		<input type="hidden" name="optimization" id="form-optimization" value="<?php echo (isset($optimization)) ? $optimization: 1; ?>">
		<input type="submit" style="display:none;">
		<a id="to-send-form" class="btn main" data-to-authorize="<?php echo ($authenticated) ? "0" : "1"; ?>"><?php echo __("Create list!"); ?></a>

		<div id="settings">
			<div class="settings-option visibility">
				<div class="supra-option left active" data-value="1">
					<span class="option"><?php echo __("Public List"); ?></span>
					<span class="info"><?php echo __("The list will be available to the public"); ?></span>
				</div>
				<div class="supra-option right" data-value="0">
					<span class="option"><?php echo __("Private List"); ?></span>
					<span class="info"><?php echo __("The list will be available just for you"); ?></span>
				</div>
				<div class="clear"></div>
			</div>
			<div class="settings-option optimization">
				<div class="supra-option left active" data-value="1">
					<span class="option"><?php echo __("Complete"); ?></span>
					<span class="info"><?php echo __("All influencers will be added. Slower."); ?></span>
				</div>
				<div class="supra-option right" data-value="0">
					<span class="option"><?php echo __("Quick"); ?></span>
					<span class="info"><?php echo __("Not all influencers may be added. Quicker."); ?></span>
				</div>
				<div class="clear"></div>
			</div>

		</div>
		<div class="disabler"></div>
	</div>
</form>
<iframe id="progress" name="progress"></iframe>
<div id="progress-render">
	<span id="progress-message" class="">
		<?php if ($authenticated) {
			echo __("*Lists will have a max. number of 1000 users");
		} else {
			echo __("*You'll be redirected to sign in with Twitter<br>[No automatic tweets nor similar shit, promised]");
		} ?>
	</span>
	<div id="progress-bar"></div>
	<div class="clear"></div>
</div>

<div class="influencers">
	<h2><?php echo __("Need inspiration? Who influences some super influencers?"); ?></h2>
	<?php foreach ($influencers as $influencer) {?>
		<div class="influencer" data-screen-name="<?php echo $influencer['Influencer']['screen_name']; ?>">
			<div class="header">
				<span class="supra-title"><?php echo __("Who influences");?></span>
				<span class="title"><?php echo "@".$influencer['Influencer']['screen_name']?></span>
			</div>
			<div class="body">
				<span class="image" style="background-image:url(<?php echo str_replace("_normal.",".",$influencer['Influencer']['image']);?>)" alt="<?php echo __("profile image on Twitter of ") . $influencer['Influencer']['name']; ?>"></span>
			</div>
			<div class="footer">
				<a class="btn to-select-influencer"><?php echo __("Create list!"); ?></a>
			</div>
		</div>
	<?php }?>
	<div class="clear"></div>
</div>

<script>
$(document).ready(function() {
	main();
	<?php if ($authenticated && isset($username)) {?>
		$("#username").val('<?php echo $username; ?>');
		$("#main-form").submit();
	<?php }?>
});
</script>