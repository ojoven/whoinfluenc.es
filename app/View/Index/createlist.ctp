<html>
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,300' rel='stylesheet' type='text/css'>
<?php echo $this->AssetCompress->css('styles'); ?>
</head>
<body>

<script>
function update_progress(step,total,message,type,url) {
	var percentage = (step/total)*100;
	window.parent.updateProgressBar(percentage);
	window.parent.updateProgressMessage(message,type);
	if (type=="error" || type=="success") {
		window.parent.finishCreateList(type,url);
	}
}
</script>

<?php
$twitterList = ClassRegistry::init('TwitterList');
$twitterList->createList($userId,$username,$visibility,$optimization);?>
</body>
</html>
