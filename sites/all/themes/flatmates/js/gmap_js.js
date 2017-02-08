/***/
(function ($) {
		$(document).ready(function(){
			if($('body').hasClass("node-type-rooms")){
					$(document).on('click', '#nearest-facility .show-more', function(){ 
							$(this).prev().toggle();
						}
					);
					$(document).on('click', '.map_info', function(){ 
							gmap = Drupal.gmap.getMap("auto2map");
							gmap.map.setCenter(new google.maps.LatLng($(this).find("span.name").find(".lat").val(), $(this).find("span.name").find(".lng").val()));
							gmap.map.setZoom(19);
							$("body").stop().animate({scrollTop:$("#nearest-facility-map").offset().top}, '200', 'swing');
							$("span.name").removeClass("active");
							$(this).find("span.name").addClass("active");
						}
					);					
					setTimeout(function(){
						var gmap;
						gmap = Drupal.gmap.getMap("auto2map");
						//google.maps.event.addListenerOnce(gmap, 'idle', function(){				
							var centerLat = gmap.map.getCenter().lat();
							var centerLng = gmap.map.getCenter().lng();
							var service, distance;
							var train_station = new Array(), bus_station = new Array(), grocery_or_supermarket = new Array(), cafe = new Array(), duration, durations = new Array(),
							result_length_cafe,	result_length_train_station,	result_length_bus_station,	result_length_grocery_or_supermarket;
							if(gmap){
								request = {
									location: gmap.map.getCenter(),
									radius: '700',
									types: ['train_station']
								};
								service = new google.maps.places.PlacesService(gmap.map);
								distance = new google.maps.DistanceMatrixService();
								service.nearbySearch(request, callback_train_station);
								
								request = {
									location: gmap.map.getCenter(),
									radius: '700',
									types: ['bus_station']
								};
								service = new google.maps.places.PlacesService(gmap.map);
								service.nearbySearch(request, callback_bus_station);	
								
								request = {
									location: gmap.map.getCenter(),
									radius: '700',
									types: ['grocery_or_supermarket']
								};
								service = new google.maps.places.PlacesService(gmap.map);
								service.nearbySearch(request, callback_grocery_or_supermarket);	
								
								var global_counter_cafe = 0, global_counter_train_station = 0,
								global_counter_bus_station = 0, global_counter_grocery_or_supermarket = 0;
								request = {
									location: gmap.map.getCenter(),
									radius: '700',
									types: ['cafe']
								};
								service = new google.maps.places.PlacesService(gmap.map);
								service.nearbySearch(request, callback_cafe);					
							
								function callback_train_station(results, status) {
									result_length_train_station = results.length;
									if (status === google.maps.places.PlacesServiceStatus.OK) {
										for (var i = 0; i < results.length; i++) {
											train_station[i] = new Array();
											train_station[i].push(results[i].name);
											train_station[i].push(results[i].id);
											train_station[i].push(results[i].geometry.location);
											
											var lat = results[i].geometry.location.lat();
											var lng = results[i].geometry.location.lng();
											
											console.log(results[i]);
											
											var origin = new google.maps.LatLng(centerLat, centerLng);
											var destination = new google.maps.LatLng(lat, lng);
											distance.getDistanceMatrix(
											{
												origins: [origin],
												destinations: [destination],
												travelMode: google.maps.TravelMode.WALKING,
												unitSystem: google.maps.UnitSystem.METRIC,
												avoidHighways: false,
												avoidTolls: false
											}, callback);		
											function callback(response, status) {
												if (status == google.maps.DistanceMatrixStatus.OK) {
													var origins = response.originAddresses;
													var destinations = response.destinationAddresses;														
													for (var i = 0; i < origins.length; i++) {
														var results = response.rows[i].elements;
														for (var j = 0; j < results.length; j++) {
															var element = results[j];
															var distance = element.distance.text;
															duration = element.duration.text;
															var from = origins[i];
															var to = destinations[j];
															var lat = train_station[global_counter_train_station][2].lat();
															var lng = train_station[global_counter_train_station][2].lng();	
															if(global_counter_train_station > 2 && result_length_train_station > 2){
																if(global_counter_train_station == 3){
																	$("#train_station .wrapper").append("<div class='show-more'>Show more</div>");
																}
																$("#train_station .wrapper .more").prepend("<div class='map_info' id='" + train_station[global_counter_train_station][1] + "'><span class='name'>" + train_station[global_counter_train_station][0] + "<input type='hidden' class='lat' value='" + lat + "'><input type='hidden' class='lng' value='" + lng + "'></span> <span>~" + duration + " walk</span></div>");
															}else{
																$("#train_station .wrapper").prepend("<div class='map_info' id='" + train_station[global_counter_train_station][1] + "'><span class='name'>" + train_station[global_counter_train_station][0] + "<input type='hidden' class='lat' value='" + lat + "'><input type='hidden' class='lng' value='" + lng + "'></span> <span>~" + duration + " walk</span></div>");
															}													
															global_counter_train_station ++;
														}
													}
												}
											}									
										}
									}
								}
											
								function callback_bus_station(results, status) {
									if (status === google.maps.places.PlacesServiceStatus.OK) {
										result_length_bus_station = results.length;
										for (var i = 0; i < results.length; i++) {
											bus_station[i] = new Array();
											bus_station[i].push(results[i].name);
											bus_station[i].push(results[i].id);
											bus_station[i].push(results[i].geometry.location);
											var lat = results[i].geometry.location.lat();
											var lng = results[i].geometry.location.lng();
											var origin = new google.maps.LatLng(centerLat, centerLng);
											var destination = new google.maps.LatLng(lat, lng);
											distance.getDistanceMatrix(
											{
												origins: [origin],
												destinations: [destination],
												travelMode: google.maps.TravelMode.WALKING,
												unitSystem: google.maps.UnitSystem.METRIC,
												avoidHighways: false,
												avoidTolls: false
											}, callback);		
											function callback(response, status) {
												if (status == google.maps.DistanceMatrixStatus.OK) {
													var origins = response.originAddresses;
													var destinations = response.destinationAddresses;														
													for (var i = 0; i < origins.length; i++) {
														var results = response.rows[i].elements;
														for (var j = 0; j < results.length; j++) {
															var element = results[j];
															var distance = element.distance.text;
															duration = element.duration.text;
															var from = origins[i];
															var to = destinations[j];
															var lat = bus_station[global_counter_bus_station][2].lat();
															var lng = bus_station[global_counter_bus_station][2].lng();	
															if(global_counter_bus_station > 2 && result_length_bus_station > 2){
																if(global_counter_bus_station == 3){
																	$("#bus_stops .wrapper").append("<div class='show-more'>Show more</div>");
																}
																$("#bus_stops .wrapper .more").prepend("<div class='map_info' id='" + bus_station[global_counter_bus_station][1] + "'><span class='name'>" + bus_station[global_counter_bus_station][0] + "<input type='hidden' class='lat' value='" + lat + "'><input type='hidden' class='lng' value='" + lng + "'></span> <span>~" + duration + " walk</span></div>");
															}else{
																$("#bus_stops .wrapper").prepend("<div class='map_info' id='" + bus_station[global_counter_bus_station][1] + "'><span class='name'>" + bus_station[global_counter_bus_station][0] + "<input type='hidden' class='lat' value='" + lat + "'><input type='hidden' class='lng' value='" + lng + "'></span> <span>~" + duration + " walk</span></div>");
															}													
															global_counter_bus_station ++;
														}
													}
												}
											}								
										}
									}
								}		
											
								function callback_grocery_or_supermarket(results, status) {
									if (status === google.maps.places.PlacesServiceStatus.OK) {
										result_length_grocery_or_supermarket = results.length;
										for (var i = 0; i < results.length; i++) {
											grocery_or_supermarket[i] = new Array();
											grocery_or_supermarket[i].push(results[i].name);
											grocery_or_supermarket[i].push(results[i].id);
											grocery_or_supermarket[i].push(results[i].geometry.location);
											var lat = results[i].geometry.location.lat();
											var lng = results[i].geometry.location.lng();
											var origin = new google.maps.LatLng(centerLat, centerLng);
											var destination = new google.maps.LatLng(lat, lng);	
											distance.getDistanceMatrix(
											{
												origins: [origin],
												destinations: [destination],
												travelMode: google.maps.TravelMode.WALKING,
												unitSystem: google.maps.UnitSystem.METRIC,
												avoidHighways: false,
												avoidTolls: false
											}, callback);		
											function callback(response, status) {
												if (status == google.maps.DistanceMatrixStatus.OK) {
													var origins = response.originAddresses;
													var destinations = response.destinationAddresses;														
													for (var i = 0; i < origins.length; i++) {
														var results = response.rows[i].elements;
														for (var j = 0; j < results.length; j++) {
															var element = results[j];
															var distance = element.distance.text;
															duration = element.duration.text;
															var from = origins[i];
															var to = destinations[j];
															var lat = grocery_or_supermarket[global_counter_grocery_or_supermarket][2].lat();
															var lng = grocery_or_supermarket[global_counter_grocery_or_supermarket][2].lng();	
															if(global_counter_grocery_or_supermarket > 2 && result_length_grocery_or_supermarket > 2){
																if(global_counter_grocery_or_supermarket == 3){
																	$("#grocery_or_supermarket .wrapper").append("<div class='show-more'>Show more</div>");
																}
																$("#grocery_or_supermarket .wrapper .more").prepend("<div class='map_info' id='" + grocery_or_supermarket[global_counter_grocery_or_supermarket][1] + "'><span class='name'>" + grocery_or_supermarket[global_counter_grocery_or_supermarket][0] + "<input type='hidden' class='lat' value='" + lat + "'><input type='hidden' class='lng' value='" + lng + "'></span> <span>~" + duration + " walk</span></div>");
															}else{
																$("#grocery_or_supermarket .wrapper").prepend("<div class='map_info' id='" + grocery_or_supermarket[global_counter_grocery_or_supermarket][1] + "'><span class='name'>" + grocery_or_supermarket[global_counter_grocery_or_supermarket][0] + "<input type='hidden' class='lat' value='" + lat + "'><input type='hidden' class='lng' value='" + lng + "'></span> <span>~" + duration + " walk</span></div>");
															}													
															global_counter_grocery_or_supermarket ++;
														}
													}
												}
											}								
										}
										
									}
								}		
											
								function callback_cafe(results, status) {
									if (status === google.maps.places.PlacesServiceStatus.OK) {
										result_length_cafe = results.length;
										for (var i = 0; i < results.length; i++) {
											cafe[i] = new Array();
											cafe[i].push(results[i].name);
											cafe[i].push(results[i].id);
											cafe[i].push(results[i].geometry.location);
											var lat = results[i].geometry.location.lat();
											var lng = results[i].geometry.location.lng();
											var origin = new google.maps.LatLng(centerLat, centerLng);
											var destination = new google.maps.LatLng(lat, lng);	
											distance.getDistanceMatrix(
											{
												origins: [origin],
												destinations: [destination],
												travelMode: google.maps.TravelMode.WALKING,
												unitSystem: google.maps.UnitSystem.METRIC,
												avoidHighways: false,
												avoidTolls: false
											}, callback);											
											function callback(response, status) {
												if (status == google.maps.DistanceMatrixStatus.OK) {
													var origins = response.originAddresses;
													var destinations = response.destinationAddresses;														
													for (var i = 0; i < origins.length; i++) {
														var results = response.rows[i].elements;
														for (var j = 0; j < results.length; j++) {
															var element = results[j];
															var distance = element.distance.text;
															duration = element.duration.text;
															var from = origins[i];
															var to = destinations[j];
															var lat = cafe[global_counter_cafe][2].lat();
															var lng = cafe[global_counter_cafe][2].lng();	
															if(global_counter_cafe > 2 && result_length_cafe > 2){
																if(global_counter_cafe == 3){
																	$("#cafe .wrapper").append("<div class='show-more'>Show more</div>");
																}
																$("#cafe .wrapper .more").prepend("<div class='map_info' id='" + cafe[global_counter_cafe][1] + "'><span class='name'>" + cafe[global_counter_cafe][0] + "<input type='hidden' class='lat' value='" + lat + "'><input type='hidden' class='lng' value='" + lng + "'></span> <span>~" + duration + " walk</span></div>");
															}else{
																$("#cafe .wrapper").prepend("<div class='map_info' id='" + cafe[global_counter_cafe][1] + "'><span class='name'>" + cafe[global_counter_cafe][0] + "<input type='hidden' class='lat' value='" + lat + "'><input type='hidden' class='lng' value='" + lng + "'></span> <span>~" + duration + " walk</span></div>");
															}													
															global_counter_cafe ++;
														}
													}
												}
											}
										}
									}
								}									
							}
						//});
					}, 1500);		

			}});
}(jQuery));