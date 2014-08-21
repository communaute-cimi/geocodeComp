<?php
    require_once('config.inc.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Outil de test pour le géocodage</title>
		<meta charset="utf-8" />

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="resources/libs/jQuery-2.1.1.js"></script>

		<link rel="stylesheet" href="resources/libs/bootstrap-3.2.0-dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="resources/libs/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css">
        
        <script src="resources/libs/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>     
        
		<script src="resources/libs/leaflet-0.7.3/leaflet.js"></script>
        <link rel="stylesheet" href="resources/libs/leaflet-0.7.3/leaflet.css" />
        
        <!-- awesome-markers pour la coloration des markers -->
        <script src="resources/libs/Leaflet.awesome-markers-2.0-develop/leaflet.awesome-markers.min.js"></script>
        <link rel="stylesheet" href="resources/libs/Leaflet.awesome-markers-2.0-develop/leaflet.awesome-markers.css" />
        <link rel="stylesheet" href="resources/libs/font-awesome-4.1.0/css/font-awesome.min.css" />        
        
        
        <script src="resources/js/geocoder.js"></script>
        <link rel="stylesheet" href="resources/css/geocoderCompare.css" />

	</head>
	
	<body onload="initMap('<?php echo $oJsonConf['map']['osmUrl'];?>');">

		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">Géocodage</a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li>
							<a href="#about">A propos</a>
						</li>
						
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>

		<div class="container">
			<div>
				<form class="form-geocode">
				    <h2 class="form-signin-heading">Geocoder compare</h2>
				    <input class="form-control" type="text" id="adresse" placeholder="Adresse" />
				    <input class="form-control code-postal" type="text" id="cpVille" placeholder="Code postal / ville" required/>
					<div class="form-btn-action">
					    
					    <?php
					       
					       foreach ($geocoders as $geocoder) {

                              $tpl = <<<EOF
                                <button onclick='%s; return false;' class='btn btn-xs btn-default'>
                                    <span class='glyphicon glyphicon-flash'></span>&nbsp;%s&nbsp;<span style='background-color:%s'>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                </button>
EOF;
                            printf($tpl, $geocoder->getJsFunction(), $geocoder->name,  $geocoder->iconColor);
						   }
					    ?>						
					</div>
				</form>
			</div>
			<div id="map" style="height: 700px"></div>

		</div><!-- /.container -->



	</body>
</html>