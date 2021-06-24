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
                     "esri/layers/GraphicsLayer"
                     ], 
                     function (esriConfig, 
                               Map, 
                               MapView, 
                               FeatureLayer, 
                               Locate,
                               Track,
                               Graphic,
                               GraphicsLayer
                               ) {

                        esriConfig.apiKey = "AAPK7c7895bf808a4f23b5d9f049944cebbdRBkAqsrkczQn96GKC9hWASWXII9K0ZIVOf8r2PUZgA8KfrpB4cYBBgUkZYwlWHo9";

                        const map = new Map({
                                    //basemap: "arcgis-topographic" // Basemap layer service
                                    basemap: "arcgis-navigation"
                                    });

                        const view = new MapView({
                                        map: map,
                                        //center: [-118.80543,34.02700], // Longitude, latitude
                                        //center: [55.946185897155274, 23.302453535021],
                                        //center: [-40, 28],
                                        center: [23.3023, 55.9461], //lon/lat
                                        //zoom: 13, // Zoom level
                                        zoom: 10,
                                        container: "viewDiv" // Div element
                                    });

                        // Add marker to location
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

                        // // Create a line geometry
                        // const polyline = {
                        //     type: "polyline",
                        //     paths: [
                        //         [23.3023, 55.9461], //Longitude, latitude
                        //         [24.3023, 56.9461], //Longitude, latitude
                        //         [22.3023, 54.9461]  //Longitude, latitude
                        //     ]
                        // };
                        // const simpleLineSymbol = {
                        //     type: "simple-line",
                        //     color: [226, 119, 40], // Orange
                        //     width: 2
                        // };
                        // const polylineGraphic = new Graphic({
                        //     geometry: polyline,
                        //     symbol: simpleLineSymbol
                        // });
                        // graphicsLayer.add(polylineGraphic);
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

                    // console.log(view);
                        //For debugging purposes
                        view.when(function(){
                        // All the resources in the MapView and the map have loaded. Now execute additional processes
                            console.log("I have some data!");
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
