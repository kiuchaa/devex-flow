# Pixel Flow Theme - CLAUDE.md

Detailed guide for working with the **Pixel Flow** WordPress theme.

## 🌟 Theme Overview
Pixel Flow is a premium WordPress 6.x theme built with **ACF Pro (Blocks)** and **Bootstrap 5.3+**. It emphasizes a "Pixel Flow" workflow where content is structured into modular blocks, ensuring visual excellence and performance through selective asset loading.

### Key Technologies
- **PHP**: WordPress 6.x.
- **Fields**: ACF Pro (JSON-synced).
- **Styling**: SCSS (Bootstrap 5.3 integrated).
- **Automation**: Gulp for SCSS to CSS compilation.
- **Architecture**: BEM (Block-Element-Modifier) for CSS scoping.

---

## 🛠 Project Workflows

### `/block` - Creating ACF Blocks
1. **Scaffold**: Run `npm run make:block "{Block Name}"` to generate PHP and SCSS files.
2. **Register**: Add the block to `$pixel_flow_blocks` in `/functions/acf.php`.
3. **ACF JSON**: Create/Update JSON in `/acf-json/` with prefix `field_block_{slug}_`.
   - **Tab 1: Inhoud** (Dutch label for Content).
   - **Tab 2: Opties** (Dutch label for Styling/Settings).
4. **Style**: Edit `/assets/scss/blocks/{slug}.scss` (Selective loading).

### `/component` - Creating Reusable Components
1. **Template**: Create `/components/{name}.php`. Components should be "dumb" (UI-only).
2. **Interface**: Pass data via the `$args` array.
3. **Integration**: Use `get_template_part('components/{name}', null, $args)`.

---

## 📜 Coding Standards

### Naming & Language
- **Logic**: Use English for all code, variables, and logic.
- **CMS**: Use **Dutch** for all Admin labels, field titles, and CMS messages.
- **Prefixing**: All global PHP functions must start with `pixel_flow_`.
- **Files**: Use `kebab-case` for all files, slugs, and CSS classes.

### Styling (SCSS)
- **Scoping**: Strict BEM scoping. Root class must match the block/component slug.
- **Variables**: Always use Bootstrap variables or theme tokens from `_common-vars.scss`.

### Security
- Always escape outputs using `esc_html()`, `esc_attr()`, `wp_kses_post()`.
- Use `esc_url()` for all links.

---

## 🚀 Tooling & Commands

### PHPStorm File Watcher
For developers using PHPStorm, a file watcher is pre-configured in `.tools/watchers.xml`.
- **Function**: Automatically runs `npm run build` on every explicit save of an SCSS file.
- **Benefit**: You don't need a separate terminal with `npm run dev` running if you use this watcher.

### Manual Commands
- `npm run make:block "{Block Name}"` - Scaffold a new block (PHP/SCSS).
- `npm run dev` - (Gulp) Start watcher for developers not using the PHPStorm file watcher.
- `npm run build` - (Gulp) One-time production build of all SCSS assets.

> [!TIP]
> If you are using PHPStorm, ensure the file watcher is enabled to automate the "Pixel Flow" asset compilation. For all other editors, keep `npm run dev` active in your terminal.
