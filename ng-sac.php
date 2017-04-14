<?php

namespace sacaliens;

class Sacaliens
{
    protected $dataProvider;

    public function __construct($dataProvider) {
        $this->dataProvider = $dataProvider;
    }

    public function index()
    {
        echo "Liste<br>";
        // $links = \ORM::forTable(DB_TABLE_PREFIX . 'url')->findMany();

        // $sql = "SELECT * FROM (" ;
        // $sql .= "  SELECT ".DB_TABLE_PREFIX."url.id as urlid, ";
        // $sql .= " GROUP_CONCAT(DISTINCT ".DB_TABLE_PREFIX."tag.label ORDER BY label SEPARATOR ' ') as tags, " ;
        // $sql .= " url, " ;
        // $sql .= " title, ";
        // $sql .= " description, ";
        // $sql .= " timecreate, " ;
        // $sql .= "  UCASE(DATE_FORMAT(timecreate, '%d %b %y')) as datecreate " ;
        // $sql .= "  FROM ".DB_TABLE_PREFIX."url " ;
        // $sql .= "  LEFT JOIN ".DB_TABLE_PREFIX."url_tag on ".DB_TABLE_PREFIX."url_tag.url_id = ".DB_TABLE_PREFIX."url.id " ;
        // $sql .= "  LEFT JOIN ".DB_TABLE_PREFIX."tag on ".DB_TABLE_PREFIX."tag.id = ".DB_TABLE_PREFIX."url_tag.tag_id " ;
        // $sql .= "  GROUP BY urlid ";
        // // $sql .= " LIMIT 10" ;
        // $sql .= ") as links " ;
        // $links = \ORM::forTable(DB_TABLE_PREFIX . 'url')->raw_query($sql)->find_array();
// $pdo = \ORM::get_db();
// $links = $pdo->query($sql);
// while($row = $links->fetch()) {
//     echo $row['title'] . "<br>";
// }
        // debug($sql);
        // debug($links);
        // debug(\ORM::get_query_log());

        $links = $this->dataProvider->getAllLinks();

        echo "<ul>";
        foreach ($links as $i => $link) {
            echo '<li>' . $link['title'] . '(' . $link['url'] . ') ' . join(' #', $link['tags']) . '</li>';
        }
        echo "</ul>";
    }
}
