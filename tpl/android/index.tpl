<!DOCTYPE html>
<html lang="en">
<head>
	<title>:: Sacaliens ::</title>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="img/icon-bookin.png">
	<link rel="stylesheet" href="/sacaliens/android.css" />
	<meta name="viewport" content="width=device-width" />
	<script type="text/javascript">
	  function toTop() {
	    if (top.location != self.document.location) {
	      top.location = self.document.location;
	    }
	  }
	</script>
</head>
<body onLoad="toTop();">

<header>
  <h1>:: Sacaliens ::</h1>
</header>

<form id="form" action="<?= $self; ?>" method="post">
<div id="container" style="text-align: center">
<div id="log">
	<input type="hidden" name="act" value="login">
	<h3><?= $signin ; ?></h3>
	<div class="erreur"><?= $error_msg ; ?></div>
	<div id="ident">
		<?= $login; ?> : <input type="text" name="login" autofocus />
	</div>
	<div id="motpasse">
		<?= $password; ?> : <input type="password" name="passwd" />
	</div>
	<div id="valide">
		<input type="submit" value="<?= $send; ?>">
	</div>
</div>
</div>
</form>

</body>

</html>
