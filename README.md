# Pixel Flow Theme

Welkom bij de **Pixel Flow Theme**. Dit is een geavanceerd WordPress base-theme, ontworpen voor maximale efficiëntie, modulariteit en automatische updates via GitHub.

## Hoe dit thema werkt

Pixel Flow is gebouwd met een moderne workflow in gedachten:
- **SCSS Architectuur**: Gebruikt een modulaire opzet om styling georganiseerd te houden.
- **Bootstrap 5 Geïntegreerd**: Alleen de onderdelen die je daadwerkelijk gebruikt worden meegeleverd in de uiteindelijke build.
- **Autoloading**: PHP functies worden automatisch ingeladen vanuit de `functions/` map. Zorgt voor een zeer schone installatie, zonder Wordpress meuk.

## Plugin Update Checker (PUC)

Dit thema maakt gebruik van de **Plugin Update Checker** bibliotheek. Dit betekent dat sites die dit thema gebruiken automatisch meldingen krijgen van nieuwe versies, net zoals thema's op WordPress.org.

### Hoe updates werken:
1. De bibliotheek controleert de `Version:` header in de root `style.css` op de GitHub `main` branch.
2. Als de versie op GitHub hoger is dan de lokaal geïnstalleerde versie, verschijnt er een update-melding in het WordPress Dashboard.
3. Bij het klikken op "Nu bijwerken", downloadt WordPress de nieuwste bestanden rechtstreeks van GitHub.

## Project Structuur

Hieronder volgt een overzicht van de belangrijkste mappen en bestanden:

```text
pixel-flow/
├── assets/
│   ├── scss/                # Alle styling bronbestanden
│   │   ├── components/      # Herbruikbare UI componenten
│   │   ├── sections/        # Specifieke pagina-secties
│   │   ├── variables/       # Bootstrap & Thema variabelen
│   │   └── style.scss       # Hoofdbestand (importeert alle modules)
│   └── js/                  # JavaScript bestanden
├── functions/               # PHP Logica
│   ├── hooks/               # WordPress acties en filters
│   ├── autoload.php         # Laadt automatisch alle PHP bestanden
│   └── puc.php              # Configuratie voor de Update Checker
├── libs/                    # Externe bibliotheken (o.a. PUC)
├── template-parts/          # Herbruikbare HTML templates
├── functions.php            # WordPress entry point (minimaal)
├── package.json             # Build scripts en dependencies
├── style.css                # Gecompileerde CSS (niet handmatig bewerken!)
└── .gitignore               # Zorgt dat node_modules niet in Git komen
```

## Bijdragen als Developer (op het juiste manier)

Om ervoor te zorgen dat updates correct worden doorgegeven aan alle sites, moet je deze workflow volgen:

### 1. Voorbereiding
Het is geadviseerd om gebruik te maken van PHPStorm, een IDE die wij met z'n allen gebruiken.

Zorg dat er een correcte file-watcher is ingesteld. Gebruik hiervoor de PHPStorm's import file watcher; selecteer .tools/watchers.xml
- Dit is belangrijk omdat het style.scss moet schrijven naar het bestand CSS in de root folder van een thema.

### 2. Ontwikkeling & Styling
Bewerk alleen bestanden in de `assets/scss/` map. Wij splitsen onze (S)CSS logica, met de volgende guidelines:
- Global / Theme: `themes/_theme.scss` voor algemene zaken zoals body styling, links, of utility klassen die overal op de site worden gebruikt
- Variables: kijkt naar de kleuren die zijn gedefinieërd in het CMS, een manual override is mogelijk via `variables/_theme.scss`
- Nieuwe modules: maak je een nieuwe component aan? Maak dan een nieuwe component.scss aan via `components/my-new-component.scss`. Importeer deze dan in `components/_components.scss` met `@import "my-new-component";`

### 3. Build & Versiebeheer (Cruciaal voor PUC)
Zodra je klaar bent om je wijzigingen te pushen:

1.  **Compileer de CSS**: Zorg ervoor dat de File Watcher jouw wijzigingen heeft doorgevoerd aan de `style.css` in de thema root folder.
2.  **Verhoog de Versie**: Pas het versienummer aan bovenaan in `assets/scss/style.scss`. De build zorgt dat dit ook in de root `style.css` komt te staan.
3. **Git Workflow & OTA Updates**: Wij gebruiken een gestructureerd model om de stabiliteit van klantwebsites te garanderen terwijl de ontwikkelsnelheid hoog blijft. De Plugin Update Checker (PUC) monitort de main branch voor nieuwe tags om updates te pushen.
   - Main Branch: De stabiele bron van waarheid. Zodra een versie-tag naar deze branch wordt gepusht, wordt de update direct beschikbaar voor alle klantensites via OTA. 
   - Develop Branch: De primaire integratiebranch voor nieuwe features. Hier vindt de dagelijkse ontwikkeling plaats voordat deze naar een release gaat. 
   - Feature Branches (feature/*): Geïsoleerde branches voor specifieke componenten of optimalisaties; deze worden uiteindelijk gemerged naar develop. 
   - Hotfix Branches (hotfix/*): Noodoplossingen die direct vanaf main worden afgesplitst om direct een performance-fix te pushen zonder onvoltooide functies uit develop mee te nemen.
4. **Develop PR naar Main**: Wanneer een PR is gemerged naar main, maak dan via het web-ui van Git een nieuwe release aan, dit wordt vervolgens gedetecteerd door PUC, en maakt het mogelijk voor elke klant met deze thema om te updaten.
## Belangrijke regels
- **Bewerk NOOIT de root `style.css` direct**. Je wijzigingen gaan verloren bij de volgende build.
- **Gooi de `libs/` map niet weg**. Hierin staat de code voor de automatische updates.
- **Houd `functions.php` minimaal**. Plaats nieuwe logica in de `functions/` map; deze wordt automatisch geladen.

## Onderhoud
Elke thema maakt gebruik van 2 verschillende libraries (/libs/), dit vraagt dan ook om wat onderhoud wanneer iets niet optimaal werkt. Deze zijn onderscheidend gemaakt van:
```
- install-required-plugins
- plugin-update-checker
```
- 28-01-2026: wanneer de install-required-plugins een API fout geeft bij het installeren, check het `auto-install-plugin.php` bestand en check of de slug overeenkomt met de Wordpress store voor elk plug-in.
