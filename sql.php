<?php

$_sql_auth_tunnistus = "SELECT tunnus, yllapeto FROM kayttajat WHERE tunnus = '%s' AND salasana = '%s'";

$_sql_uusi_kayttaja = "INSERT INTO kayttajat VALUES ('%s', '%s')";

$_sql_lainaa_kaarme  = "INSERT INTO lainat (kaarme, lainaaja) VALUES (%s, '%s')";
$_sql_palauta_kaarme = "UPDATE lainat SET loppu = CURRENT_TIMESTAMP WHERE lainaaja = '%s' AND kaarme = %s AND loppu IS NULL";

$_sql_json_megakysely = <<<MEGA
SELECT k.id,
       k.nimi,
       l.laji,
       l.latin,
       a.alkupera,
       l.vari,
       m.myrkyllisyys,
       l.uhanalaisuus,
       CASE WHEN t.lainaaja = '%s' THEN 'sulla'
            WHEN t.lainaaja IS NOT NULL THEN 'varattu'
            ELSE 'vapaa'
       END AS laina
FROM   lajit l,
       alkupera a,
       myrkyllisyys m,
       kaarmeet k
       LEFT OUTER JOIN lainat t
       ON k.id = t.kaarme AND
          t.loppu IS NULL
WHERE  k.laji = l.id AND
       l.alkupera = a.id AND
       l.myrkyllisyys = m.id
MEGA;

$_sql_hali_kayttajat = "SELECT tunnus, yllapeto, luotu FROM kayttajat ORDER BY luotu";
$_sql_hali_kaarmeet  = "SELECT id, nimi, laji FROM kaarmeet ORDER BY id";
$_sql_hali_lajit     = "SELECT id, laji, latin, alkupera, vari, myrkyllisyys, uhanalaisuus FROM lajit ORDER BY id";

$_sql_hali_poista_kayttaja = "DELETE FROM kayttajat WHERE tunnus = '%s'";
$_sql_hali_poista_kaarme   = "DELETE FROM kaarmeet WHERE id = %s";
$_sql_hali_poista_laji     = "DELETE FROM lajit WHERE laji = '%s'";

?>
