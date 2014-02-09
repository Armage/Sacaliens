<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
	<title>:: <?= $tTitle;?> ::</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="icon" type="image/png" href="img/icon-bookin.png">
	<link rel="stylesheet" type="text/css" href="<?= $resourcesUrl ;?>/css/style.css" title="default">
	<link rel="alternate stylesheet" type="text/css" href="<?= $resourcesUrl ;?>/css/steampunk.css" title="steampunk">
	<link rel="stylesheet" type="text/css" href="<?= $resourcesUrl ;?>/libjs/jquery.autocomplete.css">
	<meta name="window-target" content="_top">
	<script type="text/javascript" src="<?= $resourcesUrl ;?>/libjs/jquery.js"></script>
	<script type="text/javascript" src="<?= $resourcesUrl ;?>/libjs/jquery.autocomplete.js"></script>
	<script type="text/javascript">
	  function toTop() {
	    if (top.location != self.document.location) {
	      top.location = self.document.location;
	    }
	  }
	  $(document).ready(function() {
	    $(".ftag").autocomplete('<?= $appUrl ;?>/search/tags', {
		  width: 300,
		  matchContains: true,
		}) ;
	  }) ;
	</script>
</head>

<body onLoad="toTop();">

<div id="container">

<h1>:: <?= $tTitle;?> ::</h1>

<div id="commands">
  <a href="<?= $appUrl ;?>/urls/"><?= $tBack ;?></a>
</div>

<?php if(isset($msg) and $msg != ''): ?>
<div id="error">
  <?= $msg ; ?>
</div>
<?php endif; ?>

<div class="tagaction">
  <form name="editform" action="<?= $appUrl ;?>/tags/" method="post">
  <?= $tTagChoosen ;?> : <input class="ftag" name="old_label" type="text"> <?= $tTagRenameAction ;?> <input name="new_label" type="text">
  <input type="submit" name="tagedit" value="<?= $tSend ;?>">
  </form>
</div>

<div class="tagaction">
  <form name="editform" action="<?= $appUrl ;?>/tags/" method="post">
  <?= $tTag1 ;?> : <input class="ftag" name="tag1" type="text">, <?= $tTag2 ;?> : <input class="ftag" name="tag2" type="text">
  <?= $tTagMergeAction ;?> <input name="newtagfusion" type="text">
  <input type="submit" name="tagfusion" value="<?= $tSend ;?>">
  </form>
</div>

<div id="tagcloud">
<?php
if (is_array($tags) and count($tags) > 0) {
	foreach($tags as $tag) {
?>
<a href="<?= $appUrl ;?>/urls/<?= $tag['label'] ;?>" style="font-size: <?= $tag['size'] ;?>em" title="<?= $tag['label'].' : '.$tag['nb'];?>"><?= $tag['label'] ;?></a>
<?php
	}
}
else {
?><?= $tTagNoTag ;?><?php
}
?>
</div>

<div class="fin">
  <?= $nbTags ;?><?= $tTagFound ;?>
</div>

</div> <!-- end container -->

</body>

</html>
