<?php
/* Template Name: ExpertPageCustom */



// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>

<?php get_header(); ?>
<section id="content" <?php Avada()->layout->add_style( 'content_style' ); ?>>
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php echo fusion_render_rich_snippets_for_pages(); // WPCS: XSS ok. ?>
			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php if ( Avada()->settings->get( 'featured_images_pages' ) ) : ?>
					<?php if ( 0 < avada_number_of_featured_images() || get_post_meta( $post->ID, 'pyre_video', true ) ) : ?>
						<div class="fusion-flexslider flexslider post-slideshow">
							<ul class="slides">
								<?php if ( get_post_meta( $post->ID, 'pyre_video', true ) ) : ?>
									<li>
										<div class="full-video">
											<?php echo apply_filters( 'privacy_iframe_embed', get_post_meta( $post->ID, 'pyre_video', true ) ); // WPCS: XSS ok. ?>
										</div>
									</li>
								<?php endif; ?>
								<?php if ( has_post_thumbnail() && 'yes' != get_post_meta( $post->ID, 'pyre_show_first_featured_image', true ) ) : ?>
									<?php $attachment_data = Avada()->images->get_attachment_data( get_post_thumbnail_id() ); ?>
									<?php if ( is_array( $attachment_data ) ) : ?>
										<li>
											<a href="<?php echo esc_url_raw( $attachment_data['url'] ); ?>" data-rel="iLightbox[gallery<?php the_ID(); ?>]" title="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>" data-title="<?php echo esc_attr( $attachment_data['title_attribute'] ); ?>" data-caption="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>">
												<img src="<?php echo esc_url_raw( $attachment_data['url'] ); ?>" alt="<?php echo esc_attr( $attachment_data['alt'] ); ?>" role="presentation" />
											</a>
										</li>
									<?php endif; ?>
								<?php endif; ?>
								<?php $i = 2; ?>
								<?php while ( $i <= Avada()->settings->get( 'posts_slideshow_number' ) ) : ?>
									<?php $attachment_new_id = fusion_get_featured_image_id( 'featured-image-' . $i, 'page' ); ?>
									<?php if ( $attachment_new_id ) : ?>
										<?php $attachment_data = Avada()->images->get_attachment_data( $attachment_new_id ); ?>
										<?php if ( is_array( $attachment_data ) ) : ?>
											<li>
												<a href="<?php echo esc_url_raw( $attachment_data['url'] ); ?>" data-rel="iLightbox[gallery<?php the_ID(); ?>]" title="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>" data-title="<?php echo esc_attr( $attachment_data['title_attribute'] ); ?>" data-caption="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>">
													<img src="<?php echo esc_url_raw( $attachment_data['url'] ); ?>" alt="<?php echo esc_attr( $attachment_data['alt'] ); ?>" role="presentation" />
												</a>
											</li>
										<?php endif; ?>
									<?php endif; ?>
									<?php $i++; ?>
								<?php endwhile; ?>
							</ul>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; // Password check. ?>

			<div class="post-content">
				<?php the_content(); ?>
				<?php fusion_link_pages(); ?>

                <!-- content expert here -->
                <?php
                $url_token = 'https://apifactory.telkom.co.id:8243/hcm/auth/v1/token';
                $url_get = 'https://apifactory.telkom.co.id:8243/hcm/cognitium/v1/listexpert';
                $response = wp_remote_post( $url_token, array(
                    'method' => 'POST',
                    'timeout' => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
                    'body' => array( 'username' => '930328', 'password' => 'November30' ),
                    'cookies' => array()
                    )
                );

                if ( is_wp_error( $response ) ) {
                    $error_message = $response->get_error_message();
                    echo "Something went wrong: $error_message";
                } else {
                    // echo 'Response:<pre>';
                    $body = wp_remote_retrieve_body( $response );
                    $data = json_decode( $body );
                    if (!empty($data)) {
                        //print_r( $data->data->jwt->token );
                        // echo '</pre>';
                        $request = wp_remote_get($url_get, array('timeout' => 45, 'headers' => array('x-authorization' => 'Bearer '. $data->data->jwt->token)));
                       // print_r ( $request);

                        if( is_wp_error( $request ) ) {
                            $error_message = $response->get_error_message();
                            echo "Something went wrong: $error_message";
                        }
                         else {
                            // echo 'Request Result :<pre>';
                            $body_request = wp_remote_retrieve_body( $request );
                            $data_expert = json_decode( $body_request );
                            if (!empty($data_expert)){
                                // print_r($data_expert->data);
                            ?>
								<!-- <ul id="member-list" class="member-list"> -->
								<div class="row">
                                <?php foreach($data_expert->data as $v) {?>
                                    <!-- <li> -->
									<div class="col-lg-3 col-sm-6">
										<div class="card hovercard">
											<div class="cardheader">
											</div>
											<div class="avatar">
												<!-- <img src="<?php echo 'https://apifactory.telkom.co.id:8243/hcm/cognitium/v1/fotoexpert/'.$v->nik ; ?>" alt="Foto Expert"> -->

												<?php 
													$req_pohoto = wp_remote_get($v->photo, array('timeout' => 45, 'headers' => array('x-authorization' => 'Bearer '. $data->data->jwt->token)));
													//print_r($req_pohoto);
													//$imageData = base64_encode(file_get_contents($req_pohoto));
													$req_img_request = wp_remote_retrieve_body( $req_pohoto );
                            						//$img_decode = json_decode( $req_img_request );

                            						//$src = "data:image/jpeg;base64,".base64_decode($req_img_request);

													$src = "data:image/jpeg;base64,".base64_encode($req_img_request);
													//print_r($req_img_request);
												?>
													 <img src="<?php echo $src?>" alt="Foto Expert"> 
											</div>
											<div class="info">
												<div class="title">
													<?php echo $v->nama; ?>
												</div>
												<div class="desc"><?php echo $v->nik; ?></div>
												<div class="desc"><?php echo $v->expertise; ?></div>
												<div class="desc"><?php echo $v->major_competency; ?></div>
											</div>
											<!-- <div class="bottom">
												<a class="btn btn-primary btn-twitter btn-sm" href="https://twitter.com/webmaniac">
													<i class="fa fa-twitter"></i>
												</a>
												<a class="btn btn-danger btn-sm" rel="publisher"
												href="https://plus.google.com/+ahmshahnuralam">
													<i class="fa fa-google-plus"></i>
												</a>
												<a class="btn btn-primary btn-sm" rel="publisher"
												href="https://plus.google.com/shahnuralam">
													<i class="fa fa-facebook"></i>
												</a>
												<a class="btn btn-warning btn-sm" rel="publisher" href="https://plus.google.com/shahnuralam">
													<i class="fa fa-behance"></i>
												</a>
											</div> -->
										</div>
									</div>
                                <?php } ?>
								<!-- </ul> -->
								</div>
                            <?php                                 
                            } else {
                                echo "data Empty";
                            }
                        }
                    }
                }
                ?>
                <!-- end of content expert here -->
			</div>

			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php do_action( 'avada_before_additional_page_content' ); ?>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<?php $woo_thanks_page_id = get_option( 'woocommerce_thanks_page_id' ); ?>
					<?php $is_woo_thanks_page = ( ! get_option( 'woocommerce_thanks_page_id' ) ) ? false : is_page( get_option( 'woocommerce_thanks_page_id' ) ); ?>
					<?php if ( Avada()->settings->get( 'comments_pages' ) && ! is_cart() && ! is_checkout() && ! is_account_page() && ! $is_woo_thanks_page ) : ?>
						<?php wp_reset_postdata(); ?>
						<?php comments_template(); ?>
					<?php endif; ?>
				<?php else : ?>
					<?php if ( Avada()->settings->get( 'comments_pages' ) ) : ?>
						<?php wp_reset_postdata(); ?>
						<?php comments_template(); ?>
					<?php endif; ?>
				<?php endif; ?>
				<?php do_action( 'avada_after_additional_page_content' ); ?>
			<?php endif; // Password check. ?>
		</div>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
</section>
<?php do_action( 'avada_after_content' ); ?>
<?php
get_footer();