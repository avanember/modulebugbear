<?php

/**
 * adatbazis beallitasok
 */
$db["host"] = "localhost";
$db["user"] = "";
$db["pass"] = "";
$db["db"] = "";
/*
 * adatbazis kapcsolodas
 */
$mysql_conn = mysql_connect($db["host"], $db["user"], $db["pass"]) or die(mysql_error());
$mysql_db = mysql_select_db($db["db"], $mysql_conn) or die(mysql_error());
/*
 * amelyik user-re kivancsiak vagyunk
 */
$user = "modulebugbear@randomthings.com";
/*
 * lekerdezes, kapott adatok feldolgozasa
 */
$res = mysql_query("select t1.day as day, sum(t1.impression*t1.amount) as amount from statistic t1 left join "
        . "banner t2 on t1.banner_id = t2.id left join user t3 on t2.user_id = t3.id "
        . "where t3.email='" . $user . "' group by t1.day order by t1.day") or die(mysql_error());
$num = mysql_numrows($res) or die(mysql_error());
if ($num > 0) {
    $json = array();
    /*
     * az elso elem a user(0) neve lesz, amit majd js-ben shiftelek
     */
    $json[] = array($user);
    /*
     * fejlec
     */
    $json[] = array("Mikor", "Mennyit");
    while ($arr = mysql_fetch_assoc($res)) {
        /*
         * adatok
         */
        $json[] = array($arr["day"], number_format($arr["amount"], 0, ",", ".") . " Ft");
    }
    /*
     * kiiratjuk az elokeszitett tombot json formatumban
     */
    echo json_encode($json);
} else {
    /*
     * nincs adat
     */
}
/*
 * lezarjuk az adatbazis kapcsolatot
 */
mysql_close($mysql_conn);
