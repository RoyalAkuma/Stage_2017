var Widgets = Widgets || function () {
	var private = {
		ashx: 'service.ashx',
		svc: 'services/parcelshop-picker.svc',
		w_name: 'parcelshop-picker/v3_0',
		sw_url: '',
		img_url: 'www.mondialrelay.fr',
		bounds: null,
		map: null,
		overlays: [],
		InfoWindow: null,
		container: null,
		callback: null,
		mapLoaded: false,
		containerId: null,
		params: null,
		protocol: 'https://',

		jsonpcall: function (fn, paramArray, callbackFn) {
			// Create list of parameters in the form (http get format):
			// paramName1 = paramValue1 & paramName2 = paramValue2 &
			var paramList = '';
			if (paramArray.length > 0) {
				for (var i = 0; i < paramArray.length; i += 2) {
					paramList += paramArray[i] + '=' + paramArray[i + 1] + '&';
				}
			}
			jQuery.getJSON(private.protocol + private.sw_url + '/' + fn + '?' + paramList + 'method=?', callbackFn);
		},

		loadhtml: function (container, urlraw, callback) {
			var urlselector = (urlraw).split(" ", 1);
			var url = urlselector[0];
			var selector = urlraw.substring(urlraw.indexOf(' ') + 1, urlraw.length);
			private.container = container;
			private.callback = callback;
			private.jsonpcall(private.ashx, ['downloadurl', escape(url)],
			function (msg) {
				// gets the contents of the Html in the 'msg'
				// todo: apply selector
				private.container.html(msg);
				if (jQuery.isFunction(private.callback)) {
					private.callback();
				}
			});
		},

		Manage_Response: function (result, container, Target, TargetDisplay, TargetDisplayInfoPR) {
			if (result.Error == null) {
				container.find(".MRW-Results").slideDown('slow');
				container.find(".MRW-RList").html(result.Value).show();
				if (private.params.ShowResultsOnMap) {
					// Ajout des points sur la google map
					if (!private.mapLoaded) {
						private.MR_LoadMap(private.params);
						private.mapLoaded = true;
					}

					// Supprime le contenu de la carte
					private.MR_clearOverlays();

					// Boucle sur les Points Relais
					for (var i = 0; i < result.PRList.length; i++) {
						// Ajout d'un marker pour chaque Point Relais

						private.MR_AddGmapMarker(
							private.map,
							new google.maps.LatLng(result.PRList[i].Lat.replace(',', '.'), result.PRList[i].Long.replace(',', '.')),
							result.PRList[i],
							i,
							private.sw_url
						);

					}

					// Redimentionne la carte
					private.map.fitBounds(private.bounds);

					// AutoSelect
					if (private.params.AutoSelect) {
							private.MR_FocusOnMaker(private.params.AutoSelect);
					}
				} else {
					jQuery('#MRW-Map', private.container).html("");
						for (var i = 0; i < result.PRList.length; i++) {
							jQuery('#MRW-Map', private.container).append(private.MR_BuildparcelShopDetails(result.PRList[i]));
							jQuery.data(jQuery('#MRW-Map > div:last-child')[0], "ParcelShop", result.PRList[i]);
							jQuery('#MRW-Map > div:last-child').bind("select", function () { private.MR_SelectparcelShop(jQuery.data(jQuery(this)[0], "ParcelShop")); });
							jQuery('#MRW-Map > div', private.container).hide();
						}
					}

			} else {
				container.find(".MRW-Results").hide();
				container.find(".MRW-Errors").html(result.Error).slideDown("slow");
			}

			container.find('.progressBar').hide();

			// Gestion du hover sur les items
			container.find('.PR-List-Item').mouseover(function () {
				jQuery(this).addClass("PR-hover");
			});
			container.find('.PR-List-Item').mouseout(function () {
				jQuery(this).removeClass("PR-hover");
			});

		},
		MR_Widget_Call: function (container, Target, TargetDisplay, TargetDisplayInfoPR, UseMyPosition) {
			container.find(".MRW-Errors").hide();
			container.find('.progressBar').show();
			container.find(".MRW-Errors").html("");

			var a0 = container.find('input.Arg0')[0].value;
			var a1 = container.find('input.Arg1')[0].value;
			var a2 = container.find('input.Arg2')[0].value;
			var a3 = container.find('input.Arg3')[0].value;
			var a4 = container.find('input.Arg4')[0].value;
			var a5 = container.find('input.Arg5')[0].value;
			var a6 = container.find('input.Arg6')[0].value;
			var a7 = container.find('input.Arg7')[0].value;
			var a8 = private.params.VacationBefore || '';
			var a9 = private.params.VacationAfter || '';
			var a10 = container.find('input.Arg10')[0].value;

			if (UseMyPosition) {
				navigator.geolocation.getCurrentPosition(
					function (position) {
						private.jsonpcall(private.w_name + "/" + private.svc + "/SearchPR",
							["Brand", a0, "Country", a1, "PostCode", "", "ColLivMod", a3, "Weight", a4, "NbResults", a5, "SearchDelay", a6, "SearchFar", a7, "ClientContainerId", private.containerId, "VacationBefore", a8, "VacationAfter", a9, "Service", a10, "Latitude", position.coords.latitude, "Longitude", position.coords.longitude],
							function (result) {
								private.Manage_Response(result, container, Target, TargetDisplay, TargetDisplayInfoPR);
							});
					},
					function (error) {
						alert("Erreur ï¿½ l'obtention des donnï¿½es de gï¿½olocalisation : [" + error.code + "] " + error.message);
					},
					{
						timeout: 1000,
						maximumAge: 30000
					}
				);
			}
			else {
				private.jsonpcall(private.w_name + "/" + private.svc + "/SearchPR",
					["Brand", a0, "Country", a1, "PostCode", a2, "ColLivMod", a3, "Weight", a4, "NbResults", a5, "SearchDelay", a6, "SearchFar", a7, "ClientContainerId", private.containerId, "VacationBefore", a8, "VacationAfter", a9, "Service", a10, "Latitude", '', "Longitude", ''],
					function (result) {
							private.Manage_Response(result, container, Target, TargetDisplay, TargetDisplayInfoPR);
					});
			}
		},
		MR_LoadMap: function (prms) {
			var myOptions = {
				zoom: 5,
				center: new google.maps.LatLng(46.80000, 1.69000),
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				panControl: false, // Flï¿½ches de direction
				rotateControl: true,
				scaleControl: true, // Mesure de distance
				scrollwheel: prms.MapScrollWheel ? prms.MapScrollWheel : false, // Zoom avec la molette de la souris
				streetViewControl: prms.MapStreetView ? prms.MapStreetView : false, // Autorisation de StreetView
				zoomControl: true // Zoom
			};
			private.map = new google.maps.Map(document.getElementById('MRW-Map'), myOptions);
			private.bounds = new google.maps.LatLngBounds();
			private.overlays = [];
		},
		MR_clearOverlays: function () {
			for (var n = 0, overlay; overlay = private.overlays[n]; n++) {
				overlay.setMap(null);
			}
			// Clear overlays from collection
			private.overlays = [];
			private.bounds = new google.maps.LatLngBounds();
		},
		MR_FocusOnMaker: function (id) {
			// Boucle sur les Markers
			for (var i = 0; i < private.overlays.length; i++) {
				// Test de validitï¿½
				if (id == private.overlays[i].get("id")) {
					private.MR_FocusOnMap(i);
				}
			}
		},

		MR_AddGmapMarker: function (map, latLng, PRI, Id, sw_url) {
			// Get the letter for the marker
			var letter = String.fromCharCode("A".charCodeAt(0) + (private.overlays.length));

			// Create the marker
			var marker = new google.maps.Marker({
				position: latLng,
				map: map,
				icon: new google.maps.MarkerImage(private.protocol + private.sw_url + "/" + private.w_name + "/css/imgs/gmaps_pr02" + letter + ".png")
			});

			// Add clickListener
			google.maps.event.addListener(marker, 'click', function () {
				// Fermeture de la fenêtre précédente
				if (private.InfoWindow != null) { private.InfoWindow.close(); }

				private.InfoWindow = new google.maps.InfoWindow(
				{
					content: private.MR_BuildparcelShopDetails(PRI)
				}
			);

				private.InfoWindow.open(private.map, marker);
				private.map.setCenter(marker.getPosition());
			});

			// Add clickListener
			google.maps.event.addListener(marker, 'click', function () {
				private.MR_SelectparcelShop(PRI);
			});

			// Add Marker to Overlays collection
			private.overlays.push(marker);

			// Redimentionne la carte
			private.bounds.extend(latLng);
			//map.fitBounds(bounds);

			return marker;
		},
		MR_SelectparcelShop: function (PRI) {
			jQuery(private.params.Target).val(PRI.Pays + '-' + PRI.ID).trigger('change');
			jQuery(private.params.TargetDisplay).html(PRI.Pays + '-' + PRI.ID);
			if (private.params.TargetDisplayInfoPR) {
				jQuery(private.params.TargetDisplayInfoPR).html(PRI.Nom + '<br/>' + PRI.Adresse1 + '<br/>' + PRI.Adresse2 + '<br/>' + PRI.Pays + '-' + PRI.CP + ' ' + PRI.Ville + ' ');
			}

			jQuery(".PR-Selected").removeClass("PR-Selected");
			jQuery('.PR-Id[Value="' + PRI.Pays + '-' + PRI.ID + '"]').parent().addClass("PR-Selected");

			if (private.params.OnParcelShopSelected) {
				private.params.OnParcelShopSelected(PRI);
			}
		},

		MR_BuildparcelShopDetails: function (PRI) {
			var content = '<div class="InfoWindow">'
			+ '<div class="PR-Name">' + PRI.Nom + '</div>'
			+ '<div class="Tabs-Btns">'
			+ '<span class="Tabs-Btn Tabs-Btn-Selected" id="btn_01" onclick="jQuery(\'#' + private.containerId + '\').trigger(\'TabSelect\',\'01\');">'+MondialRelayLanguage.Horaires+'</span>'
			+ '<span class="Tabs-Btn" id="btn_02" onclick="jQuery(\'#' + private.containerId + '\').trigger(\'TabSelect\',\'02\');">'+MondialRelayLanguage.Photo+'</span>'
			+ '</div>'
			+ '<div class="Tabs-Tabs">'
			+ '<div class="Tabs-Tab Tabs-Tab-Selected" id="tab_01">' + PRI.HoursHtmlTable + '</div>'
			+ '<div class="Tabs-Tab" id="tab_02">'
			+ '<img src="' + private.protocol + private.img_url + '/img/dynamique/pr.aspx?id=' + PRI.Pays + private.MR_pad_left(PRI.ID, '0', 6) + '" width="182" height="112"/>'
			+ '</div>'
			+ '</div>'
			+ '</div>';
			return content;
		},
		MR_loadjscssfile: function (filename, filetype) {
			var fileref;
			if (filetype == "js") {
				fileref = document.createElement('script');
				fileref.setAttribute("type", "text/javascript");
				fileref.setAttribute("src", filename);
			}
			else if (filetype == "css") {
				fileref = document.createElement("link");
				fileref.setAttribute("rel", "stylesheet");
				fileref.setAttribute("type", "text/css");
				fileref.setAttribute("href", filename);
			}
			if (typeof fileref != "undefined") { document.getElementsByTagName("head")[0].appendChild(fileref); }
		},

		MR_pad_left: function (s, c, n) {
			if (!s || !c || s.length >= n) {
				return s;
			}

			var max = (n - s.length) / c.length;
			for (var i = 0; i < max; i++) {
				s = c + s;
			}

			return s;
		},

		// Initialisation du Widget aprï¿½s chargement du contrï¿½le
		MR_Widget_Init: function (container, prms) {
			private.params = prms;
			// Autocomplete sur le nom de ville
			var t = container.find('input.iArg0');
			var autoCpl = jQuery("<div>");
			autoCpl.addClass("PR-AutoCplCity");
			autoCpl.css("width", t.width());

			container.find('.MRW-Search').append(autoCpl);

			container.find('input.Arg2').live('keydown', function (e) {
					container.find('.PR-AutoCplCity').html("").slideUp("fast");
			});

			container.find('input.iArg0').live('keydown', function (e) {
				var keyCode = e.keyCode || e.which;

				var ia0 = container.find('input.iArg0')[0].value;
				var a2 = ""; //container.find('input.Arg2')[0].value;
				var a1 = container.find('input.Arg1')[0].value;

				var inp = String.fromCharCode(keyCode);
				//dï¿½placement par les touches
				//en cas de touche fleche vers le bas
				if (keyCode == 40) {
					if (container.find('.PR-AutoCplCity .AutoCpl-Hover').length === 0) {
						container.find('.PR-AutoCplCity div:first-child').addClass("AutoCpl-Hover");
					} else if (container.find('.AutoCpl-Hover').next().length > 0) {
						container.find('.AutoCpl-Hover').removeClass("AutoCpl-Hover").next().addClass("AutoCpl-Hover");
					}
				}
				//en cas de touche fleche vers le haut
				else if (keyCode == 38) {
					if (container.find('.PR-AutoCplCity .AutoCpl-Hover').length === 0) {
						container.find('.PR-AutoCplCity div:last-child').addClass("AutoCpl-Hover");
					} else if (container.find('.AutoCpl-Hover').prev().length > 0) {
						container.find('.AutoCpl-Hover').removeClass("AutoCpl-Hover").prev().addClass("AutoCpl-Hover");
					}
				}
				//en cas de touche entrï¿½e
				else if ((keyCode == 13 || keyCode == 9) && container.find('.AutoCpl-Hover').length > 0) {
					e.preventDefault();
					container.find('input.Arg2')[0].value = container.find('.AutoCpl-Hover').attr("title");
					container.find('input.iArg0')[0].value = container.find('.AutoCpl-Hover').attr("name");
					container.find('.PR-AutoCplCity').html("").slideUp("fast");
					return;
				}
				//pour toute autre touche de type caractï¿½re
				else if (/[a-zA-Z0-9\-_ ]/.test(inp)) {
					ia0 = ia0 + inp;
					if (ia0.length > 3) {
						container.find('.PR-AutoCplCity').css("top", (this.offsetTop + 20) + "px");
						container.find('.PR-AutoCplCity').css("left", (this.offsetLeft) + "px");

						private.jsonpcall(private.w_name + "/" + private.svc + "/AutoCPLCity",
							["PostCode", a2, "Country", a1, "City", ia0],
							function (result) {
								container.find('.PR-AutoCplCity').html("");

								for (var i = 0; i < result.Value.length; i++) {
									var elm = jQuery("<div>");
									elm.attr("title", result.Value[i].PostCode);
									elm.attr("name", result.Value[i].Name);
									elm.addClass("PR-City");

									elm.html(result.Value[i].Name + " (" + result.Value[i].PostCode + ")");
									container.find('.PR-AutoCplCity').append(elm);
									elm.click(function () {
										container.find('input.Arg2')[0].value = jQuery(this).attr("title");
										container.find('input.iArg0')[0].value = jQuery(this).attr("name");
										container.find('.PR-AutoCplCity').html("").slideUp("fast");
									});
								}
								container.find('.PR-AutoCplCity').slideDown("fast");
							});

					}
				}
				else {
					container.find('.PR-AutoCplCity').html("").slideUp("fast");
				}
			});

			container.find('input.iArg0').blur(function (event) {
				if (container.find('.AutoCpl-Hover').length) {
					container.find('input.Arg2')[0].value = container.find('.AutoCpl-Hover').attr("title");
					container.find('input.iArg0')[0].value = container.find('.AutoCpl-Hover').attr("name");
				}
			});

			// Fonction au click sur le bouton 'rechercher'
			container.find('.MRW-BtGo').click(function () {
				var btn = jQuery(this);
				private.MR_Widget_Call(container, prms.Target, prms.TargetDisplay, prms.TargetDisplayInfoPR, false);
				return false;
			});

			if (!("geolocation" in navigator)) { private.params.EnableGeolocalisatedSearch = false; }
			if (private.params.EnableGeolocalisatedSearch) {
				// Fonction au click sur le bouton 'utiliser ma position'
				container.find('.MRW-BtGeoGo').click(function () {
					var btn = jQuery(this);
					private.MR_Widget_Call(container, prms.Target, prms.TargetDisplay, prms.TargetDisplayInfoPR, true);
					return false;
				});
			} else {
				container.find('.MRW-BtGeoGo').hide();
			}

			// Fonction au click sur la selection des pays
			container.find('.MRW-flag').click(function () {
				var btn = jQuery(this);
				container.find('.MRW-fl-Select').slideDown("fast").css("top", (this.offsetTop + this.height + 2) + "px").css("left", this.offsetLeft - 3 + "px");
			});

			// Fonction au click sur la selection d'un pays
			container.find('.MRW-fl-Item').click(function () {
				var btn = jQuery(this);
				container.find('.MRW-fl-Select').slideUp("fast");
				container.find('.MRW-flag').attr('src', btn.find('img').attr('src'));
				container.find('input.Arg1')[0].value = btn.find('img').attr('alt');
			});

			container.find('input.Arg0')[0].value = prms.Brand;
			container.find('input.Arg1')[0].value = prms.Country;
			container.find('input.Arg2')[0].value = prms.PostCode;
			container.find('input.Arg3')[0].value = prms.ColLivMod;
			container.find('input.Arg4')[0].value = prms.Weight;
			container.find('input.Arg5')[0].value = prms.NbResults;
			container.find('input.Arg6')[0].value = prms.SearchDelay;
			container.find('input.Arg7')[0].value = prms.SearchFar;
			container.find('input.Arg10')[0].value = prms.Service;

			if (prms.PostCode != "") { private.MR_Widget_Call(container, prms.Target, prms.TargetDisplay, prms.TargetDisplayInfoPR, false); }
		}
	};

	var pub = {
		MR_WidgetJq: function (Div, prms) {
			var settings = jQuery.extend({
				Target: "",                             // (Obligatoire)    Selecteur JQuery de l'ï¿½lï¿½ment dans lequel sera renvoyï¿½ l'ID du Point Relais sï¿½lectionnï¿½ (gï¿½nï¿½ralement un champs input hidden)
				TargetDisplay: "",                      // (Facultatif)     Selecteur JQuery de l'ï¿½lï¿½ment dans lequel sera renvoyï¿½ l'ID du Point Relais sï¿½lectionnï¿½ (secondaire pour affichage)
				TargetDisplayInfoPR: "",                // (Facultatif)     Selecteur JQuery de l'ï¿½lï¿½ment dans lequel seront renvoyï¿½ les coordonnï¿½es complï¿½tes de la selection de l'utilisateur
				Brand: "",                              // (Obligatoire)    Le code client Mondial Relay
				Country: "FR",                          // (Facultatif)     Code ISO 2 lettres du pays utilisï¿½ pour la recherche
				AllowedCountries: "",                   // (Facultatif)     Liste des pays selectionnable par l'utilisateur pour la recherche (codes ISO 2 lettres sï¿½parï¿½s par des virgules)
				PostCode: "",                           // (Facultatif)     Code postal pour lancer une recherche par dï¿½faut
				EnableGeolocalisatedSearch: true,          // (Facultatif)     Active ou non la possibilitï¿½ d'effectuer la recherche sur la position courante (demande au navigateur)
				ColLivMod: "24R",                       // (Facultatif)     Permet de filtrer les Points Relais selon le mode de livraison utilisï¿½ (Point Relais L (24R), Xl (24L), XXL (24X)) rï¿½fï¿½rez vous ï¿½ votre contrat pour plus d'informations
				Weight: "",                             // (Facultatif)     Permet de filtrer les Points Relais selon le Poids (en grammes) du colis ï¿½ livrer
				Service: "",                            // (Facultatif)     Permet de filtrer les Points Relais selon les services proposï¿½s
				NbResults: "7",                         // (Facultatif)     Nombre de Point Relais ï¿½ renvoyer
				SearchDelay: "",                        // (Facultatif)     Permet de spï¿½cifier le nombre de jour entre la recheche et la dï¿½pose du colis dans notre rï¿½seau. Cette option permet de filtrer les Point Relais qui seraient ï¿½ventuellement en congï¿½s au moment de la livraison
				SearchFar: "75",                        // (Facultatif)     Permet de limiter la recherche des Points Relais ï¿½ une distance maximum
				CSS: "1",                               // (Facultatif)     Permet de spï¿½cifier que vous souhaitez utiliser votre propre feuille de style CSS lorsque vous lui donnez la valeur "0"
				MapScrollWheel: false,                  // (Facultatif)     Active ou non le zoom on scroll sur la carte des rï¿½sultats
				MapStreetView: false,                   // (Facultatif)     Active ou non le mode Street View sur la carte des rï¿½sultats (attention aux quotas imposï¿½s par Google)
				ShowResultsOnMap: true,                 // (Facultatif)     Active ou non l'affichage des rï¿½sultats sur une carte
				UseSSL: false,                          // (Facultatif)     Communique avec les serveurs Mondial Relay en HTTPS
				ServiceUrl: 'widget.mondialrelay.com',  // (Facultatif)     Permet de redï¿½finir le service fournisseur de donnï¿½es
				OnParcelShopSelected: null              // (Facultatif)     Fonction de callback ï¿½ la selection d'un Point Relais, le paramï¿½tre Data contient un objet avec les informations du Point Relais
			}, prms);

			if (settings.UseSSL) {
				private.protocol = 'https://';
				if (settings.ServiceUrl === 'widget.mondialrelay.com') {
					settings.ServiceUrl = 'www.mondialrelay.fr/widget';
				}
			}

			private.sw_url = settings.ServiceUrl;

			if (settings.AllowedCountries == "") {
				settings.AllowedCountries = settings.Country;
			}

			if (!Div.attr("id")) { Div.attr("id", "MRParcelShopPicker_" + Math.floor((Math.random() * 10000000) + 1)); }

			private.containerId = Div.attr("id");

			if (settings.CSS != "0") {
					private.MR_loadjscssfile(private.protocol + private.sw_url + "/" + private.w_name + "/css/style.min.css", "css");
			}
			private.container = Div;
			private.loadhtml(
				private.container,
				private.protocol + private.sw_url + "/" + private.w_name + "/services/widget.aspx?allowedCountries=" + settings.AllowedCountries + "&Country=" + settings.Country + "&UseSSL=" + settings.UseSSL + "&Brand=" + settings.Brand,
				function () { private.MR_Widget_Init(private.container, settings); }
			);

			return this;
		},
		// load widget into 'container' from 'host'
		MR_Widget: function (Div, prms) {
			return pub.MR_WidgetJq(jQuery(Div), prms);
		},
		MR_Destroy: function (Div, prms) {
			private.container = jQuery(Div);
			private.container.find('input.Arg2').unbind('keydown');
			private.container.find('input.iArg0').unbind('keydown');
			private.mapLoaded = false;
		},
		MR_FocusOnMap: function (i) {
			if (private.params.ShowResultsOnMap) {
				google.maps.event.trigger(private.overlays[i], "click");
			} else {
				jQuery('#MRW-Map > div', private.container).hide();
				jQuery('#MRW-Map > div:nth-child(' + (i + 1) + ')', private.container).show().trigger('select');
			}
		},
		MR_RebindMap: function () {
			if (private.params == null) {
				console.info('MR_RebindMap() method has been called too early.');
			} else {
				if (private.params.ShowResultsOnMap) {
					google.maps.event.trigger(private.map, 'resize');
					private.map.fitBounds(private.bounds);
				}
			}
		},
		MR_tabselect: function (tab) {
			jQuery(".Tabs-Btn-Selected", private.container).removeClass("Tabs-Btn-Selected");
			jQuery('#btn_' + tab, private.container).addClass("Tabs-Btn-Selected");
			jQuery(".Tabs-Tab-Selected", private.container).removeClass("Tabs-Tab-Selected");
			jQuery('#tab_' + tab, private.container).addClass("Tabs-Tab-Selected");
		}
	};

	return pub;
} ();

function MR_Var_Wrapper(callback) {
	var value;
	this.set = function (v) {
		value = v;
		callback(this);
	};
	this.get = function () { return value; };
}

; (function (jQuery, doc, win) {
	"use strict";
	var name = 'MondialRelay-ParcelShopPicker';
	jQuery.fn.MR_ParcelShopPicker = function (opts) {
		return this.each(function (i, el) {
			var base = el;
			base.init = function () {
				base.MR = new Widgets.MR_Widget(el, opts);
				jQuery("#" + base.id).bind("FocusOnMap", function (evt, id) {
					this.MR.MR_FocusOnMap(id);
				});
				jQuery("#" + base.id).bind("TabSelect", function (evt, id) {
					this.MR.MR_tabselect(id);
				});
				jQuery("#" + base.id).bind("MR_RebindMap", function (evt) {
					this.MR.MR_RebindMap();
				});
			};
			base.init();
		});
	};
})(jQuery, document, window);
