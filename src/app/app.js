var geoserverUrl = 'http://localhost:8081/geoserver/';
var center = ol.proj.transform([73.0667, 33.7167], 'EPSG:4326', 'EPSG:3857')
var zoom = 8;
var pointerDown = false;
var currentMarker = null;
var changed = false;
var routeSource;
var travelTime;
var k=0;
var m=0;
var vectorLayer=new Array();
var routeLayer=new Array();
var travelDist;
var format = new ol.format.GeoJSON();
var x = Math.floor((Math.random() * 254) + 1);
var y = Math.floor((Math.random() * 254) + 1);
var z = Math.floor((Math.random() * 254) + 1);
//function for route options
function condition() {
        var options = document.getElementById('options').value;
        var route_options;
        if (document.getElementById('r1').checked) {
            distanceRoute();
        } else if (document.getElementById('r2').checked) {
            timeRoute();
        } else if (document.getElementById('r3').checked) {
            FastestBarrierFreeRoute();
        }
		 else if (document.getElementById('r4').checked) {
            ShortestBarrierFreeRoute();
        }
    }
    // elements in HTML document
var info = document.getElementById('info');
var popup = document.getElementById('popup');
// format a single place name
function formatPlace(name) {
        if (name == null || name == '') {
            return 'unnamed street';
        } else {
            return name;
        }
    }
    // format the list of place names, which may be single roads or intersections

function formatPlaces(list) {
        var text;
        if (!list) {
            return formatPlace(null);
        }
        var names = list.split(',');
        if (names.length == 0) {
            return formatPlace(null);
        } else if (names.length == 1) {
            return formatPlace(names[0]);
        } else if (names.length == 2) {
            text = formatPlace(names[0]) + ' and ' + formatPlace(names[1]);
        } else {
            text = ' and ' + formatPlace(names.pop());
            names.forEach(function(name) {
                text = name + ', ' + text;
            });
        }
        return 'the intersection of ' + text;
    }
    // format times for display

function formatTime(time) {
        var mins = Math.round(time * 60);
        if (mins == 0) {
            return 'less than a minute';
        } else if (mins == 1) {
            return '1 minute';
        } else {
            return mins + ' minutes';
        }
    }
    // format distances for display

function formatDist(dist) {
    var units;
    dist = dist.toFixed(2);
    if (dist < 1) {
        dist = dist * 1000;
        units = 'm';
    } else {
        units = 'km';
    }
    // make sure distances like 5.0 appear as just 5
    dist = dist.toString().replace(/\.0$/, '');
    return dist + units;
}

function createMarker(point, colour) {
    var marker = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.transform(point,
            'EPSG:3857', 'EPSG:3857'))
    });
    marker.setStyle(
        [new ol.style.Style({
            image: new ol.style.Circle({
                radius: 6,
                fill: new ol.style.Fill({
                    color: 'rgba(' + colour.join(
                        ',') + ', 1)'
                })
            })
        })]);
    marker.on('change', changeHandler);
    return marker;
}

function createBarrier(point, colour) {
    var barriers = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.transform(point,
            'EPSG:3857', 'EPSG:3857'))
    });
    barriers.setStyle(
        [new ol.style.Style({
            image: new ol.style.Circle({
                radius: 6,
                fill: new ol.style.Fill({
                    color: 'rgba(' + colour.join(
                        ',') + ', 1)'
                })
            })
        })]);
    // barriers.on('change', changeHandler);
    return barriers;
}
var sourceMarker = createMarker([8131151.38847, 3987096.24238], [0, 255, 0]);
var targetMarker = createMarker([8151440.27921, 4052275.48374], [255, 0, 0]);
var barrier = createBarrier([8192445.27921, 4073280.48374], [0, 0, 255]);
// create overlay to display the markers
var markerOverlay = new ol.FeatureOverlay({
    features: [sourceMarker, targetMarker],
});
var barrierOverlay = new ol.FeatureOverlay({
    features: [barrier],
});
// record when we move one of the source/target markers on the map
function changeHandler(e) {
    if (pointerDown) {
        changed = true;
        currentMarker = e.target;
    }
}
var moveMarker = new ol.interaction.Modify({
    features: markerOverlay.getFeatures(),
    tolerance: 20
});
var movebarrier = new ol.interaction.Modify({
    features: barrierOverlay.getFeatures(),
    tolerance: 20
});
// create overlay to show the popup box
var popupOverlay = new ol.Overlay({
    element: popup
});
// style routes differently when clicked
var selectSegment = new ol.interaction.Select({
    condition: ol.events.condition.click,
    style: new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'rgba(255, 0, 128, 1)',
            width: 8
        })
    })
});
// set the starting view
var view = new ol.View({
    center: center,
    zoom: zoom
});
var projection=ol.proj.get('EPSG:3857');
var vector = new ol.layer.Vector({
title:'kml',
visible:false,
  source: new ol.source.KML({
    projection: projection,
    url: 'http://localhost:80/routing/routingapp-dimension.kml'
  })
});


//(function() {
// create the map with OSM data
var map = new ol.Map({
    target: 'map',
    layers: [
        new ol.layer.Group({
            'title': 'Base maps',
            layers: [
                new ol.layer.Tile({
                    title: 'OSM',
                    type: 'base',
                    visible: true,
                    source: new ol.source.OSM()
                }),
                new ol.layer.Tile({
                    title: 'Bing Imagery',
                    type: 'base',
                    visible: false,
                    source: new ol.source.BingMaps({
                        // Get your own key at http://bingmapsportal.com/
                        // Replace the key below with your own.
                        key: 'AhaJDO_bWhekq58C0nGLRkwJSMphRFDTYeccozENkqZTAAa1W0OgoaWmcgbPxatZ',
                        imagerySet: 'AerialWithLabels'
                    })
                }),
				new ol.layer.Image({
					title: 'Choke Points',
                    visible: false,
					source: new ol.source.ImageWMS({
					url: 'http://localhost:8081/geoserver/wms',
					params: {'LAYERS': 'countinput'},
					serverType: 'geoserver'
    })
  }),vector
            ]
        }),
    ],
    view: view,
    overlays: [popupOverlay, markerOverlay, barrierOverlay]
});
//});
var layerSwitcher = new ol.control.LayerSwitcher({
    tipLabel: 'Légende' // Optional label for button
});
map.addControl(layerSwitcher);
map.addInteraction(moveMarker);
map.addInteraction(movebarrier);
map.addInteraction(selectSegment);
// show pop up box when clicking on part of route
var getFeatureInfo = function(coordinate) {
    var pixel = map.getPixelFromCoordinate(coordinate);
    var feature = map.forEachFeatureAtPixel(pixel, function(feature, layer) {
        if (layer == routeLayer[k]) {
            return feature;
        }
    });
    var text = null;
    if (feature) {
        text = '<strong>' + formatPlace(feature.get('name')) +
            '</strong><br/>';
		text += '<p>Road Type: <code>' + formatPlace(feature.get('type')) +
            '</code></p>';
        text += '<p>Distance: <code>' + formatDist(feature.get('distance')) +
            '</code></p>';
        text += '<p>Estimated travel time: <code>' + formatTime(feature.get(
            'time')) + '</code></p>';
        text = text.replace(/ /g, '&nbsp;');
    }
    return text;
};
// display the popup when user clicks on a route segment
map.on('click', function(evt) {
    var coordinate = evt.coordinate;
    var text = getFeatureInfo(coordinate);
    if (text) {
        popupOverlay.setPosition(coordinate);
        popup.innerHTML = text;
        popup.style.display = 'block';
    }
});
// record start of click
map.on('pointerdown', function(evt) {
    pointerDown = true;
    popup.style.display = 'none';
});
// record end of click
map.on('pointerup', function(evt) {
    pointerDown = false;
    // if we were dragging a marker, recalculate the route
    if (currentMarker) {
        getVertex(currentMarker);
        currentMarker = null;
    }
});
// timer to update the route when dragging
window.setInterval(function() {
    if (currentMarker && changed) {
        getVertex(currentMarker);
        // getRoute();
        changed = false;
    }
}, 250);
// WFS to get the closest vertex to a point on the map
function getVertex(marker) {
    var coordinates = marker.getGeometry().getCoordinates();
    var url = geoserverUrl + 'wfs?service=WFS&version=1.1.0&' +
        'request=GetFeature&typeName=routingapp:nearvertex&' +
        'outputformat=application/json&' + 'viewparams=x:' + coordinates[0] +
        ';y:' + coordinates[1];
    //alert('vertex URL: '+ url);
    var information = $.ajax({
        url: url,
        async: false,
        dataType: 'json',
        success: function(json) {
            loadVertex(json, marker == sourceMarker)
        }
    });
	var obj = eval('(' + information.responseText + ')');
        var barrierinfo = (obj.features[0].id);
        //console.log(barrierinfo);
        var res = barrierinfo.substring(11);
        //alert(res);
	
}

function passingvalue()
{
	 var coor1 = sourceMarker.getGeometry().getCoordinates(); 
	var coor2 = targetMarker.getGeometry().getCoordinates(); 
	
    var url = geoserverUrl + 'wfs?service=WFS&version=1.1.0&' +
        'request=GetFeature&typeName=routingapp:nearvertex&' +
        'outputformat=application/json&' + 'viewparams=x:' + coor1[0] +
        ';y:' + coor1[1];
    //alert('vertex URL: '+ url);
    var information1 = $.ajax({
        url: url,
        async: false,
        dataType: 'json',
        success: function(json) {
            //loadVertex(json, marker == sourceMarker)
        }
    });
	var obj1 = eval('(' + information1.responseText + ')');
        var barrierinfo1 = (obj1.features[0].id);
        //console.log(barrierinfo);
        var res1 = barrierinfo1.substring(11);
        //alert('sourceVertex: ' + res1);
        var check_p1 = parseInt(res1);
		
		
		var url = geoserverUrl + 'wfs?service=WFS&version=1.1.0&' +
        'request=GetFeature&typeName=routingapp:nearvertex&' +
        'outputformat=application/json&' + 'viewparams=x:' + coor2[0] +
        ';y:' + coor2[1];
    //alert('vertex URL: '+ url);
    var information2 = $.ajax({
        url: url,
        async: false,
        dataType: 'json',
        success: function(json) {
           // loadVertex(json, marker == sourceMarker)
        }
    });
	var obj2 = eval('(' + information2.responseText + ')');
        var barrierinfo2 = (obj2.features[0].id);
        //console.log(barrierinfo);
        var res2= barrierinfo2.substring(11);
        //alert('targetVertex: ' + res2);
        var check_p2 = parseInt(res2);
		
		


  $.ajax({
            url: 'intersection.php',
            data: {
                x: check_p1, y: check_p2 
            },
            type: 'POST'
        });

}
function getBarrierVertex(marker) {
        var coordinates = marker.getGeometry().getCoordinates();
        var url = geoserverUrl + 'wfs?service=WFS&version=1.1.0&' +
            'request=GetFeature&typeName=routingapp:nearvertex&' +
            'outputformat=application/json&' + 'viewparams=x:' + coordinates[0] +
            ';y:' + coordinates[1];
        //alert('vertex URL: '+ url);
        var information = $.ajax({
            url: url,
            async: false,
            dataType: 'json',
            success: function(json) {
                // loadVertex(json, marker == sourceMarker)
            }
        });
        var obj = eval('(' + information.responseText + ')');
        var barrierinfo = (obj.features[0].id);
        //console.log(barrierinfo);
        var res = barrierinfo.substring(11);
        
        var check_p = parseInt(res);
        //var $my_variable = "value";
		
       
        $.ajax({
            url: 'addbarrier.php',
            data: {
                x: res
            },
            type: 'POST'
        });
    }
    // load the response to the nearest_vertex layer
	
	function removeBarrier()
	{$.ajax({
            url: 'removebarrier.php',
            type: 'POST'
        });
		for(var j=0;j<m;j++)
	{
		map.removeLayer(vectorLayer[j]);
	}
	m=0;
	}
	

function loadVertex(response, isSource) {
        //console.log(response);
        var geojson = new ol.format.GeoJSON();
        var features = geojson.readFeatures(response);
        if (isSource) {
            if (features.length == 0) {
                map.removeLayer(routeLayer[k]);
                source = null;
                return;
            }
            source = features[0];
        } else {
            if (features.length == 0) {
                map.removeLayer(routeLayer[k]);
                target = null;
                return;
            }
            target = features[0];
        }
    }
    // WFS to get the route using the source/target vertices

function timeRoute() {
    // set up the source and target vertex numbers to pass as parameters
    var viewParams = ['source:' + source.getId().split('.')[1], 'target:' +
        target.getId().split('.')[1], 'cost:time'
    ];
    var url = geoserverUrl + 'wfs?service=WFS&version=1.1.0&' +
        'request=GetFeature&typeName=routingapp:TimePath&' +
        'outputformat=application/json&' + 'viewparams=' + viewParams.join(
            ';');
    // create a new source for our layer
    routeSource = new ol.source.ServerVector({
        format: new ol.format.GeoJSON(),
        strategy: ol.loadingstrategy.all,
        loader: function(extent, resolution) {
            $.ajax({
                url: url,
                dataType: 'json',
                success: loadRoute,
                async: false
            });
        },
    });
    // remove the previous layer and create a new one
    routeLayer[k] = new ol.layer.Vector({
        source: routeSource,
        style: new ol.style.Style({
            stroke: new ol.style.Stroke({
                color: 'rgba(0,0,255,0.5)',
                width: 8
            })
        })
    });
    //add new layer to map
    map.addLayer(routeLayer[k]);
	k++;
    x = Math.floor((Math.random() * 254) + 1);
    y = Math.floor((Math.random() * 254) + 1);
    z = Math.floor((Math.random() * 254) + 1);
}

function FastestBarrierFreeRoute() {
        // set up the source and target vertex numbers to pass as parameters
        var viewParams = ['source:' + source.getId().split('.')[1], 'target:' +
            target.getId().split('.')[1], 'cost:time'
        ];
        var url = geoserverUrl + 'wfs?service=WFS&version=1.1.0&' +
            'request=GetFeature&typeName=routingapp:FastestBarrierFree&' +
            'outputformat=application/json&' + 'viewparams=' + viewParams.join(
                ';');
        // create a new source for our layer
        routeSource = new ol.source.ServerVector({
            format: new ol.format.GeoJSON(),
            strategy: ol.loadingstrategy.all,
            loader: function(extent, resolution) {
                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: loadRoute,
                    async: false
                });
            },
        });
        // remove the previous layer and create a new one
        routeLayer[k] = new ol.layer.Vector({
            source: routeSource,
            style: new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: 'rgba(0,255,255,0.5)',
                    width: 8
                })
            })
        });
        //add new layer to map
        map.addLayer(routeLayer[k]);
		k++;
        x = Math.floor((Math.random() * 254) + 1);
        y = Math.floor((Math.random() * 254) + 1);
        z = Math.floor((Math.random() * 254) + 1);
    }
    //shortest distance function

function ShortestBarrierFreeRoute() {
        // set up the source and target vertex numbers to pass as parameters
        var viewParams = ['source:' + source.getId().split('.')[1], 'target:' +
            target.getId().split('.')[1], 'cost:time'
        ];
        var url = geoserverUrl + 'wfs?service=WFS&version=1.1.0&' +
            'request=GetFeature&typeName=routingapp:ShortestBarrierFree&' +
            'outputformat=application/json&' + 'viewparams=' + viewParams.join(
                ';');
        // create a new source for our layer
        routeSource = new ol.source.ServerVector({
            format: new ol.format.GeoJSON(),
            strategy: ol.loadingstrategy.all,
            loader: function(extent, resolution) {
                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: loadRoute,
                    async: false
                });
            },
        });
        // remove the previous layer and create a new one
        routeLayer[k] = new ol.layer.Vector({
            source: routeSource,
            style: new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: 'rgba(255,0,255,0.5)',
                    width: 8
                })
            })
        });
        //add new layer to map
        map.addLayer(routeLayer[k]);
		k++;
        x = Math.floor((Math.random() * 254) + 1);
        y = Math.floor((Math.random() * 254) + 1);
        z = Math.floor((Math.random() * 254) + 1);
    }
	
function distanceRoute() {
    // set up the source and target vertex numbers to pass as parameters
    var viewParams = ['source:' + source.getId().split('.')[1], 'target:' +
        target.getId().split('.')[1], 'cost:time'
    ];
    var url = geoserverUrl + 'wfs?service=WFS&version=1.1.0&' +
        'request=GetFeature&typeName=routingapp:distancepath&' +
        'outputformat=application/json&' + 'viewparams=' + viewParams.join(
            ';');
    // create a new source for our layer
    routeSource = new ol.source.ServerVector({
        format: new ol.format.GeoJSON(),
        strategy: ol.loadingstrategy.all,
        loader: function(extent, resolution) {
            $.ajax({
                url: url,
                dataType: 'json',
                success: loadRoute,
                async: false
            });
        },
    });
    routeLayer[k] = new ol.layer.Vector({
        source: routeSource,
        style: new ol.style.Style({
            stroke: new ol.style.Stroke({
                color: 'rgba(255,0,0,0.5)',
                width: 8
            })
        })
    });
    // add the new layer to the map
    map.addLayer(routeLayer[k]);
	k++;
    x = Math.floor((Math.random() * 254) + 1);
    y = Math.floor((Math.random() * 254) + 1);
    z = Math.floor((Math.random() * 254) + 1);
}

function hurdle() {
        getBarrierVertex(barrier);
        var coordinates = barrier.getGeometry().getCoordinates();
        var iconFeature = new ol.Feature({
            geometry: new ol.geom.Point([coordinates[0], coordinates[1]])
        });
        var iconStyle = new ol.style.Style({
            image: new ol.style.Icon( /** @type {olx.style.IconOptions} */
                ({
                    anchor: [0.5, 0.5],
                    anchorXUnits: 'fraction',
                    anchorYUnits: 'fraction',
                    opacity: 0.75,
                    src: 'http://localhost/routing/images/marker.png'
                }))
        });
        iconFeature.setStyle(iconStyle);
        var vectorSource = new ol.source.Vector({
            features: [iconFeature]
        });
        vectorLayer[m] = new ol.layer.Vector({
            source: vectorSource
        });
        map.addLayer(vectorLayer[m]);
		m++;
    }
    // handle the response to shortest_path
var loadRoute = function(response) {
    selectSegment.getFeatures().clear();
    routeSource.clear();
    var features = routeSource.readFeatures(response)
    if (features.length == 0) {
        info.innerHTML = '';
        return;
    }
    routeSource.addFeatures(features);
    var time = 0;
    var dist = 0;
    features.forEach(function(feature) {
        time += feature.get('time');
        dist += feature.get('distance');
    });
    if (!pointerDown) {
        // set the route text
        var text = '<br>Travelling from:<strong>' + formatPlaces(source.get(
            'name')) + '<br></strong> To :<strong>' + formatPlaces(
            target.get('name')) + '<br></strong> ';
        text += 'Total distance:<strong>' + formatDist(dist) +
            '</strong><br> ';
        text += 'Travel time:<strong> ' + formatTime(time) + '</strong>';
        document.getElementById("demo").innerHTML = text;
        // snap the markers to the exact route source/target
        markerOverlay.getFeatures().clear();
        sourceMarker.setGeometry(source.getGeometry());
        targetMarker.setGeometry(target.getGeometry());
        markerOverlay.getFeatures().push(sourceMarker);
        markerOverlay.getFeatures().push(targetMarker);
        barrier.setGeometry(barrier.getGeometry());
        markerOverlay.getFeatures().push(barrier);
    }
}
var ol3d = new olcs.OLCesium(map); // map is the ol.Map instance
var scene = ol3d.getCesiumScene();
var terrainProvider = new Cesium.CesiumTerrainProvider({
    url: '//cesiumjs.org/stk-terrain/tilesets/world/tiles'
});

scene.terrainProvider = terrainProvider;
$('#toggle-globe').click(function() {
    ol3d.setEnabled(!ol3d.getEnabled());
});
$('#clear-tracks').click(function() {
	
	
	for(var c=0;c<k;c++)
	{
		map.removeLayer(routeLayer[c]);
	}
	k=0;
    /*var layers = map.getLayers();
    var i = layers.getLength();
    while (i >= 0) {
        if (layers.item(i--) instanceof ol.layer.Vector) {
            layers.pop();
        }
    }*/
});

getVertex(sourceMarker);
getVertex(targetMarker);