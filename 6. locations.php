<?php
    session_start();

    $sxgeo_path = get_template_directory() . '/inc/SxGeo/SxGeo.php';
    require_once $sxgeo_path;
    
    $sxgeo_dat_path = get_template_directory() . '/inc/SxGeo/SxGeo.dat';
    $sxGeo = new SxGeo( $sxgeo_dat_path );
    
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $user_country_code = '';
    
    if ( $_SESSION['cookies_consent'] === true ) :
        if ( ! empty( $user_ip ) ) : 
            $user_country_code = $sxGeo->getCountry($user_ip);
        endif;
    endif;
?>

<?php
$color_mode   = get_sub_field( 'color_mode' ) ? get_sub_field( 'color_mode' ) : 'white';
?>

<section class="module module-<?php echo $color_mode; ?> m-locations">
    <div class="container">

        <?php get_template_part( 'template-parts/builder/components/title' ); ?>

        <?php echo $user_country; ?>

        <?php if ( have_rows( 'locations_main_list', 'option' ) ) : ?>
            <div class="row locations-row">
                <div class="col-lg-6 main-location">
                    <?php $main_index = 1; ?>
                    <?php $match_found = false; ?>
                    <?php while ( have_rows( 'locations_main_list', 'option' ) ) : the_row(); 
                        $country = get_sub_field( 'location_country' ) ?? false; ?>
                        <?php if ( $user_country_code == $country ) : ?>
                            <?php $match_found = true; ?>
                            <?php get_template_part( 'template-parts/builder/components/location' ); ?>
                        <?php endif; ?>
                    <?php endwhile; ?>
                    
                    <?php if ( ! $match_found ) : ?>
                        <?php while ( have_rows( 'locations_main_list', 'option' ) ) : the_row(); ?>
                            <?php if ( $main_index === 1 ) : ?>
                                <?php get_template_part('template-parts/builder/components/location'); ?>
                                <?php $main_index++; ?>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
                <div class="col-lg-6 extra-location js-same-height">
                    <?php $extra_index = 1; ?>
                    <?php if ( ! $match_found ) : ?>
                        <?php while ( have_rows( 'locations_main_list', 'option' ) ) : the_row(); ?>
                            <?php if ( $extra_index > 1 && $extra_index <= 5 ) : ?>
                                <?php get_template_part('template-parts/builder/components/location'); ?>
                            <?php endif; ?>
                            <?php $extra_index++; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <?php while ( have_rows( 'locations_main_list', 'option' ) ) : the_row(); 
                            $country = get_sub_field( 'location_country' ) ?? false; ?>
                            <?php if ( $user_country_code != $country && $extra_index <= 4 ) : ?>
                                <?php get_template_part( 'template-parts/builder/components/location' ); ?>
                                <?php $extra_index++; ?>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>