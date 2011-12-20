
 -- Karmia-projektin tietokantamalli ja alkuarvoja

CREATE TABLE kayttajat (
	tunnus varchar(8) PRIMARY KEY,
	salasana varchar(40) DEFAULT '',
	yllapeto boolean DEFAULT 'false',
	luotu timestamp DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kaarmeet (
	nimi varchar(60),
	laji int DEFAULT 0,
	id SERIAL PRIMARY KEY
);

CREATE TABLE lajit (
	laji varchar(40),
	latin varchar(80),
	alkupera int,
	vari varchar(100) DEFAULT '',
	myrkyllisyys int DEFAULT 0,
	uhanalaisuus varchar(2) DEFAULT '-',
	id SERIAL PRIMARY KEY
);

CREATE TABLE lainat (
	kaarme int,
	lainaaja varchar(8),
	alku timestamp DEFAULT CURRENT_TIMESTAMP,
	loppu timestamp,
	id SERIAL PRIMARY KEY
);

CREATE TABLE alkupera (
	id int PRIMARY KEY,
	alkupera varchar(20)
);

CREATE TABLE myrkyllisyys (
	id int PRIMARY KEY,
	myrkyllisyys varchar(20)
);

INSERT INTO lajit (laji, latin, alkupera) VALUES ('tuntematon', 'vermis obscura', 0);

INSERT INTO alkupera VALUES ( 0, 'tuntematon');
INSERT INTO alkupera VALUES ( 1, 'Australia');
INSERT INTO alkupera VALUES ( 2, 'Etelä-Amerikka');
INSERT INTO alkupera VALUES ( 3, 'Väli-Amerikka');
INSERT INTO alkupera VALUES ( 4, 'Pohjois-Amerikka');
INSERT INTO alkupera VALUES ( 5, 'Eurooppa');
INSERT INTO alkupera VALUES ( 6, 'Afrikka');
INSERT INTO alkupera VALUES ( 7, 'Kaukasia');
INSERT INTO alkupera VALUES ( 8, 'Aasia');
INSERT INTO alkupera VALUES ( 9, 'Etelä-Aasia');
INSERT INTO alkupera VALUES (10, 'Kaakkois-Aasia');

INSERT INTO myrkyllisyys VALUES (0, 'ei tiedossa');
INSERT INTO myrkyllisyys VALUES (1, 'ei myrkyllinen');
INSERT INTO myrkyllisyys VALUES (2, 'myrkyllinen');
INSERT INTO myrkyllisyys VALUES (3, 'tappavan myrkyllinen');
