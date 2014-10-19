#!/usr/bin/env php

<?

include "streetview-scan-config.php";

$choice = $argv[1];

if (! in_array( $choice, array_keys($choices))) {
  print "Usage: $argv[0] <route>.\n";
	print "<route> is the short title of any of the routes listed below.\n";
  print "Your choice of '$choice' not found. Available routes are:\n";
  foreach( $choices as $c => $data) {
    print "-> '$c' (". $data["title"] .")\n";
  }
  exit(1);
}

foreach ( array_keys( $choices[$choice] ) as $key) {
  $$key = $choices[$choice][$key];
  print "$key: ". $$key ."\n";
}

include_once('kmlcreator/kml.class.php');
$kml = new KML('My KML');
$document = new KMLDocument('', "Streetview Timelapse Path: $title");


/*
$street = $panoconfig->{'data_properties'}->{'text'};
$street_range = $panoconfig->{'data_properties'}->{'street_range'};
$region = $panoconfig->{'data_properties'}->{'region'};
$country = $panoconfig->{'data_properties'}->{'country'};
$lat = $panoconfig->{'data_properties'}["lat"];
$long = $panoconfig->{'data_properties'}["lng"];
$pano_yaw_deg = $panoconfig->{'projection_properties'}["pano_yaw_deg"];
  
#print "$lat, $long | $pano_yaw_deg | $panoid | Location: $country, $region, $street, no. $street_range\n";

#print_r($panoconfig); exit(99);
#print_r($panoconfig->{'annotation_properties'}->{'link'});
#var_dump($panoconfig->{'annotation_properties'}[0]);

foreach ($panoconfig->{'annotation_properties'}->{'link'} as $link) {
  if ($link['link_text'] = $street) { 
    print "Found next link: ". $link['pano_id'] .", yaw: " . $link['yaw_deg'] ."\n"; 
    $next_pano = $link['pano_id'];
    $next_yaw_deg = $link['yaw_deg'];
    break; 
  }
}
*/

if ( ! is_dir("xml")) mkdir("xml");

// Get the initial xml only:
$panoid_xml = "http://cbk1.google.com/cbk?output=xml&panoid=$panoid&cb_client=maps_sv";
  
$xml_filename = "xml/xml_".$panoid.".xml";
if (! file_exists( $xml_filename )) {
  system("wget -nv '$panoid_xml' -O '$xml_filename'");
}
$panoconfig = simplexml_load_file($xml_filename);

$prefix = 0;
$done = false;
$batch_file = $short_title ."-batch-frames.sh"; 
file_put_contents( $batch_file, "CMD=/home/taal/projects/streetview-timelapse/prepare-streetview-frame.sh\n");
  
$start_lat = $panoconfig->{'data_properties'}["lat"];
$start_long = $panoconfig->{'data_properties'}["lng"];

while (! $done) {
  $prefix++;
  $panoid = $next_pano;
  $panoid_xml = "http://cbk1.google.com/cbk?output=xml&panoid=$panoid&cb_client=maps_sv";
  
  $xml_filename = "xml/xml_".$panoid.".xml";
  if (! file_exists( $xml_filename )) {
    usleep(100);
    #sleep(1);
    system("wget -nv '$panoid_xml' -O '$xml_filename'");
  }
  $panoconfig = simplexml_load_file($xml_filename);
  
  $street = $panoconfig->{'data_properties'}->{'text'};
  $street_range = $panoconfig->{'data_properties'}->{'street_range'};
  $region = $panoconfig->{'data_properties'}->{'region'};
  $country = $panoconfig->{'data_properties'}->{'country'};
  $lat = $panoconfig->{'data_properties'}["lat"];
  $long = $panoconfig->{'data_properties'}["lng"];
  $pano_yaw_deg = $panoconfig->{'projection_properties'}["pano_yaw_deg"];
  
  #print "$lat, $long | $panoid | Location: $country, $region, $street, no. $street_range\n";
  //print "$lat, $long | $pano_yaw_deg | $panoid | Location: $country, $region, $street, no. $street_range\n";
  
  #print_r($panoconfig);
  #print_r($panoconfig->{'annotation_properties'}->{'link'});
  #var_dump($panoconfig->{'annotation_properties'}[0]);
  
  foreach ($panoconfig->{'annotation_properties'}->{'link'} as $link) {
    if ($link['link_text'] = $street) { 
      #print "Found suitable link: ". $link['pano_id'] .", yaw: " . $link['yaw_deg'] ." | "; 
      if ( abs( $link['yaw_deg'] - $next_yaw_deg) < $waver_degree || abs( $link['yaw_deg'] - $next_yaw_deg) > (360 - $waver_degree)) {
        if ( in_array( $link['pano_id'], $blacklist)) {
          print "Looked but in blacklist! (". $link['pano_id'] .")\n"; 
        } else {
          #print "Selected: ". $link['pano_id'] .", yaw: " . $link['yaw_deg'] ."\n"; 
          $next_pano = $link['pano_id'];
          $next_yaw_deg = $link['yaw_deg'];
          #print "Selected this link!\n";
        }
      } else { printf("Not selected, wavering too much: %d\n", $link['yaw_deg'] - $next_yaw_deg); }
    }
  }
  
  $roll_x = round((($next_yaw_deg - $pano_yaw_deg) / 360) * 1664);
  if ($roll_x >= 0) { $roll_x_string = "+$roll_x"; }
  else              { $roll_x_string = "$roll_x"; }

  $distVincenty = distVincenty( $lat, $long, $start_lat, $start_long) / 1000;

  $cmd_string = sprintf ("\$CMD %07d $panoid $roll_x_string '(c)%d Google | %07d | %.4f, %.4f | %.1f km / %.1f m | %d° | $country, $region, $street, no. $street_range' \"\$1\"\n", $prefix, date('Y'), $prefix, $lat, $long, $distVincenty, $distVincenty / 1.609, $next_yaw_deg);
  print( "-> ". $cmd_string );
  file_put_contents( $batch_file, $cmd_string, FILE_APPEND);
  
  $line_points[] = array($long, $lat, 0);
  
  if ( abs($roll_x) > 700 && abs($roll_x) < 900) {
    if ( ! $previous_pano_turned ) {
      /**
        * Add a change point
        */  
      $boat = new KMLPlaceMark('', "Change @ frame: $panoid, $prefix", 'Direction that Google car faced changed here');
      $boat->setGeometry(new KMLPoint($long, $lat, 0));
      $document->addFeature($boat);
      print "Turned around!\n";
    } 
    $previous_pano_turned = true;
  } else {
    $previous_pano_turned = false;
  }


  if ( is_array($override_link) &&  array_key_exists ( "$panoid", $override_link) ) {
    $next_pano = $override_link["$panoid"]["id"]; 
    $next_yaw_deg = $override_link["$panoid"]["yaw"];
    print "Override link selected!!!\n";
  }
  
  
  if ($next_pano == $panoid) {
    print "Dead end unfortunately!!!\n";
    $done = true;
  }

  if ($lat < $miny || $lat > $maxy || $long < $minx || $long > $maxx) {
    print "Outside of boundaries!!!!\n";
    $done = true;
  }
  
  
}

$roadTrace = new KMLPlaceMark('', 'Follow');
$roadTrace->setGeometry (new KMLLineString( $line_points), true, '', true);
$document->addFeature($roadTrace);
//Add your document to your kml
$kml->setFeature($document);

/**
  * Output the result
  */

$kml->output('F', $short_title .'-streetview-timelapse.kml', false);
file_put_contents( $batch_file, "sh /home/taal/projects/streetview-timelapse/movie-create.sh\n", FILE_APPEND);

exit(0);

/** 
 * Calculate geodesic distance (in meters) between two points specified by 
 * latitude/longitude using Vincenty inverse formula for ellipsoids 
 * 
 * from: Vincenty inverse formula - T Vincenty, "Direct and Inverse 
 * Solutions of Geodesics on the Ellipsoid with application of nested 
 * equations", Survey Review, vol XXII no 176, 1975 
 * http://www.ngs.noaa.gov/PUBS_LIB/inverse.pdf 
 * 
 * Ported from JavaScript to PHP Martin Milesich - http://milesich.com 
 * 
 * Original JavaScript version 
 * http://www.movable-type.co.uk/scripts/latlong-vincenty.html 
 * 
 * @param float $lat1 in form 52.2166667 
 * @param float $lat2 in form 52.35 
 * @param float $lon1 in form 5.9666667 
 * @param float $lon2 in form 4.9166667 
 * @return float      in form 73.174873 (meters) 
 */  
function distVincenty($lat1, $lon1, $lat2, $lon2)  
{  
    $lat1 = deg2rad($lat1);  
    $lat2 = deg2rad($lat2);  
    $lon1 = deg2rad($lon1);  
    $lon2 = deg2rad($lon2);  
  
    $a = 6378137; $b = 6356752.3142; $f = 1/298.257223563; // WGS-84 ellipsoid  
  
    $L = $lon2 - $lon1;  
  
    $U1 = atan((1-$f) * tan($lat1));  
    $U2 = atan((1-$f) * tan($lat2));  
  
    $sinU1 = sin($U1); $cosU1 = cos($U1);  
    $sinU2 = sin($U2); $cosU2 = cos($U2);  
  
    $lambda = $L; $lambdaP = 2 * M_PI;  
  
    $iterLimit = 20;  
  
    while (abs($lambda - $lambdaP) > 1e-12 && --$iterLimit > 0)  
    {  
        $sinLambda = sin($lambda);  
        $cosLambda = cos($lambda);  
        $sinSigma  = sqrt(($cosU2 * $sinLambda) * ($cosU2 * $sinLambda) +  
                          ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda) *  
                          ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda));  
  
        if ($sinSigma == 0) return 0; // co-incident points  
  
        $cosSigma   = $sinU1 * $sinU2 + $cosU1 * $cosU2 * $cosLambda;  
        $sigma      = atan2($sinSigma, $cosSigma); // was atan2  
        $alpha      = asin($cosU1 * $cosU2 * $sinLambda / $sinSigma);  
        $cosSqAlpha = cos($alpha) * cos($alpha);  
        $cos2SigmaM = $cosSigma - 2 * $sinU1 * $sinU2 / $cosSqAlpha;  
        $C          = $f / 16 * $cosSqAlpha * (4 + $f * (4 - 3 * $cosSqAlpha));  
        $lambdaP    = $lambda;  
        $lambda     = $L + (1 - $C) * $f * sin($alpha) *  
                      ($sigma + $C * $sinSigma * ($cos2SigmaM + $C * $cosSigma *  
                      (-1 + 2 * $cos2SigmaM * $cos2SigmaM)));  
    }  
    if ($iterLimit == 0) return false; // formula failed to converge  
  
    $uSq = $cosSqAlpha * ($a * $a - $b * $b) / ($b * $b);  
    $A   = 1 + $uSq / 16384 * (4096 + $uSq * (-768 + $uSq * (320 - 175 * $uSq)));  
    $B   = $uSq / 1024 * (256 + $uSq * (-128 + $uSq * (74 - 47 * $uSq)));  
  
    $deltaSigma = $B * $sinSigma * ($cos2SigmaM + $B / 4 * ($cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM) -  
                  $B / 6 * $cos2SigmaM * (-3 + 4 * $sinSigma * $sinSigma) * (-3 + 4 * $cos2SigmaM * $cos2SigmaM)));  
  
    $s = $b * $A * ($sigma - $deltaSigma);  
  
    $s = round($s, 3); // round to 1mm precision  
  
    return $s;  
}  

// The simple spherical function (doesn't seem to work...why?)
function getDistance($latitudeFrom, $longitudeFrom,
    $latituteTo, $longitudeTo)
{
    // 1 degree equals 0.017453292519943 radius
    (float) $degreeRadius = deg2rad(1);
 
    // convert longitude and latitude values
    // to radians before calculation
    (float) $latitudeFrom  *= $degreeRadius;
    (float) $longitudeFrom *= $degreeRadius;
    (float) $latituteTo    *= $degreeRadius;
    (float) $longitudeTo   *= $degreeRadius;
 
    // apply the Great Circle Distance Formula
    (float) $d = sin($latitudeFrom) * sin($latituteTo) + cos($latitudeFrom)
       * cos($latituteTo) * cos($longitudeFrom - $longitudeTo);
 
    (float) $avg_radius = ( 6378137 + 6356752.3142) / 2.0;
    return (float) ($avg_radius * acos($d));
}

// Seems to work a bit better
function getDistance2( $lat1, $long1, $lat2, $long2) {
$pi = 3.14159265358979; 
$rad = doubleval($pi/180.0); 

$lon1 = doubleval($lon1)*$rad; $lat1 = doubleval($lat1)*$rad; 
$lon2 = doubleval($lon2)*$rad; $lat2 = doubleval($lat2)*$rad; 

$theta = $lon2 - $lon1; 
$dist = acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($theta)); 
if ($dist < 0) { $dist += $pi; } 
$dist = $dist * ( 6378137 + 6356752.3142) / 2.0;
/*
$miles = doubleval($dist * 0.621); 
$inches = doubleval($miles*63360); 
$dist = sprintf("%.2f",$dist); 
$miles = sprintf("%.2f",$miles); 
$inches = sprintf("%.2f",$inches);*/
return $dist;
}

?>
