<?php 
$url_get = 'https://apifactory.telkom.co.id:8243/hcm/cognitium/v1/listexpert/20/1';
$data = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiOTMwMzI4IiwiZXhwIjoxNTU3NDcxMjE0LCJqdGkiOiJ1enc0cSIsInN1YiI6ImFwaS10b2tlbi1oY21kZXYuYXBwcy5wYWFzLnRlbGtvbS5jby5pZCJ9.kDBJqPHw-l7r1j-6sGHs5qSRgRLHG6yM4C_KP4bEWrs";
$request = wp_remote_get($url_get, array('timeout' => 45, 'headers' => array('x-authorization' => 'Bearer '. $data)));
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
                                                <?php 
                                                    $req_pohoto = wp_remote_get($v->photo, array('timeout' => 45, 'headers' => array('x-authorization' => 'Bearer '. $data->data->jwt->token)));
                                                    $req_img_request = wp_remote_retrieve_body( $req_pohoto );
                                                    $src = "data:image/jpeg;base64,".base64_encode($req_img_request);
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
                                           
                                        </div>
                                    </div>
                                <?php } ?>
                                <!-- </ul> -->
                                </div>
                            <?php                                 
                            } else {
                                echo "data Empty";
                            }
?>









