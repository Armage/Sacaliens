<!DOCTYPE html>
<html lang="en">

<head>
	<title>:: <?= $tTitle;?> ::</title>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="img/icon-bookin.png">
	<link rel="stylesheet" href="/sacaliens/android.css" />
	<meta name="viewport" content="width=device-width" />
	<script type="text/javascript" src="<?= $appUrl ;?>/libjs/jquery.js"></script>
	<script type="text/javascript">
	  function toTop() {
	    if (top.location != self.document.location) {
	      top.location = self.document.location;
	    }
		setTimeout(
		  function () {
  		    window.scrollTo(0, 1);
		  }, 
		  100
		);
	  }
	  function tagSearch() {
	    window.location.href='<?= $tagUrl ;?> '+$('#tag').val();
	  }
	  function urlSearch() {
	    window.location.href='<?= $appUrl ;?>/search/url/'+$('#search').val();
	  }
	  function linkAdd() {
		$('#urladd').load('<?= $appUrl ;?>/edit/url');
		$('#urladd').toggle() ;
	  }
	  function scrollTo(idElt) {
	    $('#related_tags').get(0).scrollTop = $(idElt).get(0).offsetTop - $('#related_tags').get(0).offsetTop ;
	  }
	</script>
</head>

<body onLoad="toTop();">

<div id="container">

<header>
  <h1>:: <?= $tTitle ;?> :: Android version ::</h1>
</header>

<nav>
  <div class="right">
    <a href="<?= $appUrl;?>/tags/"><?= $tTags ;?></a> | 
    <a href="<?= $appUrl;?>/delog.php"><?= $tQuit ;?></a>
  </div>
  <a href="<?= $appUrl ?>/search/url" onclick="$('#urlsearch').toggle(); return false;"><?= $tSearchURL ;?></a>
  <div id="urlsearch" style="display:none;">
  	<form name="formsearch" action="<?= $appUrl."/search/url" ;?>" method="get" onsubmit="urlSearch(); return false">
	  	<input type="text" id="search" name="search" size="40" value=""/> <br/>
		<input type="submit" value="<?= $tSearch ;?>" />
    </form>
  </div>
</nav>

<form name="tagsearch" action="tags" method="get" onsubmit="tagSearch(); return false;">
<div id="tagsearch">
  <a href="<?= $appUrl ;?>/urls/">[X]</a> <?= $tTags ;?> : 
  <div id="filtertags" class="tags">
    <?php
	  if (is_array($tagsearch)) {
	  foreach($tagsearch as $tag): 
	?>
	  <span><a href="<?= $tagUrl.' '.$tag ;?>"><?= $tag; ?></a><a href="<?= str_replace($tag, '', $tagUrl.' '.$tag) ;?>"> [-]</a></span> 
	<? endforeach ; } ?>
  </div>
  <input type="text" id="tag" name="tag" />
</div>
</form>
<div id="links">
  <?php 
    if (is_array($links)) {
    foreach($links as $link): 
  ?>
    <div class="link">
	  <div class="url">
	    <a href="<?= $link['url'];?>"><?= stripslashes($link['title']); ?></a> 
	  </div> <!-- fin url -->
	  <div class="desc"><?= stripslashes($link['description']) ;?></div>
	  <?php
	  if ($link['tags'] !== '') {
	  ?>
	  <div class="tags">
	    <?php
	    foreach(explode(' ', $link['tags']) as $tag):
	    	if ((isset($mode)) and ($mode == 'search')):
		?>
		<span><a href="<?= $appUrl.'/urls/'.$tag ;?>"><?= $tag ;?></a></span> 
	    <?php
			else:
		?>
		<span><a href="<?= $tagUrl.' '.$tag ;?>"><?= $tag ;?></a></span> 
		<?php
			endif;
	    endforeach;
	    ?>
		<span><a href="<?= $link['tags'] ?>">[=]</a></span>
	  </div> <!-- end tags -->
	  <?php
	  }
	  ?>
	</div> <!-- end link -->
  <?php 
    endforeach;
	}
	else {
	  ?> <?= $tNoLink ;?><?php
	}
  ?>

  <div class="clear"></div>
</div> <!-- end links -->

<footer>
  <nav><a href="<?= $tagUrl."?p=".$prev ;?>">précédente</a> page <?= $page ;?> / <?= $nbPages ;?> <a href="<?= $tagUrl."?p=".$next ;?>">suivante</a></nav>
</footer>

</div> <!-- end container -->

</body>

</html>
