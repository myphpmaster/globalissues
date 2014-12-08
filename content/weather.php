<?php
$current_lat = isset($_POST['lat']) ? $_POST['lat'] : $json_loc->body->latitude;
$current_long = isset($_POST['lon']) ? $_POST['lon'] : $json_loc->body->longitude;
$current_city = isset($_POST['mycity']) ? $_POST['mycity'] : $current_city;

$json_openweather = direct_json('openweather',array('lat' => $current_lat,'lon' => $current_long,'units'=>'metric'));
// checking ($json_openweather);

$json_mashweather = mashape_json('weather',array('lat' => $current_lat,'lon' => $current_long,'units'=>'metric'));
$weathers = $json_mashweather->body->list;
// checking ($weathers);

?>
<?php if(!isset($_POST['action'])): ?>
    <!-- Weather Grid Section -->
    <section id="weather" class="bg-light-gray text-center">
<?php endif; ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Weather</h2>
                    <h3 class="section-subheading text-muted" style="margin-bottom:10px"><strong>Weather report for <span class="mycity"><?php echo $current_city; ?></span></strong></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                <div role="tabpanel">
                
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#current" aria-controls="current" role="tab" data-toggle="tab">Today</a></li>
                    <li role="presentation"><a href="#forecast" aria-controls="forecast" role="tab" data-toggle="tab">Forecast</a></li>
                  </ul>
                
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="current">
                    <h3 class="section-subheading text-muted" style="margin-bottom:10px">Today weather for <span class="mycity"><?php echo $current_city; ?></span>.</h3>

                    <div id="todayweather">
						<div class="cloud">
                        	<img src="http://openweathermap.org/img/w/<?php echo $json_openweather->weather[0]->icon; ?>.png" />
                    		<span class="temp"><?php echo round($json_openweather->main->temp); ?><sup>°C</sup></span>
                        </div>
                        <div class="other">
                        	<ul>
                            	<li>Humidity: <?php echo $json_openweather->main->humidity; ?>%</li>
                            	<li>Wind: <?php echo $json_openweather->wind->speed; ?>km/h at <?php echo $json_openweather->wind->deg; ?>°</li>
                            	<li>Sunrise: <?php echo date('h:i A',$json_openweather->sys->sunrise); ?> UTC</li>
                                <li>Sunset: <?php echo date('h:i A',$json_openweather->sys->sunset); ?> UTC</li>
                            </ul>
                        </div>                    
                    </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="forecast">
                    <h3 class="section-subheading text-muted" style="margin-bottom:10px">5-day/3 hour weather forecast for <span class="mycity"><?php echo $current_city; ?></span> (Time in UTC).</h3>
            
                    <div id="weather_forecast" class="text-center">
                        <ul>
                        <?php
                        
                        $i=0;
                        $newday = 'new';
                        foreach ($weathers as $item):
                            $placement = ($i%2 == 0) ? 'top' : 'bottom';
                            $prevday = $newday;
                            $newday = date("D", strtotime($item->dt_txt));
                            $sepclass = ($prevday!==$newday&&$prevday!=='new') ? 'newday' : '';
                        ?>
                            <li class="<?php echo $sepclass; ?>" data-toggle="popover" title="<?php echo $item->dt_txt; ?>" data-placement="<?php echo $placement; ?>" data-content=" Humidity: <?php echo $item->main->humidity; ?>   Wind: <?php echo $item->wind->speed; ?>km/h at <?php echo $json_openweather->wind->deg; ?>°">
            
                                <div class="forecastweather">
                                    <div class="day"><?php echo date("D ga", strtotime($item->dt_txt)); ?></div>
                                    <div class="cloud"><img src="http://openweathermap.org/img/w/<?php echo $item->weather[0]->icon; ?>.png" /></div>
                                    <div class="temp"><?php echo $item->main->temp; ?>°C</div>
                                </div>
                            </li>
                        <?php 
                            $i++;
                        endforeach; ?>
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