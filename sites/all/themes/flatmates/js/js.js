(function ($) {
	
	function addMoreAccomodation(ele){
		var type = ele.parent().parent().find("select :selected").val();
		var name = ele.parent().parent().find("select :selected").html();
		var check = 0;
		$("#create-preferred-accomodation .item").each(
			function(){
				if($(this).find("input").val() == name){
					check = 1;
				}
			}
		);			
		if(check == 0){
			$("#create-preferred-accomodation .selected").prepend("<div class='item'><input disabled type='text' id='tid-" + type + "' value='" + name + "'><span class='close' title='Delete' data-toggle='tooltip'></span></div>");
			$('[data-toggle="tooltip"]').tooltip();
			ele.parent().find("select :selected").attr("disabled","disabled");	
		}		
	}
	
	function addMoreLocation(ele){
		var name = ele.val();
		if(name != ""){
			$("#with-g-point .selected").prepend("<div class='item'><input type='text' disabled value='" + name + "'><span class='close' title='Delete' data-toggle='tooltip'></span></div>");
			$('[data-toggle="tooltip"]').tooltip();			
			$("#edit-field-preffered-locations-google tr").each(
				function(){
						if($(this).find("input").val() == ""){
							$(this).find("input").val(name);
						}
				}
			);
			$("#edit-field-preffered-locations-google button").mousedown();
			$("#g_autocomplete").val("");
		}		
	}	
	
	var locations = new Array();
	function addMoreLocationFront(ele){
		var name = ele.val();
		if(name != ""){
			locations.push(name);
			$("#locations").prepend("<div class='item'><input title='" + name + "' data-toggle='tooltip' name='search-item' type='text' value='" + name + "'><span class='close' title='Delete' data-toggle='tooltip'></span></div>");
			setTimeout(function(){$('[data-toggle="tooltip"]').tooltip()}, 500);
			$(ele).val("");
			console.log(locations);
		}		
	}
	
	function showUpload(){
		if($("#edit-field-choose-photo-from-my-image table").length == 0){
			$("#edit-field-choose-photo-from-my-image").removeClass("active");
			$("#edit-field-image").show();
		}		
	}

	function showMediaUpload(){
		if($("#edit-field-image table").length == 0){
			$("#edit-field-image").removeClass("active");
			$("#edit-field-choose-photo-from-my-image").show();
		}else{
			$("#edit-field-image").addClass("active");
			$("#edit-field-choose-photo-from-my-image").hide();
		}	
	}
	
	jQuery(document).ajaxComplete(function(event, xhr, settings) {
    
		if($("body").hasClass("page-node-49") || $("body").hasClass("page-node-84")){
			showUpload();
			showMediaUpload();
		}
		$('[data-toggle="tooltip"]').tooltip(); 
	});
	
	function requiredInput(){
		$("label").each(function(){
				if($(this).hasClass("required")){
					if($(this).next().hasClass("selected")){
						if($(this).next().html().length == 0){
							$(this).parent().find(".error").show();
							$(this).parent().find(".error").css({"opacity": "1","width": "auto","height": "26px","bottom": "0px"});
							$("body").stop().animate({scrollTop:$("#general-details").offset().top}, '500', 'swing');
						}
					}else{
						if($(this).next().val() == ""){
							$(this).parent().find(".error").show();
							$(this).parent().find(".error").css({"opacity": "1","width": "auto","height": "26px","bottom": "0px"});
							$("body").stop().animate({scrollTop:$("#general-details").offset().top}, '500', 'swing');
						}						
					}
				}
			}
		)
	}
	
	function getLocationsFront(){
		var searchVal = $.cookie("searchVal");
		if(searchVal !== undefined){
			var res = searchVal.split("_");
			for( var i = 0; i < res.length - 1; i++ ) {
				if(res[i] != ""){
					$(".prefix #locations").prepend("<div class='item'><input title='" + res[i] + "' data-toggle='tooltip' name='search-item' type='text' value='" + res[i] + "'><span class='close' title='Delete' data-toggle='tooltip'></span></div>");
				}
			};
			$('[data-toggle="tooltip"]').tooltip();					
		}

	}
	
	function getFlatmatesData(){
			console.log(locations);
			var locationsString = locations.join("_");
			var locationsStringType;

				locationsString += "_flatmates";
				locationsStringType += "flatmates";

			var gender, sort, minRent, maxRent, minAge, maxAge, acomodationTypes, minStay, maxStay, peopleTypes, professions, roomType, bathroomType, furnishing, features;			
			gender = $("#search-flatmates select[name=gender] option:selected").val();
			if(gender == "any"){gender = "";}
			sort = $("#search-flatmates select[name=sort-priority] option:selected").val();
			var minRent_ = $("#search-flatmates select[name=min-rent] option:selected").val();
			var maxRent_ = $("#search-flatmates select[name=max-rent] option:selected").val();
			acomodationTypes = $("#search-flatmates select[name=preferred-accomodation] option:selected").val();
			peopleTypes = $("#search-flatmates select[name=people-type] option:selected").val();
			professions = $("#search-flatmates select[name=professions-type] option:selected").val();
			var minAge_ = $("#search-flatmates input.min_age").val();
			var maxAge_ = $("#search-flatmates input.max_age").val();
			var minStay_ = $("#search-flatmates input.min_stay").val();
			var maxStay_ = $("#search-flatmates input.max_stay").val();			
			$.cookie("searchType", locationsStringType, { path:'/' });
			$.cookie("searchVal", locationsString, { path:'/' });
			$.cookie("sort_f", sort, { path:'/' });
			$.cookie("gender_f", gender, { path:'/' });
			$.cookie("minRent_f", minRent_, { path:'/' });
			$.cookie("maxRent_f", maxRent_, { path:'/' });
			$.cookie("acomodationTypes_f", acomodationTypes, { path:'/' });
			$.cookie("peopleTypes_f", peopleTypes, { path:'/' });
			$.cookie("professions_f", professions, { path:'/' });
			$.cookie("minAge_f", minAge_, { path:'/' });
			$.cookie("maxAge_f", maxAge_, { path:'/' });
			$.cookie("minStay_f", minStay_, { path:'/' });
			$.cookie("maxStay_f", maxStay_, { path:'/' });			
			console.log("gender " + gender + ", sort " + sort + ", minRent_ " + minRent_ + ", maxRent_ " + maxRent_);
			console.log("acomodationTypes " + acomodationTypes + ", peopleTypes " + peopleTypes + ", professions " + professions + ", minAge_ " + minAge_);
			console.log("maxAge_ " + maxAge_ + ", minStay_ " + minStay_ + ", maxStay_ " + maxStay_);
			window.location.href = "/search/node";	
	}
	
	function getRoomsData(){
			var sort, minRent_, maxRent_, gender, roomType, bathroomType, furnishing, stayLength, 
			acomodationTypes, features;
			console.log(locations);
			var locationsString = locations.join("_");
			var locationsStringType;

				locationsString += "_rooms";
				locationsStringType += "rooms";
			sort = $("#search-rooms select[name=sort-priority] option:selected").val();
			minRent_ = $("#search-rooms select[name=min-rent] option:selected").val();//
			maxRent_ = $("#search-rooms select[name=max-rent] option:selected").val();//
			gender = $("#search-rooms select[name=gender] option:selected").val();//
			if(gender == "any"){gender = "";}							
			roomType = $("#search-rooms select[name=room-type] option:selected").val();
			bathroomType = $("#search-rooms select[name=bathroom-type] option:selected").val();
			furnishing = $("#search-rooms select[name=furnishing] option:selected").val();
			stayLength = $("#search-rooms select[name='preffered-stay-length'] option:selected").val();
			acomodationTypes = $("#search-rooms select[name='preferred-accomodation'] option:selected").val();
			if(parseInt(minRent_) > parseInt(maxRent_)){
				minRent = maxRent_;
				maxRent = minRent_;
			}else{
				minRent = minRent_;
				maxRent = maxRent_;								
			}								
			$(".checkbox input[type=checkbox]").each(
				function(){
					if($(this).is(':checked')){
						features +=	$(this).val() + "_";
					}
				}
			);	
			$.cookie("searchType", locationsStringType, { path:'/' });
			$.cookie("searchVal", locationsString, { path:'/' });
			$.cookie("sort_r", sort, { path:'/' });
			$.cookie("gender_r", gender, { path:'/' });
			$.cookie("minRent_r", minRent_, { path:'/' });
			$.cookie("maxRent_r", maxRent_, { path:'/' });
			$.cookie("acomodationTypes_r", acomodationTypes, { path:'/' });
			$.cookie("roomType_r", roomType, { path:'/' });
			$.cookie("bathroomType_r", bathroomType, { path:'/' });
			$.cookie("furnishing_r", furnishing, { path:'/' });
			$.cookie("stayLength_r", stayLength, { path:'/' });
			$.cookie("features_r", features, { path:'/' });
			console.log("sort_r " + sort);
			console.log("gender_r " + gender);
			console.log("minRent_r " + minRent);
			console.log("maxRent_r " + maxRent);
			console.log("acomodationTypes_r " + acomodationTypes);
			console.log("roomType_r " + roomType);
			console.log("bathroomType_r " + bathroomType);
			console.log("furnishing_r " + furnishing);
			console.log("stayLength_r " + stayLength);
			console.log("features_r " + features);
			console.log("locationsString " + locationsString);
			window.location.href = "/search/node";
	}

  Drupal.behaviors.social_login = {
    attach: function (context, settings) {
			if($('body').hasClass("page-node-84")){
				$("#edit-field-rooms-und-actions button").mousedown();
			}
			$(document).on('click', '.front .form-action.flatmates button', function(){
				getFlatmatesData();
			});			
			$(document).on('click', '.front .form-action.rooms button', function(){
				getRoomsData();
			});
			$("#search-block-form input[type='text']").click(
				function(){
					if($.cookie("searchType") !== undefined){
						var searchType = $.cookie("searchType");
						$(".form-search .prefix").fadeIn(100);
						if(searchType.indexOf("flatmates") > 0){
							$(".switch.flatmates").click();
							$(".switch.flatmates").addClass("active");
						}else{
							$(".switch.rooms").click();
							$(".switch.rooms").addClass("active");						
						}
					}else{
						$(".form-search .prefix").fadeIn(100);
					}
				}
			);
			$("#switcher .switch").click(
				function(){
					$(".switch").removeClass("active");
					$(this).addClass("active");
				}
			);
			getLocationsFront();
			$('[data-toggle="tooltip"]').tooltip(); 
			$('#overlay, #close').click(
				function(){
					$("#search-form").fadeOut(300, function(){
						$('#overlay').hide();
					});
				}
			);
			setTimeout(function(){
				$(".view-find-accomodation-pictures .owl-theme .owl-controls .owl-buttons").prepend("<span class='left'></span>");
				$(".view-find-accomodation-pictures .owl-theme .owl-controls .owl-buttons").prepend("<span class='right'></span>");
			}, 100);
			$("input").focus(
				function(){
					var ele = $(this);
					$(this).parent().find(".error").animate({
						opacity: 0,
						width: "0",
						height: "0",
						bottom: "0"
					}, 200, function() {
						$(this).parent().find(".error").hide();
					});
				}
			);
			$(".field-required.error").click(function(){
				var ele = $(this);
				$(this).animate({
						opacity: 0,
						width: "0",
						height: "0",
						bottom: "0"
					}, 200, function() {
						ele.parent().find("input").focus();
						ele.hide();
					});
					
			});
			
			if($('#g_autocomplete').length > 0){
				 var autocomplete = new google.maps.places.Autocomplete(
							/** @type {!HTMLInputElement} */(document.getElementById('g_autocomplete')),
							{
								types: ['geocode'],
								componentRestrictions: {country: "au"}
							});
			}
			if($("#edit-field-address-und-0-value").length > 0){
				 var autocomplete = new google.maps.places.Autocomplete(
							/** @type {!HTMLInputElement} */(document.getElementById('edit-field-address-und-0-value')),
							{
								types: ['geocode'],
								componentRestrictions: {country: "au"}
							});				
			}
			if($('#search-code-input').length > 0){
				 var autocomplete = new google.maps.places.Autocomplete(
							/** @type {!HTMLInputElement} */(document.getElementById('search-code-input')),
							{
								types: ['geocode'],
								componentRestrictions: {country: "au"}
							});
			}
			if($("#edit-search-block-form--2").length > 0){
				 var autocomplete = new google.maps.places.Autocomplete(
							/** @type {!HTMLInputElement} */(document.getElementById('edit-search-block-form--2')),
							{
								types: ['geocode'],
								componentRestrictions: {country: "au"}
							});				
			}
			$(".panel-pane.pane-custom.pane-6").click(
				function(){
					$(".panel-pane.pane-custom.pane-6").fadeOut(100,function(){
						$(".pane-user-field-mobile .field-item").fadeIn(100);
					});
				}
			);			
			$(".show_mobile .text").click(
				function(){
					$(".show_mobile .text").fadeOut(100,function(){
						$(".show_mobile .phone").fadeIn(100);
					});
				}
			);
			$('#create-preferred-accomodation .add-more').click(function(){
				var ele = $(this);
				addMoreAccomodation(ele);
			});
			$(document).on('click', '#search-block-form #close', function(){
					$(".form-search .prefix").fadeOut(100);
				}
			);			
			$(document).on('click', '#create-preferred-accomodation .selected .item span', function(){ 
				var name = $(this).prev().val();
				$("#create-preferred-accomodation select").find("option:contains('"+name+"')").removeAttr("disabled");
				$(this).parent().detach();
			}); 

			$("#with-g-point .add-more").click(function(){
				var ele = $(this);
				addMoreLocation(ele);
			});
			$("#g_autocomplete.accomodation").one( "change",	function(){
					var ele = $(this);
					setTimeout(
						function(){
							addMoreLocation(ele);
						}, 500
					)
				}
			);	

			$("#edit-field-address-und-0-value").one( "change",	function(){
				var geocoder = new google.maps.Geocoder();
				var address = document.getElementById("edit-field-address-und-0-value").value;
				geocoder.geocode({ 'address': address }, function (results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
								var latitude = results[0].geometry.location.lat();
								var longitude = results[0].geometry.location.lng();
								//alert("Latitude: " + latitude + "\nLongitude: " + longitude);
								$("#gmap-auto1map-locpick_latitude0").val(latitude);
								$("#gmap-auto1map-locpick_longitude0").val(longitude);
								$(".form-item-field-rooms-und-form-title input").val($(".field-name-field-address input").val());
						} else {
								//alert("Request failed.")
						}
				});		
			});			

			$(document).on('click', '#locations .item span', function(){ 
					var name = $(this).parent().find("input").val();
					$(this).parent().detach();
					locations.splice( $.inArray(name, locations), 1 );
				}
			);
			$("input[name=search_block_form]").change(function(){
					var ele = $(this);
					setTimeout(
						function(){
							addMoreLocationFront(ele);
							/*if($("#advanced").length == 0){


							}*/
						}, 500
					)
				}
			);
			
			$(document).on('click', '.switch.rooms', function(){
				$( "#advanced-search" ).load( "/advanced-filters #advanced", function(){
					
					$("select[name='sort-priority'] :contains('" + $.cookie("sort_r") + "')").attr("selected", "selected");
					$("select[name='min-rent'] :contains('" + $.cookie("minRent_r") + "')").attr("selected", "selected");
					$("select[name='max-rent'] :contains('" + $.cookie("maxRent_r") + "')").attr("selected", "selected");
					$("select[name='gender'] :contains('" + $.cookie("gender_r") + "')").attr("selected", "selected");
					$("select[name='room-type'] [value='" + $.cookie("roomType_r") + "']").attr("selected", "selected");
					$("select[name='bathroom-type'] [value='" + $.cookie("bathroomType_r") + "']").attr("selected", "selected");
					$("select[name='furnishing'] [value='" + $.cookie("furnishing_r") + "']").attr("selected", "selected");
					$("select[name='preffered-stay-length'] [value='" + $.cookie("stayLength_r") + "']").attr("selected", "selected");
					$("select[name='preferred-accomodation'] [value='" + $.cookie("acomodationTypes_r") + "']").attr("selected", "selected");
					console.log("Features " + $.cookie("features_r"));
					console.log($.cookie("features_r"));
					if($.cookie("features_r") != "" && $.cookie("features_r") !== undefined){
						var features_mass = $.cookie("features_r").split("_");
						console.log(features_mass);
						$.each(features_mass, function(i, val) {
							$(".checkbox input[type=checkbox]").each(
								function(){
									if($(this).val() == val){
										$(this).attr('checked',true)
									}
								}
							);	
						});
					}
					
					$("select[name='sort-priority'] :contains('" + $.cookie("sort_f") + "')").attr("selected", "selected");
					
					$("SELECT").selectBox();
				} );	
			})					
			
			$(document).on('click', '.switch.flatmates', function(){
				if($(this))
					$( "#advanced-search" ).load( "/advanced-filters-flatmates #advanced", function(){
						//alert($.cookie("sort_f"));
						$("select[name='sort-priority'] :contains('" + $.cookie("sort_f") + "')").attr("selected", "selected");
						$("select[name='gender'] :contains('" + $.cookie("gender_f") + "')").attr("selected", "selected");
						$("select[name='min-rent'] :contains('" + $.cookie("minRent_f") + "')").attr("selected", "selected");
						$("select[name='max-rent'] :contains('" + $.cookie("maxRent_f") + "')").attr("selected", "selected");
						$("select[name='preferred-accomodation'] [value='" + $.cookie("acomodationTypes_f") + "']").attr("selected", "selected");
						$("select[name='people-type'] [value='" + $.cookie("peopleTypes_f") + "']").attr("selected", "selected");
						$("select[name='professions-type'] [value='" + $.cookie("professions_f") + "']").attr("selected", "selected");
						$(".min_age input").val($.cookie("minAge_f"));
						$(".max-age input").val($.cookie("maxAge_f"));						
						$(".min_stay input").val($.cookie("minStay_f"));
						$(".max_stay input").val($.cookie("maxStay_f"));
						$("SELECT").selectBox();
					} );	
			})			
		
			
			$(document).on('click', '#advanced-search .advanced-filters .title', function(){
				$(this).parent().find(".content").toggle();
			})
			$(document).on('click', '#with-g-point .selected .item span', function(){ 
					$(this).parent().detach();
					var loc = $(this).parent().find("input").val();
					$("#edit-field-preffered-locations-google tr").each(
						function(){
								if($(this).find("input").val() == loc){
									$(this).detach();
								}
						}
					);					
			});
			$("#edit-field-choose-photo-from-my-image .media-widget a").hover(
				function(){
					$("#edit-field-choose-photo-from-my-image label.control-label").css("text-decoration","underline");
				},
				function(){
					$("#edit-field-choose-photo-from-my-image label.control-label").css("text-decoration","none");
				}
			);
			$(".node-form.node-accomodation-form button.create-list").click(
				function(){
					var name = $("#tenant_name").val();
					var age = $(".tenant-age").val();
					var who = $(".represent-who select option:selected").html();
					var gender = $(".tenant-gender input[type=radio]:checked").next().find("span").html();
					var age = $("#tenant-age").val();
					var accomodation = new Array();
					$("#create-preferred-accomodation .selected .item").each(
						function(){
							accomodation.push($(this).find("input").val());
						}
					)
					if(accomodation.length == 0){
						accomodation.push($("#create-preferred-accomodation .selectBox-label").html());
					}
					var loc = new Array();
					$("#with-g-point .selected .item").each(
						function(){
							loc.push($(this).find("input").val());
						}
					)
					var rent = $("#weekly-rent").val();
					var move_date = $("#edit-submitted-move-date").val();
					var pLength = $("input[name=preffered-length-number]").val();
					var days = $("#preffered-length select option:selected").html();
					if(name != ""){$(".form-item-title input").val(name);}
					if(rent != ""){$("#edit-field-rent-budget input").val(rent);}
					if(name != ""){$("#edit-field-accomodation-name input").val(name);}
					if(who != ""){$("#edit-field-represent-who-und :contains('" + who + "')").attr("selected", "selected");}
					if(accomodation.length > 0){$.each(accomodation, function(key, value){
							$("#edit-field-accomodation select :contains('" + value + "')").attr("selected", "selected");
						});
					}
					if(gender != ""){$("#edit-field-gender-und :contains('" + gender + "')").attr("selected", "selected");}
					if(age != ""){$("#edit-field-age-und-0-value").val(age);}
					if(rent != ""){$("#edit-field-rent-budget").val(rent);}
					if(move_date != ""){$("#edit-field-move-date-und-0-value-datepicker-popup-0").val(move_date);}
					if(days != ""){
						$("#edit-field-preferred-stay-length-und-0-field-length-und :contains('" + days + "')").attr("selected", "selected");
						$("#edit-field-stay-length-term :contains('" + days + "')").attr("selected", "selected");
					}
					if(pLength != ""){
						$("#edit-field-preferred-stay-length input").val(pLength);
						$("#edit-field-stay-length-number input").val(pLength);
					}
					check = 0;
					$("#edit-field-preffered-locations-google input").each(
						function(){
								if($(this).val() != ""){
									check = 1;
								}
						}
					);
					if(check == 0){
						$("#edit-field-preffered-locations-google input").val($("#g_autocomplete").val());
					}
					
					requiredInput();
					if($(".field-required.error").css("display") == "block"){
						return false;	
					}					
				}
			);
			$("#edit-field-choose-photo-from-my-image a.button.browse").click(
				function(){
					$("#edit-field-image").hide();
					$("#edit-field-choose-photo-from-my-image").addClass("active");
				}
			);
			$(document).on('click', '.ui-dialog-titlebar-close', function(){ 
				showUpload();
			});			
			if($(".upload_h").length == 0){
				$(".group-upload-photos").prepend("<div class='upload_h'>Upload Photos</div>");
			}
			if($(".rooms_h").length == 0){
				$(".field-type-entityreference.field-name-field-rooms").prepend("<div class='rooms_h'>About the room</div>");
			}
			$(".create-list.btn.btn-success").click(
				function(){
					//alert(1);
					$(".create-property-list input#edit-title").val($("#edit-field-address-und-0-value").val());
					//return false;
				}
			);
		}
	}
}(jQuery));