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

## Bijdragen als Developer (op de juiste manier)

Om ervoor te zorgen dat updates correct worden doorgegeven aan alle sites, moet je deze workflow volgen.

### 1. Vereisten & Installatie
Dit thema gebruikt een moderne build-tool (Gulp) om SCSS te compileren, wat sneller en beter werkt dan standaard Sass watchers.
*   **Node.js & NPM**: Zorg dat je Node.js geïnstalleerd hebt.
*   **Installatie**: Run `npm install` in de thema-root om de benodigde tools (Gulp, Sass, en plugins) te installeren.
    *   *Belangrijk*: De `node_modules` map wordt **niet** naar de productieserver gepusht (zie `.gitignore`). Deze is enkel bedoeld voor lokale ontwikkeling om jouw SCSS bestanden te compileren naar één correcte `style.css` die wel op de server komt.

### 2. IDE Configuratie (PHPStorm)
Wij gebruiken een uniforme instelling voor PHPStorm om automatisch te compileren bij het opslaan. Dit zorgt ervoor dat de output accuraat genereerd wordt via Gulp.
1.  Ga naar **Settings / Preferences** > **Tools** > **File Watchers**.
2.  Klik op het import-icoon (pijltje omhoog/mapje).
3.  Selecteer het bestand `.tools/watchers.xml` uit dit project.
4.  Zorg dat de "Gulp SCSS" watcher is ingeschakeld.
    *   *Let op*: Controleer of het pad naar 'Program' (npm) klopt voor jouw systeem (bijv. Windows vs Mac).

### 3. Ontwikkeling & Styling
Bewerk alleen bestanden in de `assets/scss/` map. Je kunt tijdens het ontwikkelen ook `npm run dev` in je terminal draaien voor een continue watch-taak.

Wij splitsen onze logica als volgt:
- **Global**: `themes/_theme.scss` voor algemene zaken (body, typography).
- **Variables**: `variables/` voor CMS kleuren en instellingen.
- **Blocks (Auto-Import)**:
    - Plaats bestanden in `assets/scss/blocks/`.
    - **Nieuw!** Alle bestanden in deze map worden **automatisch** ingeladen via Gulp. Je hoeft dus géén `@import` meer toe te voegen.
- **Components (Manual Import)**:
    - Componenten in `components/` moeten nog wel handmatig geregistreerd worden in `components/_components.scss`.

### 4. Build & Versiebeheer (Cruciaal voor PUC)
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
