<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

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
                               )
        {
            esriConfig.apiKey = "AAPK7c7895bf808a4f23b5d9f049944cebbdRBkAqsrkczQn96GKC9hWASWXII9K0ZIVOf8r2PUZgA8KfrpB4cYBBgUkZYwlWHo9";

            const map = new Map({
                                  basemap: "arcgis-navigation"
                                });

            const view = new MapView({
                                       map: map,
                                        //center: [-118.80543,34.02700], // Longitude, latitude
                                        //center: [55.946185897155274, 23.302453535021],
                                        //center: [-40, 28],
                                        center: [23.3023, 55.9461], //lon/lat
                                        //zoom: 13, // Zoom level
                                        zoom: 11,
                                        container: "mapView" // Div element
                                    });
            view.when(function(){
                // All the resources in the MapView and the map have loaded. Now execute additional processes
                console.log("Center origin point loaded!");
            }, function(error){
                // Use the errback function to handle when the view doesn't load properly
                console.log("The view's resources failed to load: ", error);
            });

            //Debug route data
            const buildingData = {!! json_encode($buildings) !!};
            if (buildingData)
            {
                //console.log(typeof buildingData);
                // console.log(buildingData);
                const graphicsLayer = new GraphicsLayer();

                map.add(graphicsLayer);
                //Placing muzeum icon symbols by using latitude and longitude coordinates
                buildingData.forEach(function(e){

                    //console.log(e.id);
                        const point = { //Create a point
                            type: "point",
                            longitude: e.long,
                            latitude: e.lat
                        };
                        const simpleMarkerSymbol = {
                            type: "picture-marker",
                            url: "https://static.arcgis.com/images/Symbols/Shapes/BlackStarLargeB.png",
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
                        const polygonPopTemplate = {
                            title: "{Name}",
                            content: "{Description}"
                        }
                        const popUpPolyAttributes = {
                            Name: e.ins_name,
                            Description: e.address + "\n\n" + e.description
                        }

                        const pointGraphic = new Graphic({
                            geometry: point,
                            symbol: simpleMarkerSymbol,
                            attributes: popUpPolyAttributes,
                            popupTemplate: polygonPopTemplate
                        });

                        graphicsLayer.add(pointGraphic);

                        
                });
            }

        });

        </script>
        <!-- Styles -->
        <style>
            #mapView {
                padding: 0;
                margin: 0;
                height: 80%;
                width: 80%;
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
                <h2>MEGISA - Muzeum Explorer GIS Application</h2>
        </div>
        <div id="mapView"></div>
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
