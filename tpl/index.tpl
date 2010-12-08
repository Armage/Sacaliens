<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>:: Sacaliens ::</title>
	<link rel="icon" type="image/png" href="img/icon-bookin.png">
	<link rel="stylesheet" type="text/css" href="style.css" title="default">
	<link rel="alternate stylesheet" type="text/css" href="steampunk.css" title="steampunk">
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
<h1>:: Sacaliens ::</h1>
<form id="form" action="<?= $self; ?>" method="post">
<div id="container" style="text-align: center">
<div id="log">
	<input type="hidden" name="act" value="login">
	<h3><?= $signin ; ?></h3>
	<div class="erreur"><?= $error_msg ; ?></div>
	<div id="ident">
		<?= $login; ?> : <input type="text" name="login" size="30">
	</div>
	<div id="motpasse">
		<?= $password; ?> : <input type="password" name="passwd" size="32">
	</div>
	<div id="valide">
		<input type="submit" value="<?= $send; ?>">
	</div>
</div>
</div>
</form>
</body>
</html>
