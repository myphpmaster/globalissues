<?php 
$json_loc = mashape_json('location', array('ip' => get_client_ip()));

$current_loc = array();
if(!empty($json_loc->body->city))
	$current_loc['city'] = $json_loc->body->city;

if(!empty($json_loc->body->region))
	$current_loc['region'] = $json_loc->body->region;
	
if(!empty($json_loc->body->country))
	$current_loc['country'] = $json_loc->body->country;
	
$current_city = implode(', ',$current_loc);

?>
    <!-- Main Content -->
    <header>
        <div class="container">
            <div class="intro-text">
                <div class="intro-heading"><i class="fa fa-globe"></i>&nbsp;<?php echo $site_vars['site_title']; ?></div>
                <div class="intro-lead-in">Enter location to begin!</div>
                <div class="intro-city">
		     <div id="eqnotice">
		     <?php if(!empty($current_city)): ?>
			<div class="alert alert-info alert-dismissible" role="alert">
  				<button type="button" class="close" data-dismiss="alert">
				<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  				We have suggested <?php echo $current_city; ?> from your ip <strong><?php echo get_client_ip(); ?></strong>
			</div>
		     <?php endif; ?>
		     </div>
		     <input id="geocity" name="city" type="text" value="<?php echo $current_city; ?>" />
		</div>
                <span class="btn btn-xl" onclick="update_city();">Let's Start</span>
            </div>
        </div>
    </header>
<script>
    function selectAllText(textbox) {
	textbox.focus();
	textbox.select(); 
}

jQuery( document ).ready(function($) {
	$('#geocity').click(function() { 
		selectAllText(jQuery(this)) 
		$('#eqnotice .alert').alert('close');
	});
});
</script>

<!-- Geocomplete script - http://jquer.in/jquery-plugins-for-html5-forms/geocomplete/  -->
<script type="text/javascript">
jQuery(function () 
 {
        $("#geocity").geocomplete();        
 });
</script>
