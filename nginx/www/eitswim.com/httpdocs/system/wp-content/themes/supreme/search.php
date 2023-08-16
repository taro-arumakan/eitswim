<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package studioelc
 */

get_header(); ?>

	<!-- CONTENT -->
	<section class="content">
		<div class="container">
			<div class="row">

				<!-- Blog post-->
				<div class="post-content col-md-9">

					<?php if (have_posts()):?>
						<?php while ( have_posts() ) : the_post(); ?>

							<!-- Blog image post-->
							<div class="post-item">
								<div class="post-content-details">
									<div class="post-title">
										<h3><a href="<?php echo the_permalink(); ?>"><?php the_title();?></a></h3>
									</div>

									<div class="post-description">
										<p><?php echo get_the_custom_excerpt(30, $post->ID); ?></p>
										<div class="post-read-more">
											<a class="read-more" href="<?php echo the_permalink(); ?>">read more <i class="fa fa-long-arrow-right"></i></a>
										</div>
									</div>
								</div>
							</div><!--end: Blog post-->

						<?php endwhile; ?>
						<?php wp_reset_query(); ?>
					<?php else:?>
						<?php
						$template = get_post_meta(get_the_ID(), '_wp_page_template', true);
						?>

						<!-- Blog image post-->
						<div class="post-item">
							<div class="post-content-details">
								<div class="post-title">
									<h3>No Result Found!</h3>
								</div>

								<div class="post-description">
									<p>検索キーワードに該当する記事がございませんでした。</p>
								</div>
							</div>
						</div><!--end: Blog post-->
					<?php endif;?>


					<!-- pagination nav -->
					<div class="text-center">
						<div class="pagination-wrap">
							<ul class="pagination">
								<?php
								//Pagination
								if (function_exists("pagination")) {
									pagination($wp_query->max_num_pages);
								}
								?>
							</ul>
						</div>
					</div>

				</div><!-- END: Blog post-->

				<!-- Sidebar-->
				<div class="sidebar sidebar-modern col-md-3">

          <!--widget newsletter-->
          <div class="widget clearfix widget-newsletter">
            <form id="widget-subscribe-form-sidebar" action="/" role="form" method="get" class="form-inline">
              <div class="input-group">
                <input type="text" name="s" aria-required="true" class="form-control required email" placeholder="Enter your keyword">
                <span class="input-group-btn">
                  <button type="submit" id="widget-subscribe-submit-button" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </span>
              </div>
            </form>
          </div><!--end: widget newsletter-->

          <?php dynamic_sidebar( 'sidebar-6' ); ?>

					<!--widget Gallery slider-->
					<?php //the_side_gallery_html();?>
					<!--end: widget Gallery slider -->

				</div><!-- END: Sidebar-->

			</div>
		</div>
	</section><!-- END: SECTION -->

<?php
get_footer();
