#!/bin/sh

prefix="$1"
panoid="$2"
roll_x="$3"
text="$4"
force="$5"

if [ -z "$text" ]; then
  echo 
  echo "Usage: `basename $0` prefix panoid roll_x text [force]"
  echo 
	echo "       Use the 'force' parameter to force an update of the frame"
  echo 
  exit 1
fi

tiledir="tiles"
stitchdir="stitched"
framedir="frames"

mkdir -vp $tiledir $stitchdir $framedir


framefile="${framedir}/${prefix}_${panoid}_frame.jpg"
 
if [ "$force" != "force-all" -a "$force" != "force" -a -s "$framefile" ]; then
	echo "Assembling frame $framefile ... (cached)"
	exit 
fi

zoom=2
echo -n "tiles: "
for x in 0 1 2 3; do
  for y in 0 1; do
    echo -n "${x}x${y}: "
    filename="${tiledir}/tile_${panoid}_zoom-${zoom}_y-${y}_x-${x}.jpg"
    if [ -s "$filename" ]; then
      echo -n "c. "
    else
      # Afraid we need to download it
      echo "d..."
      echo -n "-> $filename ... "
      wget -q -c "http://cbk1.google.com/cbk?output=tile&panoid=${panoid}&zoom=${zoom}&x=${x}&y=${y}&cb_client=maps_sv" \
        -O "$filename"  && echo "ok." || echo "fail!"
    fi
  done
done
echo
echo -n "Assembling frame $framefile ... "

y0file="${stitchdir}/full_${panoid}_zoom-${zoom}_y-0.jpg"
y1file="${stitchdir}/full_${panoid}_zoom-${zoom}_y-1.jpg"
fullfile="${stitchdir}/full_${panoid}_zoom-${zoom}.jpg"

echo -n "y0 ... "
if [ "$force" != "force-all" -a -s "$y0file" ]; then
	echo -n "(c) "
else
  nice convert +append ${tiledir}/tile_${panoid}_zoom-${zoom}_y-0_*.jpg "$y0file" && \
    echo -n "done. " || echo "fail! "
fi

echo -n "y1 ... "
if [ "$force" != "force-all" -a -s "$y1file" ]; then
	echo -n "(c) "
else
  nice convert +append ${tiledir}/tile_${panoid}_zoom-${zoom}_y-1_*.jpg "$y1file" && \
    echo -n "done. " || echo "fail! "
fi

echo -n "full ... "
if [ "$force" != "force-all" -a -s "$fullfile" ]; then
	echo -n "(c) "
else
  nice convert -append ${stitchdir}/full_${panoid}_zoom-${zoom}_y-?.jpg "$fullfile" && \
    echo -n "done. " || echo "fail! "
fi

#convert "full_${panoid}_zoom-${zoom}.jpg" -crop 1664x650+0+137 +repage \
#  -gravity southeast -font Helvetica -box black -fill white -draw "text 20,10 \" ${prefix} | (c) $(date +"%Y")  Google \"" \

if [ "$roll_x" -gt -20 -a "$roll_x" -lt 20 ]; then
  dontroll=1
elif [ "$roll_x" -gt 1640 -a "$roll_x" -lt 1690 ]; then
  dontroll=1
else
  dontroll=0
fi

#FINALCROP="1400x525+133+0"      #Maximum "usuable area"
FINALCROP="928x522+368+0"      # Maximum 16:9 aspect area
if [ "$dontroll" = 1 ]; then
  nice convert "${stitchdir}/full_${panoid}_zoom-${zoom}.jpg" -crop 1664x650+0+155 +repage \
    -crop "$FINALCROP" +repage \
    -gravity southwest -font Helvetica -box black -fill white -draw "text 20,10 \" ${text} \"" \
    "$framefile" && \
    echo " frame $prefix done." || echo "fail!"
  
else
  nice convert "${stitchdir}/full_${panoid}_zoom-${zoom}.jpg" -crop 1664x650+0+155 -roll "${roll_x}+0" +repage \
    -crop "$FINALCROP" +repage \
    -gravity southwest -font Helvetica -box black -fill white -draw "text 20,10 \" ${text} \"" \
    "$framefile" && \
    echo " frame $prefix done." || echo "fail!"
fi
