<?php

/*
 * This file is part of Sacaliens
 * Copyright (c) 2009 Patrick Paysant
 *
 * Sacaliens is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * Sacaliens is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */
namespace Sacaliens;

use Armg\DB;
use Armg\Tpl;

class Sacaliens {

    protected $tpl;
    protected $db;

    /**
    * @param Tpl $tpl
    * @return Sacaliens
    */
    public function setTpl(Tpl $tpl)
    {
        $this->tpl = $tpl;
        return $this;
    }

    /**
    * @param DB $db
    * @return Sacaliens
    */
    public function setDB(DB $db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * Display urls list
     *
     * @param array $options
     *            sets which urls are to be displayed
     */
    public function display($options = array())
    {
        global $_t;

        $time_start = microtime(true);

//         $this->tpl = new Tpl(SYS_TPL);

//         $this->db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);
        // search
        $where = "";

        // tagsearch
        $tagUrl = WEB_APP . "/urls/";
        if (isset($options['tags']) and count($options['tags']) > 0) {
            if ($where == "")
                $where = "WHERE ";

            foreach (array_keys($options['tags']) as $tag) {
                $where .= "links.tags like '%$tag%' AND ";
            }

            $this->tpl->addData(array(
                'tagsearch' => array_keys($options['tags'])
            ));
            $tagUrl .= join(' ', array_keys($options['tags']));
        }

        // url search
        if (isset($options['search']) and $options['search'] !== '') {
            if ($where == "")
                $where = "WHERE ";

            $where .= "(url LIKE '%" . $options['search'] . "%' OR description LIKE '%" . $options['search'] . "%' OR title LIKE '%" . $options['search'] . "%') ";
            $tagUrl = WEB_APP . "/search/url/" . $options['search'];
        }

        $where = rtrim($where, " AND ");

        // order
        $order = " ORDER BY timecreate DESC ";

        // nav
        if (isset($options['tags']) and count($options['tags']) > 0) {
            // tagfilter , get all filtered links then count them
            $sql = "SELECT * FROM (";
            $sql .= "  SELECT " . DB_TABLE_PREFIX . "url.id as urlid, GROUP_CONCAT(DISTINCT " . DB_TABLE_PREFIX . "tag.label ORDER BY label SEPARATOR ' ') as tags, url, title, description, timecreate, ";
            $sql .= "  UCASE(DATE_FORMAT(timecreate, '%d %b %y')) as datecreate ";
            $sql .= "  FROM " . DB_TABLE_PREFIX . "url ";
            $sql .= "  LEFT JOIN " . DB_TABLE_PREFIX . "url_tag on " . DB_TABLE_PREFIX . "url_tag.url_id = " . DB_TABLE_PREFIX . "url.id ";
            $sql .= "  LEFT JOIN " . DB_TABLE_PREFIX . "tag on " . DB_TABLE_PREFIX . "tag.id = " . DB_TABLE_PREFIX . "url_tag.tag_id ";
            $sql .= "  GROUP BY urlid";
            $sql .= ") AS links ";
            $sql .= $where . $order;

            $links = $this->db->queryFetchAllAssoc($sql);
            $total = count($links);
        } elseif (isset($options['search']) and $options['search'] !== '') {
            $sql = "SELECT * FROM (";
            $sql .= "  SELECT " . DB_TABLE_PREFIX . "url.id as urlid, GROUP_CONCAT(DISTINCT " . DB_TABLE_PREFIX . "tag.label ORDER BY label SEPARATOR ' ') as tags, url, title, description, timecreate, ";
            $sql .= "  UCASE(DATE_FORMAT(timecreate, '%d %b %y')) as datecreate ";
            $sql .= "  FROM " . DB_TABLE_PREFIX . "url ";
            $sql .= "  LEFT JOIN " . DB_TABLE_PREFIX . "url_tag on " . DB_TABLE_PREFIX . "url_tag.url_id = " . DB_TABLE_PREFIX . "url.id ";
            $sql .= "  LEFT JOIN " . DB_TABLE_PREFIX . "tag on " . DB_TABLE_PREFIX . "tag.id = " . DB_TABLE_PREFIX . "url_tag.tag_id ";
            $sql .= $where;
            $sql .= "  GROUP BY urlid";
            $sql .= ") AS links ";
            $sql .= $order;
            $links = $this->db->queryFetchAllAssoc($sql);
            $total = count($links);
        } else {
            // no tagfilter, count all urls
            $sql = "SELECT count(id) as total FROM " . DB_TABLE_PREFIX . "url";
            $result = $this->db->queryFetchAllAssoc($sql);
            $total = $result[0]['total'];
        }

        $page = 1;
        if (isset($_GET['p']) and $_GET['p'] !== '') {
            $page = $_GET['p'];
        }

        $nbPages = (($total - 1) - (($total - 1) % MAX_PER_PAGE)) / MAX_PER_PAGE + 1;
        if ($page <= 1) {
            $page = 1;
            $pagePrev = 1;
            $pageNext = 2;
        } elseif (($page > 1) or ($page < $nbPages)) {
            $pagePrev = $page - 1;
            $pageNext = $page + 1;
        }
        if ($page >= $nbPages) {
            $page = $nbPages;
            $pagePrev = $page - 1;
            $pageNext = $page;
        }
        $offset = ($page - 1) * MAX_PER_PAGE;

        // infos to display
        if ((isset($options['tags']) and count($options['tags']) > 0) or (isset($options['search']) and $options['search'] !== '')) {
            $displayLinks = array_slice($links, $offset, MAX_PER_PAGE);
        } else {
            $sql = "SELECT * FROM (";
            $sql .= "  SELECT " . DB_TABLE_PREFIX . "url.id as urlid, GROUP_CONCAT(DISTINCT " . DB_TABLE_PREFIX . "tag.label ORDER BY label SEPARATOR ' ') as tags, url, title, description, timecreate, ";
            $sql .= "  UCASE(DATE_FORMAT(timecreate, '%d %b %y')) as datecreate ";
            $sql .= "  FROM " . DB_TABLE_PREFIX . "url ";
            $sql .= "  LEFT JOIN " . DB_TABLE_PREFIX . "url_tag on " . DB_TABLE_PREFIX . "url_tag.url_id = " . DB_TABLE_PREFIX . "url.id ";
            $sql .= "  LEFT JOIN " . DB_TABLE_PREFIX . "tag on " . DB_TABLE_PREFIX . "tag.id = " . DB_TABLE_PREFIX . "url_tag.tag_id ";
            $sql .= "  GROUP BY urlid";
            $sql .= ") as links ";
            $sql .= $order . " LIMIT $offset, " . MAX_PER_PAGE;
            $displayLinks = $this->db->queryFetchAllAssoc($sql);
        }

        // related tags
        $related_tags = array();
        if ((isset($options['tags']) and count($options['tags']) > 0) or (isset($options['search']) and $options['search'] !== '')) {
            foreach ($links as $link) {
                $tags = explode(" ", $link['tags']);
                foreach ($tags as $tag) {
                    if ((isset($options['tags']) and count($options['tags']) > 0) and (! in_array($tag, array_keys($options['tags'])))) {
                        $related_tags[$tag] = array(
                            'label' => $tag
                        ); // structure identique à ce que renvoie une requete DB
                    }
                }
            }
            if (is_array($related_tags) and count($related_tags) > 0) {
                sort($related_tags);
            }
        } else {
            $sql = "SELECT label FROM " . DB_TABLE_PREFIX . "tag ORDER BY label";
            $related_tags = $this->db->queryFetchAllAssoc($sql);
        }

        $relatedTags = array();
        if (count($related_tags) > 0) {
            foreach ($related_tags as $tag) {
                $relatedTags[mb_strtoupper(mb_substr($tag['label'], 0, 1, "UTF-8"))][] = $tag;
            }
        }

        $this->tpl->addData(array(
            "links" => $displayLinks
        ));
        $this->tpl->addData(array(
            "relatedTags" => $relatedTags,
            "relatedTagsNb" => count($related_tags),
            "relatedIndex" => array_keys($relatedTags)
        ));
        $this->tpl->addData(array(
            "appUrl" => WEB_APP
        ));
        $this->tpl->addData(array(
            "tagUrl" => $tagUrl
        ));
        $this->tpl->addData(array(
            "nbLinks" => $total
        ));
        $this->tpl->addData(array(
            'page' => $page,
            'nbPages' => $nbPages,
            'prev' => $pagePrev,
            'next' => $pageNext
        ));
        if (isset($options['search']) and $options['search'] !== '')
            $this->tpl->addData(array(
                'mode' => 'search',
            ));

            // translations
        $this->tpl->addData(array(
            'tTitle' => $_t['title'],
            'tAddURL' => $_t['add_url'],
            'tSearchURL' => $_t['search_url'],
            'tLinkURL' => $_t['link_url'],
            'tLinkTitle' => $_t['link_title'],
            'tLinkDescription' => $_t['link_description'],
            'tTags' => $_t['tags'],
            'tSearch' => $_t['search'],
            'tSend' => $_t['send'],
            'tQuit' => $_t['quit'],
            'tTagRelated' => $_t['tag_related'],
            'tEdit' => $_t['edit'],
            'tDelete' => $_t['delete'],
            'tReallyDelete' => $_t['really_delete'],
            'tYes' => $_t['yes'],
            'tNo' => $_t['no'],
            'tNoLink' => $_t['link_no_link'],
            'tLinkFound' => $_t['link_found']
        ));

        $time_end = microtime(true);
        $time = $time_end - $time_start;
        $this->tpl->addData(array(
            "time" => $time
        ));

        if (isAndroid()) {
            $this->tpl->runTpl('android/sacaliens.tpl');
        } else {
            $this->tpl->runTpl("sacaliens.tpl");
        }
    }

    /**
     * Displays the add URL form, used by bookmarklet
     */
    function formAddDisplay()
    {
        global $_t;

//         $this->db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);
        $this->tpl = new Tpl(SYS_TPL);
        $whereSame = "WHERE ";

        if (isset($_GET['url']) and $_GET['url'] != '') {
            $this->tpl->addData(array(
                "url" => $_GET['url']
            ));
            $whereSame .= " url = '" . $_GET['url'] . "'";
        }
        if (isset($_GET['title']) and $_GET['title'] != '') {
            $this->tpl->addData(array(
                "title" => $_GET['title']
            ));
            if ($whereSame != '')
                $whereSame .= " OR ";
            $whereSame .= " title = '" . $_GET['title'] . "'";
        }
        $this->tpl->addData(array(
            "appUrl" => WEB_APP
        ));

        // searching same url or title
        $sql = "SELECT * FROM (";
        $sql .= "  SELECT " . DB_TABLE_PREFIX . "url.id as urlid, GROUP_CONCAT(DISTINCT " . DB_TABLE_PREFIX . "tag.label ORDER BY label SEPARATOR ' ') as tags, url, title, description, timecreate, ";
        $sql .= "  UCASE(DATE_FORMAT(timecreate, '%d %b %y')) as datecreate ";
        $sql .= "  FROM " . DB_TABLE_PREFIX . "url ";
        $sql .= "  LEFT JOIN " . DB_TABLE_PREFIX . "url_tag on " . DB_TABLE_PREFIX . "url_tag.url_id = " . DB_TABLE_PREFIX . "url.id ";
        $sql .= "  LEFT JOIN " . DB_TABLE_PREFIX . "tag on " . DB_TABLE_PREFIX . "tag.id = " . DB_TABLE_PREFIX . "url_tag.tag_id ";
        $sql .= $whereSame;
        $sql .= "  GROUP BY urlid";
        $sql .= ") AS links ";
        $sql .= $order;
        $hintSame = $this->db->queryFetchAllAssoc($sql);
        $this->tpl->addData(array(
            "hintSame" => $hintSame
        ));

        // searching similar url
        if (! empty($_GET['url'])) {
            $similarIds = getSimilarUrlIds($_GET['url']);
            if (! empty($similarIds)) {
                $whereSimilar = " WHERE " . DB_TABLE_PREFIX . "url.id IN (" . join(', ', $similarIds) . ") ";

                $sql = "SELECT * FROM (";
                $sql .= "  SELECT " . DB_TABLE_PREFIX . "url.id as urlid, GROUP_CONCAT(DISTINCT " . DB_TABLE_PREFIX . "tag.label ORDER BY label SEPARATOR ' ') as tags, url, title, description, timecreate, ";
                $sql .= "  UCASE(DATE_FORMAT(timecreate, '%d %b %y')) as datecreate ";
                $sql .= "  FROM " . DB_TABLE_PREFIX . "url ";
                $sql .= "  LEFT JOIN " . DB_TABLE_PREFIX . "url_tag on " . DB_TABLE_PREFIX . "url_tag.url_id = " . DB_TABLE_PREFIX . "url.id ";
                $sql .= "  LEFT JOIN " . DB_TABLE_PREFIX . "tag on " . DB_TABLE_PREFIX . "tag.id = " . DB_TABLE_PREFIX . "url_tag.tag_id ";
                $sql .= $whereSimilar;
                $sql .= "  GROUP BY urlid";
                $sql .= ") AS links ";
                $sql .= $order;
                $hintSimilar = $this->db->queryFetchAllAssoc($sql);
                $hintSimilar = array_diff($hintSimilar, $hintSame);
                $this->tpl->addData(array(
                    "hintSimilar" => $hintSimilar
                ));
            }
        }

        // translations
        $this->tpl->addData(array(
            'tTitle' => $_t['title'],
            'tAddURL' => $_t['add_url'],
            'tLinkURL' => $_t['link_url'],
            'tLinkTitle' => $_t['link_title'],
            'tLinkDescription' => $_t['link_description'],
            'tSend' => $_t['send'],
            'tTags' => $_t['tags'],
            'tSameLink' => $_t['same_link'],
            'tSimilarLink' => $_t['similar_link']
        ));

        $this->tpl->runTpl("form_urladd.tpl");
    }

    /**
     * get 10 first ids (best noted) from similar url
     */
    function getSimilarUrlIds($url = '')
    {
        if (empty($url)) {
            return array();
        }
        $ids = array();

        $urlFragments = parse_url($url);
        if ($urlFragments) {
//             $this->db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);
            // get all urls
            $sql = "SELECT * FROM " . DB_TABLE_PREFIX . "fragments";
            $urls = $this->db->queryFetchAllAssoc($sql);

            foreach ($urls as $fragment) {
                $note = 0;

                if ($urlFragments['scheme'] == $fragment['scheme']) {
                    $note += 1;
                }
                if ($urlFragments['host'] == $fragment['host']) {
                    $note += 5;
                }
                if ($urlFragments['path'] == $fragment['path']) {
                    $note += 10;
                }
                if (! empty($urlFragments['query']) and ($urlFragments['query'] == $fragment['query'])) {
                    $note += 2;
                }

                if ($note >= 6) {
                    $ids[$fragment['id_url']] = $note;
                }
            }

            asort($ids);
            return array_keys(array_slice($ids, 0, 10, true));
        }
    }

    /**
     * Insert tags in db
     */
    function insertTagsForUrl($urlId, $tags)
    {
//         $this->db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);
        foreach ($tags as $tag) {
            $tag = mysql_real_escape_string(trim($tag));
            if ($tag !== "") {
                $sql = "SELECT id FROM " . DB_TABLE_PREFIX . "tag WHERE label = '" . $tag . "' ;";
                $datas = $this->db->queryFetchAllAssoc($sql);
                if (count($datas) > 0) {
                    $tagId = $datas[0]['id'];
                } else {
                    $sql = "INSERT INTO " . DB_TABLE_PREFIX . "tag VALUES ('', '$tag') ;";
                    $this->db->query($sql);
                    $tagId = $this->db->lastInsertId();
                }
                $sql = "INSERT INTO " . DB_TABLE_PREFIX . "url_tag VALUES ($urlId, $tagId) ";
                $this->db->query($sql);
            }
        }
    }

    /**
     * Insert url fragments in db
     */
    function insertFragments($urlId = 0, $url = '')
    {
        global $_t;

        if (empty($url)) {
            return;
        }

//         $this->db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);

        $fragments = parse_url($url);
        // parse_url returns false on malformed url...
        if ($fragments) {
            $items = array(
                'scheme',
                'host',
                'user',
                'pass',
                'path',
                'query',
                'fragment'
            );
            $fields = array();
            $values = array();

            foreach ($items as $item) {
                if (! empty($fragments[$item])) {
                    $fields[] = $item;
                    $values[] = $fragments[$item];
                }
            }
            $sql_field = join(', ', $fields);
            $sql_values = "'" . join("', '", $values) . "'";

            $sql = "INSERT INTO " . DB_TABLE_PREFIX . "fragments (id_url, " . $sql_field . ") VALUES (" . $urlId . ", " . $sql_values . ")";
            debug($sql);
            $this->db->query($sql);
        }
    }

    /**
     * *
     * Insert url in base (insert tags if necessary)
     * *
     */
    function urlInsert()
    {
        global $_t;

//         $this->db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);

        $url = mysql_real_escape_string($_POST['url']);
        $title = mysql_real_escape_string($_POST['title']);
        $description = mysql_real_escape_string($_POST['description']);
        $tags = explode(' ', $_POST['tags']);

        $sql = "INSERT INTO " . DB_TABLE_PREFIX . "url (timecreate, url, title, description) VALUES (NOW(), '$url', '$title', '$description')";
        $this->db->query($sql);
        $urlId = $this->db->lastInsertId();

        insertTagsForUrl($urlId, $tags);

        insertFragments($urlId, $url);

        if (isset($_POST['noui']) and $_POST['noui'] == 1) {
            $this->tpl = new armgTpl(SYS_TPL);
            $this->tpl->addData(array(
                "message" => $_t['link_saved']
            ));
            $this->tpl->addData(array(
                "appUrl" => WEB_APP
            ));
            $this->tpl->runTpl("message.tpl");
        } else {
            header("Location: " . WEB_APP . "/urls/");
        }
    }

    /**
     * Displays edit URL form
     */
    function formEditDisplay($urlId)
    {
        global $_t;

        $urlId = intval($urlId);
        if ($urlId == 0)
            return;

//         $this->db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);
        $sql = "SELECT * FROM (";
        $sql .= "  SELECT " . DB_TABLE_PREFIX . "url.id as urlid, GROUP_CONCAT(DISTINCT " . DB_TABLE_PREFIX . "tag.label SEPARATOR ' ') as tags, url, title, description ";
        $sql .= "  FROM " . DB_TABLE_PREFIX . "url ";
        $sql .= "  LEFT JOIN " . DB_TABLE_PREFIX . "url_tag on " . DB_TABLE_PREFIX . "url_tag.url_id = " . DB_TABLE_PREFIX . "url.id ";
        $sql .= "  LEFT JOIN " . DB_TABLE_PREFIX . "tag on " . DB_TABLE_PREFIX . "tag.id = " . DB_TABLE_PREFIX . "url_tag.tag_id ";
        $sql .= "  GROUP BY urlid";
        $sql .= ") as links ";
        $sql .= "WHERE urlid = $urlId";
        $links = $this->db->queryFetchAllAssoc($sql);

        $link = $links[0];

        $this->tpl = new Tpl(SYS_TPL);

        $this->tpl->addData(array(
            'url' => $link['url']
        ));
        $this->tpl->addData(array(
            'title' => stripslashes($link['title'])
        ));
        $this->tpl->addData(array(
            'description' => stripslashes($link['description'])
        ));
        $this->tpl->addData(array(
            'tags' => $link['tags']
        ));
        $this->tpl->addData(array(
            "appUrl" => WEB_APP
        ));
        $this->tpl->addData(array(
            "urlId" => $urlId
        ));

        // translations
        $this->tpl->addData(array(
            'tTitle' => $_t['title'],
            'tAddURL' => $_t['add_url'],
            'tLinkURL' => $_t['link_url'],
            'tLinkTitle' => $_t['link_title'],
            'tLinkDescription' => $_t['link_description'],
            'tSend' => $_t['send'],
            'tTags' => $_t['tags']
        ));

        $this->tpl->runTpl("form_urledit.tpl");
    }

    /**
     * Update URL in db
     */
    function urlUpdate($urlId)
    {
        $urlId = intval($urlId);
        if ($urlId == 0)
            return;

//         $this->db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);

        $url = mysql_real_escape_string(trim($_POST['url']));
        $title = mysql_real_escape_string(trim($_POST['title']));
        $description = mysql_real_escape_string(trim($_POST['description']));
        $tags = explode(' ', $_POST['tags']);

        // update url
        $sql = "UPDATE " . DB_TABLE_PREFIX . "url ";
        $sql .= "SET url = '$url', title = '$title', description = '$description', lastmodif = NOW() ";
        $sql .= "WHERE id = $urlId";
        $this->db->query($sql);

        // delete current tags
        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "url_tag WHERE url_id = $urlId";
        $this->db->query($sql);

        // insert new tags
        $this->insertTagsForUrl($urlId, $tags);

        // delete current fragments for this url
        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "fragments WHERE id_url = $urlId";
        $this->db->query($sql);

        // insert new fragment
        $this->insertFragments($urlId, $url);

        // if there is some stored url in cookie, go to it
        if (! empty($_COOKIE['request'])) {
            header("Location: " . $_COOKIE['request']);
            ;
        } else {
            header("Location: " . WEB_APP . "/urls/");
        }
    }

    /**
     * Delete url
     */
    function urlDelete($urlId)
    {
        $urlId = intval($urlId);
        if ($urlId == 0)
            return;

//         $this->db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);
        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "url WHERE id = $urlId";
        $this->db->query($sql);

        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "url_tag WHERE url_id = $urlId";
        $this->db->query($sql);

        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "fragments WHERE id_url = $urlId";
        $this->db->query($sql);

        // if there is some stored url in cookie, go to it
        if (! empty($_COOKIE['request'])) {
            header("Location: " . $_COOKIE['request']);
            ;
        } else {
            header("Location: " . WEB_APP . "/urls/");
        }
    }

    /**
     * Displays tag cloud
     */
    function tagDisplay($options = array())
    {
        global $_t;

        function cmp($a, $b)
        {
            if ($a['nb'] == $b['nb'])
                return 0;
            return ($a['nb'] < $b['nb']) ? - 1 : 1;
        }

        function cmp2($a, $b)
        {
            if ($a['label'] == $b['label'])
                return 0;
            return ($a['label'] < $b['label']) ? - 1 : 1;
        }

//         $this->db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);

        $sql = "SELECT id, label, COUNT(" . DB_TABLE_PREFIX . "url_tag.url_id) AS nb ";
        $sql .= "FROM " . DB_TABLE_PREFIX . "tag ";
        $sql .= "LEFT JOIN " . DB_TABLE_PREFIX . "url_tag ON " . DB_TABLE_PREFIX . "url_tag.tag_id = " . DB_TABLE_PREFIX . "tag.id ";
        $sql .= "GROUP BY label ";
        $sql .= "ORDER BY label";
        $tags = $this->db->queryFetchAllAssoc($sql);

        $minFontSize = 1;
        $maxFontSize = 2;

        $min = 100;
        $max = 0;
        foreach ($tags as $tag) {
            if ($tag['nb'] > $max)
                $max = $tag['nb'];
            if ($tag['nb'] < $min)
                $min = $tag['nb'];
        }
        foreach ($tags as $key => $tag) {
            $tags[$key]['size'] = ($minFontSize + ($tag['nb'] - $min) * ($maxFontSize - $minFontSize) / ($max - $min));
        }

        $this->tpl = new Tpl(SYS_TPL);

        if (isset($options['msg'])) {
            $this->tpl->addData(array(
                'msg' => $options['msg']
            ));
        }

        $this->tpl->addData(array(
            'appUrl' => WEB_APP
        ));
        $this->tpl->addData(array(
            "tags" => $tags
        ));
        $this->tpl->addData(array(
            "nbTags" => count($tags)
        ));

        // Translations
        $this->tpl->addData(array(
            'tTitle' => $_t['title'],
            'tBack' => $_t['back'],
            'tTagChoosen' => $_t['tag_choosen'],
            'tTagRenameAction' => $_t['tag_to_rename'],
            'tSend' => $_t['send'],
            'tTag1' => $_t['tag1'],
            'tTag2' => $_t['tag2'],
            'tTagMergeAction' => $_t['tag_to_merge'],
            'tTagNoTag' => $_t['tag_no_tag'],
            'tTagFound' => $_t['tag_found']
        ));

        $this->tpl->runTpl("tags.tpl");
    }

    /**
     * Analyse url to get the searched tags
     * Build $options array for display() function
     */
    function tagfilter($tag_string)
    {
//         $this->db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);
        $datas = mysql_real_escape_string(trim(urldecode($tag_string), ' '));
        if ($datas !== "") {
            $tags = explode(' ', $datas);
        }

        $options['tags'] = array();
        if (is_array($tags)) {
            foreach ($tags as $tag) {
                if (trim($tag) !== '') {
                    $options['tags'][$tag] = "";
                }
            }
        }

        if (count($options['tags']) <= 0) {
            header("Location: " . WEB_APP . "/urls/");
        }

        $this->display($options);
    }

    /**
     * Ajax, send back tags list containing $_GET['q']
     */
    function tagsearch()
    {
        $q = strtolower($_GET["q"]);
        if (! $q)
            return;

//         $this->db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);
        $sql = "SELECT label FROM " . DB_TABLE_PREFIX . "tag WHERE label like '%$q%';";
        $rows = $this->db->queryFetchAllAssoc($sql);

        if (is_array($rows) and count($rows) > 0) {
            foreach ($rows as $row) {
                echo $row['label'] . "\n";
            }
        } else {
            return;
        }
    }

    /**
     * Modify tag label in base
     */
    function tagEdit()
    {
        global $_t;

//         $this->db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);

        $oldLabel = mysql_real_escape_string(trim($_POST['old_label']));
        $newLabel = mysql_real_escape_string(trim($_POST['new_label']));

        if ($oldLabel == '' or $newLabel == '') {
            tagDisplay(array(
                'msg' => $_t['tag_data_needed'],
            ));
            exit();
        }

        $sql = "UPDATE " . DB_TABLE_PREFIX . "tag SET label = '$newLabel' WHERE label = '$oldLabel'";
        $this->db->query($sql);

        header('Location: ' . WEB_APP . '/tags/');
    }

    /**
     * Merge two tags : delete the two old tags and create a new one
     */
    function tagFusion()
    {
        global $_t;

//         $this->db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);

        $label1 = mysql_real_escape_string(trim($_POST['tag1']));
        $label2 = mysql_real_escape_string(trim($_POST['tag2']));
        $newLabel = mysql_real_escape_string(trim($_POST['newtagfusion']));

        if ($label1 == '' or $label2 == '') {
            tagDisplay(array(
                'msg' => $_t['tag_data_needed']
            ));
            exit();
        }
        if (trim($label1) == trim($newLabel) or trim($label2 == $newLabel)) {
            tagDisplay(array(
                'msg' => $_t['tag_merge_distinct']
            ));
            exit();
        }

        // get tag1 id
        $sql = "SELECT id FROM " . DB_TABLE_PREFIX . "tag WHERE label = '$label1'";
        $tags = $this->db->queryFetchAllAssoc($sql);
        $id1 = $tags[0]['id'];

        // get tag2 id
        $sql = "SELECT id FROM " . DB_TABLE_PREFIX . "tag WHERE label = '$label2'";
        $tags = $this->db->queryFetchAllAssoc($sql);
        $id2 = $tags[0]['id'];

        // insert new tag (label) in 'tag'
        $sql = "INSERT INTO " . DB_TABLE_PREFIX . "tag VALUES ('', '$newLabel')";
        $this->db->query($sql);
        $newId = $this->db->lastInsertId();

        // insert new tag in 'url_tag'
        $sql = "INSERT INTO " . DB_TABLE_PREFIX . "url_tag (url_id, tag_id) ";
        $sql .= "SELECT url_id, $newId FROM " . DB_TABLE_PREFIX . "url_tag WHERE tag_id = $id1 OR tag_id = $id2 GROUP BY url_id";
        $this->db->query($sql);

        // delete tag1 and tag2 in 'url_tag'
        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "url_tag WHERE tag_id = $id1 OR tag_id = $id2";
        $this->db->query($sql);

        // delete tag1 and tag2 in 'tag'
        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "tag WHERE label = '$label1' OR label = '$label2'";
        $this->db->query($sql);

        header('Location: ' . WEB_APP . '/tags/');
    }
}
?>
