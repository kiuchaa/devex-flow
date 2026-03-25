const fs = require('fs');
const path = require('path');

const blockName = process.argv[2];

if (!blockName) {
    console.error('Provide a block name. Example: npm run make:block "Test Block"');
    process.exit(1);
}

// Convert "Test Block" to "test-block"
const slug = blockName.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');

// Ensure scripts directory has correct relative pathing up
const phpTarget = path.join(__dirname, '..', 'acf-blocks', `${slug}.php`);
const scssTarget = path.join(__dirname, '..', 'assets', 'scss', 'blocks', `${slug}.scss`);

if (fs.existsSync(phpTarget) || fs.existsSync(scssTarget)) {
    console.error(`Block ${slug} already exists!`);
    process.exit(1);
}

const phpTemplate = `<?php
/**
 * Block Name: ${blockName}
 *
 * This is the template that displays the ${blockName} block.
 */

// CSS classes
$classes = ['${slug}'];
if ( ! empty( $block['className'] ) ) {
    $classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}
if ( ! empty( $block['align'] ) ) {
    $classes[] = 'align' . $block['align'];
}
?>

<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
    <div class="container">
        <!-- Block Content -->
    </div>
</section>
`;

const scssTemplate = `@import "_common-vars";

.${slug} {
    // Styles for ${blockName}
    padding: 4rem 0;
}
`;

fs.writeFileSync(phpTarget, phpTemplate);
console.log(`Created: ${phpTarget}`);

fs.writeFileSync(scssTarget, scssTemplate);
console.log(`Created: ${scssTarget}`);

console.log('✅ Block scaffolded successfully! It will be auto-registered next time WP loads.');
