    <!-- Disasters Section -->
    <section id="disasters">
        <div class="container">
<?php

$pdcdisaster = direct_xml("pdcdisaster");
// checking($pdcdisaster);

$alldisasters = $pdcdisaster->entry;
?>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Disasters</h2>
					<h3 class=""></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                	<div class="col-lg-12">
                        <div role="tabpanel">
                        
                          <!-- Nav tabs -->
                          <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation"><a href="#act_tab" aria-controls="home" role="tab" data-toggle="tab">Active Disasters</a></li>
                            <li role="presentation" class="active"><a href="#dis_tab" aria-controls="profile" role="tab" data-toggle="tab">Earthquakes</a></li>
                          </ul>
                        
                          <!-- Tab panes -->
                          <div class="tab-content">
                            <div role="tabpanel" class="tab-pane" id="act_tab">
                            	<h3 class="section-subheading text-muted text-center">
                                <strong><?php echo count($alldisasters); ?>  active disasters have been reported around the world</strong><br/>
				Data from <a href="http://www.pdc.org/" target="_blank">Pacific Disaster Center</a> 
			(Time updated: <?php echo $pdcdisaster->updated; ?>)
            					</h3>
            					<div class="col-lg-12" id="pdcdisaster"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane active" id="dis_tab">
                    <h3 class="section-subheading text-muted text-center" style="margin-bottom: 30px;">
					<strong><span id="eqnum">You can change city name in <a href="#page-top">above section</a>. Currently set to </span><span class="mycity"><?php echo $current_city; ?></span></strong><br/>
			<span id="equpdated"></span>
		    </h3>
            <div class="row text-center">
                <div class="col-md-12">
                    	<!-- Google Map -->
    			<div id="map-canvas"></div>
			<!-- map legend -->
			<div style="display:none;">
			     <div id="eqlegend">
			     	<div class="hide_show"><a href="#" onclick="jQuery('#eqlegend table').toggle();return false;">Hide/Show Earthquake Legend</a></div>
			        <table  class="table table-striped" cellpadding="1" cellspacing="10" border="0">
				<tbody>
				<tr style="background: rgba(249, 249, 249, 0.7);">
					<td valign="top"><b>Magnitude</b></td>
					<td valign="top"><b>Earthquake Effects</b></td>
				</tr>
				<tr style="background: rgba(0, 255, 0, 0.5);">
					<td>2.5 or less</td>
					<td style="text-align: left;">Usually not felt, but can be recorded by seismograph.</td>
				</tr>
				<tr style="background: rgba(255, 255, 77, 0.5);">
					<td>2.5 to 5.4</td>
					<td style="text-align: left;">Often felt, but only causes minor damage.</td>
				</tr>
				<tr style="background: rgba(255, 255, 0, 0.5);">
					<td>5.5 to 6.0</td>
					<td style="text-align: left;">Slight damage to buildings and other structures.</td>
				</tr>
				<tr style="background: rgba(255, 145, 117, 0.5);">
					<td>6.1 to 6.9</td>
					<td style="text-align: left;">May cause a lot of damage in very populated areas.</td>
				</tr>
				<tr style="background: rgba(255, 89, 48, 0.5);">
					<td>7.0 to 7.9</td>
					<td style="text-align: left;">Major earthquake. Serious damage.</td>
				</tr>
				<tr style="background: rgba(255, 0, 0, 0.5);">
					<td>8.0 or greater</td>
					<td style="text-align: left;">Great earthquake. Can totally destroy communities near the epicenter.</td>
				</tr>
			</tbody></table>
			     </div>
			</div>
                </div><!-- # map legend -->
                            
                              </div>
                            </div>
                          </div>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
$x = 0;
foreach ($alldisasters as $disaster) {
	
	$link = $disaster->link->attributes();
	$datas[$x]['title'] = "'<a href=\'" . $link['href'] . "\' data-title=\'" . $disaster->title . "\' data-toggle=\'lightbox\' data-parent=\'\' target=\'_blank\' >" . $disaster->title . "</a>'";

	$datas[$x]['time'] = "'" . $disaster->updated . "'";
	$datas[$x]['loc'] = "'" . str_replace(' ',',',$disaster->georsspoint) . "'";
	
	$data_type = strtolower(preg_replace('/(\w+) \((\w+)\)/','$1',$disaster->summary));
	
	$icon = $data_type;
	$icon = ($icon=='highsurf') ? 'high_surf' : $icon;
	$icon = ($icon=='volcano') ? 'volcano_eruption' : $icon;
	
	$urlicon = 'http://www.pdc.org/phpThumb?src=/img/icons/big/' . $icon . '.png&w=24&h=24&zc=1&f=png';
	
	$datas[$x]['type'] = "'" . $data_type . ' <img src="' . $urlicon . '" />' . "'";
	
	$data_status = strtolower(preg_replace('/(\w+) \((\w+)\)/','$2',$disaster->summary));
	
	$ico = ($data_status == 'information') ? 'info' : $data_status;

	$datas[$x]['status'] = "'" . $data_status . "  <img src=\'http://www.pdc.org/img/icons/ico_" . $ico . ".png\' />'";
	$x++;
}

$newdatas=array();
foreach ($datas as $data){
	$newdatas[] = '[' . implode(',',$data) . ']';
}
?>
<script>
var dataSet = [
    <?php echo implode(',',$newdatas); ?>
];
 
jQuery(document).ready(function() {
    $('#pdcdisaster').html( '<table cellpadding="0" cellspacing="0" border="0" class="responsive display table table-striped table-bordered" id="latest_disasters"></table>' );
 
    $('#latest_disasters').dataTable( {
        "data": dataSet,
        "columns": [
            { "title": "Disaster Title" },
            { "title": "Updated Time" },
            { "title": "Location" },
            { "title": "Type", "class": "center" },
            { "title": "Status", "class": "center" }
        ],
		"order": [[ 1, "desc" ]],
		"responsive": true
    } );   
} );
</script>
<?php
$json_earthquake_raw = direct_json(
		'usgs',
		array(
			'latitude' => $json_loc->body->latitude,
			'longitude' => $json_loc->body->longitude,
			'maxradiuskm' => '1000',
			'callback' => 'eqfeed_callback'
			),
		'url'
		);

?>
<script>
// Google Maps Javascript V3 data layer - https://developers.google.com/maps/documentation/javascript/examples/layer-data-quakes
var geocoder = new google.maps.Geocoder();
var marker;
var my_address;
var infowindow = new google.maps.InfoWindow();
var featurewindow = new google.maps.InfoWindow();
var eqsrc = '<?php echo $json_earthquake_raw; ?>';
var pos = new google.maps.LatLng('<?php echo $json_loc->body->latitude; ?>','<?php echo $json_loc->body->longitude; ?>');
var legend = document.getElementById('eqlegend');

function initialize() {
  
  var mapOptions = {
    zoom: 5
  };

  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

  geocode_marker(pos);
  map.setCenter(pos);

  map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);

  load_eqsrc(eqsrc);
}
google.maps.event.addDomListener(window, 'load', initialize);

function load_eqsrc(new_src){

  // Create a <script> tag and set the USGS URL as the source.
  var script = document.createElement('script');

  script.src = new_src;
  script.id = 'earthquake_data';
  document.getElementsByTagName('head')[0].appendChild(script);

  map.data.setStyle(function(feature) {
    var magnitude = feature.getProperty('mag');
    var place = feature.getProperty('place');
    return {
      icon: getCircle(magnitude),
	title: place
    };
  });

  map.data.addListener('click', function(event) {
	//show an infowindow on click   
	featurewindow.setContent('<div style="line-height:1.35;overflow:hidden;white-space:nowrap;"> <strong>' +
				event.feature.getProperty("title") + "</strong><br/>" + 		
				convertTimestamp(event.feature.getProperty("time")) +
				'<br/><a href="' + event.feature.getProperty("url") + '" target="_blank">Read more</a>' +
				"</div>");
	var anchor = new google.maps.MVCObject();
	anchor.set("position",event.latLng);
	featurewindow.open(map,anchor);
  });


}

function handleNoGeolocation(errorFlag) {
  if (errorFlag) {
    var content = 'Error: The Geolocation service failed.';
  } else {
    var content = 'Error: Your browser doesn\'t support geolocation.';
  }

  var options = {
    map: map,
    position: new google.maps.LatLng(60, 105),
    content: content
  };

  infowindow = new google.maps.InfoWindow(options);
  map.setCenter(options.position);
}

function geocode_marker(pos){

  geocoder.geocode({'latLng': pos}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
        
        marker = new google.maps.Marker({
            position: pos,
            map: map
        });

		my_address = jQuery('#geocity').val();
	
        infowindow.setContent(my_address);
        infowindow.open(map, marker);

		google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map, this);
		});

    } else {
      alert('Geocoder failed due to: ' + status);

    }
  });
}

<?php
$json_earthquake_url = direct_json(
		'usgs',
		array(
			'callback' => 'eqfeed_callback'
			),
		'url'
		);

?>

function eqfeed_callback(results) {
  map.data.addGeoJson(results);
  jQuery('#eqnum').html(results.metadata.count + ' earthquakes recorded in past 30 days within 100km radius from ');
  jQuery('#equpdated').html('Data from <a href="http://earthquake.usgs.gov/" target="_blank">USGS</a> (Updated: ' + convertTimestamp(results.metadata.generated) + ')');
}

function getCircle(magnitude) {
  var circle = {
    path: google.maps.SymbolPath.CIRCLE,
    fillColor: color_mag(magnitude),
    fillOpacity: .5,
    scale: Math.pow(2, magnitude) / 2,
    strokeColor: color_mag(magnitude),
    strokeWeight: .5,
    zIndex: 1000-magnitude*100
  };
  return circle;
}

function color_mag(value){

	if(value<=2.50)
		return '#00FF00';
	else if(value>2.50&&value<5.5)
		return '#FFFF00';
	else if(value>=5.50&&value<6.0)
		return '#FFFF4D';
	else if(value>=6.0&&value<7.0)
		return '#FF9175';
	else if(value>=7.0&&value<8.0)
		return '#FF5930';
	else if(value>=8.0)
		return '#FF0000';

}
</script>
<script>
// Function to convert timestamp to local time - https://gist.github.com/kmaida/6045266 
function convertTimestamp(timestamp) {
  var d = new Date(timestamp),	// Convert the passed timestamp to milliseconds
		yyyy = d.getFullYear(),
		mm = ('0' + (d.getMonth() + 1)).slice(-2),	// Months are zero based. Add leading 0.
		dd = ('0' + d.getDate()).slice(-2),			// Add leading 0.
		hh = d.getHours(),
		h = hh,
		min = ('0' + d.getMinutes()).slice(-2),		// Add leading 0.
		ampm = 'AM',
		time;
			
	if (hh > 12) {
		h = hh - 12;
		ampm = 'PM';
	} else if (hh === 12) {
		h = 12;
		ampm = 'PM';
	} else if (hh == 0) {
		h = 12;
	}

	// Added timezone
	tz = d.getTimezoneOffset();
	
	if(tz==0)
		tzs = ' UTC';
	else if(tz>0)
		tzs = ' UTC-';
	else
		tzs = ' UTC+';
	
	// get absolute value
	tz = Math.abs(tz);
	
	tzhh = Math.floor(tz/60);
	
	tzmm = tz - tzhh*60;
	if(tzmm==0)
		tzmm = '00';
	
	// ie: 2013-02-18, 8:35 AM (Added UTC+8:00)
	time = yyyy + '-' + mm + '-' + dd + ', ' + h + ':' + min + ' ' + ampm + ' ' + tzs + tzhh + ':' + tzmm;
		
	return time;
}

function update_city(){

	var city = jQuery('#geocity').val();
	var earthquake_data = '<?php echo $json_earthquake_url; ?>';
	var eqvars = [];
	var new_loc;
	
	if(city=='<?php echo $current_city; ?>'){
		
		        $('html, body').stop().animate({
		            scrollTop: $('#disasters').offset().top
		        }, 1500, 'easeInOutExpo');
				return false;
	}
		
	eqvars['maxradiuskm'] = '1000';

	geocoder.geocode( { 'address': city}, function(results, status) {
      		if (status == google.maps.GeocoderStatus.OK) {

			new_loc
			eqvars['latitude'] = results[0].geometry.location.lat();
			eqvars['longitude'] = results[0].geometry.location.lng();

			for (var i in eqvars) {
       				earthquake_data += "&" + i + "=" + eqvars[i];
    			}

			jQuery('#earthquake_data').remove();
			jQuery('#map-canvas').show();

			eqsrc = earthquake_data;			
			pos = results[0].geometry.location;
			
			initialize();

        		map.setCenter(results[0].geometry.location);

			marker = new google.maps.Marker({
			            position: pos,
			            map: map
			        });
        		marker.setPosition(results[0].geometry.location);

			infowindow.setContent('<div style="line-height:1.35;overflow:hidden;white-space:nowrap;">' + city + '</div>');

			load_eqsrc(earthquake_data);
			jQuery('.mycity').html(city);
			$('#eqnotice .alert').alert('close');

		        $('html, body').stop().animate({
		            scrollTop: $('#disasters').offset().top
		        }, 1500, 'easeInOutExpo');

	      	} else {
        		jQuery('#eqnotice').html(
				'<div class="alert alert-warning alert-dismissible" role="alert">'+
  					'<button type="button" class="close" data-dismiss="alert">' +
					'<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' +
  					'Geocode was not successful for the following reason:  <strong>' + status + '</strong>'+
				'</div>');
			return false;
      		}
			
			// Updating Weather section
			$.ajax({
			  type: "POST",
			  url: "ajax.php",
			  data: { action: "weather", mycity: city, lat: eqvars['latitude'], lon: eqvars['longitude'] }
			})
			  .done(function( content ) {
				$( "#weather" ).html(content);
			  });
			  
			// Updating Economic section
			$.ajax({
			  type: "POST",
			  url: "ajax.php",
			  data: { action: "economic", mycity: city, country: 'MYS' }
			})
			  .done(function( content ) {
				$( "#economic" ).html(content);
			  });
			  
			// Updating Facilities section
			$.ajax({
			  type: "POST",
			  url: "ajax.php",
			  data: { action: "facilities", mycity: city, lat: eqvars['latitude'], lon: eqvars['longitude'] }
			})
			  .done(function( content ) {
				$( "#facilities" ).html(content);
			  });
			  
			  
			// Updating Social section
			$.ajax({
			  type: "POST",
			  url: "ajax.php",
			  data: { action: "social", mycity: city, lat: eqvars['latitude'], lon: eqvars['longitude'] }
			})
			  .done(function( content ) {
				$( "#social" ).html(content);
			  });
    	});
}

$(function () {
  $('[data-toggle="popover"]').popover();
})

</script>
