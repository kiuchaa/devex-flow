<?php
/**
 * The search results template
 *
 * @package pixel-flow
 */

get_header();
?>

<div class="search-page">

    <?php
    $search_query = get_search_query();
    $result_count = $GLOBALS['wp_query']->found_posts;
    ?>

    <!-- Search Hero -->
    <section class="search-page__hero bg-primary text-white">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <?php if ( $search_query ) : ?>
                        <p class="text-uppercase fw-bold mb-3 search-page__label">
                            Zoekresultaten
                        </p>
                        <h1 class="display-4 fw-bold mb-4 ls-tight">
                            &ldquo;<?php echo esc_html( $search_query ); ?>&rdquo;
                        </h1>
                        <p class="search-page__count">
                            <?php
                            printf(
                                _n(
                                    '<strong>%s</strong> resultaat gevonden',
                                    '<strong>%s</strong> resultaten gevonden',
                                    $result_count,
                                    'pixel-flow'
                                ),
                                number_format_i18n( $result_count )
                            );
                            ?>
                        </p>
                    <?php else : ?>
                        <h1 class="display-4 fw-bold mb-4 ls-tight">Zoeken</h1>
                    <?php endif; ?>

                    <!-- Inline search form -->
                    <div class="search-page__form-wrapper mt-5">
                        <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-page__form">
                            <div class="d-flex align-items-center gap-2">
                                <input
                                    type="search"
                                    class="search-page__input"
                                    placeholder="Zoek nogmaals&hellip;"
                                    value="<?php echo esc_attr( $search_query ); ?>"
                                    name="s"
                                    id="search-field"
                                    autocomplete="off"
                                />
                                <button type="submit" class="search-page__submit btn btn-white text-primary fw-bold">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zm-5.242 1.656a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
                                    </svg>
                                    Zoeken
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Results -->
    <section class="search-page__results bg-white">
        <div class="container">

            <?php if ( have_posts() ) : ?>

                <div class="row g-4">
                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php
                        $categories   = get_the_terms( get_the_ID(), 'category' );
                        $cat_name     = ! empty( $categories ) && ! is_wp_error( $categories ) ? $categories[0]->name : get_post_type_object( get_post_type() )->labels->singular_name ?? '';
                        $has_thumb    = has_post_thumbnail();
                        $date         = get_the_date( 'j F Y' );
                        ?>

                        <div class="col-12">
                            <article id="post-<?php the_ID(); ?>" <?php post_class( 'search-page__result-item' ); ?>>

                                <?php if ( $has_thumb ) : ?>
                                    <div class="search-page__thumb">
                                        <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                                            <?php the_post_thumbnail( 'medium', [ 'class' => 'w-100 h-100 object-fit-cover', 'loading' => 'lazy' ] ); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <div class="search-page__result-body">
                                    <div class="search-page__meta">
                                        <?php if ( $cat_name ) : ?>
                                            <span class="search-page__badge"><?php echo esc_html( $cat_name ); ?></span>
                                        <?php endif; ?>
                                        <span class="search-page__date"><?php echo esc_html( $date ); ?></span>
                                    </div>

                                    <h2 class="search-page__result-title">
                                        <a href="<?php the_permalink(); ?>" class="stretched-link text-decoration-none text-reset">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>

                                    <p class="search-page__excerpt">
                                        <?php echo wp_trim_words( get_the_excerpt(), 25 ); ?>
                                    </p>

                                    <div class="search-page__cta fw-bold">
                                        Lees meer
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right ms-1" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </div>
                                </div>

                            </article>
                        </div>

                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div class="search-page__pagination">
                    <?php the_posts_pagination( [
                        'mid_size'           => 2,
                        'prev_text'          => '← Vorige',
                        'next_text'          => 'Volgende →',
                        'screen_reader_text' => ' ',
                    ] ); ?>
                </div>

            <?php else : ?>

                <!-- No results -->
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center search-page__no-results">
                        <div class="search-page__no-results-icon mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zm-5.242 1.656a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
                            </svg>
                        </div>
                        <h2 class="fw-bold mb-3"><?php esc_html_e( 'Geen resultaten gevonden', 'pixel-flow' ); ?></h2>
                        <p class="text-muted mb-5"><?php esc_html_e( 'We konden niets vinden voor jouw zoekopdracht. Probeer een andere zoekterm of neem contact met ons op.', 'pixel-flow' ); ?></p>

                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary fw-semibold px-5">
                            Terug naar home <span class="ms-2">→</span>
                        </a>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </section>

</div>

<?php get_footer(); ?>
