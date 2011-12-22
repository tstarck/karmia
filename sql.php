<?php

/* Käyttäjätietojen kysely
 */
$_sql_auth_tunnistus = "SELECT tunnus, yllapeto, luotu FROM kayttajat WHERE tunnus = '%s' AND salasana = '%s'";

/* Käyttäjän lisääminen
 */
$_sql_uusi_kayttaja  = "INSERT INTO kayttajat VALUES ('%s', '%s')";

/* Käärmeen lainaaminen ja palauttaminen
 */
$_sql_lainaa_kaarme  = "INSERT INTO lainat (kaarme, lainaaja) VALUES (%s, '%s')";
$_sql_palauta_kaarme = "UPDATE lainat SET loppu = CURRENT_TIMESTAMP WHERE lainaaja = '%s' AND kaarme = %s AND loppu IS NULL";

/* Käyttäjän lainalista
 */
$_sql_oma_lainat = <<<LAINAT
SELECT   l.id,
         COALESCE(k.nimi, '-') AS nimi,
         l.alku,
         l.loppu
FROM     lainat l

LEFT OUTER JOIN kaarmeet k
         ON k.id = l.kaarme

WHERE    l.lainaaja = '%s'

ORDER BY l.loppu DESC NULLS FIRST

LIMIT    42
LAINAT;

$_sql_json_kaikkimullehetinyt = <<<JSON
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
       ON k.id = t.kaarme AND t.loppu IS NULL

WHERE  k.laji = l.id AND
       l.alkupera = a.id AND
       l.myrkyllisyys = m.id
JSON;

/* Isohalin listat
 */
$_sql_hali_kayttajat = "SELECT tunnus, yllapeto, luotu FROM kayttajat ORDER BY luotu";
$_sql_hali_kaarmeet  = "SELECT id, nimi, laji FROM kaarmeet ORDER BY id";
$_sql_hali_lajit     = "SELECT id, laji, latin, alkupera, vari, myrkyllisyys, uhanalaisuus FROM lajit ORDER BY id";
$_sql_hali_alkuperat = "SELECT id, alkupera FROM alkupera ORDER BY id";

/* Anna käyttäjälle ylläpeto-oikeudet
 */
$_sql_hali_promoa_kayttaja  = "UPDATE kayttajat SET yllapeto = true WHERE tunnus = '%s'";

/* Lisää uusi käärme tai laji
 */
$_sql_hali_uusi_kaarme      = "INSERT INTO kaarmeet VALUES ('%s', '%s')";
$_sql_hali_uusi_laji        = "INSERT INTO lajit VALUES ('%s', '%s', '%s', '%s', '%s', '%s')";

/* Käyttäjän avoimet lainat suljetaan,
 * jos käyttäjä poistetaan
 */
$_sql_hali_pois_kayt_lainat = "UPDATE lainat SET loppu = CURRENT_TIMESTAMP WHERE lainaaja = '%s' AND loppu IS NULL";
$_sql_hali_poista_kayttaja  = "DELETE FROM kayttajat WHERE tunnus = '%s'";

/* Käärmettä koskevat avoimet lainat
 * suljetaan, jos käärme poistetaan
 */
$_sql_hali_pois_kaar_lainat = "UPDATE lainat SET loppu = CURRENT_TIMESTAMP WHERE kaarme = '%s' AND loppu IS NULL";
$_sql_hali_poista_kaarme    = "DELETE FROM kaarmeet WHERE id = %s";

/* Jos lajiluokka poistetaan, pitää lajin
 * käärmeet merkitä tuntemattomiksi
 */
$_sql_hali_refaktoroi_kaarmeet = "UPDATE kaarmeet SET laji = 1 WHERE laji = (SELECT id FROM lajit WHERE laji LIKE '%s')";
$_sql_hali_poista_laji         = "DELETE FROM lajit WHERE laji = '%s'";

?>
