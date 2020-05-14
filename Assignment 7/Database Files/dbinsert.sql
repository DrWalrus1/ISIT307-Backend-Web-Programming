use games;
# TODO: Consider implementing measures to prevent duplication of consoles
INSERT INTO platforms (initial, pName, manufacturer) VALUES ('PS4', 'PlayStation 4', 'PlayStation');

INSERT INTO platforms (initial, pName, manufacturer) VALUES ('PS3', 'PlayStation 3', 'PlayStation');

INSERT INTO platforms (initial, pName, manufacturer) VALUES ('PSVITA', 'PlayStation Vita', 'PlayStation');

INSERT INTO platforms (initial, pName, manufacturer) VALUES ('Xbox One', 'Xbox One', 'Xbox');

INSERT INTO platforms (initial, pName, manufacturer) VALUES ('Xbox 360', 'Xbox 360', 'Xbox');

INSERT INTO platforms (initial, pName, manufacturer) VALUES ('Switch', 'Nintendo Switch', 'Nintendo');

INSERT INTO platforms (initial, pName, manufacturer) VALUES ('Wii', 'Nintendo Wii', 'Nintendo');

INSERT INTO platforms (initial, pName, manufacturer) VALUES ('Wii U', 'Nintendo Wii U', 'Nintendo');

INSERT INTO platforms (initial, pName, manufacturer) VALUES ('3DS', 'Nintendo 3DS', 'Nintendo');

INSERT INTO platforms (initial, pName, manufacturer) VALUES ('DS', 'Nintendo DS', 'Nintendo');

INSERT INTO platforms (initial, pName, manufacturer) VALUES ('PC', 'PC', 'PC');

INSERT INTO classification (initial, description) VALUES ('G', 'General');

INSERT INTO classification (initial, description) VALUES ('PG', 'Parental Guidance');

INSERT INTO classification (initial, description) VALUES ('M', 'Mature');

INSERT INTO classification (initial, description) VALUES ('MA', 'Mature Accompanied 15+');

INSERT INTO classification (initial, description) VALUES ('R', 'Restricted 18+');

INSERT INTO genres (gName, description) VALUES ('Action', 'Action');

INSERT INTO genres (gName, description) VALUES ('Adventure', 'Adventure');

INSERT INTO genres (gName, description) VALUES ('Family', 'Family');

INSERT INTO genres (gName, description) VALUES ('Fighting', 'Fighting');

INSERT INTO genres (gName, description) VALUES ('Horror', 'Horror');

INSERT INTO genres (gName, description) VALUES ('Music', 'Music');

INSERT INTO genres (gName, description) VALUES ('Puzzle', 'Puzzle');

INSERT INTO genres (gName, description) VALUES ('Racing', 'Racing');

INSERT INTO genres (gName, description) VALUES ('Role Playing', 'Role Playing');

INSERT INTO genres (gName, description) VALUES ('Shooter', 'Shooter');

INSERT INTO genres (gName, description) VALUES ('Simulation', 'Simulation');

INSERT INTO genres (gName, description) VALUES ('Sport', 'Sport');

INSERT INTO genres (gName, description) VALUES ('Strategy', 'Strategy');


SELECT * FROM platforms;
SELECT * FROM classification;
SELECT * FROM genres;