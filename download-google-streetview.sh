#!/bin/sh

#panoid="Xh8vS2N9htsuhLXcbTs_rQ"
#wget "http://cbk1.google.com/cbk?output=xml&panoid=${panoid}&cb_client=maps_sv" -O "${panoid}.xml"
## For Zoom level 2: x = 0-3, y = 0-1 ::== 4x2 = 8 images
## For Zoom level 3: x = 0-6, usuable y = 0-2 ::== 7x3 = 21 images
#for zoom in 1; do
#  for x in 0 1 2; do
#    for y in 0 1; do
#      echo -n "-> ${panoid}_zoom-${zoom}_x-${x}_y-${y}.jpg ..."
#      wget -q "http://cbk1.google.com/cbk?output=tile&panoid=${panoid}&zoom=${zoom}&x=${x}&y=${y}&cb_client=maps_sv" \
#        -O "${panoid}_zoom-${zoom}_x-${x}_y-${y}.jpg"  && echo "ok." || echo "fail!"
#    done
#  done
#done

get_zoom2_image() {
  panoid="$1"
  zoom=2
  for x in 0 1 2 3; do
    for y in 0 1; do
      echo -n "-> ${panoid}_zoom-${zoom}_y-${y}_x-${x}.jpg ..."
      wget -q -c "http://cbk1.google.com/cbk?output=tile&panoid=${panoid}&zoom=${zoom}&x=${x}&y=${y}&cb_client=maps_sv" \
        -O "${panoid}_zoom-${zoom}_y-${y}_x-${x}.jpg"  && echo "ok." || echo "fail!"
    done
  done
  echo -n "Assembling panorama... "
  convert +append ${panoid}_zoom-${zoom}_y-0_*.jpg "${panoid}_zoom-${zoom}_y-0_full.jpg" && \
  convert +append ${panoid}_zoom-${zoom}_y-1_*.jpg "${panoid}_zoom-${zoom}_y-1_full.jpg" && \
  convert -append ${panoid}_zoom-${zoom}_y-?_full.jpg "${panoid}_zoom-${zoom}_full.jpg" && \
    echo "done." || echo "fail!"
}

get_zoom3_image() {
  panoid="$1"
  zoom=3
  for x in 0 1 2 3 4 5 6; do
    for y in 0 1 2; do
      echo -n "-> ${panoid}_zoom-${zoom}_y-${y}_x-${x}.jpg ..."
      wget -q -c "http://cbk1.google.com/cbk?output=tile&panoid=${panoid}&zoom=${zoom}&x=${x}&y=${y}&cb_client=maps_sv" \
        -O "${panoid}_zoom-${zoom}_y-${y}_x-${x}.jpg"  && echo "ok." || echo "fail!"
    done
  done
  echo -n "Assembling panorama... "
  convert +append ${panoid}_zoom-${zoom}_y-0_*.jpg "${panoid}_zoom-${zoom}_y-0_full.jpg" && \
  convert +append ${panoid}_zoom-${zoom}_y-1_*.jpg "${panoid}_zoom-${zoom}_y-1_full.jpg" && \
  convert +append ${panoid}_zoom-${zoom}_y-2_*.jpg "${panoid}_zoom-${zoom}_y-2_full.jpg" && \
  convert -append ${panoid}_zoom-${zoom}_y-?_full.jpg "${panoid}_zoom-${zoom}_full.jpg" && \
    echo "done." || echo "fail!"
}

get_full_image() {
  panoid="$1"
  wget -nv "http://cbk1.google.com/cbk?output=xml&panoid=${panoid}&cb_client=maps_sv" -O "${panoid}.xml"
  wget -q "http://cbk1.google.com/cbk?output=tile&panoid=${panoid}&zoom=0&x=0&y=0&cb_client=maps_sv" \
        -O "${panoid}_full_image.jpg"  && echo "ok." || echo "fail!"
}

prepare_frame(){
  panoid="$1"
  roll_x="$2"
  #convert "${panoid}_zoom-2_full.jpg" -crop 1664x650+0+137 "${panoid}_zoom-${zoom}_fullcircle.jpg"
  #convert "${panoid}_zoom-${zoom}_fullcircle.jpg" -roll "${roll_x}+0" -crop 1400x650+133+0 "${panoid}_frame.jpg"
  convert "${panoid}_zoom-2_full.jpg" -crop 1664x650+0+137 -roll "${roll_x}+0" +repage -crop 1400x650+133+0 "${panoid}_frame.jpg"
}

#for p in PgDnHASpMcK8OJHDNTp2ew XtY4EVtrM0u8nT1RbF4Mow 6qoO2c7IWn1lz2iDJe5tuA; do
for p in mUVKNox0SmldTLn9QKpqtQ 092ssWicGAazjm9YuG3xqg; do
  #get_full_image $p
  #get_zoom2_image $p
  #get_zoom3_image $p
  prepare_frame $p -416
done
