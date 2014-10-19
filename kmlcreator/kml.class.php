<?php
/**
  * KML Main Class File 
  * 
  * File containing all classes to create KML or KMZ file
  *
  * @package kml
  * @version 0.1
  * @author Ken LE PRADO <ken@leprado.com>
  * @link http://www.leprado.com/kml
  */  

/**#@+
 * Constants
 */
 
/**
  * Version of the KML Class
  */
define('KML_CLASS_VERSION', 0.1);


/**
  * KML class to create kml file 
  *
  * @package kml
  *
  */  
class KML {
   private $title;
   private $visibility;
   private $open;
   private $files;
   
   /**
     * Feature
     */
   private $feature;
   
   public function __construct($title = '', $visibility = true, $open = true) {
      $this->$title       = $title;
      $this->$visibility  = $visibility;
      $this->$open        = $open;
      
   }
   
   /**
     * Retourne la chaîne kml
     *
     * @return string contenu du KML
     */   
   public function __toString() {

      //Ajout entete
		$string = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$string .= "<kml xmlns=\"http://earth.google.com/kml/2.1\">\n";

      if (!empty($this->feature)) {
         $string .= $this->feature->__toString();
      }
            
      //Ajout pied
		$string .= "</kml>\n";
		
      return $string;
   }

   /**
     * Add a Feature
     *
     * @param object feature Object "Feature" to add
     */   
   public function setFeature ($feature) {
      $this->feature = $feature;
   }

   
   /**
     * Enregistrement du KML
     *
     * @param string type Type d'export : S=String F=File A=Attachement
     * @param string filename Nom du fichier exporté
     */
   
   public function output($type = 'S', $filename = '') {
      switch ($type) {
	      case 'A':
      		header('Content-type: application/vnd.google-earth.kml');
      		header('Content-Disposition:attachment; filename="' . $filename . '"');
      		
      		echo $this->__toString();

      		exit;
      		break;
      		
         case 'Z':
   		// Format de sortie compressé
   			$fichier = new ZipArchive();
   			if($fichier->open($filename, ZIPARCHIVE::OVERWRITE) !== true) {	
				   echo('Impossible de créer le fichier !');
				   return false;
			   }
			
   			$fichier->addFromString('doc.kml', $this->__toString());
   			foreach($this->files as $displayFileName => $fileName) {
   			   if (is_file($fileName)) {
   			      $fichier->addFile($fileName, $displayFileName);
   			   }
   			}
   			
   			$fichier->close();
			   return true;
            break;


         case 'F':
            if (file_put_contents($filename, $this->__toString())) {
               return true;
            } else {
               return false;
            }          
         
            break;

         case 'S':
            return $this->__toString();         
            break;
		}
   
   }
   
   public function addFile($fileName, $displayFileName = '') {
      if (empty ($displayFileName)) {
         $displayFileName = basename($fileName);
      }
      $this->files[$displayFileName] = $fileName; 
   }

}

/**
  * KML Object 
  *  has an id
  * 
  * @package kml
  *
  */
class KMLObject {
   private $id;
   private $type;
   
   public function __construct($type = '', $id = '') {
      $this->type = $type;
      $this->id   = $id;
   }   
   
   public function headerToString() {
      if (empty($this->id)) {
         $string = "<".$this->type.">\n";
      } else {
         $string = '<'.$this->type.' id="'.$this->id."\">\n";
      }   
      return $string;
   }
   
   public function footerToString() {
      $string = "</".$this->type.">\n";
      return $string;
   }
   
}

/**
  * Feature KML 
  *
  * @package kml
  *
  */
class KMLFeature extends KMLObject {
   private $name;
   private $description;
   private $visibility;
   private $styles;
   private $styleUrl;
   private $features;
   private $TimePrimitive;
   

   public function __construct($type, $id = '', $name = '', $description = '', $visibility = true) {
      $this->name        = $name;
      $this->description = $description;
      $this->visibility  = $visibility;
      
      $this->features = array();
      $this->styles = array();
      parent::__construct($type, $id);
   }
   
   public function headerToString() {
      $string = parent::headerToString();
      
      $string .= "<name>".utf8_encode($this->name)."</name>\n";
      
      if (!empty($this->description)) {
         $string .= "<description>\n";
         $string .= "<![CDATA[\n";
         $string .= utf8_encode($this->description) . "\n";
         $string .= "]]>\n";
         $string .= "</description>\n";
      }
      
      if ($this->visibility === true) {
         $string .= "<visibility>1</visibility>\n";
      } else {
         $string .= "<visibility>0</visibility>\n";
      }      
      
      if (!empty($this->styles)) {
         foreach($this->styles as $style) {
            $string .= $style->__toString();
         }
      }
                    
      if (!empty($this->styleUrl)) {
         $string .= "<styleUrl>" . $this->styleUrl . "</styleUrl>\n";
      }

      if (!empty($this->TimePrimitive)) {
         $string .= $this->TimePrimitive->__toString();
      }

      foreach ($this->features as $feature) {
         $string .= $feature->__toString();
      }

      return $string;

   }
   
   public function footerToString() {
      $string = parent::footerToString();
      return $string;
   }
   
   public function addStyle($style) {
      $this->styles[] = $style;
   }
   
   public function setStyleUrl($styleUrl) {
      $this->styleUrl = $styleUrl;
   }

   public function __toString() {}


   public function addFeature($feature) {
      $this->features[] = $feature;
   }
   
   public function setTimePrimitive($TimePrimitive) {
      $this->TimePrimitive = $TimePrimitive;
   }   
}

/**
  * Folder KML 
  *
  * @package kml
  *
  */
class KMLFolder extends KMLFeature {

   public function __construct($id = '', $name = '', $description = '', $visibility = true) {
      parent::__construct('Folder', $id, $name, $description, $visibility);

   }
   
   public function __toString() {
      $string = '';
      
      $string .= parent::headerToString();


   
      
      $string .= parent::footerToString();

      return $string;
   }

}

/**
  * Document KML 
  *
  * @package kml
  *
  */
class KMLDocument extends KMLFeature {

   private $placemarks;
   
   public function __construct($id = '', $name = '', $description = '', $visibility = true) {
      parent::__construct('Document', $id, $name, $description, $visibility);

      $this->placemarks = array();
      
   }
   
   public function __toString() {
      $string = '';
      
      $string .= parent::headerToString();


      foreach ($this->placemarks as $placemark) {
         $string .= $placemark->__toString();
      }
   
      
      $string .= parent::footerToString();

      return $string;
   }

   public function addPlaceMark($placemark) {
      $this->placemarks[] = $placemark;
   }

}


/**
  * PlaceMark KML 
  *
  * @package kml
  *
  */  
class KMLPlaceMark extends KMLFeature {

   private $geometry;

   public function __construct($id = '', $name = '', $description = '', $visibility = true) {
      parent::__construct('Placemark', $id, $name, $description, $visibility);
      
   }
   
   public function __toString() {
      $string = '';
      
      $string .= parent::headerToString();
    
      $string .= $this->geometry->__toString();
      
      $string .= parent::   footerToString();

      return $string;
   }
   
   public function setGeometry($geometry) {
      $this->geometry = $geometry;
   }
   
}

/**
  * Geometry KML 
  *
  * @package kml
  *
  */
class KMLGeometry extends KMLObject {
   public function __construct($type, $id = '') {
      parent::__construct($type, $id);
   }

}


/**
  * Point KML 
  *
  * @package kml
  *
  */  
class KMLPoint extends KMLGeometry {
   private $longitude;
   private $latitude;
   private $altitude;
   private $extrude;
   private $altitudeMode;

   public function __construct($longitude, $latitude, $altitude = 0, $extrude = true, $altitudeMode = 'clampToGround') {
      $this->longitude    = $longitude;
      $this->latitude     = $latitude;
      $this->altitude     = $altitude;
      $this->extrude      = $extrude;
      $this->altitudeMode = $altitudeMode;
   }
   
   public function __toString() {
      $string = '';
      $string .= "<Point>\n";
      if (!empty($this->extrude)) {
         if ($this->extrude === true) {
            $string .= "<extrude>1</extrude>\n";
         } else {
            $string .= "<extrude>0</extrude>\n";         
         }
      }
      
      if (!empty($this->altitudeMode)) {
         $string .= "<altitudeMode>".$this->altitudeMode."</altitudeMode>\n";      
      }
      
      $string .= "<coordinates>";
      $string .= $this->longitude . ', ' . $this->latitude . ', ' .  $this->altitude ."\n";
      $string .= "</coordinates>\n";
      $string .= "</Point>\n";
      return $string;
   }
}

/**
  * LineString KML 
  *
  * @package kml
  *
  */  
class KMLLineString extends KMLGeometry {
   private $points;
   private $extrude;
   private $altitudeMode;
   private $tessellate;

   public function __construct($points, $extrude = true, $altitudeMode = 'clampToGround', $tessellate = true) {
      $this->points  = $points;
      $this->extrude      = $extrude;
      $this->altitudeMode = $altitudeMode;
      $this->tessellate   = $tessellate;
   }
   
   public function __toString() {
      $string = '';
      $string .= "<LineString>\n";

      if (!empty($this->extrude)) {
         if ($this->extrude === true) {
            $string .= "<extrude>1</extrude>\n";
         } else {
            $string .= "<extrude>0</extrude>\n";         
         }
      }
      
      if (!empty($this->tessellate)) {
         if ($this->tessellate === true) {
            $string .= "<tessellate>1</tessellate>\n";
         } else {
            $string .= "<tessellate>0</tessellate>\n";         
         }
      }
      
      if (!empty($this->altitudeMode)) {
         $string .= "<altitudeMode>".$this->altitudeMode."</altitudeMode>\n";      
      }

      $string .= "<coordinates>\n";
      foreach ($this->points as $point) {
         $string .= $point[0].','.$point[1].','.$point[2]." \n";
      }
      $string .= "</coordinates>\n";
      $string .= "</LineString>\n";

      return $string;
   }
}

/**
  * Polygon KML 
  *
  * @package kml
  *
  */  
class KMLPolygon extends KMLGeometry {
   private $outerBoundary;
   private $extrude;
   private $altitudeMode;
   private $tessellate;

   public function __construct($outerBoundary, $extrude = true, $altitudeMode = 'clampToGround', $tessellate = true) {
      $this->outerBoundary  = $outerBoundary;
      $this->extrude      = $extrude;
      $this->altitudeMode = $altitudeMode;
      $this->tessellate   = $tessellate;
   }
   
   public function __toString() {
      $string = '';
      $string .= "<Polygon>\n";

      if (!empty($this->extrude)) {
         if ($this->extrude === true) {
            $string .= "<extrude>1</extrude>\n";
         } else {
            $string .= "<extrude>0</extrude>\n";         
         }
      }
      
      if (!empty($this->tessellate)) {
         if ($this->tessellate === true) {
            $string .= "<tessellate>1</tessellate>\n";
         } else {
            $string .= "<tessellate>0</tessellate>\n";         
         }
      }
      
      if (!empty($this->altitudeMode)) {
         $string .= "<altitudeMode>".$this->altitudeMode."</altitudeMode>\n";      
      }
      

      
      $string .= "<outerBoundaryIs>\n";
      $string .= "<LinearRing>\n";
      $string .= "<coordinates>\n";
      
      foreach ($this->outerBoundary as $point) {
         $string .= $point[0].','.$point[1].','.$point[2]." \n";
      }

      $string .= "</coordinates>\n";
      $string .= "</LinearRing>\n";
      $string .= "</outerBoundaryIs>\n";
      $string .= "</Polygon>\n";

      return $string;
   }
}



/**
  * Polygon KML 
  *
  * @package kml
  *
  */  
class KMLMultiGeometry extends KMLGeometry {
   private $geometries;

   public function __construct() {
      $this->geometries   = array();
   }
   
   public function __toString() {
      $string = '';

      $string = "<MultiGeometry>\n";

      foreach ($this->geometries as $geometry) {
         $string .= $geometry->__toString();
      }
      
      $string = "</MultiGeometry>\n";
      
      return $string;
   }
   
   public function addGeometry($geometry) {
      $this->geometries[] = $geometry;
   }
}



/**
  * StyleSelector KML 
  *
  * @package kml
  *
  */
class KMLStyleSelector extends KMLObject {
   public function __construct($type, $id = '') {
      parent::__construct($type, $id);
   }
}

/**
  * Style KML 
  *
  * @package kml
  *
  */
class KMLStyle extends KMLStyleSelector {
   private $IconStyle;
   private $LabelStyle;
   private $LineStyle;
   private $PolyStyle;
   private $BalloonStyle;
   
   
   public function __construct($id = '') {
   
      parent::__construct('Style', $id);
   }
   
   public function __toString() {
      $string = '';
      $string .= KMLObject::headerToString();
      
      if (!empty($this->IconStyle)) {
         $string .= "<IconStyle>\n";

         if (!empty($this->IconStyle['color'])) {
            $string .= "<color>".$this->IconStyle['color']."</color>\n";
         }
         
         if (!empty($this->IconStyle['colorMode'])) {
            $string .= "<colorMode>".$this->IconStyle['colorMode']."</colorMode>\n";
         }

         if (!empty($this->IconStyle['scale'])) {
            $string .= "<scale>".$this->IconStyle['scale']."</scale>\n";
         }

         if (!empty($this->IconStyle['icon'])) {
            $string .= "<Icon>\n";
            $string .= "<href>".$this->IconStyle['icon']."</href>\n";
            $string .= "</Icon>\n";
         }

         $string .= "</IconStyle>\n";
      }
      
      
      
      if (!empty($this->LabelStyle)) {
         $string .= "<LabelStyle>\n";

         if (!empty($this->LabelStyle['color'])) {
            $string .= "<color>".$this->LabelStyle['color']."</color>\n";
         }
         
         if (!empty($this->LabelStyle['colorMode'])) {
            $string .= "<colorMode>".$this->LabelStyle['colorMode']."</colorMode>\n";
         }

         if (!empty($this->LabelStyle['scale'])) {
            $string .= "<scale>".$this->LabelStyle['scale']."</scale>\n";
         }

         $string .= "</LabelStyle>\n";
      }
      

      if (!empty($this->PolyStyle)) {
         $string .= "<PolyStyle>\n";

         if (!empty($this->PolyStyle['color'])) {
            $string .= "<color>".$this->PolyStyle['color']."</color>\n";
         }
         
         if (!empty($this->PolyStyle['colorMode'])) {
            $string .= "<colorMode>".$this->PolyStyle['colorMode']."</colorMode>\n";
         }

         if (!empty($this->PolyStyle['fill'])) {
            if ($this->PolyStyle['fill'] === true) {
               $string .= "<fill>1</fill>\n";
            } else {
               $string .= "<fill>0</fill>\n";
            }
         }

         if (!empty($this->PolyStyle['outline'])) {
            if ($this->PolyStyle['outline'] === true) {
               $string .= "<outline>1</outline>\n";
            } else {
               $string .= "<outline>0</outline>\n";
            }
         }

         $string .= "</PolyStyle>\n";
      }
      
      
      
      if (!empty($this->LineStyle)) {
         $string .= "<LineStyle>\n";

         if (!empty($this->LineStyle['color'])) {
            $string .= "<color>".$this->LineStyle['color']."</color>\n";
         }
         
         if (!empty($this->LineStyle['colorMode'])) {
            $string .= "<colorMode>".$this->LineStyle['colorMode']."</colorMode>\n";
         }

         if (!empty($this->LineStyle['width'])) {
            $string .= "<width>".$this->LineStyle['width']."</width>\n";
         }

         $string .= "</LineStyle>\n";
      }

      if (!empty($this->BalloonStyle)) {
         $string .= "<BalloonStyle>\n";

         if (!empty($this->BalloonStyle['bgColor'])) {
            $string .= "<bgColor>".$this->BalloonStyle['bgColor']."</bgColor>\n";
         }
         
         if (!empty($this->BalloonStyle['textColor'])) {
            $string .= "<textColor>".$this->BalloonStyle['textColor']."</textColor>\n";
         }
         
         if (!empty($this->BalloonStyle['text'])) {
            $string .= "<text>".utf8_encode($this->BalloonStyle['text'])."</text>\n";
         }
         
         if (!empty($this->BalloonStyle['displayMode'])) {
            $string .= "<displayMode>\n";
            $string .= "<![CDATA[\n";
            $string .= utf8_encode($this->BalloonStyle['displayMode']) . "\n";
            $string .= "]]>\n";
            $string .= "</displayMode>\n";
         }
         

         $string .= "</BalloonStyle>\n";
      }
      
      $string .= KMLObject::footerToString();
      return $string;

   }
   
   
   public function setIconStyle($icon = '', $color = '', $colorMode = 'normal', $scale = 1) {
      $this->IconStyle = Array(
                              'icon'       => $icon,
                              'color'      => $color,
                              'colorMode'  => $colorMode,
                              'scale'      => $scale,
                           );
   }
   
   public function setLabelStyle ($color = '', $colorMode = 'normal', $scale = 1) {
      $this->LabelStyle = Array(
                              'color'      => $color,
                              'colorMode'  => $colorMode,
                              'scale'      => $scale,
                           );
   
   }
 
   public function setPolyStyle ($color = '', $colorMode = 'normal', $fill = true, $outline = true) {
      $this->PolyStyle = Array(
                              'color'      => $color,
                              'colorMode'  => $colorMode,
                              'fill'       => $fill,
                              'outline'    => $outline
                           );
   
   }
 
   public function setLineStyle ($color = '', $colorMode = 'normal', $width = 1) {
      $this->LineStyle = Array(
                              'color'      => $color,
                              'colorMode'  => $colorMode,
                              'width'    => $width
                           );
   
   }
 
   public function setBalloonStyle ($text = '', $textColor = '', $bgColor = '', $displayMode = 'default') {
      $this->BalloonStyle = Array(
                              'text'        => $text,
                              'textColor'   => $textColor,
                              'bgColor'     => $bgColor,
                              'displayMode' => $displayMode
                           );
 
   } 
 
}

/**
  * StyleSelector KML 
  *
  * @package kml
  *
  */
class KMLTimePrimitive extends KMLObject {
   public function __construct($type, $id = '') {
      parent::__construct($type, $id);
   }
}

/**
  * TimeStamp KML 
  *
  * @package kml
  *
  */
class KMLTimeStamp extends KMLTimePrimitive {
   private $timestamp;   
   
   public function __construct($id = '', $timestamp) {
      $this->timestamp = $timestamp;
      parent::__construct('TimeStamp', $id);
   }
   
   public function __toString() {
      $string = '';
      $string .= KMLObject::headerToString();
  
      $string .= "<when>".$this->timestamp."</when>\n";
  
      $string .= KMLObject::footerToString();
      return $string;

   }
   
   /**
     * Test date format
     *
     * @param string date Date to test
     * @result boolean Result of the test (boolean => isDate)
     */
   public function isDate ($date) {
      //A 
      if (ereg("^([0-9]{4})(-[0-9]{2}(.*))?", $date)) {
         return true;
      } else {
         return false;
      }
   }
}


/**
  * TimeSpan KML 
  *
  * @package kml
  *
  */
class KMLTimeSpan extends KMLTimePrimitive {
   private $begintime;   
   private $endtime;   
   
   public function __construct($id = '', $begintime = '', $endtime = '') {
      $this->begintime = $begintime;
      $this->endtime   = $endtime;
      parent::__construct('TimeStamp', $id);
   }
   
   public function __toString() {
      $string = '';
      $string .= KMLObject::headerToString();
  
      if (!empty($this->$begintime)) {      
         $string .= "<begin>".$this->begintime."</begin>\n";
      }
      if (!empty($this->$endtime)) {      
         $string .= "<begin>".$this->endtime."</begin>\n";
      }
  
      $string .= KMLObject::footerToString();
      return $string;

   }
}




?>