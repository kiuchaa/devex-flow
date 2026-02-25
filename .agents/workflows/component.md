---
description: Create a reusable component
---

Step 1: PHP Template Creation
Create a PHP file in the /components/ directory named {component-name}.php. Design the component to be UI-focused and "dumb," meaning it does not handle complex logic. Use a single root element with a class name that matches the filename. Utilize the $args array to handle dynamic data and define default values for these arguments.

Step 2: Integration Logic
Call components from parent blocks or templates using get_template_part('components/{component-name}', null, $args). Ensure the parent block handles all data processing before passing it to the component.

Step 3: Styling and Quality
Write SCSS for the component using strict BEM scoping. Ensure component styles do not conflict with parent block styles or global theme CSS. Search existing directories before creating new components to prevent redundancy and promote reuse.