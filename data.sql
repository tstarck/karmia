
 -- Testidataa

INSERT INTO lajit VALUES ('paraguaynanakonda', 'Eunectes notaeus', 1, 'keltainen, mustat täplät', 1, 'NE');
INSERT INTO lajit VALUES ('viheranakonda', 'Eunectes murinus', 1, 'oliivinvihreä, mustat täplät', 1, '');
INSERT INTO lajit VALUES ('aavikkotaipaani', 'Oxyuranus microlepitodus', 0, 'talvella ruskea, kesällä oliivinvihreä', 3, '');
INSERT INTO lajit VALUES ('teksasinkalkkarokäärme', 'Crotalus atrox', 3, 'kirjava harmaa ruskea', 3, 'LC');
INSERT INTO lajit VALUES ('kuningaskobra', 'Ophiophagus hannah', 9, 'oliivinvihreä, musta, keltaiset raidat', 3, 'VU');
INSERT INTO lajit VALUES ('mustamamba', 'Dendroaspis polylepsis', 5, 'oliivinvihreä, metallinharmaa', 3, 'LC');
INSERT INTO lajit VALUES ('vihermamba', 'Dendroaspis angusticeps', 5, 'ruohonvihreä, keltaisenvihreä vatsa', 3, '');
INSERT INTO lajit VALUES ('kyy', 'Vipera berus', 5, 'harmaanruskea, sahalaitakuvio', 2, 'LC');
INSERT INTO lajit VALUES ('rantakäärme', 'Natrix natrix', 5, 'oliivinvihreä, harmaanvihreä, niskatäplät', 1, 'LC');

INSERT INTO kaarmeet (nimi, laji) VALUES ('Enska', (SELECT id FROM lajit WHERE nimi = 'paraguaynanakonda'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Ofelia', (SELECT id FROM lajit WHERE nimi = 'viheranakonda'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Tuomas', (SELECT id FROM lajit WHERE nimi = 'aavikkotaipaani'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Alf', (SELECT id FROM lajit WHERE nimi = 'teksasinkalkkarokäärme'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Krtek', (SELECT id FROM lajit WHERE nimi = 'kuningaskobra'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Mustikka', (SELECT id FROM lajit WHERE nimi = 'mustamamba'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Billy-Bob', (SELECT id FROM lajit WHERE nimi = 'vihermamba'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Kalevi', (SELECT id FROM lajit WHERE nimi = 'kyy'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Rane', (SELECT id FROM lajit WHERE nimi = 'rantakäärme'));

 -- LC, Least Concern, elinvoimainen
 -- NT, Near Threatened, silmälläpidettävä
 -- VU, Vulnerable, vaarantunut
 -- EN, Endangered, erittäin uhanalainen
 -- CR, Critically Endangered, äärimmäisen uhanalainen
 -- EW, Extinct in the Wild, luonnosta hävinnyt
 -- EX, Extinct, hävinnyt
 -- DD, Data Deficient, puutteellisesti tunnettu
 -- NE, Not Evaluated, arvioimatta jätetty
