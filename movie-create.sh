#!/bin/sh


# prepare youtube split 500 kbs files:
find frames/ -maxdepth 1 -type f | sort | split -l 14975 - framelist_


# Collection of all the mencoder flags
# -vf crop=xxx:xxx:xx:xx,scale,harddup 
MENCODER_FLAGS_1="-nosound -of rawvideo -vf scale -zoom -xy 768 -ovc x264 -x264encopts bitrate="
#MENCODER_FLAGS_2=":frameref=6:analyse=all:me=umh:subme=7:trellis=2:bframes=1:subq=7:brdo:mixed_refs:weight_b:bime:no_fast_pskip:direct_pred=auto:mixed_refs:nr=200:threads=auto"
MENCODER_FLAGS_2=":frameref=6:analyse=all:me=umh:subme=7:trellis=2:bframes=1:subq=7:mixed_refs:weight_b:no_fast_pskip:direct_pred=auto:mixed_refs:nr=200:threads=auto"

# 3000 kbs = 22 Mb / min [ 3000*60/(8*1024) ]
# Create a 500 kbs for youtube and 3000 for high quality

# First youtube:
part_num=1
bitrate=500
for part_file in framelist_*; do
	basename="$(basename `pwd`)-${bitrate}-kbs-part-${part_num}";
  
  if [ -f "${basename}.mp4" ]; then
	  echo "Already found: $basename.mp4 ."
  else
	  echo "Rendering part: $basename.mp4 ..."
	  #pass1
	  nice mencoder ${MENCODER_FLAGS_1}${bitrate}${MENCODER_FLAGS_2}:turbo=2:pass=1 -noskip "mf://@${part_file}" -mf fps=25 -o /dev/null
		ls -la divx2pass.log
		#pass2
	  nice mencoder ${MENCODER_FLAGS_1}${bitrate}${MENCODER_FLAGS_2}:pass=2 -noskip "mf://@${part_file}" -mf fps=25 -o "${basename}.264"
		nice mp4creator -rate=25 -a "${basename}.264" "${basename}.mp4" && rm -f "${basename}.264"
	  ls -la "${basename}.mp4"
  fi  
	part_num=$((part_num+1))
done

for bitrate in 3000; do
	basename="$(basename `pwd`)-${bitrate}-kbs";
  #pass1
  nice mencoder ${MENCODER_FLAGS_1}${bitrate}${MENCODER_FLAGS_2}:turbo=2:pass=1 -noskip "mf://frames/*_frame.jpg" -mf fps=25 -o /dev/null
	ls -la divx2pass.log
	#pass2
  nice mencoder ${MENCODER_FLAGS_1}${bitrate}${MENCODER_FLAGS_2}:pass=2 -noskip "mf://frames/*_frame.jpg" -mf fps=25 -o "${basename}.264"
	ls -la "${basename}.264"
	nice mp4creator -rate=25 -a "${basename}.264" "${basename}.mp4" && rm -f "${basename}.264"
  ls -la "${basename}.mp4"
done
