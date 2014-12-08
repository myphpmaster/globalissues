<?php
$current_lat = isset($_POST['lat']) ? $_POST['lat'] : $json_loc->body->latitude;
$current_long = isset($_POST['lon']) ? $_POST['lon'] : $json_loc->body->longitude;
$current_city = isset($_POST['mycity']) ? $_POST['mycity'] : $current_city;

$twit_woeid = twitter_json("closest", array('lat'=>$current_lat,'long'=>$current_long));
$twit_woeid = $twit_woeid[0]['woeid'];

$tophashtags = twitter_json("trending", array('id'=>$twit_woeid));

// checking($tophashtags);
$tweets = isset($tophashtags[0]['trends']) ? $tophashtags[0]['trends'] : false;

$insta_photos = direct_json(
		'instagram',
		array('lat'=>$current_lat,'lng'=>$current_long)
		);
// checking($insta_photos);
$insta_data = $insta_photos->data; 

$yt_videos = google_json(
					'youtube',
					array(
						'q' => 'test',
						'order' => 'date',
						'type' => 'video',
						'part' => 'snippet',
						'maxResults' => '21',
						'location' => $current_lat . ',' . $current_long,
                		'locationRadius' => '50km', 
						)
					);
// checking($yt_videos);
?>
<?php if(!isset($_POST['action'])): ?>
    <!-- Social Section -->
    <section id="social">
<?php endif; ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Social</h2>
                    <h3 class="section-subheading text-muted">Displaying latest data from social network site near <span class="mycity"><?php echo $current_city; ?></span>.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div role="tabpanel">
                    
                      <!-- Nav tabs -->
                      <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#twitfeed" aria-controls="home" role="tab" data-toggle="tab">Twitter</a></li>
                        <li role="presentation"><a href="#instafeed" aria-controls="profile" role="tab" data-toggle="tab">Instagram</a></li>
                        <li role="presentation"><a href="#ytfeed" aria-controls="profile" role="tab" data-toggle="tab">YouTube</a></li>
                      </ul>
                    
                      <!-- Tab panes -->
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="twitfeed">
                            <h3 class="section-subheading text-muted">Top trending tweets near <span class="mycity"><?php echo $current_city; ?></span></h3>
        					<div id="twitpost">
                            	<ul>
                                <?php foreach ($tweets as $item): ?>
                                	<li><a href="<?php echo $item['url']; ?>" target="_blank"><?php echo $item['name']; ?></a></li>
                                <?php endforeach; ?>
                                </ul>
                            </div>                  
                        </div>
                        <div role="tabpanel" class="tab-pane" id="instafeed">
                            <h3 class="section-subheading text-muted">Instagram images uploaded near <span class="mycity"><?php echo $current_city; ?></span></h3>
        					<div id="instapost">
		<?php  
	        if (count($insta_data) > 0) {
	          echo '<ul>';
	          foreach ($insta_data as $item) {
	            echo '<li style="display: inline-block; padding: 10px"><a href="' . 
	              $item->images->standard_resolution->url . '" data-toggle="lightbox" data-gallery="multiimages" data-title=" ' . implode(', ', $item->tags) . '"><img src="' . $item->images->thumbnail->url . '" /></a> <br/>';
	            echo 'By: <em>' . $item->user->username . '</em></li>';
	          }
	          echo '</ul>';
	        }
		?>
        					</div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="ytfeed">                   
						   
                          <h3 class="section-subheading text-muted">Youtube videos uploaded near <span class="mycity"><?php echo $current_city; ?></span></h3>
                          <div id="ytpost">
                            <ul>
                                <?php foreach($yt_videos as $item):	?>
                                <li style="display: inline-block; padding: 10px">
                                    <a href="http://www.youtube.com/watch?v=<?php echo $item['id']['videoId']; ?>" data-toggle="lightbox" data-gallery="youtubevideos" data-title="<?php echo $item['snippet']['title']; ?>">
                                            <img style="width:150px;height:auto;" src="<?php echo $item['snippet']['thumbnails']['medium']['url']; ?>" width="150" class="img-responsive">
                                     </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                    	   </div>
                    
                        </div>
                      </div>
                    
                    </div>
                </div>
            </div>
        </div>
<?php if(!isset($_POST['action'])): ?>
    </section>
<?php endif; ?>