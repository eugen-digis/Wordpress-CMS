<?php
/**
 * AJAX functions
 *
 * @link https://developer.wordpress.org/reference/hooks/wp_ajax_action/
 */


// Filter functions
add_action('wp_ajax_myfilter_country', 'digis_filter_function');
add_action('wp_ajax_nopriv_myfilter_country', 'digis_filter_function');
add_action('wp_ajax_myfilter_service', 'digis_filter_function');
add_action('wp_ajax_nopriv_myfilter_service', 'digis_filter_function');
add_action('wp_ajax_myfilter_industry', 'digis_filter_function');
add_action('wp_ajax_nopriv_myfilter_industry', 'digis_filter_function');

function digis_filter_function() {
    $args = array(
        'post_type' => 'project',
        'orderby' => 'date',
        'order' => $_POST['date'],
    );

    if (isset($_POST['countryfilter'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'country',
                'field' => 'id',
                'terms' => $_POST['countryfilter'],
            ),
        );
    } elseif (isset($_POST['servicefilter'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'service',
                'field' => 'id',
                'terms' => $_POST['servicefilter'],
            ),
        );
    } elseif (isset($_POST['industryfilter'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'industry',
                'field' => 'term_id',
                'terms' => $_POST['industryfilter'],
            ),
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

		get_template_part( 'template-parts/case' );

        endwhile;
        wp_reset_postdata();
    else :
        $projects = new WP_Query([
			'post_type' => 'project',
			'posts_per_page' => -1,
			'orderby' => 'date',
			'order' => 'DESC',
			'paged' => 1,
			]);

			if( $projects->have_posts() ) :
				while ( $projects->have_posts() ): $projects->the_post();
					get_template_part( 'template-parts/case' );
				endwhile;
			endif; 
		wp_reset_postdata();
    endif;

    wp_die();
}