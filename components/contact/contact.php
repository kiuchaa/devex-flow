<?php if($show_contact):
    $info = get_field('contact_informatie', 'contact-info');
    $google_maps = get_field('google_maps', 'contact-info');
    $google_maps_url = $google_maps['google_maps_url'];
    ?>
    <ul class="list-unstyled">
        <?php if (!empty($info['e-mail'])): ?>
            <li><i class="text-primary fa-solid fa-envelope fa-fw me-2"></i> <a href="mailto:<?= htmlspecialchars($info['e-mail']) ?>"><?= htmlspecialchars($info['e-mail']) ?></a></li>
        <?php endif; ?>

        <?php if (!empty($info['telefoonnummer'])): ?>
            <li><i class="text-primary fa-solid fa-phone fa-fw me-2"></i> <a href="tel:<?= htmlspecialchars($info['telefoonnummer']) ?>"><?= htmlspecialchars($info['telefoonnummer']) ?></a></li>
        <?php endif; ?>

        <?php if (!empty($info['straatnaam'])): ?>
            <li><i class="text-primary fa-solid fa-location-dot fa-fw me-2"></i>
                <?php if($google_maps_url): ?> <a href="<?= $google_maps_url ?>" target="_blank"> <?php endif; ?>
                    <?= htmlspecialchars($info['straatnaam']) ?>
                    <?php if($google_maps_url): ?> </a> <?php endif; ?>
            </li>
        <?php endif; ?>

        <?php if (!empty($info['plaats']) || !empty($info['postcode'])): ?>
            <li><i class="text-primary fa-solid fa-city fa-fw me-2"></i>
                <?php if($google_maps_url): ?> <a href="<?= $google_maps_url ?>" target="_blank"> <?php endif; ?>
                    <?= htmlspecialchars($info['plaats']) ?>
                    <?= htmlspecialchars($info['postcode']) ?>
                    <?php if($google_maps_url): ?> </a> <?php endif; ?>
            </li>
        <?php endif; ?>
    </ul>
<?php endif; ?>