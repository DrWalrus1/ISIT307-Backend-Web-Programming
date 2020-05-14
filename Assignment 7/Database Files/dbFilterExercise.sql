use games;

SELECT gName FROM genres where gID = 1;


# Filter on one attribute
SELECT *
FROM (SELECT games.gID, games.title, games.price, g.gName genreName, p.pName platformName, c.initial Classification
    FROM games
    LEFT JOIN genres g ON games.genre = g.gID
    LEFT JOIN platforms p on games.platform = p.pID
    LEFT JOIN classification c on games.classification = c.cID) as ggpc
WHERE ggpc.genreName = 'Action';

# Filter on two attributes
SELECT *
FROM (SELECT games.gID, games.title, games.price, g.gName genreName, p.pName platformName, c.initial Classification
    FROM games
    LEFT JOIN genres g ON games.genre = g.gID
    LEFT JOIN platforms p on games.platform = p.pID
    LEFT JOIN classification c on games.classification = c.cID) as ggpc
WHERE ggpc.genreName = 'Action'
AND ggpc.platformName = 'PC';

# Insert additional record
INSERT INTO games (title, price, genre, platform, classification) VALUES ('DOOM Eternal', 59.95, 1, 5, 4);

# Update existing record
UPDATE games
SET platform = 11
WHERE title = 'DOOM Eternal';

# Count total games in particular categories
SELECT g.gName Genre, COUNT(*) Total
FROM games
LEFT JOIN genres g on games.genre = g.gID
GROUP BY genre;