
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
	vari int,
	myrkyllisyys int,
	aggressiivisuus int,
	uhanalaisuus varchar(2)
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

CREATE TABLE vari (
	id int PRIMARY KEY,
	vari varchar(20)
);

CREATE TABLE myrkyllisyys (
	id int PRIMARY KEY,
	myrkyllisyys varchar(20)
);

CREATE TABLE aggressiivisuus (
	id int PRIMARY KEY,
	aggressiivisuus varchar(20)
);

INSERT INTO lajit VALUES (0, 'ei tiedossa', 'obscura', 0, 0, 0, 0, '?');
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
