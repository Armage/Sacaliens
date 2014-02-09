<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
	<title>:: <?= $tTitle;?> ::</title>
	<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
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
	    $("#tags").autocomplete('<?= $appUrl ;?>/search/tags', {
		  width: 300,
		  matchContains: true,
		  multiple: true,
		  multipleSeparator: " "
		}) ;
	  }) ;
	</script>
</head>

<body onLoad="toTop();">

<div id="container" style="width:730px;">

<h1>:: <?= $tTitle;?> ::</h1>

<div id="commands">
<div>
  <h3><?= $tAddURL ;?></h3>
  <form name="formadd" action="<?= $appUrl."/edit/url" ;?>" method="post">
  	<input type="hidden" name="noui" value="1" />
  	<?= $tLinkURL ;?> : <input type="text" name="url" size="40" value="<?= $url ;?>"/> <br/>
	<?= $tLinkTitle ;?> : <input type="text" name="title" size="60" value="<?= $title ;?>"/> <br/>
	<?= $tLinkDescription ;?>
	<textarea name="description" cols="80" rows = "3"><?= $description ;?></textarea><br />
	<?= $tTags ;?> : <input type="text" id="tags" name="tags" size="40" value="<?= $tags ;?>"/><br />
	<input type="submit" value="<?= $tSend ;?>" />
  </form>
</div>

<hr>

<div id="hints">
	<?php
	if (!empty($hintSame) and is_array($hintSame)) {
	?>
	<div>
	<?= $tSameLink; ?>
	<ul>
	<?php foreach($hintSame as $url): ?>
		<li>
			<div class="url">
				<a href="<?= $url['url']; ?>" title="<?= $url['description']; ?>"><?= $url['title']; ?></a> (<?= $url['datecreate']; ?>)<br />
				<?= $url['tags']; ?>
			</div>
		</li>
	<?php endforeach;
	}
	?>
	</ul>
	</div>
	<?php
	if (!empty($hintSimilar) and is_array($hintSimilar)) {
	?>
	<div>
	<?= $tSimilarLink; ?>
	<ul>
	<?php foreach($hintSimilar as $url): ?>
		<li>
			<div class="url">
				<a href="<?= $url['url']; ?>" title="<?= $url['description']; ?>"><?= $url['title']; ?></a> (<?= $url['datecreate']; ?>)<br />
				<?= $url['tags']; ?>
			</div>
		</li>
	<?php endforeach;
	}
	?>
	</ul>
	</div>
</div>

</div>

<div class="fin"><?= $tEnd ;?></div>

</div> <!-- end container -->

</body>

</html>
