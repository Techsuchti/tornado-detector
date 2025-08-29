# Tornado-Detector

🌪️ **Tornado-Gefahren-Erkennung anhand Wetterdaten-APIs mit Visualisierung auf PHP-Website**

## Projektbeschreibung

Das Tornado-Detector System ist eine umfassende Lösung zur Erkennung und Visualisierung von Tornado-Gefahren in Deutschland. Das System nutzt aktuelle Wetterdaten von verschiedenen APIs, analysiert diese auf Tornado-Indikatoren und stellt die Ergebnisse über eine interaktive Kartenvisualisierung mit OpenStreetMap zur Verfügung.

### Hauptfunktionen

- ⚡ **Echtzeit-Wetterdatenanalyse** von deutschen Städten
- 🎯 **Tornado-Risiko-Scoring** auf einer Skala von 1-10
- 🗺️ **Interaktive Kartenvisualisierung** mit OpenStreetMap
- 📊 **Darstellung der Zugrichtung** und Bewegungsmuster
- 💾 **SQLite-Datenbank** für historische Datenanalyse
- 🔄 **Automatische Datenaktualisierung** über API-Endpunkte

## Technologie-Stack

- **Backend**: PHP 7.4+
- **Datenbank**: SQLite
- **Frontend**: HTML5, CSS3, JavaScript
- **Kartensystem**: OpenStreetMap mit Leaflet.js
- **APIs**: Wetterdaten-APIs (OpenWeatherMap, etc.)

## Projektstruktur

```
tornado-detector/
├── .gitignore                  # Git-Ignore für PHP/SQLite
├── README.md                   # Diese Datei
├── api/
│   ├── updateWeather.php      # Wetterdaten-Update-Endpunkt
│   └── getWeather.php         # Wetterdaten-Abruf-Endpunkt
├── db/
│   └── tornado.sqlite         # SQLite-Beispieldatenbank
├── public/
│   ├── index.php             # Haupt-Webseite
│   └── js/
│       └── tornadoMap.js     # JavaScript für Kartenvisualisierung
└── config/
    └── config.php            # Konfigurationsdatei
```

## Installation und Setup

### Voraussetzungen

- PHP 7.4 oder höher
- SQLite3-Unterstützung
- Webserver (Apache/Nginx)
- Wetter-API-Schlüssel (z.B. OpenWeatherMap)

### Installation

1. **Repository klonen**
   ```bash
   git clone https://github.com/Techsuchti/tornado-detector.git
   cd tornado-detector
   ```

2. **Konfiguration anpassen**
   ```bash
   cp config/config.php.example config/config.php
   # API-Schlüssel und Datenbankpfad anpassen
   ```

3. **Datenbank initialisieren**
   - Die SQLite-Datenbank wird automatisch erstellt
   - Beispieldaten sind in `db/tornado.sqlite` verfügbar

4. **Webserver konfigurieren**
   - Document Root auf `public/` Verzeichnis setzen
   - PHP-Module: `pdo_sqlite`, `curl`, `json`

## API-Endpunkte

### Wetterdaten aktualisieren
```
GET/POST /api/updateWeather.php
```
**Beschreibung**: Ruft aktuelle Wetterdaten ab und berechnet Tornado-Scores

**Antwort-Beispiel**:
```json
{
  "success": true,
  "message": "Wetterdaten erfolgreich aktualisiert",
  "data": [
    {
      "city": "Berlin",
      "score": 7,
      "timestamp": "2025-08-30 01:08:00",
      "weather": {
        "temperature": 28,
        "humidity": 85,
        "pressure": 995,
        "wind_speed": 45,
        "wind_direction": 270
      }
    }
  ]
}
```

### Wetterdaten abrufen
```
GET /api/getWeather.php[?city=Berlin&limit=10]
```
**Beschreibung**: Ruft gespeicherte Wetterdaten und Tornado-Scores ab

## Tornado-Risiko-Bewertung

Das System bewertet das Tornado-Risiko auf einer Skala von **1-10** basierend auf:

- 🌡️ **Temperatur**: Höhere Temperaturen erhöhen das Risiko
- 💧 **Luftfeuchtigkeit**: Hohe Luftfeuchtigkeit begünstigt Tornadoentstehung
- 📈 **Luftdruck**: Niedriger Luftdruck ist ein Risikoindikator
- 💨 **Windgeschwindigkeit**: Starke Winde erhöhen das Tornado-Potenzial
- 🌧️ **Niederschlag**: Gewitter und Sturmsysteme

### Risikokategorien

| Score | Kategorie | Beschreibung | Farbe |
|-------|-----------|--------------|-------|
| 1-2   | Niedrig   | Minimales Tornado-Risiko | 🟢 Grün |
| 3-4   | Gering    | Leicht erhöhtes Risiko | 🟡 Gelb |
| 5-6   | Mittel    | Mäßiges Tornado-Risiko | 🟠 Orange |
| 7-8   | Hoch      | Erhöhte Tornado-Gefahr | 🔴 Rot |
| 9-10  | Extrem    | Sehr hohe Tornado-Gefahr | 🟣 Violett |

## Kartenvisualisierung

Die interaktive Karte zeigt:

- 📍 **Städte-Marker** mit farbkodierten Risikostufen
- 🎯 **Tornado-Scores** als Popup-Informationen
- ➡️ **Zugrichtung** basierend auf Windrichtung
- 📊 **Verlaufsdaten** der letzten Stunden
- 🔄 **Automatische Updates** alle 15 Minuten

## Datenbank-Schema

### Tabelle: weather_data
```sql
CREATE TABLE weather_data (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    city_name TEXT NOT NULL,
    latitude REAL NOT NULL,
    longitude REAL NOT NULL,
    temperature REAL,
    humidity INTEGER,
    pressure REAL,
    wind_speed REAL,
    wind_direction INTEGER,
    precipitation REAL,
    clouds INTEGER,
    visibility REAL,
    tornado_score INTEGER,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

## Entwicklung und Beitrag

### Entwicklungsumgebung

1. PHP-Entwicklungsserver starten:
   ```bash
   php -S localhost:8000 -t public/
   ```

2. API-Tests durchführen:
   ```bash
   curl http://localhost:8000/api/updateWeather.php
   ```

### Code-Standards

- **PSR-4** Autoloading-Standard
- **Deutschsprachige** Kommentare und Dokumentation
- **Fehlerbehandlung** mit try-catch-Blöcken
- **JSON-Antworten** mit UTF-8-Kodierung

## Sicherheitshinweise

- 🔐 API-Schlüssel in Umgebungsvariablen speichern
- 🛡️ Input-Validierung für alle Benutzereingaben
- 🚫 SQLite-Datenbank außerhalb des Web-Root
- 🔒 HTTPS für Produktionsumgebung

## Roadmap

- [ ] **Erweiterte Wettermodelle** (ECMWF, DWD)
- [ ] **Mobile App** für iOS/Android
- [ ] **Warnbenachrichtigungen** per E-Mail/SMS
- [ ] **Historische Datenanalyse** mit ML
- [ ] **Multi-Language Support** (EN, FR, ES)

## Lizenz

Dieses Projekt ist unter der MIT-Lizenz lizenziert. Siehe [LICENSE](LICENSE) für Details.

## Kontakt und Support

- 📧 **E-Mail**: [info@tornado-detector.de](mailto:info@tornado-detector.de)
- 🐛 **Issues**: [GitHub Issues](https://github.com/Techsuchti/tornado-detector/issues)
- 📖 **Wiki**: [Projektdokumentation](https://github.com/Techsuchti/tornado-detector/wiki)

---

**Entwickelt mit ❤️ für die Sicherheit in Deutschland 🇩🇪**

*Letzte Aktualisierung: 30. August 2025*
