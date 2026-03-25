---
description: Make an ACF block by workflow.
---

Step 1: Scaffolding
Run the command `npm run make:block "{Block Name}"` via the terminal in the root directory. This script automatically generates the PHP template (`/acf-blocks/{block-name}.php`) with a wrapper class and the SCSS file (`/assets/scss/blocks/{block-name}.scss`) with BEM scoping and necessary imports. The block is also dynamically registered for you. 
Note: Once generated, open the PHP template to build out your HTML structure and integrate components using `get_template_part()` for internal elements.

Step 2: SCSS Styling
Open the newly created SCSS file in `/assets/scss/blocks/` named `{block-name}.scss`. Scope all styles within the main block class to prevent leakage. Use existing theme variables for colors and spacing. Follow BEM methodology for all sub-elements.

Step 3: ACF JSON Definition
Generate a JSON file in `/acf-json/`. Name the file using the slugified field group title. Use the `field_block_{block-name}_` prefix for all keys. Organize fields into two tabs:

Tab 1 (Inhoud): Content fields inside an ACF Group.

Tab 2 (Opties): Styling fields inside an ACF Group.

Set the location rule specifically to `"value": "acf/{block-name}"`.

Step 4: ACF CMS definition
Add the newly added block to the array of $pixel_flow_blocks in the acf.json file. The labels must be Dutch and be visible in the CMS as Dutch.