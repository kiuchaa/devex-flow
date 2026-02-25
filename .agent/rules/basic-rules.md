---
trigger: always_on
---

# AntiGravity AI Agent: System Rules & Protocol (v4.2)

## 1. Core Architecture & File Mapping

| Feature Type | Directory Location | Naming Convention |
| ACF Block (PHP) | /acf-blocks/ | {block-name}.php |
| Block Styles (SCSS) | /assets/scss/blocks/ | {block-name}.scss |
| ACF Field Group (JSON) | /acf-json/ | {slugified-title}.json |
| Reusable Component | /components/ | {component-name}.php |

## 2. Intelligence & Asset Scanning

Before code generation, perform "Site Context Scan":
- Check /components/ for existing elements; use `get_template_part()`
- Search theme files for existing SCSS variables, mixins, utility classes
- All new code follows BEM methodology

## 3. The /block Workflow

### Step 1: PHP Template (/acf-blocks/)
- Create {block-name}.php
- Wrapper class must match block name (e.g., `<section class="hero-v1">`)
- Include components via `get_template_part()`

### Step 2: SCSS Styling (/assets/scss/blocks/)
- Create {block-name}.scss
- Scope all styles within main block class
- No global leakage; use variables for colors/spacing

### Step 3: ACF JSON Definition (/acf-json/)
- **Filename**: slugified version of field group title (e.g., "Blok: FAQ" → blok-faq.json)
- **Key prefix**: `field_block_{block-name}_`
- **Tab Architecture**:
  - Tab 1 "Inhoud": Content fields inside ACF Group
  - Tab 2 "Opties": Styling/config fields inside ACF Group
- **Location**: `"value": "acf/{block-name}"` (never "all")

## 4. The /component Workflow

### Step 1: PHP Template (/components/)
- Create {component-name}.php
- Use `$args` array for dynamic data with default values
- Single root element with class matching filename

### Step 2: Integration Logic
- Components are "dumb" (UI-focused); logic handled by parent
- Use `get_template_part('components/{component-name}', null, $args)`

### Step 3: Styling (SCSS)
- Follow BEM; no conflicts with parent block styles

## 5. Maintenance & JSON Synchronization
- Search directories to find correct existing files when editing
- Update `"modified"` key to current Unix timestamp when editing ACF JSON

## 6. Safety & Quality Standards
- No redundancy; reuse /components/
- Strict BEM scoping to prevent CSS collisions
- Never use generic keys (e.g., `field_title`); always use specific prefixes