<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
    <title>:: <?= $tTitle;?> ::</title>
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?= $resourcesUrl ;?>/css/semantic.css" title="default">
    <link rel="alternate stylesheet" type="text/css" href="<?= $resourcesUrl ;?>/css/steampunk.css" title="steampunk">
    <link rel="stylesheet" type="text/css" href="<?= $resourcesUrl ;?>/libjs/jquery.autocomplete.css">
    <style>
      body {
        background-color: #D3B99F;
        padding: 5px;
      }
      .ui.cards > .card, .ui.card {
        background-color: #F3DEB2;
        box-shadow: 0px 0.2em 0px 0px #AC773E, 0px 0px 0px 1px #724E28;
      }
      .ui.menu, .ui.segment, .ui.form, .ui.form input[type="text"], .ui.form input[type="email"], .ui.form input[type="date"], .ui.form input[type="datetime-local"], .ui.form input[type="password"], .ui.form input[type="number"], .ui.form input[type="url"], .ui.form input[type="tel"] {
        background-color: #F3DEB2;
      }
      .ui.table {
        background-color: #F3DEB2;
        border: 1px solid #724E28;
      }
      .ui.table tr td {
        border-top: 1px solid #AC773E;
      }
      .ui.celled.table tr td {
        border-left: 1px solid #AC773E;
      }
      a, a:hover {
        color: #000;
      }
    </style>
    <meta name="window-target" content="_top">
    <script type="text/javascript" src="<?= $resourcesUrl ;?>/libjs/jquery.js"></script>
    <script type="text/javascript" src="<?= $resourcesUrl ;?>/libjs/jquery.autocomplete.js"></script>
    <script type="text/javascript" src="<?= $resourcesUrl ;?>/libjs/semantic.js"></script>
    <script type="text/javascript">
      function toTop() {
        if (top.location != self.document.location) {
          top.location = self.document.location;
        }
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
      function linkEdit(linkId) {
        if ($('#urledit_'+linkId).data('loaded') != true) {
            $.ajax({
                url: '<?= $appUrl ;?>/edit/url/' + linkId,
                success: function(data) {
                    $('#urledit_'+linkId).html(data);
                    $('#urledit_'+linkId+' input.editable').autocomplete('<?= $appUrl ;?>/search/tags', {
                        width: 300,
                        matchContains: true,
                        multiple: true,
                        multipleSeparator: " "
                    }) ;
                    $('#urledit_'+linkId).data('loaded', true) ;
                }
            });
        }
        $('#urledit_'+linkId).toggle() ;
      }
      function scrollTo(idElt) {
        $('#related_tags').get(0).scrollTop = $(idElt).get(0).offsetTop - $('#related_tags').get(0).offsetTop ;
      }
      $(document).ready(function() {
        $("#tag").autocomplete('<?= $appUrl ;?>/search/tags', {
          width: 300,
          matchContains: true,
          multiple: true,
          multipleSeparator: " "
        }) ;
        $("#urladd #tags").autocomplete('<?= $appUrl ;?>/search/tags', {
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

<h1 class="ui center header aligned">:: <?= $tTitle ;?> ::</h1>

<div id="commands" class="ui menu">
  <a href="add" onclick="$('#urladd').toggle(); return false;" class="item"><?= $tAddURL ;?></a>
  <a href="<?= $appUrl ?>/search/url" onclick="$('#urlsearch').toggle(); return false;" class="item"><?= $tSearchURL ;?></a>
  <div class="right menu">
    <a href="<?= $appUrl;?>/tags/" class="item"><?= $tTags ;?></a>
    <a href="<?= $appUrl;?>/logout" class="item"><?= $tQuit ;?></a>
  </div>
</div>

  <div id="urladd" class="ui segment" style="display:none;">
    <form name="formadd" action="<?= $appUrl."/edit/url" ;?>" method="post">
        <?= $tLinkURL ;?> : <input type="text" name="url" size="40" value=""/> <br/>
        <?= $tLinkTitle ;?> : <input type="text" name="title" size="60" value=""/> <br/>
        <?= $tLinkDescription ;?>
        <textarea name="description" cols="80" rows = "3"></textarea><br />
        <?= $tTags ;?> : <input type="text" id="tags" name="tags" size="40" value=""/><br />
        <input type="submit" value="<?= $tSend ;?>" />
    </form>
  </div>
  <div id="urlsearch" class="ui segment" style="display:none;">
    <form name="formsearch" action="<?= $appUrl."/search/url" ;?>" method="get" onsubmit="urlSearch(); return false">
        <input type="text" id="search" name="search" size="40" value=""/> <br/>
        <input type="submit" value="<?= $tSearch ;?>" />
    </form>
  </div>

<div class="ui segment">
  <form name="tagsearch" action="tags" method="get" onsubmit="tagSearch(); return false;">
  <div id="tagsearch" class="ui form">
    <div class="inline field">
      <div id="filtertags" class="tags">
        <a href="<?= $appUrl ;?>/urls/">[X]</a> <?= $tTags ;?> :
        <?php
          if (isset($tagsearch) and is_array($tagsearch)) {
          foreach($tagsearch as $tag):
        ?>
          <span><a href="<?= $tagUrl.' '.$tag ;?>" class="ui mini tag label"><?= $tag; ?></a><a href="<?= str_replace($tag, '', $tagUrl.' '.$tag) ;?>"> [-]</a></span>
        <? endforeach ; } ?>
        <input type="text" id="tag" name="tag" class="ui fluid input"/>
      </div>
    </div>
  </div>
  </form>
</div>

<div id="links" class="ui grid">
  <div class="related_tags_box two wide column">
    <?php
      if ($relatedTagsNb > INDEX_TAGS_SEUIL) :
    ?>
    <div id="index">
      <table class="ui celled table">
      <tbody>
      <?php
        $nb = 1;
        foreach($relatedIndex as $index) {
          if ($nb == 1) {
            echo "<tr>";
          }
      ?>
      <td><a href="" onclick="scrollTo('#<?= $index ;?>');return false" ><?= $index ;?></a></td>
      <?php
          $nb++;
          if ($nb == 7) {
            $nb = 1;
            echo "</tr>\n";
          }
        }
      ?>
      </tbody>
      </table>
    </div>
    <?php
      endif ;
    ?>

    <div class="ui segment">
      <strong><?= $tTagRelated ;?></strong>
      <div id="related_tags" class="ui list" style="max-height: 37em; overflow: auto;">
      <?php
        if ($relatedTagsNb > 0) :
          foreach($relatedTags as $alpha => $alphaTags) :
            $first = " id=\"$alpha\"";
      ?>
      <?php
          // $alpha : one character
          // $alphaTags : array of tags beginning with same character
            foreach($alphaTags as $tag) {
      ?>
          <div<?= $first;?> class="item"><a href="<?= $tagUrl.' '.$tag['label'] ;?>"><?= $tag['label'] ;?></a></div>
      <?php
              $first = '';
            }
          endforeach ;
        else :
          echo "Aucun" ;
        endif ;
      ?>
      </div>
    </div>
  </div>

  <div class="fourteen wide column ui cards">
  <?php
    if (is_array($links)) {
    $curDate = "" ;
    foreach($links as $link):
  ?>
    <div class="link card" style="width:800px; min-height: 130px;">
      <div class="content">
      <div class="url header">
        <a href="<?= $link['url'];?>"><?= stripslashes($link['title']); ?></a>
        <span class="action"><a href="<?= $appUrl ;?>/edit/url/<?= $link['urlid']; ?>" onclick="linkEdit(<?= $link['urlid'] ;?>); return false"><i class="write icon"></i></a></span>
        <span class="action"><a href="<?= $appUrl ;?>/delete/url/<?= $link['urlid'];?>" onclick="$('#urldelete_<?= $link['urlid'] ;?>').toggle(200);return false"><i class="remove circle icon"></i></a></span>
      </div> <!-- fin url -->
      <div id="urledit_<?= $link['urlid'] ;?>" class="urledit" style="display:none;"></div>
      <div id="urldelete_<?= $link['urlid'] ;?>" class="urldelete" style="display: none;">
        <?= $tReallyDelete ;?> <a href="<?= $appUrl ;?>/delete/url/<?= $link['urlid'];?>"><?= $tYes ;?></a> <a href="" onclick="$('#urldelete_<?= $link['urlid'] ;?>').toggle();return false"><?= $tNo ;?></a>
      </div> <!-- end delete action -->
      <div class="desc description"><?= stripslashes($link['description']) ;?></div>
      <div class="extra content" style="position: absolute; bottom: 5px; width: 96%;">
        <?php
        if ($link['tags'] !== '') {
        ?>
        <div class="tags right floated" style="float: right;">
          <?php
          foreach(explode(' ', $link['tags']) as $tag):
              if ((isset($mode)) and ($mode == 'search')):
          ?>
          <span><a href="<?= $appUrl.'/urls/'.$tag ;?>" class="ui mini tag label"><?= $tag ;?></a></span>
          <?php
              else:
          ?>
          <span><a href="<?= $tagUrl.' '.$tag ;?>" class="ui mini tag label"><?= $tag ;?></a></span>
          <?php
              endif;
          endforeach;
          ?>
          <span><a href="<?= $link['tags'] ?>">[=]</a></span>
        </div>
        <div class="date created"><?= $link['datecreate'] ;?></div>
      </div>
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
  </div>

  <div class="clear"></div>
</div> <!-- end links -->

<div class="fin">
  <div class="nav ui segment center aligned"><a href="<?= $tagUrl."?p=".$prev ;?>">&lt;</a> page <?= $page ;?> / <?= $nbPages ;?> <a href="<?= $tagUrl."?p=".$next ;?>">&gt;</a></div>
  <?= $nbLinks ;?> <?= $tLinkFound ;?>
  <?php if ($time) {  ?><div><?= $time ;?></div><?php } ?>
</div>

</div> <!-- end container -->

</body>

</html>
