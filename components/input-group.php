<?php
/**
 * Input Group Component
 *
 * @param string $placeholder  - The placeholder text for the input (default: 'Typ iets...')
 * @param string $button_text  - The text for the submit button (default: 'Verstuur')
 * @param string $name         - The name attribute for the input (default: 'input_name')
 * @param string $type         - The type attribute for the input (default: 'text')
 * @param string $class        - Additional classes for the wrapper
 * @param string $value        - Initial value for the input
 * @param bool   $required     - Whether the input is required (default: false)
 * @param string $form_action  - Action URL for the form (optional)
 * @param string $method       - Form method (default: GET)
 * @package pixel-flow
 */

$placeholder = $args['placeholder'] ?? 'Typ iets...';
$button_text = $args['button_text'] ?? 'Verstuur';
$name = $args['name'] ?? 'input_name';
$type = $args['type'] ?? 'text';
$class = $args['class'] ?? '';
$value = $args['value'] ?? '';
$required = !empty($args['required']) ? 'required' : '';
$form_action = $args['form_action'] ?? '';
$method = $args['method'] ?? 'GET';

$wrapper_classes = 'c-input-group ' . $class;
?>

<div class="<?php echo esc_attr($wrapper_classes); ?>">
    <?php if ($form_action) : ?>
    <form action="<?php echo esc_url($form_action); ?>" method="<?php echo esc_attr($method); ?>" class="c-input-group__form shadow-sm">
    <?php endif; ?>
        
        <div class="input-group">
            <input type="<?php echo esc_attr($type); ?>" 
                   name="<?php echo esc_attr($name); ?>" 
                   class="form-control c-input-group__input" 
                   placeholder="<?php echo esc_attr($placeholder); ?>" 
                   value="<?php echo esc_attr($value); ?>"
                   aria-label="<?php echo esc_attr($placeholder); ?>"
                   <?php echo $required; ?>>
            <button class="btn btn-primary c-input-group__button" type="submit">
                <?php echo esc_html($button_text); ?>
            </button>
        </div>

    <?php if ($form_action) : ?>
    </form>
    <?php endif; ?>
</div>
