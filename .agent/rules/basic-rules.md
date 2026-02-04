---
trigger: always_on
---

AntiGravity AI Agent: System Rules & Protocol
---

This document defines the operational parameters for the **AntiGravity AI Agent**. You are tasked with maintaining, creating, and evolving a WordPress-based ecosystem using ACF (Advanced Custom Fields), SCSS, and modular components.

---

## 1. Core Architecture & File Mapping
Every feature you build must follow a strict naming and structural convention. Consistency is the primary directive.

| Feature Type | Directory Location | Naming Convention |
| :--- | :--- | :--- |
| **ACF Block (PHP)** | `/acf-blocks/` | `{block-name}.php` |
| **Block Styles** | `/assets/scss/blocks/` | `{block-name}.scss` |
| **ACF Field Group** | `/acf-json/` | `group_{block-name}.json` |
| **Reusable Component** | `/components/` | `{component-name}.php` |

---

## 2. Intelligence & Asset Scanning
Before generating any new code, you must perform a "Site Context Scan":
* **SCSS Reuse:** Scan all existing files in the theme's folder for SCSS classes, variables, or mixins.
* **Avoid Redundancy:** Do not add unnecessary classes. If a class already exists in the theme that achieves the desired style, reuse it.
* **Style Alignment:** New blocks and components must be up to par with existing code and visually in line with the rest of the website.

---

## 3. ACF Block Creation Protocol (`acfblock`)
When creating a new ACF block (e.g., "hero-v1"):

1.  **PHP Logic:** Create the file in `/acf-blocks/hero-v1.php`.
2.  **Styles:** Create a corresponding SCSS file in `/assets/scss/blocks/hero-v1.scss`. The naming **must** match the block name exactly.
3.  **Field Definition (JSON):** * Create a new JSON file in `/acf-json/`.
    * **Filename Rule:** The file must start with "group_" followed by the block name (e.g., `group_hero-v1.json`).
    * The `name` and `key` within the JSON file must match the ACF block name.
    * Define dynamic fields based on developer input or logical necessity.

---

## 4. Component Strategy
**Components** are fixed code snippets (e.g., Buttons, Icons) located in the `/components/` folder.
* **Fixed Placement:** Components are for code that appears multiple times in fixed positions or within other blocks.
* **Integration:** Components should be leveraged by ACF blocks (via `get_template_part`) to maintain DRY (Don't Repeat Yourself) principles.

---

## 5. Maintenance & Editing Workflow
When modifying existing assets, follow this strict verification loop:

### Step 1: Verification
* **Search First:** Check `/acf-blocks/`, `/assets/scss/blocks/`, and `/acf-json/` before creating new files.
* **Locate Existing:** Use the `group_` prefix for JSON and matching slugs for PHP/SCSS.

### Step 2: JSON Synchronization
If you edit an existing ACF JSON file in `/acf-json/`:
* **Update Modified Key:** You **must** update the `modified` key in the JSON file to the **current time** (Current Time: 1738686290).
* **Purpose:** This update is mandatory to enable the import of that JSON file into the WordPress database.

---

## 6. Safety & Quality Standards
* **No Redundancy:** Never create a class that already exists in the SCSS directory.
* **Database Sync:** Always ensure the JSON `modified` key is updated during edits, or the database will not sync.
* **Scalability:** Match the existing theme's indentation, BEM logic, and architectural patterns.