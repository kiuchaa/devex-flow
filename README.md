# Pixel Flow Theme

Welkom bij de **Pixel Flow Theme** - een solide WordPress base-theme gebouwd voor maximale efficiëntie, modulariteit en automatische updates via GitHub. Lees dit even door voordat je begint.

## Hoe dit thema werkt

Pixel Flow is gebouwd met een moderne workflow in gedachten:
- **Block-specific asset loading & minification**: Gulp compileert styles per ACF block apart naar `assets/css/blocks/` en minified ze. Ze worden alleen geladen wanneer het block daadwerkelijk op de pagina staat - goed voor performance.
- **One-click block scaffolding**: Genereer direct nieuwe blokken via `npm run make:block "Naam"`. Het script bouwt automatisch de PHP/SCSS structuur en registreert het block.
- **Auto-register ACF blocks**: Drop gewoon een PHP bestand in `acf-blocks/` en het block is beschikbaar in de WordPress Editor.
- **Bootstrap 5 geïntegreerd**: Alleen de onderdelen die je daadwerkelijk gebruikt komen mee in de uiteindelijke build.
- **Autoloading**: PHP functies worden automatisch ingeladen vanuit de `functions/` map - houdt alles netjes.

## Project structuur

```
pixel-flow/
├── acf-blocks/              # PHP templates voor auto-registered blokken
├── acf-json/                # Gesynchroniseerde ACF Field Groups
├── assets/
│   ├── css/                 # Gecompileerde CSS (niet handmatig bewerken!)
│   │   └── blocks/          # Geïsoleerde en geminified CSS per block
│   ├── scss/                # Alle styling bronbestanden
│   │   ├── blocks/          # SCSS per block (wordt apart gecompileerd)
│   │   ├── components/      # Herbruikbare UI componenten
│   │   ├── sections/        # Specifieke pagina-secties
│   │   ├── variables/       # Bootstrap & thema variabelen
│   │   └── style.scss       # Hoofdbestand (importeert alle globale modules)
│   └── js/                  # JavaScript bestanden
├── components/              # Dumb UI componenten
├── functions/               # PHP logica (auto-loaded)
│   ├── hooks/               # WordPress acties en filters
│   ├── autoload.php         # Laadt automatisch alle PHP bestanden
│   └── puc.php              # Configuratie voor de Update Checker
├── libs/                    # Externe bibliotheken (o.a. PUC)
├── scripts/                 # Node.js scripts (o.a. make-block.js)
├── template-parts/          # Herbruikbare HTML templates
├── functions.php            # WordPress entry point (minimaal)
├── package.json             # Build scripts en dependencies (NPM commands)
├── style.css                # Gecompileerde algemene CSS
└── .gitignore               # Zorgt dat node_modules niet in Git komen
```

---

## Belangrijk tijdens het developen

Dit zijn dingen die je regelmatig moet doen of in je achterhoofd moet houden. Sla deze niet over, anders krijg je gezeik.

### ACF blocks & JSON - altijd syncen na een wijziging

**Na elke wijziging (of na het binnenhalen van wijzigingen van een ander)** moet je de `acf-blocks/` en `acf-json/` mappen binnenhalen via PHPStorm:

> **Deployment → Download from [remote]**

Dit geldt ook omgekeerd: wanneer je zelf iets aanpast via het CMS, haal dan direct daarna de wijzigingen op. Anders krijg je inconsistenties op de site en loop je je vrolijk vast in sync-problemen.

### Hoe ACF met JSON omgaat

Het CMS kijkt **altijd eerst** naar de JSON bestanden in `acf-json/`. Wil je een veld aanpassen? Doe het dan direct in de JSON, niet via het CMS. Dat voorkomt conflicten en onverwachte overschrijvingen.

Heb je toch via het CMS een veld aangepast? Haal dan direct de nieuwe versie op via **Deployment → Download from remote** in PHPStorm.

### Unix timestamps voor ACF sync

Wanneer je een ACF veld handmatig in de JSON bijwerkt en wil dat het correct gesynchroniseerd wordt naar de database, heb je een verse unix timestamp nodig. Die haal je hier op:

- [https://www.unixtimestamp.com/](https://www.unixtimestamp.com/)

### Klantwebsites - altijd een child theme

Een klantwebsite moet **altijd** een child theme gebruiken van de main Pixel Flow theme. Zo worden klantspecifieke aanpassingen niet overschreven bij een update, en houd je maatwerk keurig gescheiden.

---

## Build setup & Node.js

### Node.js & file watchers

Je hebt **Node.js** nodig om de file watchers te draaien. Dit is geen optie - Gulp's SCSS bulk compiler draait hier op. Zonder Node draait er geen watcher en worden je SCSS wijzigingen niet gecompileerd.

De npm commands zelf (`npm run build`, `npm run dev`) zijn handig maar niet verplicht, als je de file watchers hebt ingesteld in PHPStorm hoef je ze niet handmatig te draaien.

Installeer de dependencies eenmalig via:
```bash
npm install
```

> De `node_modules` map wordt **niet** gepusht naar de server - die is alleen voor lokaal gebruik.

**Beschikbare npm commands:**
- `npm run build` - compileert en minified `style.scss` en alle block stijlen
- `npm run dev` - start de Gulp watcher voor live compilatie tijdens ontwikkeling
- `npm run make:block "Blok Naam"` - genereert direct een PHP file in `/acf-blocks` en SCSS file in `/assets/scss/blocks`

### IDE configuratie (PHPStorm)

PHPStorm kan automatisch compileren via file watchers zodra je iets opslaat:

1. Ga naar **Settings / Preferences** → **Tools** → **File Watchers**
2. Klik op het import-icoon
3. Selecteer `.tools/watchers.xml` uit dit project
4. Zorg dat de "Gulp SCSS" watcher aan staat
5. Check of het pad naar 'Program' (npm) klopt voor jouw systeem (Windows vs Mac)

### Styling

Bewerk alleen bestanden in `assets/scss/`. Logica is als volgt gesplitst:

- **Global**: `themes/_theme.scss` voor body, typography, etc.
- **Variables**: `variables/` voor CMS kleuren en instellingen
- **Blocks (geïsoleerde compilatie)**:
    - Bestanden in `assets/scss/blocks/` worden apart gecompileerd naar `/assets/css/blocks/`
    - Elk block *moet* `@import "_common-vars";` bovenaan hebben (dit gebeurt automatisch via het make:block script)
    - **Geen** globale imports van blocks in `style.scss`
- **Components (handmatige import)**:
    - Componenten in `components/` moet je nog wel zelf importeren in `components/_components.scss`

---

## Plugin Update Checker (PUC)

Dit thema maakt gebruik van de **Plugin Update Checker** bibliotheek, zodat sites die het thema gebruiken automatisch update-meldingen krijgen - net zoals bij thema's op WordPress.org.

### Hoe updates werken

1. De bibliotheek checkt de `Version:` header in `style.css` op de GitHub `main` branch
2. Is de versie op GitHub hoger? Dan verschijnt er een melding in het WordPress Dashboard
3. Bij "Nu bijwerken" downloadt WordPress de nieuwste bestanden direct van GitHub

---

## Build & versiebeheer (cruciaal voor PUC)

Voordat je pusht:

1. **Compileer de CSS** - zorg dat de file watcher je wijzigingen heeft doorgevoerd in de root `style.css`
2. **Verhoog de versie** - pas het versienummer aan bovenaan in `assets/scss/style.scss`, de build regelt de rest
3. **Git workflow**:
    - **Main branch** - stabiele bron. Zodra een versie-tag naar main wordt gepusht, is de update live voor alle klantensites via OTA
    - **Develop branch** - dagelijkse ontwikkeling, features komen hier samen voor een release
    - **Feature branches** (`feature/*`) - geïsoleerde branches per feature/component, mergen uiteindelijk naar develop
    - **Hotfix branches** (`hotfix/*`) - directe noodoplossingen afgesplitst van main, zonder onvoltooide features uit develop mee te nemen
4. **PR naar main** - wanneer een PR gemerged is naar main, maak dan via de Git web-UI een nieuwe release aan. PUC pikt dit op en alle klanten kunnen updaten.

---

## Bekende quirks

Kleine dingen die je vroeg of laat tegenkomt:

- **CMS blijft nieuwe ACF JSON bestanden aanmaken** - als het CMS je bestaande JSON bestand negeert en telkens een nieuw bestand aanmaakt, verwijder dan het automatisch gegenereerde bestand via de **File Explorer in Plesk**. Het juiste bestand (met de juiste naam) blijft dan over.
- **ACF block hernoemd?** - als je een acf-block bestandsnaam wijzigt, moet je soms het oude bestand handmatig verwijderen via Plesk. Dit is een beetje buggy helaas.

---

## Belangrijke regels

- **Bewerk NOOIT de root `style.css` direct** - je wijzigingen gaan verloren bij de volgende build
- **Gooi de `libs/` map niet weg** - hierin staat de code voor automatische updates
- **Houd `functions.php` minimaal** - nieuwe logica hoort in de `functions/` map, die wordt automatisch geladen

---

## Onderhoud

Elke installatie gebruikt twee libraries in `/libs/`:

```
- install-required-plugins
- plugin-update-checker
```

**28-01-2026**: als `install-required-plugins` een API fout geeft bij het installeren, check dan `auto-install-plugin.php` en verifieer of de slug overeenkomt met de WordPress store voor elk plugin.