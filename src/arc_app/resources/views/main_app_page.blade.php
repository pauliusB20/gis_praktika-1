<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <title>MEGISA - main page</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://js.arcgis.com/4.19/esri/themes/light/main.css">
        <script src="https://js.arcgis.com/4.19/"></script>

        <script src="{{ asset('js/require.js')}}"></script>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script>
        //Main app code        
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
                     "esri/tasks/support/FeatureSet",
                     "esri/widgets/Legend",
                     "esri/widgets/Legend/LegendViewModel"
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
                               FeatureSet,
                               Legend,
                               LegendVM
                               )
        {
            esriConfig.apiKey = "AAPK7c7895bf808a4f23b5d9f049944cebbdRBkAqsrkczQn96GKC9hWASWXII9K0ZIVOf8r2PUZgA8KfrpB4cYBBgUkZYwlWHo9";

            const map = new Map({
                                  basemap: "arcgis-navigation"
                                });

            const view = new MapView({
                                       map: map,
                                        //center: [-40, 28],
                                        center: [23.31667, 55.93333], //lon/lat
                                        //zoom: 13, // Zoom level
                                        zoom: 12,
                                        container: "mapView" // Div element
                                    });
            view.when(function(){
                // All the resources in the MapView and the map have loaded. Now execute additional processes
                console.log("Center origin point loaded!");
            }, function(error){
                // Use the errback function to handle when the view doesn't load properly
                console.log("The view's resources failed to load: ", error);
            });

            //  Routing paths to buildings
            const routeTask = new RouteTask({
                                url: "https://route-api.arcgis.com/arcgis/rest/services/World/Route/NAServer/Route_World"
            });
            
            function addGraphic(type, point) {
                    const graphic = new Graphic({
                                symbol: {
                                type: "simple-marker",
                                color: (type === "origin") ? "green" : "blue",
                                size: "8px"
                                },
                                geometry: point,
                                outline: {
                                    color: [255, 255, 255], // White
                                    width: 1
                                }
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
                                color: [0, 150, 100],
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
            
            //Debug route data
            const buildingData = {!! json_encode($buildings) !!};
            if (buildingData)
            {
                //console.log(typeof buildingData);
                // console.log(buildingData);
                const graphicsLayer = new GraphicsLayer();

                map.add(graphicsLayer);
                //Placing muzeum icon symbols by using latitude and longitude coordinates
                //var points = [];
                let startPoint = {
                    type: "point",  // autocasts as new Point()
                    longitude: 23.3023,
                    latitude: 55.9461
                };
                addGraphic("origin", startPoint); //Origin position

                // Adding graphic points and route destination points

                buildingData.forEach(function(e)
                {
                        //let pointData = {"long": e.long, "lat": e.lat};
                        // points.push(pointData);

                        // Constructing route destination point
                        let endPoint = {
                            type: "point",  // autocasts as new Point()
                            longitude: e.long,
                            latitude: e.lat
                        };
                        addGraphic("destination", endPoint);
                        getRoute();
                        
                        // Constructing legend icon with pop ups
                        const point = { //Create a point
                            type: "point",
                            longitude: e.long,
                            latitude: e.lat
                        };
                        const simpleMarkerSymbol = {
                            type: "picture-marker",
                            url: "https://img.icons8.com/ios/452/museum.png",
                            color: [226, 119, 40],  // Orange
                            height: "30px",
                            width: "30px",
                            outline: {
                                color: [255, 255, 255], // White
                                width: 1
                            }
                        };
                        // const webStyleSymbol = new WebStyleSymbol({
                        //     name: "museum",
                        //     styleName: "Esri2DPointSymbolsStyle"
                        // });
                        // Pop up tempalte
                        const pointPopTemplate = {
                            title: "<b>{name}</b>",
                            content: "{description}"
                            // type: "image",
                            // caption: "{description}",
                            // value: {
                            //     sourceURL: e.img_url
                            // }
                        }
                        const popUpPolyAttributes = {
                            name: e.ins_name,
                            description: "<em>" + e.address + "</em><br/><br/>" + e.description + "<img src = "+ e.img_url +"></img>"
                        }

                        const pointGraphic = new Graphic({
                            geometry: point,
                            symbol: simpleMarkerSymbol,
                            attributes: popUpPolyAttributes,
                            popupTemplate: pointPopTemplate
                        });


                        graphicsLayer.add(pointGraphic);                       

                });
                
                // Geo Location tracking
                const locator = new Locate({
                            view: view,
                            useHeadingEnabled: false,
                            goToLocationEnabled: false,
                            popupEnabled: true,
                            goToOverride: function(view, options) {
                                
                                return view.goTo(options.target);                                
                            }
                });

                view.ui.add(locator, "top-left");

               //console.log(options.target);
            //    locator.locate().then(function(event){
            //            // Fires after the user's location has been found
            //            console.log("User's location has been found!");
            //            console.log(event);
            //     });

                // view.on("locate", function(event){
                //     alert(event.position.coords.latitude + ", " + event.position.coords.longitude);
                // });

                const tracker = new Track({
                                         view: view,
                                         graphic: new Graphic({
                                         symbol: {
                                         type: "simple-marker",
                                         size: "12px",
                                         color: "lightgreen",
                                         outline: {
                                             color: "#efefef",
                                             width: "1.5px"
                                         }
                                        }
                                        }),
                                         useHeadingEnabled: false
                                    });
                 view.ui.add(tracker, "top-left");

                 var legendBox = new Legend({
                                                view: view,
                                                viewModel: new LegendVM({
                                                    view: view
                                                })
                                            });

                 view.ui.add(legendBox, "bottom-right");

             
                // Constructing the possible routes
               
                // points.forEach(function(e)
                // {
                //    

                //     addGraphic("destination", endPoint);
                //     getRoute();
                // });
               
            }
           
        });
        function displayDistance()
        {
                 //Routing paths
                // const routeTask = new RouteTask({
                //                 url: "https://route-api.arcgis.com/arcgis/rest/services/World/Route/NAServer/Route_World"
                // });  
                alert("Getting data...");
        }
        </script>
        <!-- Styles -->
        <style>
            #mapView {
                padding: 0;
                margin: 0;
                height: 80%;
                width: 80%;
                margin-left:10em;
                border-style: solid;
                border-width:5px;
            }
            #dist{
                margin-left:10em;
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
    <!-- Page header -->
        <div class="content">
                <h2>
                    <img src = "https://img.icons8.com/ios/452/museum.png" style = "width:1.2em; height:1.2em;"></img>
                    <b>MEGISA - Museum Explorer GIS Application</b>
                    <img src = "https://img.icons8.com/ios/452/museum.png" style = "width:1.2em; height:1.2em;"></img>
                </h2>
        </div>
        <div id="mapView"></div>
        <button id = "dist" onclick="displayDistance();"><b>View distance</b></button>
        <!-- <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif -->

            <!-- <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://vapor.laravel.com">Vapor</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div> -->
        <!-- </div> -->
    </body>
</html>
