<?php
$color_mode     = get_sub_field( 'color_mode' ) ? get_sub_field( 'color_mode' ) : 'white';
$feedback_type  = get_sub_field( 'feedback_type' ) ? get_sub_field( 'feedback_type' ) : 'project';
$project_id     = $feedback_type == 'project' ? get_sub_field( 'project' ) : false;

$feedback_txt   = $feedback_type == 'project' ? get_field( 'feedback_full', $project_id ) : get_sub_field( 'feedback' );
$feedback_video = $feedback_type == 'project' ? get_field( 'feedback_video', $project_id ) : get_sub_field( 'feedback_video' );
$feedback_url   = $feedback_type == 'project' ? get_field( 'feedback_url', $project_id ) : get_sub_field( 'feedback_url' );
$author_name    = $feedback_type == 'project' ? get_field( 'author_name', $project_id ) : get_sub_field( 'author_name' );
$author_photo   = $feedback_type == 'project' ? get_field( 'author_photo', $project_id ) : get_sub_field( 'author_photo' );
$author_job     = $feedback_type == 'project' ? get_field( 'author_job', $project_id ) : get_sub_field( 'author_job' );
$author_linked  = $feedback_type == 'project' ? get_field( 'author_linkedin_url', $project_id ) : get_sub_field( 'author_linkedin_url' );
$company_name   = $feedback_type == 'project' ? get_field( 'company_name', $project_id ) : get_sub_field( 'company_name' );
$company_url    = $feedback_type == 'project' ? get_field( 'company_url', $project_id ) :  get_sub_field( 'company_url' );
$platform_icon  = $feedback_type == 'project' ? get_field( 'platform_icon', $project_id ) :  get_sub_field( 'platform_icon' );
?>


<?php if ( $feedback_type == 'random' || $project_id ) : ?>
    <section class="module module-<?php echo $color_mode; ?> m-feedback">
        <div class="container">
            <?php get_template_part( 'template-parts/builder/components/title' ); ?>

            <div class="m-feedback__wrapper">
                <div class="m-feedback__wrapper-icons">
                    <svg class="quote-icon"><use xlink:href="#quote-grey"></use></svg>
                    <?php if ($platform_icon ) : ?>
                        <?php echo wp_get_attachment_image( $platform_icon, 'full', false, [ 'class' => 'm-feedback__wrapper-icons-platform' ] ); ?>
                    <?php endif; ?>
                </div>

                <h4 class="m-feedback__wrapper-title"><?php _e( 'feedback summary:', 'digis' );?></h4>
                <?php if ( $feedback_txt ) : ?>
                    <div class="m-feedback__wrapper-feedback editor">
                        <?php echo wp_kses_post( $feedback_txt ); ?>
                    </div>
                <?php endif; ?>
                <div class="m-feedback__wrapper-author">
                    <?php if ( $author_photo ) : ?>
                        <div class="author-photo">
                            <?php echo wp_get_attachment_image( $author_photo, 'full', false, [ 'class' => 'photo' ] ); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ( $author_name ) : ?>
                        <div class="author-info">
                            <div class="author-info__top">
                                <span class="author-name d-block"><?php echo $author_name; ?></span>    
                                <?php if ( $author_linked ) : ?>
                                    <div class="socials">
                                        <a class="socials__item" href="<?php echo $author_linked; ?>" target="_blank" rel="nofollow">
                                            <svg class="icon-linkedin">
                                                <use xlink:href="#linkedin"></use>
                                            </svg>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if ( $author_job ) : ?>
                                <div class="author-info__bottom">
                                    <span class="author-job">
                                        <?php echo $author_job; ?>
                                        <?php if ( $company_name && $company_url ) : ?>
                                            <?php _e( 'at', 'digis' ); ?>
                                        <?php endif; ?>
                                    </span>
                                    <a class="author-job-link" href="<?php echo $company_url; ?>" target="_blank"><?php echo $company_name; ?></a>
                                </div>
                            <?php endif; ?>
                        </div>    
                    <?php endif; ?>
                </div>
                <div class="m-feedback__wrapper-buttons">
                    <?php if ( $feedback_url ) : ?>
                        <a class="btn btn-secondary" href="<?php echo $feedback_url; ?>" target="_blank">
                            <svg class="icon">
                                <use xlink:href="#clutch"></use>
                            </svg>
                            <span><?php _e( 'review feedback'); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if ( $feedback_video ) : ?>
                            <a class="btn btn-secondary btn-outline" data-fancybox="video" href="<?php echo esc_url( $feedback_video ); ?>">
                                <svg class="icon">
                                    <use xlink:href="#video"></use>
                                </svg>
                                <?php _e( 'video review', 'digis' ); ?>
                            </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>      
    </section>
<?php endif; ?>