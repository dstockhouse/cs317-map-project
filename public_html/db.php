<?php

class Node {
}

class Edge {
}

class Graph {
}

// echo "Doin xml stuff <br>";

$json_out = array("type"=>"FeatureCollection",
	"features"=>array());

$map_xml = simplexml_load_file("map.kml") or die("Failed");

// Loop through KML map and generate graph
// echo "<br>Looping Placemarks: ";
foreach($map_xml->Document->Placemark as $pm) {

    // Print out Placemark information
    // echo "<br>&nbsp&nbsp;&nbsp;" . $pm->name;
    if($pm->Point) {
	// echo " is a point.<br>";
	// echo "&nbsp&nbsp;&nbsp;&nbsp&nbsp;&nbsp;";
	// echo $pm->Point->coordinates;

	// echo "<br>Parsing<br>";

	// Split into coordinate array
	$coords_str = trim($pm->Point->coordinates);
	$coords = explode(",", $coords_str);

	// echo "\nCoords:" . $coords[0];
	// echo "\nCoords:" . $coords[1];
	// echo "\n<br>";

	// Add to JSON
	array_push($json_out["features"], array(
		    "type"=>"feature", "geometry"=>array(
			"type"=>"Point", "coordinates"=>array(
			    floatval($coords[0]),floatval($coords[1])))));

    } else if ($pm->LineString) {
	// echo " is a line.<br>";
	// echo "&nbsp&nbsp;&nbsp;&nbsp&nbsp;&nbsp;";
	// echo $pm->LineString->coordinates;

	// Split into coordinate arrays
	$json_coords = array();
	$coords_strings = preg_split("/\s+/", $pm->LineString->coordinates);
	foreach($coords_strings as $coord) {
	    if($coord) {
		$current = explode(",", $coord);
		array_push($json_coords, array(
			    floatval($current[0]),
			    floatval($current[1])));
	    }
	}

	/*
	foreach($json_coords as $coord) {
	    echo "\nCoords:" . $coord[0];
	    echo "\nCoords:" . $coord[1];
	    echo "\n<br>";
	}
	*/

	// Add to JSON
	array_push($json_out["features"], array(
		    "type"=>"feature", "geometry"=>array(
			"type"=>"LineString", "coordinates"=>$json_coords)));
    } else {
	echo " is unknown.";
    }

}

// echo "<br><br>JSON: <br>";
echo json_encode($json_out);

?>

