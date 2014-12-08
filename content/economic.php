<?php

$ctry_code = isset($_POST['country']) ? $_POST['country'] : 'MYS';
$current_city = isset($_POST['mycity']) ? $_POST['mycity'] : $current_city;

$quandls = array('gdppc','gdpgro','cpi','pop','unemp');
$quandls_datas = array();

foreach($quandls as $item){
	$quandls_datas[$item] = quandl_json($item,$ctry_code,array('trim_start'=>'2005-12-31','trim_end'=>'2020-12-31'));
}

// checking($quandls_datas['gdppc']);

$quandls_title = array(
					'gdppc' => 'GDP per Capita',
					'gdpgro' => 'Real GDP Growth',
					'cpi' => 'Consumer Price Index (CPI)',
					'pop' => 'Population',
					'unemp' => 'Unemployment',
					);
?>
<?php if(!isset($_POST['action'])): ?>
    <!-- Economic Section -->
    <section id="economic">
<?php endif; ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Economic</h2>
                    <h3 class="section-subheading text-muted">Some economic report for <strong><span class="mycountry"><?php echo $current_loc['country']; ?></span></strong>, the country of <span class="mycity"><?php echo str_replace(', '.$current_loc['country'],'',$current_city); ?></span>.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div role="tabpanel">
    
                      <!-- Nav tabs -->
                      <ul class="nav nav-tabs" role="tablist" id="ecoTab">
                      <?php foreach($quandls as $item) : ?>
                        <li role="presentation"><a href="#<?php echo $item; ?>" aria-controls="<?php echo $item; ?>" role="tab" data-toggle="tab"><?php echo $quandls_title[$item]; ?></a></li>
                      <?php endforeach; ?>
                      </ul>
                    
                      <!-- Tab panes -->
                      <div class="tab-content">
                      <?php foreach($quandls as $item) : ?>
                        <div role="tabpanel" class="tab-pane" id="<?php echo $item; ?>">
                            <h3 class="section-heading2"><?php echo $quandls_title[$item]; ?></h3>
                            <p><?php echo $quandls_datas[$item]->description; ?></p>
                            <canvas class="chart" width="800" height="400"></canvas>
                        </div>
                      <?php endforeach; ?>
                      </div>
                    
                    </div>
                </div>
            </div>
        </div>
<?php    
foreach($quandls as $item) :

	$datas[$item] = array_reverse($quandls_datas[$item]->data);
	$qd_label[$item] = array();
	$qd_data[$item] = array();

	foreach($datas[$item] as $data){
		$qd_label[$item][] = '"' . substr($data[0],0,4) . '"';
		$qd_data[$item][] = $data[1];
	}

?>
<script>
// Display chart using Chart.js
var ctx = $("#<?php echo $item; ?> .chart").get(0).getContext("2d");
var gdppcData = {
    labels: [<?php echo (implode(',',$qd_label[$item])); ?>],
    datasets: [
        {
            label: "<?php echo $quandls_datas[$item]->name; ?>",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php echo (implode(',',$qd_data[$item])); ?>]
        }
    ]
};
var gdppcOpts = {
    scaleShowValues: true, 
    scaleValuePaddingX: 13,
    scaleValuePaddingY: 13
};
var gdppcChart = new Chart(ctx).Line(gdppcData,gdppcOpts);
</script>
<?php endforeach; ?>
<script>
$('#ecoTab a:first').tab('show') // Select last tab
</script>
<?php if(!isset($_POST['action'])): ?>
    </section>
<?php endif; ?>