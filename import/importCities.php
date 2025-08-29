<?php
/**
 * Städte-Import-Skript für Tornado Detector
 * 
 * Dieses Skript importiert Städtedaten aus einer CSV-Datei in die SQLite-Datenbank.
 * Die CSV-Datei sollte das Format haben: Name,Latitude,Longitude
 * 
 * Verwendung: php importCities.php [csv-datei] [datenbank-datei]
 * Beispiel: php importCities.php cities.csv ../tornado.sqlite
 * 
 * @author Tornado Detector Team
 * @version 1.0
 */

// Standard-Pfade definieren
$defaultCsvFile = __DIR__ . '/cities.csv';
$defaultDbFile = __DIR__ . '/../tornado.sqlite';

// Kommandozeilen-Parameter auswerten
$csvFile = $argv[1] ?? $defaultCsvFile;
$dbFile = $argv[2] ?? $defaultDbFile;

// Hilfstext anzeigen
if (isset($argv[1]) && ($argv[1] === '--help' || $argv[1] === '-h')) {
    echo "\nStädte-Import-Skript für Tornado Detector\n";
    echo "==========================================\n\n";
    echo "Verwendung: php importCities.php [csv-datei] [datenbank-datei]\n\n";
    echo "Parameter:\n";
    echo "  csv-datei      Pfad zur CSV-Datei (Standard: ./cities.csv)\n";
    echo "  datenbank-datei Pfad zur SQLite-Datenbank (Standard: ../tornado.sqlite)\n\n";
    echo "CSV-Format: Name,Latitude,Longitude\n";
    echo "Beispiel: Berlin,52.5200,13.4050\n\n";
    echo "Datenquellen-Hinweise:\n";
    echo "- GeoNames (geonames.org) - Umfassende geografische Datenbank\n";
    echo "- OpenStreetMap (openstreetmap.org) - Freie Geodaten\n";
    echo "- Open Data Portale der Bundesländer und Kommunen\n\n";
    exit(0);
}

echo "\n=== Tornado Detector - Städte-Import ===\n";
echo "CSV-Datei: $csvFile\n";
echo "Datenbank: $dbFile\n\n";

// CSV-Datei prüfen
if (!file_exists($csvFile)) {
    die("❌ Fehler: CSV-Datei '$csvFile' nicht gefunden!\n");
}

if (!is_readable($csvFile)) {
    die("❌ Fehler: CSV-Datei '$csvFile' ist nicht lesbar!\n");
}

// Datenbank-Datei prüfen
if (!file_exists($dbFile)) {
    die("❌ Fehler: Datenbank '$dbFile' nicht gefunden!\n");
}

// SQLite-Verbindung aufbauen
try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Verbindung zur Datenbank hergestellt\n";
} catch (PDOException $e) {
    die("❌ Datenbankfehler: " . $e->getMessage() . "\n");
}

// Tabelle cities prüfen/erstellen
try {
    $checkTable = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='cities'");
    if ($checkTable->rowCount() == 0) {
        echo "⚠️  Tabelle 'cities' existiert nicht. Erstelle Tabelle...\n";
        $pdo->exec("
            CREATE TABLE cities (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                latitude REAL NOT NULL,
                longitude REAL NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE(name, latitude, longitude)
            )
        ");
        echo "✅ Tabelle 'cities' erstellt\n";
    } else {
        echo "✅ Tabelle 'cities' existiert bereits\n";
    }
} catch (PDOException $e) {
    die("❌ Fehler beim Erstellen der Tabelle: " . $e->getMessage() . "\n");
}

// CSV-Datei öffnen und importieren
$handle = fopen($csvFile, 'r');
if ($handle === false) {
    die("❌ Fehler: CSV-Datei konnte nicht geöffnet werden!\n");
}

// Header-Zeile überspringen (falls vorhanden)
$firstLine = fgetcsv($handle);
if ($firstLine && strtolower($firstLine[0]) === 'name') {
    echo "📋 Header-Zeile erkannt und übersprungen\n";
} else {
    // Erste Zeile ist kein Header, zurückspulen
    fseek($handle, 0);
}

// Import-Statement vorbereiten
$stmt = $pdo->prepare("
    INSERT OR IGNORE INTO cities (name, latitude, longitude) 
    VALUES (?, ?, ?)
");

$importCount = 0;
$errorCount = 0;
$lineNumber = 1;

echo "\n🔄 Starte Import...\n";

while (($row = fgetcsv($handle)) !== false) {
    $lineNumber++;
    
    // Zeile validieren
    if (count($row) < 3) {
        echo "⚠️  Zeile $lineNumber: Unvollständige Daten (" . implode(', ', $row) . ")\n";
        $errorCount++;
        continue;
    }
    
    $name = trim($row[0]);
    $latitude = (float) $row[1];
    $longitude = (float) $row[2];
    
    // Basale Validierung
    if (empty($name)) {
        echo "⚠️  Zeile $lineNumber: Stadtname fehlt\n";
        $errorCount++;
        continue;
    }
    
    if ($latitude < -90 || $latitude > 90) {
        echo "⚠️  Zeile $lineNumber: Ungültiger Breitengrad: $latitude\n";
        $errorCount++;
        continue;
    }
    
    if ($longitude < -180 || $longitude > 180) {
        echo "⚠️  Zeile $lineNumber: Ungültiger Längengrad: $longitude\n";
        $errorCount++;
        continue;
    }
    
    // Datensatz einfügen
    try {
        $stmt->execute([$name, $latitude, $longitude]);
        if ($stmt->rowCount() > 0) {
            $importCount++;
            if ($importCount % 100 == 0) {
                echo "📊 $importCount Städte importiert...\n";
            }
        }
    } catch (PDOException $e) {
        echo "⚠️  Zeile $lineNumber: Fehler beim Einfügen von '$name': " . $e->getMessage() . "\n";
        $errorCount++;
    }
}

fclose($handle);

// Zusammenfassung anzeigen
echo "\n=== Import-Zusammenfassung ===\n";
echo "✅ Erfolgreich importiert: $importCount Städte\n";
if ($errorCount > 0) {
    echo "⚠️  Fehler/Übersprungen: $errorCount Zeilen\n";
}

// Gesamtanzahl der Städte in der Datenbank
$totalCount = $pdo->query("SELECT COUNT(*) FROM cities")->fetchColumn();
echo "📊 Gesamt in Datenbank: $totalCount Städte\n";

echo "\n✅ Import abgeschlossen!\n\n";

// Beispiele für weitere Nutzung anzeigen
if ($importCount > 0) {
    echo "💡 Nützliche SQL-Abfragen:\n";
    echo "   - Alle Städte: SELECT * FROM cities;\n";
    echo "   - Städte in der Nähe: SELECT * FROM cities WHERE latitude BETWEEN 52.0 AND 53.0 AND longitude BETWEEN 13.0 AND 14.0;\n";
    echo "   - Städte löschen: DELETE FROM cities WHERE name LIKE '%Test%';\n\n";
}

?>
