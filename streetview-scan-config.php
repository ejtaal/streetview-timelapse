<?

//======== template =================================
/*
$choices[""] = array( 
  'title' => "",
  'short_title' => "",
          'maxy' => , 
                /* +----+ */
#  'minx' =>    ,/* |    | */ 'maxx' => , 
                /* +----+ */
/*          'miny' => , 
  'waver_degree' => 30,
  'panoid' => "tb-lTtsgpijuiNPoTue4dg",
	// This allows you to forcibly skip certain panoid, this may often
	// be quicker than creating an override-link
  'blacklist' => array(""),
  'next_yaw_deg' => 57,
  'next_pano' => "50cMSOdLCwiP6eowFd_eJQ",

	/*
	Sometimes a wrong panoid is selected, for instance at a highway exit
	so if that happens trace back the last known good panoid, make it the
	the key of the 'id'/'yaw' subarray, then get the next panoid on the
	right path, put that in the id=> string, and usually you can copy the
	yaw value as the yaw to the last known good panoid. This situation also
	occurs when a dead end is encountered, as sometimes a pano is missing
	along a long road so you have to then manually tell it the next panoid
	to follow.
	*/
  //'override_link' => array(
  //  "" => array( "id" => "", "yaw" =>  ),
  //)
//);

//===================================================

/*
TODO: 
  - ayers rock (no views available yet)
  v monument valley
  - grand canyon
  - Oz outback
  - Viaduc de Millau
  - M6 through Lake District
  - Akashi Kaikyo bridge, jp
  v US1, highway over the keys
  v Interstate 70, Colorado & Utah section
	- tokyo bay aqua line
*/
/*
		315			0    45
		270			x	     90
		225   180  	 135


*/

/* var_dump($argv);
$panoid = $argv[1];
$minx = $argv[2];
$maxx = $argv[3]; 
$miny = $argv[4]; 
$maxy = $argv[5]; 
*/

//======== template =================================
$choices["ld3"] = array( 
  'title' => "Lake district 3, ",
  'short_title' => "ld3",
          'maxy' => 54.6, 
                /* +----+ */
  'minx' =>    -10,/* |    | */ 'maxx' => 10, 
                /* +----+ */
          'miny' => 40, 
  'waver_degree' => 74,
  'panoid' => "OAL_X4nfWTWKHXRB43KJew",
	// This allows you to forcibly skip certain panoid, this may often
	// be quicker than creating an override-link
  'blacklist' => array(""),
  'next_yaw_deg' => 38,
  'next_pano' => "zzgsaI2ZgnbPB66atLRu1A",

  'override_link' => array(
    "VT0HZ-lAMChhnCVMQ5SCnQ" => array( "id" => "hW0tExd_xAixeHku5npopA", "yaw" => 335 ),
  )
);

//======== template =================================
$choices["ld2"] = array( 
  'title' => "Lake district 2, B5289",
  'short_title' => "ld2",
          'maxy' => 54.6, 
                /* +----+ */
  'minx' =>    -10,/* |    | */ 'maxx' => 10, 
                /* +----+ */
          'miny' => 40, 
  'waver_degree' => 48,
  'panoid' => "FCCKSm9kPOk1HD9FvcARmA",
	// This allows you to forcibly skip certain panoid, this may often
	// be quicker than creating an override-link
  'blacklist' => array(""),
  'next_yaw_deg' => 180,
  'next_pano' => "gTljoVMHiBYBSK1uBFj_IA",

  'override_link' => array(
    //"" => array( "id" => "", "yaw" =>  ),
  )
);

//======== template =================================
$choices["ld1"] = array( 
  'title' => "Lake district 1, A592",
  'short_title' => "ld1",
          'maxy' => 54.6, 
                /* +----+ */
  'minx' =>    -10,/* |    | */ 'maxx' => 10, 
                /* +----+ */
          'miny' => 40, 
  'waver_degree' => 38,
  'panoid' => "AkFcvyK0bITEQe4VFdaZfw",
	// This allows you to forcibly skip certain panoid, this may often
	// be quicker than creating an override-link
  'blacklist' => array("b1SEgNwp7LcSTmarGGbG-Q"),
  'next_yaw_deg' => 0,
  'next_pano' => "_3n9rywdkPktl9QElc2wVg",

  'override_link' => array(
    "R0T6J4rXyxvtlsC1X9Kl8Q" => array( "id" => "LVhU0ThbSQAyrTgEwUfWPg", "yaw" => 282 ),
    "yTzYkh0OogXxwtGXDJbdMw" => array( "id" => "TEV6AVoqX8wAFwh2Qn-_3w", "yaw" => 319 ),
    "Q89e9c2XCHHIkhoBP1nwUw" => array( "id" => "Y4D59roRkOip98fMv7mKpg", "yaw" => 302 ),
    "_jbGO2qPvyJW9wuXobmDmA" => array( "id" => "WEwVl068Ol-THuz5OzejJg", "yaw" => -88 ),
    "KtLdG_RReC_jbo-tgN7s2Q" => array( "id" => "i2HiMbS4IHnsMHpy4H7gyg", "yaw" => 348 ),
  )
);

//=========== Stuart Highway, Australia: ============
// Starting in Darwin
/*
$maxy = -12.40; 
$minx = 130.825; $maxx = 132; 
        $miny = -13.00; 
$waver_degree = 30;
$panoid = "dRwwJN0adGEiPCBLhamIlQ";
$blacklist = array("vU8-UUUNAeyQrMjPJzPv1w", "RsjTzb_O68XzQpdKDnsEyw");
*/
//===================================================

//=========== Monument Valley, USA: =================
$choices["mv"] = array( 
  'title' => "US-163, Monument Valley, USA",
  'short_title' => "mv",
    'maxy' => 37.3, 
  'minx' => -110.3, 'maxx' => -109.7,
    'miny' => 36.7,
  'waver_degree' => 30,
  'panoid' => "q0An2c_zNsAgUPxdHdTcGg",
  'blacklist' => array("Fq6TeLtgbICvzzTv-_6WBA"),
  'override_link' => array(),
  'next_yaw_deg' => 48,
  'next_pano' => 'g3hcEuyldSe7YmL9ejcx5g',
  );
//===================================================

//=========== US 1, USA: =================
$choices["us1"] = array( 
  'title' => "US 1, USA (Florida Keys Highway)",
  'short_title' => "us1",
        'maxy' => 25.3,
'minx' => -81.85, 'maxx' => -80.3,
        'miny' => 24.5,
'waver_degree' => 37,
'panoid' => "bOC019Cotx3D30DJJTstrg",
'blacklist' => array(""),
'next_yaw_deg' => 180,
'next_pano' => "3gatmj3JZAw4F2Yocb4opw",
'override_link' => array(
  "EuTtNwSh2Dofa-6Fm3L_Bg" => array( "id" => "ktH9Dj4Nq_7i7QJeHwlsFQ", "yaw" => 225 ),
  "iaPx7NxRBWOcr6Vb5wboUA" => array( "id" => "8-5FdCSAlkjhvfzg7hZzIQ", "yaw" => 247 ),
  "YUGIjzwkZt8TTopAfWwD6Q" => array( "id" => "Lls8A3QY9eRMM8aODf2YKw", "yaw" => 247 ),
  "qQ1BPd9MuIcskMrQy0rQAg" => array( "id" => "ct38h67RWQcooJE2Sz4o8g", "yaw" => 261 ))
  );

//===================================================

//=========== I-70, USA: =================
$choices["i70"] = array( 
  'title' => "I 70, Utah/Colorado",
  'short_title' => "i70",
          'maxy' => 40, 
  'minx' => -113,   'maxx' => -105.1, 
          'miny' => 38.5, 
  'waver_degree' => 39,
  'panoid' => "w9LzpaZj0BEBbg7tVeYfvQ",
  'blacklist' => array("5HtPVzWlsEcyAY0Dm59MmA","lP5jia4E1_vMgr722L-eow","V5Q6H4ml52JmE2qmgIKjXg","qUV_TqaqNJkQeQ4OJh0M3g"),
  'next_yaw_deg' => 57,
  'next_pano' => "50cMSOdLCwiP6eowFd_eJQ",

  'override_link' => array(
    "xjok0q_XyitS4YaLeW4rew" => array( "id" => "T5X73a_rfufqz5eiAVovyg", "yaw" => 39 ),
    "90d1B9YE6HXtB4dS0YusCA" => array( "id" => "2bFvUJ0YkAon7rcPV7SdWg", "yaw" => 45 ),
    "gT9O8oGbwHSg5fAydhLaXg" => array( "id" => "wKAkz3Wu0Ln58ZLQOYM5BA", "yaw" => 42 ),
    "g8PtGiqyyy56NRsmEICsEg" => array( "id" => "5Os6QDSOMxWCEDvvGKPpJA", "yaw" => 90 ),
    "Vd4n6nMQMQIfU8ydmOl2gA" => array( "id" => "xi9dtN8NK8cZzN3MuiuxVA", "yaw" => 57 ),
    "qHpSM2ir9ukclVOa60ZVlw" => array( "id" => "VG4UsmSQCWNZcvEN253SoA", "yaw" => 89 ),
    "7rDvnCkDqt4zeXDC0wgQdg" => array( "id" => "GrnyfQLkCvbVt7MNJSp87A", "yaw" => 76 ),
    "U1RYBmkAahi7TpecLKbdBA" => array( "id" => "8Q6vAQLSaSKcx9x2Tu3PtA", "yaw" => 105 ),
    "B0LULZZE93gcB1iAWshC8w" => array( "id" => "jSHBrrpsOUYWiuEeWveaBQ", "yaw" => 90 ),
    "TuWDodu8R5EOOGTu9WBO6w" => array( "id" => "VIJOd4armC2SsSDRkA6jVA", "yaw" => 20 ),
    "kPSORJEl6bXz7MWx5LsAlg" => array( "id" => "olCtrzJnpafAJSrruhCOEQ", "yaw" => 81 ),
    "DiaU8o7b0gp_mbvXtCMJBQ" => array( "id" => "sZArq3puB5BJx-_6cckjzg", "yaw" => 102 ),
  )
);

$choices["akb"] = array( 
  'title' => "Akashi Kaikyo bridge, Japan",
  'short_title' => "akb",
          'maxy' => 34.65, 
                /* +----+ */
  'minx' =>135 ,/* |    | */ 'maxx' => 135.04,
                /* +----+ */
          'miny' => 34.57, 
  'waver_degree' => 30,
  'panoid' => "uRAOtFWGgvyfSstAqG0oCQ",
  'blacklist' => array(""),
  'next_yaw_deg' => 52.18,
  'next_pano' => "EL_GCJL5vQpH1wDpskbWmw",
 
  'override_link' => array()
  //'override_link' => array(
  //  "" => array( "id" => "", "yaw" =>  ),
  //)
);

$choices["nbp"] = array( 
  'title' => "90 Mile Straight, Nullabor Plain, Australia",
  'short_title' => "nbp",
          'maxy' => -32, 
                   /* +----+ */
  'minx' => 123.75,/* |    | */ 'maxx' => 126, 
                   /* +----+ */
          'miny' => -32.5, 
  'waver_degree' => 30,
  'panoid' => "X0DQnH7R7tdxQ8AH7T3m8g",
  'blacklist' => array(""),
  'next_yaw_deg' => 231,
  'next_pano' => "fwoug4byURx1j5fntcbSvg",
 
  'override_link' => array(
    "1Zm9d-e2pZ5uYOb1TfHzow" => array( "id" => "4XPkTnJqLuicgkT1YYqwzg", "yaw" => 245 ),
    "NexxVmjvUMf5v333m1MDQg" => array( "id" => "7UZn5bBjcg6-z5r0YPTktA", "yaw" => 242 ),
  //  "" => array( "id" => "", "yaw" =>  ),
  )
);

//======== Longest straight usa road ========================
$choices["lusa"] = array( 
  'title' => "Longest straight road of the USA",
  'short_title' => "lusa",
          'maxy' => 46.7, 
                /* +----+ */
  'minx' =>   -99.5,/* |    | */ 'maxx' => -96.5, 
                /* +----+ */
          'miny' => 46, 
  'waver_degree' => 30,
  'panoid' => "ofCY3QtsJP9JtxF_P0rX9A",
  'blacklist' => array(""),
  'next_yaw_deg' => 24,
  'next_pano' => "CAnSGVE0XGPObv2L6BQvbA",
 
  'override_link' => array(
    "OelamzK7McfMlmHaaKIxxg" => array( "id" => "Ll2q8z7ORucIYrY4epR-EQ", "yaw" => 90 ),
  )
);

//======== Millau N->S ==============================
$choices["millau-ns"] = array( 
  'title' => "Viaduc de Millau (N -> S)",
  'short_title' => "millau-ns",
          'maxy' => 44.20, 
                /* +----+ */
  'minx' => 3,/* |    | */ 'maxx' => 3.05, 
                /* +----+ */
          'miny' => 44, 
  'waver_degree' => 30,
  'panoid' => "CMQDY9MRDHZWA4V4Fiuspg",
  'blacklist' => array(""),
  'next_yaw_deg' => 180,
  'next_pano' => "czyoSlR-YrihZmTe8V5HdA",
 
  'override_link' => array(
    "Rq9gHjxh4QqQedlsNY9HfQ" => array( "id" => "kyJpCVKmu-LIhGGSvrBUXQ", "yaw" => 180 ),
  )
);
//======== Millau S->N ==============================
$choices["millau-sn"] = array( 
  'title' => "Viaduc de Millau (S -> N)",
  'short_title' => "millau-sn",
          'maxy' => 44.20, 
                /* +----+ */
  'minx' => 3,/* |    | */ 'maxx' => 3.05, 
                /* +----+ */
          'miny' => 44, 
  'waver_degree' => 30,
  'panoid' => "YIJhzOFj07iy5Amay0ynTA",
  'blacklist' => array(""),
  'next_yaw_deg' => 315,
  'next_pano' => "bjzsYcyW91RiWNRmUC802Q",
 
  /*
	'override_link' => array(
    "Rq9gHjxh4QqQedlsNY9HfQ" => array( "id" => "kyJpCVKmu-LIhGGSvrBUXQ", "yaw" => 180 ),
  )
	*/
);

//======== Storebaelt Bridge ========================
$choices["storebaelt"] = array( 
  'title' => "Storebaelt (Great Belt) bridge",
  'short_title' => "storebaelt",
          'maxy' => 55.45, 
                /* +----+ */
  'minx' => 10.75,/* |    | */ 'maxx' => 11.20, 
                /* +----+ */
          'miny' => 55.25, 
  'waver_degree' => 30,
  'panoid' => "HXCF5O05xHCDA4nFADVKrA",
  'blacklist' => array(""),
  'next_yaw_deg' => 135,
  'next_pano' => "yMPNmDzkUd03Rxo3qlx7mQ",
 
	'override_link' => array(
    "VMEtW7u3CJ6wyCL02BRoVg" => array( "id" => "PT9JxfpGGXfPOdYGuuAYeg", "yaw" => 71 ),
    "uKiASHZpx87imXpv3HGArQ" => array( "id" => "PHTgmglhN3lcEqczpz8QVg", "yaw" => 71 ),
  )
);
//======== Oresund Bridge ========================
$choices["oresund"] = array( 
  'title' => "Oresund bridge",
  'short_title' => "oresund",
          'maxy' => 55.67, 
                /* +----+ */
  'minx' => 12.5,/* |    | */ 'maxx' => 13, 
                /* +----+ */
          'miny' => 55.5, 
  'waver_degree' => 30,
  'panoid' => "yoLZOBITrlwwjXH3JEYr1g",
  'blacklist' => array(""),
  'next_yaw_deg' => 90,
  'next_pano' => "bwoY442fk6XmWINyKkDw_g",
 
	'override_link' => array(
    "IC0aByPJ5nzQtLJj_IvsOQ" => array( "id" => "wkGwCJcOTyVDWCtEWmd3hA", "yaw" => 90 ),
  )
);

?>
