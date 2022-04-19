<?php 
	require_once("functions.php");
	require_once("db-const.php");
	session_start();
	if (logged_in() == true) {
		goto come;
			}
	else {
		redirect_to("login.php");
	}
	come:
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
	<title>Routing Application</title>
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
	<link rel="stylesheet" type="text/css" href="css/default.css" />
	<link rel="stylesheet" type="text/css" href="css/component.css" />
    <link rel="stylesheet" href="src/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="src/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="src/ol3/ol.css">
    <link rel="stylesheet" href="src/css/Popup.css">
    <link rel="stylesheet" href="src/css/LayersControl.css">
	<link rel="stylesheet" href="src/ol3-layerswitcher.css" />
	<script src="src/ol3/ol-debug.js"></script>
    <script src="js/jquery.js"></script>
	<script src="src/ol3-cesium/Cesium/Cesium.js"></script>
    <script src="src/ol3-cesium/ol3cesium.js"></script>
    <script src="src/bootstrap/js/bootstrap.js"></script>
	<script src="src/ol3-layerswitcher.js"></script>
	
	
    <script src="http://openlayers.org/api/OpenLayers.js"></script>
	
	
    <!--<script src="layerswitcher.js"></script>
    <!--<script src="src/bootstrap/js/bootstrap.js"></script>-->
	<script src="js/modernizr.custom.js"></script>
    <style>
      .layers-control {
        position: fixed;
        bottom: 10px;
        top: auto;
      }
      .navbar .navbar-brand {
        font-weight: bold;
        font-size: 25px;
        color: white;
      }
      #popup-content {
        max-height: 200px;
        overflow-y: auto;
      }
    </style>
    
  </head>
  <body bgcolor="#000000">
  <table width="188%" border="0" id="topmenu">
  <tr>
    <td width="434" height="57" class="header"><img src="images/logo1.png" id="logo"></td>
	<td class="header" >
	<div class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a id="toolsDrop" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe" style="color: white"></i>&nbsp;&nbsp;Tools <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" onclick="map.getView().setCenter(center); map.getView().setZoom(zoom); return false;"><i class="fa fa-arrows-alt"></i>&nbsp;&nbsp;Zoom To Full Extent</a></li>
			  <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="toggle-globe"><i class="fa fa-globe"></i>Toggle globe view</a></li>
         
		   <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="clear-tracks"><i class="fa fa-trash-o"></i>&nbsp;&nbsp;Clear all tracks</a></li>
		   </ul>
		   
          </li>
        </ul>
      </div>
	  </td>
    <td width="70" class="topmenu"><a href="user_home.php">Home</a></td>
    <td width="70" class="topmenu"><a href="index.php">Get Started</a></td>
    <td width="70" class="topmenu"><a href="user_documentation.php">Documentation</a></td>
    <td width="70" class="topmenu"><a href="user_support.php">Support</a></td>
    <td width="70" class="topmenu"><a href="login.php">Login</a></td>
  </tr>
  <tr>
    <td colspan="7" class="my_body">
	
    <!--<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a id="toolsDrop" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe" style="color: white"></i>&nbsp;&nbsp;Tools <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" onclick="map.getView().setCenter(center); map.getView().setZoom(zoom); return false;"><i class="fa fa-arrows-alt"></i>&nbsp;&nbsp;Zoom To Full Extent</a></li>
            </ul>
          </li>
        </ul>
      </div>//.navbar-collapse 
    </div>-->
	<div id="info"></div>
    <div id="map">
      <div id="popup" class="ol-popup">
      </div>
	  <!--<button onclick="mybarrier()">Add barrier</button>
	  <button onclick="removeBarrier()">Remove barrier</button>
      <button onclick="passingvalue()">Getting coordinates</button>-->
    </div>
    <script src="src/app/app.js"></script>
	<script>
		function mybarrier()
		{
		hurdle();
		}
	</script>
	 </td>
  </tr>
  <tr>
    <td height="67" colspan="7" class="footer">
    <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
			<h3>Find Route(s)</h3>
            <form style="margin-left:25px" value="formatPlaces(source.get('name'))">
           <div id="options">
            <h4>ROUTE OPTIONS:</h4>
             <input type="radio" id="r1" name="Route" value="shortest"  />Shortest<br />
             <input type="radio" id="r2" name="Route" value="fastest" />Fastest<br />
			 <input type="radio" id="r3" name="Route" value="fastestbarrier" />Barrier-Free<br />
			 <!--<input type="radio" id="r4" name="Route" value="shortestbarrier" />Shortest Barrier-Free<br />-->
            </div>
            <form style="margin-left:25px">
            <h4>IDENTIFY:</h4>
            <p>Choke Points:</p><button type="button" onclick="passingvalue()" class="button_intersection"></button><br />
            <!--<input type="radio" name="identify" value="Road Classification" />Road Classification<br />-->
            </form>
            <form style="margin-left:25px">
            <!--<h4>AVOID FEATURES:</h4>
            <input type="radio" name="barriers" value="bridges"  />Bridges<br />
            <input type="radio" name="barriers" value="boundaries"  />Boundary Lines<br />
            <input type="radio" name="barriers" value="water"  />Water Bodies<br />-->
            </form><br />
            <button type="button" style="width:40px;height:30px;margin-left:160px;" onclick="condition()">Go</button>
			<!-- <button type="button" style="width:40px;height:30px;margin-left:160px;" onclick="mybarrier()">Barrier</button>-->
            <h5 style="margin-left:25px;" >RESULTS:</h5>
			<div style="border-color:white;margin-left:15px;width:220px;height:50px;" id="demo"></div>
		</nav>
        <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s2">
          	<button type="button" class="button_userloc" <abbr title="User Profile"></abbr><a style="background-color:transparent; border:none" href="profile.php"></a></button><br />
            <button type="button" onclick="mybarrier()" class="button_barrier" <abbr title="Add Barrier"></abbr></button><br />
            <button type="button" onclick="removeBarrier()" class="button_rbarrier" <abbr title="Remove Barrier"></abbr></button><br />
            <button type="button" onclick="map.getView().setCenter(center); map.getView().setZoom(zoom); return false;" class="button_view" <abbr title="Zoom to Full Extent"></abbr></button><br />
            <button type="button" onclick="passingvalue()" class="button_intersection" <abbr title="Choke Points"></abbr></button><br />
            <!--<button type="button" onclick="#" class="button_elevprofile"></button><br />
            <button type="button" onclick="#" class="button_export"></button><br />-->
		</nav>
		<div class="container">
        <div style="float:right"><p>&copy; All Rights Reserved<br>Institute of Geographical Information System </p></div>
		<div class="main">
		  <section>
					<!--<h2>Slide Menus</h2>-->
					<!-- Class "cbp-spmenu-open" gets applied to menu -->
				<button id="showLeft">Left  Menu</button>
                <button id="showRight">Right  Menu</button>
		  </section>
			</div></br>
			<a id="export-png" class="btn" download="map.png"> <i class="icon-download"></i>Export PNG</a>
		</div>
		<!-- Classie - class helper functions by @desandro https://github.com/desandro/classie -->
		<script src="js/classie.js"></script>
		<script>
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				menuRight = document.getElementById( 'cbp-spmenu-s2' ),
				body = document.body;

				showLeft.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeft' );
			};
				showRight.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( menuRight, 'cbp-spmenu-open' );
				disableOther( 'showRight' );
			};
		</script>
		
		
		
		
	
    </td>
  </tr>
</table>
 
<script>
var exportPNGElement = document.getElementById('export-png');
exportPNGElement.crossOrigin="anonymous";
if ('download' in exportPNGElement) {
  exportPNGElement.addEventListener('click', function(e) {
    map.once('postcompose', function(event) {
      var canvas = event.context.canvas;
      exportPNGElement.href = canvas.toDataURL('image/png');
    });
    map.renderSync();
  }, false);
} else {
  var info = document.getElementById('no-download');
  /**
   * display error message
   */
  info.style.display = '';
}
		</script>


</body>
</html>
