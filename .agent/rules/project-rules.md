---
trigger: always_on
---


## 1. Core Directives for Agents
- **Be Proactive**: If a user asks for a block, scaffold it, register it in PHP, and prepare the SCSS in one go.
- **Search First**: Before creating a new component, search `/components/` and `/acf-blocks/` to prevent duplication.
- **Context Awareness**: Always check `functions.php` and its included files (like `functions/acf.php`) for global logic patterns.
- **Reminders**: After CSS/SCSS changes, remind the developer to ensure `npm run dev` is running or to execute `npm run build`.

## 2. Technical Stack & Environment
- **Platform**: WordPress 6.x / ACF PRO (Blocks).
- **Styling**: SCSS (Bootstrap 5.3+ integrated).
- **Automation**: Gulp for SCSS compilation.
- **Scaffolding**: `npm run make:block "{Name}"` (node scripts/make-block.js).

## 3. The "Pixel Flow" Workflow

### A. Creating ACF Blocks (The Core Unit)
1. **Scaffolding**: Execute `npm run make:block "{Block Name}"` to create the PHP/SCSS skeleton.
2. **PHP Registration**:
   - MUST add the block to `$pixel_flow_blocks` in `/functions/acf.php`.
   - **Label Rule**: Titles/Labels MUST be in **Dutch**. Keywords can be English.
   - **Icon**: Choose a relevant Dashicon (e.g., `admin-generic`, `megaphone`, `list-view`).
3. **Field Configuration (`acf-json`)**:
   - All field keys MUST follow: `field_block_{block_slug}_{field_name}`.
   - Organise fields into two ACF Tabs:
     - **Tab 1: Inhoud** (Content)
     - **Tab 2: Opties** (Styling/Settings)
   - Use Group fields to keep the namespace clean if necessary.
4. **Rendering**:
   - Use the `$classes` logic provided in the scaffold.
   - Separate UI logic into `/components/` if the UI element is reusable.

### B. Creating Components
- **Location**: `/components/{name}.php`.
- **Nature**: "Dumb" components (UI only). No heavy database queries.
- **Interface**: Data must be passed via the `$args` array.
- **Integration**: Use `get_template_part('components/{name}', null, $args)`.

### C. SCSS & Styling Standards
- **Scoping**: Strict BEM (Block-Element-Modifier). The root class must be the block slug.
- **Selective Loading**: The theme uses a selective CSS loading system.
  - Gulp compiles `/assets/scss/blocks/{slug}.scss` into `/assets/css/blocks/{slug}.css`.
  - PHP automatically enqueues the `.css` file only when the block is on the page.
- **Variables**: Always use `$primary`, `$secondary`, and Bootstrap spacers from `_common-vars.scss` or `_variables.scss`.

## 4. Coding Standards (Agent Enforcement)
- **Namespacing**: Prefix global functions with `pixel_flow_`.
- **Security**: 
  - ALWAYS escape outputs: `esc_html()`, `esc_attr()`, `wp_kses_post()`.
  - Use `esc_url()` for links.
- **Naming**: `kebab-case` for all files, slugs, and class names.
- **Language**: 
  - **English**: Code, variables, logic, documentation, commits.
  - **Dutch**: Admin labels, field titles, CMS messages.

## 5. Aesthetics & UX Philosophy
- **WOW Factor**: Designs should feel premium. Use smooth transitions and modern typography.
- **Mobile First**: All blocks must look flawless on mobile. Use Bootstrap grid (`.row`, `.col-lg-X`).
- **Micro-interactions**: Suggest/add subtle hover effects and entry animations.

## 6. Project Checklist for Agents
- [ ] Is the block registered in `/functions/acf.php`?
- [ ] Are labels Dutch?
- [ ] Are field keys prefixed correctly?
- [ ] Is output escaped?
- [ ] Is the SCSS scoped correctly?
- [ ] Have I reminded the user to run the build task?