<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
	<title>:: Sacaliens ::</title>
	<link rel="icon" type="image/png" href="img/icon-bookin.png">
	<link rel="stylesheet" type="text/css" href="<?= $resourcesUrl ;?>/css/style.css">
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

<div id="container">

<h1>:: Sacaliens ::</h1>

<div id="commands">
  <a href="<?= $appUrl ;?>/urls/">Retour</a>
</div>

<div id="linkedit">
  <form name="formadd" action="<?= $action ;?>" method="post">
    Url : <input type="text" name="url" size="40" value="<?= $url ;?>"/> <br/>
	Titre : <input type="text" name="title" size="60" value="<?= $title ;?>"/> <br/>
	Description
	<textarea name="description" cols="80" rows = "3"><?= $description ;?></textarea><br />
	Tags : <input type="text" id="tags" name="tags" size="40" value="<?= $tags ;?>"/><br />
	<input type="submit" value="Go" />
  </form>
</div>

<div class="fin">Fin</div>

</div> <!-- end container -->

</body>

</html>
