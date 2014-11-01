<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/30/2014
 * Time: 4:03 PM


CREATE TABLE tournaments
(
tid INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
name VARCHAR(20) NOT NULL,
bracket VARCHAR(20),
size INT DEFAULT 8 NOT NULL,
prize INT,
status VARCHAR(10),
entrants TEXT,
winner VARCHAR(20)
);

CREATE TABLE icms.tourn_matches
(
mid INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
home VARCHAR(20) NOT NULL,
away VARCHAR(20) NOT NULL,
winner VARCHAR(20) NOT NULL
);
 *
CREATE TABLE icms.tourn_players
(
tid INT NOT NULL,
player_name VARCHAR(30) NOT NULL,
wins INT,
losses INT,
paid BOOLEAN DEFAULT FALSE  NOT NULL,
ready BOOLEAN DEFAULT FALSE  NOT NULL
);
 */