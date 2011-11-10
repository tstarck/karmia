CREATE TABLE kayttajat (
--id, nimi, yllapeto
	tunnus varchar(8) PRIMARY KEY,
	salasana varchar(40) DEFAULT '',	
	yllapeto boolean DEFAULT 'false'
);

CREATE TABLE kaarmeet (
--id, nimi, laji
	id int PRIMARY KEY,
	nimi varchar(60),
	laji int DEFAULT 0
);

CREATE TABLE lajit (
--id, nimi, alkupera, vari, myrkyllisyys, aggressiivisuus, uhanalaisuus
	id int PRIMARY KEY,
	nimi varchar(40),
	latin varchar(100),
	alkupera int,
	vari int,
	myrkyllisyys int,
	aggressiivisuus int,
	uhanalaisuus varchar(2)
);

CREATE TABLE lainat (
--id, käärme, lainaaja, lainan alku, lainan loppu
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

CREATE TABLE vari (
	id int PRIMARY KEY,
	alkupera varchar(20)
);

CREATE TABLE myrkyllisyys (
	id int PRIMARY KEY,
	alkupera varchar(20)
);

CREATE TABLE aggressiivisuus (
	id int PRIMARY KEY,
	alkupera varchar(20)
);

INSERT INTO lajit VALUES (0, 'ei tiedossa', 'obscurus', 0, 0, 0, 0, '?');
INSERT INTO alkupera VALUES (0, 'ei tiedossa');
INSERT INTO vari VALUES (0, 'ei tiedossa');
INSERT INTO myrkyllisyys VALUES (0, 'ei tiedossa');
INSERT INTO aggressiivisuus VALUES (0, 'ei tiedossa');

 -- DROP TABLE kayttajat;
 -- DROP TABLE kaarmeet;
 -- DROP TABLE lajit;
 -- DROP TABLE lainat;
 -- DROP TABLE alkupera;
 -- DROP TABLE vari;
 -- DROP TABLE myrkyllisyys;
 -- DROP TABLE aggressiivisuus;
