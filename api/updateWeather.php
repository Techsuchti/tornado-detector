<?php
/**
 * Wetterdaten-Aktualisierung für Tornado-Gefahren-Erkennung
 * 
 * Dieses Skript ruft aktuelle Wetterdaten von verschiedenen APIs ab
 * und analysiert sie auf Tornado-Indikatoren.
 * 
 * @author Tornado-Detector Team
 * @version 1.0
 * @date 2025-08-30
 */

require_once '../config/config.php';
require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

/**
 * Haupt-Update-Funktion
 */
function updateWeatherData() {
    try {
        // Verbindung zur SQLite-Datenbank
        $db = new PDO('sqlite:../db/tornado.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Beispiel-Städte für Deutschland
        $cities = [
            ['name' => 'Berlin', 'lat' => 52.5200, 'lon' => 13.4050],
            ['name' => 'Hamburg', 'lat' => 53.5511, 'lon' => 9.9937],
            ['name' => 'München', 'lat' => 48.1351, 'lon' => 11.5820],
            ['name' => 'Köln', 'lat' => 50.9375, 'lon' => 6.9603],
            ['name' => 'Frankfurt', 'lat' => 50.1109, 'lon' => 8.6821]
        ];
        
        $results = [];
        
        foreach ($cities as $city) {
            $weatherData = fetchWeatherData($city['lat'], $city['lon']);
            $tornadoScore = calculateTornadoRisk($weatherData);
            
            // Daten in Datenbank speichern
            saveWeatherData($db, $city, $weatherData, $tornadoScore);
            
            $results[] = [
                'city' => $city['name'],
                'score' => $tornadoScore,
                'timestamp' => date('Y-m-d H:i:s'),
                'weather' => $weatherData
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Wetterdaten erfolgreich aktualisiert',
            'data' => $results,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Fehler beim Aktualisieren der Wetterdaten: ' . $e->getMessage(),
            'error_code' => $e->getCode()
        ];
    }
}

/**
 * Wetterdaten von API abrufen (Beispiel-Implementation)
 */
function fetchWeatherData($lat, $lon) {
    // TODO: Echte Wetter-API implementieren (z.B. OpenWeatherMap)
    // Für Demo-Zwecke simulierte Daten
    
    return [
        'temperature' => rand(15, 35),
        'humidity' => rand(40, 90),
        'pressure' => rand(980, 1030),
        'wind_speed' => rand(5, 50),
        'wind_direction' => rand(0, 360),
        'precipitation' => rand(0, 20),
        'clouds' => rand(0, 100),
        'visibility' => rand(5, 15)
    ];
}

/**
 * Tornado-Risiko basierend auf Wetterdaten berechnen
 */
function calculateTornadoRisk($weather) {
    $score = 0;
    
    // Temperatur-Faktor
    if ($weather['temperature'] > 25) $score += 2;
    if ($weather['temperature'] > 30) $score += 1;
    
    // Luftfeuchtigkeit
    if ($weather['humidity'] > 70) $score += 2;
    if ($weather['humidity'] > 80) $score += 1;
    
    // Luftdruck (niedrig = höheres Risiko)
    if ($weather['pressure'] < 1000) $score += 2;
    if ($weather['pressure'] < 990) $score += 2;
    
    // Windgeschwindigkeit
    if ($weather['wind_speed'] > 30) $score += 3;
    if ($weather['wind_speed'] > 40) $score += 2;
    
    // Niederschlag
    if ($weather['precipitation'] > 10) $score += 1;
    if ($weather['precipitation'] > 15) $score += 1;
    
    // Begrenzung auf 1-10 Skala
    return min(max($score, 1), 10);
}

/**
 * Wetterdaten in Datenbank speichern
 */
function saveWeatherData($db, $city, $weather, $score) {
    $stmt = $db->prepare("
        INSERT OR REPLACE INTO weather_data 
        (city_name, latitude, longitude, temperature, humidity, pressure, 
         wind_speed, wind_direction, precipitation, clouds, visibility, 
         tornado_score, timestamp) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $city['name'],
        $city['lat'],
        $city['lon'],
        $weather['temperature'],
        $weather['humidity'],
        $weather['pressure'],
        $weather['wind_speed'],
        $weather['wind_direction'],
        $weather['precipitation'],
        $weather['clouds'],
        $weather['visibility'],
        $score,
        date('Y-m-d H:i:s')
    ]);
}

// API-Endpunkt ausführen
if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = updateWeatherData();
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Methode nicht erlaubt. Nur GET und POST unterstützt.'
    ], JSON_UNESCAPED_UNICODE);
}
?>
