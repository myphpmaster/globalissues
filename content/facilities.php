<?php
$current_lat = isset($_POST['lat']) ? $_POST['lat'] : $json_loc->body->latitude;
$current_long = isset($_POST['lon']) ? $_POST['lon'] : $json_loc->body->longitude;
$current_rad = isset($_POST['radius']) ? $_POST['radius'] : '5000';		// in meters
$current_city = isset($_POST['mycity']) ? $_POST['mycity'] : $current_city;

// Full list of types at https://developers.google.com/places/documentation/supported_types
$faci_lists = array('train_station','airport','lawyer','doctor','dentist','pharmacy','police','post_office','fire_station','hospital');

$json_gplace = direct_json(
		'gplaces',
		array(
			'location' => $current_lat . ',' . $current_long,
			'radius' => $current_rad, 
			'types' => implode('|',$faci_lists)		
			),
		'decode'
		);
// checking($json_gplace);
?>
<?php if(!isset($_POST['action'])): ?>
    <!-- Facilities Section -->
    <section id="facilities" class="bg-light-gray">
<?php endif; ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Facilities</h2>
                    <h3 class="section-subheading text-muted">Displaying facilities within <span id="radius"><?php echo $current_rad; ?></span>m radius from <span class="mycity"><?php echo $current_city; ?></span>.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
		    <div id="places_nearby" class="row">
		    <?php foreach ($json_gplace->results as $item): ?>
			<div class="item mosque col-lg-3 col-md-3">
			    <div class="wrap">
			        <img src="<?php echo $item->icon; ?>" />
				<?php
				
				$json_gpdetails = direct_json(
						'gpdetails',
						array('placeid' => $item->place_id),
						'decode'
						);
				// checking($json_gpdetails);
				$gp_phone = isset($json_gpdetails->result->formatted_phone_number) ? $json_gpdetails->result->formatted_phone_number : '';
				?>
				<p class="name">
					<a href="<?php echo $json_gpdetails->result->url; ?>" target="_blank">
						<?php echo $item->name; ?>
					</a>
					<p><?php echo $json_gpdetails->result->formatted_address; ?></p>
					<?php echo $gp_phone; ?>
				</p>
			    </div>
			</div>
		    <?php endforeach; ?>
		    </div>
		    <p><img src="https://maps.gstatic.com/mapfiles/api-3/images/powered-by-google-on-white2.png" /></p>
                </div>
            </div>
        </div>
        
<script>
var $container = $('#places_nearby');
// init
$container.isotope({
  // options
  itemSelector: '.item',
  layoutMode: 'fitRows'
});
</script>

        
<?php if(!isset($_POST['action'])): ?>
    </section>
<?php endif; ?>