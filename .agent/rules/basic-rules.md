---
trigger: always_on
---

# AntiGravity AI Agent: System Rules & Protocol (v4.0)

---

## 1. Core Architecture & File Mapping
Every feature you build must follow a strict naming and structural convention. 

| Feature Type | Directory Location | Naming Convention |
| :--- | :--- | :--- |
| **ACF Block (PHP)** | `/acf-blocks/` | `{block-name}.php` |
| **Block Styles (SCSS)** | `/assets/scss/blocks/` | `{block-name}.scss` |
| **ACF Field Group (General)** | `/acf-json/` | `group_{name}.json` |
| **ACF Field Group (Block)** | `/acf-json/` | `group_block_{block-name}.json` |
| **Reusable Component** | `/components/` | `{component-name}.php` |

---

## 2. Intelligence & Asset Scanning
Before generating any new code, you must perform a "Site Context Scan" to prevent duplication and ensure visual harmony.
* **Component Reuse (CRITICAL):** Scan the `/components/` library for existing reusable UI elements (e.g., `button.php`, `card.php`). If a component exists that matches the required UI, you must use `get_template_part()` to load it instead of rewriting the logic.
* **SCSS Reuse:** Scan all existing files in the theme's folder for SCSS classes, variables, or mixins.
* **Avoid Redundancy:** Do not add unnecessary classes. If a class already exists in the theme that achieves the desired style, reuse it.
* **Style Alignment:** New blocks and components must match the existing codebase's architectural patterns (e.g., BEM methodology) and be visually consistent with the rest of the website.

---

## 3. ACF Block Creation Protocol (`acfblock`)
When creating a new ACF block (e.g., "hero-v1"), execute the following steps in order:

1.  **PHP Logic:** Create the template file in `/acf-blocks/hero-v1.php`. Ensure the wrapper element uses a class matching the block name (e.g., `.hero-v1`). Integrate existing components via `get_template_part()` where applicable.
2.  **Styles:** Create the corresponding SCSS file in `/assets/scss/blocks/hero-v1.scss`. The filename **must** match the block name exactly. Scope all CSS strictly within the main block class.
3.  **Field Definition (JSON):** * Create a new JSON file in `/acf-json/`.
    * **Filename Rule:** Because this is a block, the file must be named with the `group_block_` prefix (e.g., `group_block_hero-v1.json`). 
    * **Key Uniqueness:** The group `key` must match the prefix rule (e.g., `group_block_hero-v1`). All individual field keys inside the JSON **must** be prefixed with `field_block_{block-name}_` to prevent cross-contamination in the WordPress database.
    * **Tab Architecture (CRITICAL):** The fields within the JSON must be strictly organized into two tabs. You must use reasoning to route generated fields into their appropriate tab:
        * **Tab 1: "Inhoud" (Top position):** Must contain an ACF Group field. **Routing Logic:** Place all content-related fields here (e.g., text, headings, WYSIWYG editors, images, links, repeating content).
        * **Tab 2: "Opties":** Must contain an ACF Group field. **Routing Logic:** Place all configuration and styling fields here (e.g., background colors, layout toggles, margins/padding adjustments, alignment choices).
* **Location Constraint (CRITICAL):** The `location` rule in the JSON file MUST always target the specific block name.
    * **CORRECT:** `"value": "acf/{block-name}"`
    * **FORBIDDEN:** `"value": "all"` (This causes fields to leak into every other block).
    * **VERIFY:** Always double-check that the generated JSON does not contain `"value": "all"`.
---

## 4. Component Strategy
**Components** are fixed code snippets (e.g., Buttons, Icons, Cards) located in the `/components/` folder.
* **Fixed Placement:** Components are intended for UI elements that appear multiple times in fixed positions or within other blocks.
* **Integration:** Leverage components inside ACF blocks (via `get_template_part()`) to maintain DRY (Don't Repeat Yourself) principles. 
* **Encapsulation:** Components must handle their own internal logic and accept variables/args seamlessly from their parent blocks.

---

## 5. Maintenance & Editing Workflow
When modifying existing assets, follow this strict verification loop:

### Step 1: Verification
* **Search First:** Check `/acf-blocks/`, `/components/`, `/assets/scss/blocks/`, and `/acf-json/` to locate existing files before generating new ones.
* **Locate Existing:** Remember to look for the `group_` prefix for general JSON groups, and `group_block_` for ACF block JSON groups.

### Step 2: JSON Synchronization & Timestamps
If you edit or append an existing ACF JSON file in `/acf-json/`, you must execute this step:
* **Update Modified Key:** You **must always** update the `modified` key in the JSON file.
* **Format:** The key must be updated to the **current Unix timestamp** at the exact moment of your edit (e.g., `1738686290`). 
* **Critical Rule:** This update is mandatory. Without a newer Unix timestamp, WordPress will not detect the changes and the database will fail to sync the new fields.

---

## 6. Safety & Quality Standards
* **Database Sync Priority:** Never output an updated ACF JSON file without verifying the `modified` key is updated to the current Unix timestamp.
* **Strict Scoping (BEM):** Never write globally leaking CSS. All styles must be scoped to their specific block or component class using BEM (Block Element Modifier) logic.
* **Field Key Integrity:** Never use generic field keys (like `field_title`). Always prefix them tightly to the group or block they belong to.
* **No Redundancy:** Never create a class, mixin, or component that already exists in the global directories.