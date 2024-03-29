
INSERT INTO lajit VALUES ('paraguaynanakonda', 'Eunectes notaeus', 1, 'keltainen, mustat täplät', 1, 'NE');
INSERT INTO lajit VALUES ('viheranakonda', 'Eunectes murinus', 1, 'oliivinvihreä, mustat täplät', 1, '');
INSERT INTO lajit VALUES ('aavikkotaipaani', 'Oxyuranus microlepitodus', 0, 'talvella ruskea, kesällä oliivinvihreä', 3, '');
INSERT INTO lajit VALUES ('teksasinkalkkarokäärme', 'Crotalus atrox', 3, 'kirjava harmaa ruskea', 3, 'LC');
INSERT INTO lajit VALUES ('kuningaskobra', 'Ophiophagus hannah', 9, 'oliivinvihreä, musta, keltaiset raidat', 3, 'VU');
INSERT INTO lajit VALUES ('mustamamba', 'Dendroaspis polylepsis', 5, 'oliivinvihreä, metallinharmaa', 3, 'LC');
INSERT INTO lajit VALUES ('vihermamba', 'Dendroaspis angusticeps', 5, 'ruohonvihreä, keltaisenvihreä vatsa', 3, '');
INSERT INTO lajit VALUES ('kyy', 'Vipera berus', 5, 'harmaanruskea, sahalaitakuvio', 2, 'LC');
INSERT INTO lajit VALUES ('rantakäärme', 'Natrix natrix', 5, 'oliivinvihreä, harmaanvihreä, niskatäplät', 1, 'LC');

INSERT INTO kaarmeet (nimi, laji) VALUES ('Enska', (SELECT id FROM lajit WHERE laji = 'paraguaynanakonda'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Ofelia', (SELECT id FROM lajit WHERE laji = 'viheranakonda'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Tuomas', (SELECT id FROM lajit WHERE laji = 'aavikkotaipaani'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Alf', (SELECT id FROM lajit WHERE laji = 'teksasinkalkkarokäärme'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Krtek', (SELECT id FROM lajit WHERE laji = 'kuningaskobra'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Nget', (SELECT id FROM lajit WHERE laji = 'kuningaskobra'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Mustikka', (SELECT id FROM lajit WHERE laji = 'mustamamba'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Billy-Bob', (SELECT id FROM lajit WHERE laji = 'vihermamba'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Kalevi', (SELECT id FROM lajit WHERE laji = 'kyy'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Toivo', (SELECT id FROM lajit WHERE laji = 'kyy'));
INSERT INTO kaarmeet (nimi, laji) VALUES ('Rane', (SELECT id FROM lajit WHERE laji = 'rantakäärme'));
