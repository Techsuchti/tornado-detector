-- Städte-Tabelle und Beispieldaten für Tornado-Detektor
-- Erstellt automatisch die 'cities' Tabelle mit deutschen Städten
-- 
-- Tornado-Detektor: Verwaltung deutscher Städte für Wetteranalyse
-- Version: 1.0
-- Datum: 30. August 2025

-- Erstelle die Städte-Tabelle falls sie nicht existiert
CREATE TABLE IF NOT EXISTS cities (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    latitude REAL NOT NULL,
    longitude REAL NOT NULL,
    bundesland TEXT,
    einwohner INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Lösche vorhandene Daten für saubere Installation
DELETE FROM cities;

-- Einfügen der wichtigsten deutschen Städte
-- Großstädte (über 500.000 Einwohner)
INSERT INTO cities (name, latitude, longitude, bundesland, einwohner) VALUES
('Berlin', 52.5200, 13.4050, 'Berlin', 3669491),
('Hamburg', 53.5511, 9.9937, 'Hamburg', 1899160),
('München', 48.1351, 11.5820, 'Bayern', 1487708),
('Köln', 50.9375, 6.9603, 'Nordrhein-Westfalen', 1087863),
('Frankfurt am Main', 50.1109, 8.6821, 'Hessen', 736414),
('Stuttgart', 48.7758, 9.1829, 'Baden-Württemberg', 626275),
('Düsseldorf', 51.2277, 6.7735, 'Nordrhein-Westfalen', 617280),
('Leipzig', 51.3397, 12.3731, 'Sachsen', 593145),
('Dortmund', 51.5136, 7.4653, 'Nordrhein-Westfalen', 588250),
('Essen', 51.4566, 7.0103, 'Nordrhein-Westfalen', 579432),
('Bremen', 53.0793, 8.8017, 'Bremen', 569352),
('Dresden', 51.0504, 13.7373, 'Sachsen', 554649),
('Hannover', 52.3759, 9.7320, 'Niedersachsen', 536925),
('Nürnberg', 49.4521, 11.0767, 'Bayern', 518365),
('Duisburg', 51.4344, 6.7623, 'Nordrhein-Westfalen', 498686);

-- Mittelstädte (200.000 - 500.000 Einwohner)
INSERT INTO cities (name, latitude, longitude, bundesland, einwohner) VALUES
('Bochum', 51.4819, 7.2160, 'Nordrhein-Westfalen', 364628),
('Wuppertal', 51.2562, 7.1508, 'Nordrhein-Westfalen', 354572),
('Bielefeld', 52.0302, 8.5325, 'Nordrhein-Westfalen', 334195),
('Bonn', 50.7374, 7.0982, 'Nordrhein-Westfalen', 327258),
('Münster', 51.9607, 7.6261, 'Nordrhein-Westfalen', 315293),
('Karlsruhe', 49.0069, 8.4037, 'Baden-Württemberg', 308436),
('Mannheim', 49.4875, 8.4660, 'Baden-Württemberg', 309817),
('Augsburg', 48.3705, 10.8978, 'Bayern', 299265),
('Wiesbaden', 50.0826, 8.2400, 'Hessen', 278474),
('Gelsenkirchen', 51.5177, 7.0857, 'Nordrhein-Westfalen', 260654),
('Mönchengladbach', 51.1805, 6.4428, 'Nordrhein-Westfalen', 261742),
('Braunschweig', 52.2689, 10.5268, 'Niedersachsen', 248292),
('Chemnitz', 50.8278, 12.9214, 'Sachsen', 247237),
('Kiel', 54.3233, 10.1228, 'Schleswig-Holstein', 247717),
('Aachen', 50.7753, 6.0839, 'Nordrhein-Westfalen', 248960),
('Halle (Saale)', 51.4969, 11.9695, 'Sachsen-Anhalt', 238762),
('Magdeburg', 52.1315, 11.6407, 'Sachsen-Anhalt', 238136),
('Freiburg im Breisgau', 47.9990, 7.8421, 'Baden-Württemberg', 229144),
('Krefeld', 51.3388, 6.5853, 'Nordrhein-Westfalen', 226551),
('Lübeck', 53.8655, 10.6866, 'Schleswig-Holstein', 217198),
('Oberhausen', 51.4963, 6.8781, 'Nordrhein-Westfalen', 210934),
('Erfurt', 50.9848, 11.0299, 'Thüringen', 213699),
('Mainz', 49.9929, 8.2473, 'Rheinland-Pfalz', 217118),
('Rostock', 54.0887, 12.1432, 'Mecklenburg-Vorpommern', 208886),
('Kassel', 51.3127, 9.4797, 'Hessen', 201585),
('Hagen', 51.3546, 7.4791, 'Nordrhein-Westfalen', 188687),
('Saarbrücken', 49.2401, 6.9969, 'Saarland', 179634);

-- Kleinere Städte für bessere regionale Abdeckung
INSERT INTO cities (name, latitude, longitude, bundesland, einwohner) VALUES
('Potsdam', 52.3906, 13.0645, 'Brandenburg', 180334),
('Hamm', 51.6806, 7.8142, 'Nordrhein-Westfalen', 179397),
('Ludwigshafen am Rhein', 49.4774, 8.4453, 'Rheinland-Pfalz', 172253),
('Mülheim an der Ruhr', 51.4266, 6.8827, 'Nordrhein-Westfalen', 170880),
('Oldenburg', 53.1435, 8.2146, 'Niedersachsen', 169605),
('Leverkusen', 51.0459, 6.9891, 'Nordrhein-Westfalen', 163838),
('Osnabrück', 52.2799, 8.0472, 'Niedersachsen', 164119),
('Solingen', 51.1681, 7.0836, 'Nordrhein-Westfalen', 158726),
('Heidelberg', 49.3988, 8.6724, 'Baden-Württemberg', 159914),
('Herne', 51.5386, 7.2255, 'Nordrhein-Westfalen', 156374),
('Neuss', 51.2044, 6.6895, 'Nordrhein-Westfalen', 152457),
('Regensburg', 49.0134, 12.1016, 'Bayern', 152610),
('Paderborn', 51.7189, 8.7545, 'Nordrhein-Westfalen', 151633),
('Ingolstadt', 48.7665, 11.4257, 'Bayern', 137392),
('Offenbach am Main', 50.1005, 8.7656, 'Hessen', 130280),
('Fürth', 49.4771, 10.9891, 'Bayern', 128497),
('Würzburg', 49.7913, 9.9534, 'Bayern', 127934),
('Ulm', 48.3974, 9.9934, 'Baden-Württemberg', 126329),
('Heilbronn', 49.1427, 9.2109, 'Baden-Württemberg', 125960),
('Pforzheim', 48.8919, 8.6940, 'Baden-Württemberg', 125542),
('Wolfsburg', 52.4227, 10.7865, 'Niedersachsen', 123840),
('Göttingen', 51.5412, 9.9159, 'Niedersachsen', 117665),
('Bottrop', 51.5216, 6.9285, 'Nordrhein-Westfalen', 117311),
('Trier', 49.7596, 6.6441, 'Rheinland-Pfalz', 110570),
('Recklinghausen', 51.6142, 7.1972, 'Nordrhein-Westfalen', 110714),
('Reutlingen', 48.4914, 9.2044, 'Baden-Würtemberg', 115456),
('Bremerhaven', 53.5396, 8.5809, 'Bremen', 113634),
('Koblenz', 50.3569, 7.5890, 'Rheinland-Pfalz', 113388),
('Bergisch Gladbach', 50.9851, 7.1382, 'Nordrhein-Westfalen', 111645),
('Jena', 50.9271, 11.5865, 'Thüringen', 108306),
('Remscheid', 51.1789, 7.1925, 'Nordrhein-Westfalen', 111338),
('Erlangen', 49.5897, 11.0044, 'Bayern', 112528),
('Moers', 51.4508, 6.6279, 'Nordrhein-Westfalen', 103725),
('Siegen', 50.8748, 8.0241, 'Nordrhein-Westfalen', 102355),
('Hildesheim', 52.1565, 9.9517, 'Niedersachsen', 101055);

-- Tornado-spezifische Tabelle für historische Daten erweitern
CREATE TABLE IF NOT EXISTS tornado_data (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    city_id INTEGER NOT NULL,
    tornado_score INTEGER NOT NULL CHECK(tornado_score >= 1 AND tornado_score <= 10),
    wind_direction INTEGER CHECK(wind_direction >= 0 AND wind_direction <= 360),
    risk_details TEXT, -- JSON-String mit detaillierten Risikofaktoren
    weather_conditions TEXT, -- JSON-String mit Wetterbedingungen
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (city_id) REFERENCES cities(id)
);

-- Index für bessere Performance bei Abfragen
CREATE INDEX IF NOT EXISTS idx_cities_name ON cities(name);
CREATE INDEX IF NOT EXISTS idx_cities_coordinates ON cities(latitude, longitude);
CREATE INDEX IF NOT EXISTS idx_tornado_data_city_timestamp ON tornado_data(city_id, timestamp);
CREATE INDEX IF NOT EXISTS idx_tornado_data_score ON tornado_data(tornado_score);

-- Informative Ausgabe
SELECT 'Städte-Datenbank erfolgreich initialisiert!' as Status;
SELECT COUNT(*) as 'Anzahl_Städte' FROM cities;
SELECT bundesland, COUNT(*) as 'Städte_pro_Bundesland' FROM cities GROUP BY bundesland ORDER BY COUNT(*) DESC;
