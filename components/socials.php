<?php
/**
 * Socials component
 *
 * $size - determine what size the social icon will be.
 */

$size = $args['size'] ?? 'fa-xl'; 

$socials = get_field('social_media', 'contact-info');
?>

<div class="d-flex flex-row gap-4 mb-4">
    <?php if (!empty($socials['instagram'])): ?>
        <a href="<?= htmlspecialchars($socials['instagram']) ?>" aria-label="Instagram"><i class="fa-brands fa-instagram <?= $size ?>"></i></a>
    <?php endif; ?>

    <?php if (!empty($socials['facebook'])): ?>
        <a href="<?= htmlspecialchars($socials['facebook']) ?>" aria-label="Facebook"><i class="fa-brands fa-facebook <?= $size ?>"></i></a>
    <?php endif; ?>

    <?php if (!empty($socials['twitter'])): ?>
        <a href="<?= htmlspecialchars($socials['twitter']) ?>" aria-label="Twitter"><i class="fa-brands fa-x-twitter <?= $size ?>"></i></a>
    <?php endif; ?>

    <?php if (!empty($socials['linkedin'])): ?>
        <a href="<?= htmlspecialchars($socials['linkedin']) ?>" aria-label="LinkedIn"><i class="fa-brands fa-linkedin <?= $size ?>"></i></a>
    <?php endif; ?>
</div>