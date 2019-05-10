        <?php the_content(); ?>
				<?php fusion_link_pages(); ?>

                <?php
                $url_token = 'https://apifactory.telkom.co.id:8243/hcm/auth/v1/token';
                $url_get = 'https://apifactory.telkom.co.id:8243/hcm/cognitium/v1/listexpert/4/1';
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
											<div class="bottom">
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
											</div>
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
               
