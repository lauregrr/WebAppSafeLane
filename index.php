<?php
require 'flight/Flight.php';
$server = 'localhost';
$port = '5432';
$base= 'amenagement_velo_paris';
$user = 'postgres';
$password = 'user';
$dsn = "host=$server port=$port dbname=$base user=$user password=$password";
$link = pg_connect($dsn);

if (!$link) {
  die('Erreur de connexion');
} else {
  Flight::set('BDD', $link) ;
};


Flight::route('/', function(){
    Flight::render('accueil');
});

Flight::route('POST /lumino', function(){

    // Récupérer les données POST envoyées par le client
    $lumi = isset($_POST['lumi']) ? $_POST['lumi'] : '';
    $meteo = isset($_POST['meteo']) ? $_POST['meteo'] : '';
    $link = Flight::get('BDD');

    $results = pg_query($link, "SELECT geom FROM accident_velo_2010_2022 WHERE lum = 1");

    $elements = [];
    while ($row = pg_fetch_assoc($results)) {
        $elements[] = $row['geom'];
    }

    Flight::json($elements);
});

Flight::route('POST /recup_annee', function(){
    $res = null;
    if (isset($_POST['annee'])){
        $link = Flight::get('BDD');

        $accidents = pg_query($link, "SELECT *, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geo FROM voie_cyclable_geovelo WHERE annee <= '" . $_POST['annee'] . "' OR annee IS NULL;");

        $features = [];
        while ($row = pg_fetch_assoc($accidents)) {
            $geometry = json_decode($row['geo']);
            unset($row['geom']); // on retire la colonne geom pour ne garder que la geo en geojson
            $features[] = array(
                'type' => 'Feature',
                'geometry' => $geometry,
                'properties' => $row
            );
        }

        $geojson = array(
            'type' => 'FeatureCollection',
            'features' => $features
        );
    }
    Flight::json($geojson);
});

Flight::route('/connexion', function(){
    Flight::render('connexion');
});

Flight::route('/map', function(){
    Flight::render('map');
});

Flight::route('/map3', function(){
    Flight::render('map3', );
});

Flight::route('/map4', function(){
    Flight::render('map4', );
});

Flight::route('/cesium', function(){
    Flight::render('cesium');
});

Flight::route('/mapJeanne', function(){
    Flight::render('mapJeanne');
});

Flight::route('GET /recupere_pistes', function(){
    $link = Flight::get('BDD');

    $accidents = pg_query($link, "SELECT *, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geo FROM voie_cyclable_geovelo;");

    $features = [];
    while ($row = pg_fetch_assoc($accidents)) {
        $geometry = json_decode($row['geo']);
        unset($row['geom']); // on retire la colonne geom pour ne garder que la geo en geojson
        $features[] = array(
            'type' => 'Feature',
            'geometry' => $geometry,
            'properties' => $row
        );
    }

    $geojson = array(
        'type' => 'FeatureCollection',
        'features' => $features
    );

    Flight::json($geojson);
});


Flight::route('GET /recupere_acci', function(){
    $link = Flight::get('BDD');

    $accidents = pg_query($link, "SELECT *, ST_AsGeoJSON(geom) AS geo FROM accident_velo_2010_2022");

    $features = [];
    while ($row = pg_fetch_assoc($accidents)) {
        $geometry = json_decode($row['geo']);
        unset($row['geom']); // on retire la colonne geom pour ne garder que la geo en geojson
        $features[] = array(
            'type' => 'Feature',
            'geometry' => $geometry,
            'properties' => $row
        );
    }

    $geojson = array(
        'type' => 'FeatureCollection',
        'features' => $features
    );

    Flight::json($geojson);
});


Flight::route('GET /recupere_plan', function(){
    $link = Flight::get('BDD');

    $accidents = pg_query($link, "SELECT ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geom, statut FROM plan_velo;");

    $features = [];
    while ($row = pg_fetch_assoc($accidents)) {
        $geometry = json_decode($row['geom']);
        $features[] = array(
            'type' => 'Feature',
            'geometry' => $geometry,
            'properties' => array('statut' => $row['statut']) 
        );
    }

    $geojson = array(
        'type' => 'FeatureCollection',
        'features' => $features
    );

    Flight::json($geojson);
});

Flight::start();

?>
