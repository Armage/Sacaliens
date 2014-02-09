<?php

/*
 * This file is part of Sacaliens
 * Copyright (c) 2009 Patrick Paysant
 *
 * Saaliens is free software; you can redistribute it and/or
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
namespace Utils;

class Utils
{

    public static function ajax_debug($var, $text = "")
    {
        if (DEBUG) {
            $file = "/tmp/log.txt";
            $h = fopen($file, 'a');
            if (is_array($var)) {
                fputs($h, date('d/m/Y H:i:s') . ' array ' . $text . "\n");
                foreach ($var as $key => $val) {
                    fputs($h, '    [' . $key . '] => :' . $val . ":\n");
                }
            } else {
                fputs($h, date('d/m/Y H:i:s') . ' ' . $text . ' :' . $var . ":\n");
            }
            fclose($h);
        }
    }

    public static function debug($var, $msg = "", $lvl = 0, $border = false)
    {
        if (DEBUG) {
            if (php_sapi_name() == 'cli') {
                $space_char = " ";
                $cr_char = "";
            } else {
                $space_char = "&nbsp;";
                $cr_char = "<br />";
            }

            $tabul = str_repeat($space_char . $space_char, $lvl);
            ;

            if ($border) {
                echo '<div style="background-color:#d99;text-align:left;margin:5px;padding:5px;color:black;border:3px solid red;">';
            }

            if (is_array($var)) {
                echo $tabul . $msg . " (array)" . $cr_char . "\n";
                foreach ($var as $key => $val) {
                    debug($val, "[$key]", $lvl + 1);
                }
            } elseif (is_object($var)) {
                $array = array();
                $array = (array) $var;
                echo $tabul . $msg . " (object " . get_class($var) . ") " . $cr_char . "\n";
                debug($array, "", $lvl + 1);
            } elseif (is_bool($var)) {
                $boolean2string = ($var) ? "TRUE" : "FALSE";
                echo $tabul . $msg . " (boolean):" . $boolean2string . ":" . $cr_char . "\n";
            } else {
                echo $tabul . $msg . " (" . gettype($var) . "):" . $var . ":" . $cr_char . "\n";
            }

            if ($border) {
                echo "</div>";
            }
        }
    }

    public static function getDateFromMysqlDatetime($mysqlDatetime)
    {
        list ($date, $time) = explode(' ', $mysqlDatetime);
        return $date;
    }

    public static function getLang()
    {
        $lang = '';

        if (isset($_SESSION['s_id_user']) and ($_SESSION['s_id_user']) != '') {
            $db = \Armg\DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);
            $sql = "select lang from " . DB_TABLE_PREFIX . "user where id = " . $_SESSION['s_id_user'];
            $lang = $db->queryFetchFirstField($sql);
        }

        if (empty($lang)) {
            $lang = DEFAULT_LANG;
        }

        return $lang;
    }

    public static function loadLang($lang = '')
    {
        global $_t;

        if ($lang == '') {
            $lang = DEFAULT_LANG;
        }

        if (file_exists(SYS_LANG . '/lang_' . $lang . '.php')) {
            if ($lang != DEFAULT_LANG) {
                include_once (SYS_LANG . '/lang_' . DEFAULT_LANG . '.php');
            }
            include_once (SYS_LANG . '/lang_' . $lang . '.php');
        } else {
            include_once (SYS_LANG . '/lang_EN.php');
        }
    }

    public static function isAndroid()
    {
        return stristr($_SERVER['HTTP_USER_AGENT'], 'android');
    }

    /**
     * store the query url (to be able to "header location" to it later)
     */
    public static function storeUrlInCookie($url = '')
    {
        if ($url == '')
            return;
        setcookie('request', $url, 0, WEB_APP);
    }
}