<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"> -->
        <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />
        <title>ARCGIS APP  @yield('title')</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://js.arcgis.com/4.19/esri/themes/light/main.css">
        <script src="https://js.arcgis.com/4.19/"></script>

        <script src="{{ asset('js/require.js')}}"></script>

       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <!-- Main map code -->
        <script>
            require([
                     "esri/config",
                     "esri/Map",
                     "esri/views/MapView",
                     "esri/layers/FeatureLayer",
                     "esri/widgets/Locate",
                     "esri/widgets/Track",
                     "esri/Graphic",
                     "esri/layers/GraphicsLayer",
                     "esri/tasks/RouteTask",
                     "esri/tasks/support/RouteParameters",
                     "esri/tasks/support/FeatureSet"
                     ],
                     function (esriConfig,
                               Map,
                               MapView,
                               FeatureLayer,
                               Locate,
                               Track,
                               Graphic,
                               GraphicsLayer,
                               RouteTask,
                               RouteParameters,
                               FeatureSet
                               ) {

                        esriConfig.apiKey = "AAPK7c7895bf808a4f23b5d9f049944cebbdRBkAqsrkczQn96GKC9hWASWXII9K0ZIVOf8r2PUZgA8KfrpB4cYBBgUkZYwlWHo9";

                        const map = new Map({
                                    //basemap: "arcgis-topographic" // Basemap layer service
                                    basemap: "arcgis-navigation"
                                    });

                        // mapOnLoad = map.on("load", function(){
                        //     map.graphics.on("click", myClickHandler);
                        // }); //For 3.36


                        const view = new MapView({
                                        map: map,
                                        //center: [-118.80543,34.02700], // Longitude, latitude
                                        //center: [55.946185897155274, 23.302453535021],
                                        //center: [-40, 28],
                                        center: [23.3023, 55.9461], //lon/lat
                                        //zoom: 13, // Zoom level
                                        zoom: 18,
                                        container: "viewDiv" // Div element
                                    });
                        //For routing
                        const routeTask = new RouteTask({
                                url: "https://route-api.arcgis.com/arcgis/rest/services/World/Route/NAServer/Route_World"
                        });
                        view.on("click", function(event){
                            if (view.graphics.length === 0) {
                                addGraphic("origin", event.mapPoint);
                            } else if (view.graphics.length === 1) {
                                addGraphic("destination", event.mapPoint);
                                getRoute();

                            } else {
                                view.graphics.removeAll();
                                addGraphic("origin",event.mapPoint);
                            }
                        });
                        function addGraphic(type, point) {
                            const graphic = new Graphic({
                                symbol: {
                                type: "simple-marker",
                                color: (type === "origin") ? "white" : "black",
                                size: "8px"
                                },
                            geometry: point
                            });
                            view.graphics.add(graphic);
                        }
                        function getRoute() {
                            const routeParams = new RouteParameters({
                                stops: new FeatureSet({
                                features: view.graphics.toArray()
                                }),
                                returnDirections: true
                            });
                            routeTask.solve(routeParams)
                            .then(function(data) {
                            data.routeResults.forEach(function(result) {
                                result.route.symbol = {
                                type: "simple-line",
                                color: [5, 150, 255],
                                width: 3
                                };
                                view.graphics.add(result.route);
                            });

                        // Display directions
                        if (data.routeResults.length > 0) {
                        const directions = document.createElement("ol");
                        directions.classList = "esri-widget esri-widget--panel esri-directions__scroller";
                        directions.style.marginTop = "0";
                        directions.style.padding = "15px 15px 15px 30px";
                        const features = data.routeResults[0].directions.features;

                        // Show each direction
                        features.forEach(function(result,i){
                            const direction = document.createElement("li");
                            var distance = (result.attributes.length.toFixed(2) * 1.609344);
                            direction.innerHTML = result.attributes.text + " (" + distance + " km)";
                            directions.appendChild(direction);
                        });

                        view.ui.empty("top-right");
                        view.ui.add(directions, "top-right");
                        }

                        })
                        .catch(function(error){
                            console.log(error);
                        })
                        }
                        //-------------------------
                        // Add marker to home location
                        const graphicsLayer = new GraphicsLayer();
                        map.add(graphicsLayer);
                        const point = { //Create a point
                            type: "point",
                            longitude: 23.3023,
                            latitude:  55.9461
                        };
                        const simpleMarkerSymbol = {
                            type: "simple-marker",
                            color: [226, 119, 40],  // Orange
                            outline: {
                                color: [255, 255, 255], // White
                                width: 1
                            }
                        };
                        const pointGraphic = new Graphic({
                            geometry: point,
                            symbol: simpleMarkerSymbol
                        });
                        graphicsLayer.add(pointGraphic);

                        // Create a line connected area
                        const polyline = {
                            type: "polyline",
                            paths: [
                                [23.3023, 55.9461], //Longitude, latitude
                                [23.3033, 55.9451], //Longitude, latitude
                                [23.3040, 55.9470],
                                [23.3023, 55.9461]  //Longitude, latitude
                            ]
                        };
                        const simpleLineSymbol = {
                            type: "simple-line",
                            color: [226, 119, 40], // Orange
                            width: 2
                        };
                        //Drawing poly lines and adding pop ups
                        const linePopUpTemplate = {
                            title: "{Name}",
                            content: "{Description}"
                        }
                        const popUpLineAttributes = {
                            Name: "My kingdom area lines",
                            Description: "My kingdom territory lines:D"
                        }
                        const polylineGraphic = new Graphic({
                            geometry: polyline,
                            symbol: simpleLineSymbol,
                            attributes: popUpLineAttributes,
                            popupTemplate: linePopUpTemplate
                        });
                        graphicsLayer.add(polylineGraphic);
                        //Create a polygon
                        const polygon = {
                            type: "polygon",
                            rings: [
                                [23.3023, 55.9461], //Longitude, latitude
                                [23.3033, 55.9451], //Longitude, latitude
                                [23.3040, 55.9470],
                                [23.3023, 55.9461]  //Longitude, latitude
                            ]
                        };
                        const simpleFillSymbol = {
                                type: "simple-fill",
                                color: [0, 255, 0, 0.5],
                                outline: {
                                    color: [0, 255, 0],
                                    width: 1
                                }
                        };

                        //Drawing a polygon and adding pop ups to drawed geometry
                        const polygonPopTemplate = {
                            title: "{Name}",
                            content: "{Description}"
                        }
                        const popUpPolyAttributes = {
                            Name: "My kingdom area",
                            Description: "The area in which I rule as a king!:)"
                        }
                        const polygonGraphic = new Graphic({
                            geometry: polygon,
                            symbol: simpleFillSymbol,
                            attributes: popUpPolyAttributes,
                            popupTemplate: polygonPopTemplate

                        });
                        graphicsLayer.add(polygonGraphic);
                        //-------------------------
                        const popupTrailheads = {
                            "title": "Information pop up",
                            "content": "<b>Trail:</b> {TRL_NAME}<br><b>City:</b> {CITY_JUR}<br><b>Cross Street:</b> {X_STREET}<br><b>Parking:</b> {PARKING}<br><b>Elevation:</b> {ELEV_FT} ft"
                        }
                        const trailheads = new FeatureLayer({
                            url: "https://services3.arcgis.com/GVgbJbqm8hXASVYi/arcgis/rest/services/Trailheads_Styled/FeatureServer/0",
                            outFields: ["TRL_NAME","CITY_JUR","X_STREET","PARKING","ELEV_FT"],
                            popupTemplate: popupTrailheads
                        });

                        map.add(trailheads);

                        const popupTrails = {
                            title: "Trail Information",
                            content: [{
                            type: "media",
                            mediaInfos: [{
                                type: "pie-chart",
                                caption: "Some data",
                                value: {
                                fields: [ "ELEV_MIN","ELEV_MAX" ],
                                normalizeField: null,
                                tooltipField: "Min and max elevation values"
                                }
                                }]
                            }]
                        }
                        const trails = new FeatureLayer({
                            url: "https://services3.arcgis.com/GVgbJbqm8hXASVYi/arcgis/rest/services/Trails_Styled/FeatureServer/0",
                            outFields: ["TRL_NAME","ELEV_GAIN"],
                            popupTemplate: popupTrails
                        });

                        map.add(trails,0);

                        // Define popup for Parks and Open Spaces
                        const popupOpenspaces = {
                            "title": "{PARK_NAME}",
                            "content": [{
                            "type": "fields",
                            "fieldInfos": [
                                {
                                "fieldName": "AGNCY_NAME",
                                "label": "Agency",
                                "isEditable": true,
                                "tooltip": "",
                                "visible": true,
                                "format": null,
                                "stringFieldOption": "text-box"
                                },
                                {
                                "fieldName": "TYPE",
                                "label": "Type",
                                "isEditable": true,
                                "tooltip": "",
                                "visible": true,
                                "format": null,
                                "stringFieldOption": "text-box"
                                },
                                {
                                "fieldName": "ACCESS_TYP",
                                "label": "Access",
                                "isEditable": true,
                                "tooltip": "",
                                "visible": true,
                                "format": null,
                                "stringFieldOption": "text-box"
                                },

                                {
                                "fieldName": "GIS_ACRES",
                                "label": "Acres",
                                "isEditable": true,
                                "tooltip": "",
                                "visible": true,
                                "format": {
                                    "places": 2,
                                    "digitSeparator": true
                                },

                                "stringFieldOption": "text-box"
                                }
                            ]
                            }]
                        }

                        const openspaces = new FeatureLayer({
                            url: "https://services3.arcgis.com/GVgbJbqm8hXASVYi/arcgis/rest/services/Parks_and_Open_Space_Styled/FeatureServer/0",
                            outFields: ["TYPE","PARK_NAME", "AGNCY_NAME","ACCESS_TYP","GIS_ACRES","TRLS_MI","TOTAL_GOOD","TOTAL_FAIR", "TOTAL_POOR"],
                            popupTemplate: popupOpenspaces
                        });

                        map.add(openspaces,0);

                        // Geo Location tracking
                        const locate = new Locate({
                            view: view,
                            useHeadingEnabled: false,
                            goToOverride: function(view, options) {
                                options.target.scale = 1500;
                                return view.goTo(options.target);
                            }
                        });
                        view.ui.add(locate, "top-left");

                        const track = new Track({
                            view: view,
                            graphic: new Graphic({
                                symbol: {
                                type: "simple-marker",
                                size: "12px",
                                color: "green",
                                outline: {
                                    color: "#efefef",
                                    width: "1.5px"
                                }
                                }
                            }),
                            useHeadingEnabled: false
                            });
                         view.ui.add(track, "top-left");

                        //  view.on("click", function(event) { //For getting long/lat via mouse click
                        //     let lat = Math.round(event.mapPoint.latitude * 1000) / 1000;
                        //     let lon = Math.round(event.mapPoint.longitude * 1000) / 1000;
                        //     console.log("click event at: (lat=" + lat + ", long=" + lon + ")");
                        //  });


                    // console.log(view);
                        //For debugging purposes
                        view.when(function(){
                        // All the resources in the MapView and the map have loaded. Now execute additional processes
                            console.log("I have some map data!");
                        }, function(error){
                            // Use the errback function to handle when the view doesn't load properly
                            console.log("The view's resources failed to load: ", error);
                        });
                     });
         </script>
        <!-- Styles -->
        <style>
            #viewDiv {
                padding: 0;
                margin: 0;
                height: 100%;
                width: 100%;
            }
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>

        </head>
    <body>
         <div id="viewDiv"></div>
        <!-- <div class="flex-center position-ref full-height">

            <div class="content">
                <h1>ARCGIS Test APP</h1>




            </div>
         </div>  -->
    </body>
</html>
