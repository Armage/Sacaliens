<div id="linkedit_<?= $urlId ;?>">
  <form name="formedit" action="<?= $appUrl."/edit/url/".$urlId ;?>" method="post">
    <?= $tLinkURL ;?> : <input type="text" name="url" size="40" value="<?= $url ;?>"/> <br/>
	<?= $tLinkTitle ;?> : <input type="text" name="title" size="60" value="<?= $title ;?>"/> <br/>
	<?= $tLinkDescription ;?>
	<textarea name="description" cols="80" rows = "3"><?= $description ;?></textarea><br />
	<?= $tTags ;?> : <input type="text" id="tags" name="tags" class="editable" size="40" value="<?= $tags ;?>"/><br />
	<input type="submit" value="<?= $tSend ;?>" />
  </form>
</div>
