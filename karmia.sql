
 -- Karmia-projektin tietokantamalli ja alkuarvoja

CREATE TABLE kayttajat (
	tunnus varchar(8) PRIMARY KEY,
	salasana varchar(40) DEFAULT '',
	yllapeto boolean DEFAULT 'false'
);

CREATE TABLE kaarmeet (
	id int PRIMARY KEY,
	nimi varchar(60),
	laji int DEFAULT 0
);

CREATE TABLE lajit (
	id int PRIMARY KEY,
	nimi varchar(40),
	latin varchar(100),
	alkupera int,
	vari varchar(100),
	myrkyllisyys int DEFAULT 0,
	uhanalaisuus varchar(2) DEFAULT ''
);

CREATE TABLE lainat (
	id int PRIMARY KEY,
	kaarme varchar(40),
	lainaaja varchar(8),
	alku timestamp DEFAULT CURRENT_TIMESTAMP,
	loppu timestamp
);

CREATE TABLE alkupera (
	id int PRIMARY KEY,
	alkupera varchar(20)
);

CREATE TABLE myrkyllisyys (
	id int PRIMARY KEY,
	myrkyllisyys varchar(20)
);

INSERT INTO lajit VALUES (0, 'tuntematon', 'obscura vermis', NULL, '', 0, '');

INSERT INTO alkupera VALUES (0, 'Australia');
INSERT INTO alkupera VALUES (1, 'Etelä-Amerikka');
INSERT INTO alkupera VALUES (2, 'Väli-Amerikka');
INSERT INTO alkupera VALUES (3, 'Pohjois-Amerikka');
INSERT INTO alkupera VALUES (4, 'Eurooppa');
INSERT INTO alkupera VALUES (5, 'Afrikka');
INSERT INTO alkupera VALUES (6, 'Kaukasia');
INSERT INTO alkupera VALUES (7, 'Aasia');
INSERT INTO alkupera VALUES (8, 'Etelä-Aasia');
INSERT INTO alkupera VALUES (9, 'Kaakkois-Aasia');

INSERT INTO myrkyllisyys VALUES (0, 'ei tiedossa');
INSERT INTO myrkyllisyys VALUES (1, 'ei myrkyllinen');
INSERT INTO myrkyllisyys VALUES (2, 'myrkyllinen');
INSERT INTO myrkyllisyys VALUES (3, 'tappavan myrkyllinen');

 -- DROP TABLE kayttajat;
 -- DROP TABLE kaarmeet;
 -- DROP TABLE lajit;
 -- DROP TABLE lainat;
 -- DROP TABLE alkupera;
 -- DROP TABLE myrkyllisyys;
