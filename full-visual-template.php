<?php
/**
 * Template Name: Full Visual Template
 */
esc_html__( 'Full Visual Template', 'the-core' );

get_header();

while ( have_posts() ) :
	the_post(); ?>

	<?php if ( post_password_required() ) : ?>
		<div <?php tfuse_class('middle'); ?>>
			<div class="container clearfix box_page">
				<div class="content">
					<div class="contact_box">
						<div class="box_content clearfix">
								<?php the_content(); ?>
						</div>
					</div><!--/ .contact_box -->
				</div><!--/ content -->
			</div><!--/ .container -->
		</div><!--/ #middle -->
	<?php else : ?>
		<?php the_content(); ?>
	<?php endif; ?>

<?php endwhile;

get_footer();