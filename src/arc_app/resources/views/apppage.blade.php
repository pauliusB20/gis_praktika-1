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
            require(["esri/config","esri/Map", "esri/views/MapView"], function (esriConfig, Map, MapView) {

                esriConfig.apiKey = "AAPK7c7895bf808a4f23b5d9f049944cebbdRBkAqsrkczQn96GKC9hWASWXII9K0ZIVOf8r2PUZgA8KfrpB4cYBBgUkZYwlWHo9";

                const map = new Map({
                            basemap: "arcgis-topographic" // Basemap layer service
                            });

                const view = new MapView({
                                map: map,
                                center: [-118.805, 34.027], // Longitude, latitude
                                zoom: 13, // Zoom level
                                container: "viewDiv" // Div element
                            });
            // console.log(view);
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
