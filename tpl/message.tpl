<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
	<title>:: Sacaliens ::</title>
	<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
	<link rel="icon" type="image/png" href="img/icon-bookin.png">
	<link rel="stylesheet" type="text/css" href="<?= $resourcesUrl ;?>/css/style.css" title="default">
	<link rel="alternate stylesheet" type="text/css" href="<?= $resourcesUrl ;?>/css/steampunk.css" title="steampunk">
	<meta name="window-target" content="_top">
	<script type="text/javascript">
	  function toTop() {
	    if (top.location != self.document.location) {
	      top.location = self.document.location;
	    }
	  }
	</script>
</head>

<body onLoad="toTop();">

<div id="container" style="width:730px;">

<h1>:: Sacaliens ::</h1>

<div id="commands">
<?= $message; ?>
</div>

<div style="text-align:center">
  <a href="<?= $appUrl ;?>/" onclick="javascript:window.close();return false">Fermer cette fenêtre</a>
</div>

<div class="fin">Fin</div>

</div> <!-- end container -->

</body>

</html>
