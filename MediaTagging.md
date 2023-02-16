# Media Tagging

## MusicBrainz Picard

### File Naming Script
**Title**: `[album artist]/[album] [[musicbrainz_albumid]]/[disc and track #] [artist] - [title]`
```
$if2(%albumartist%,%artist%,[unknown])/
$if(%albumartist%,%album%$if(%musicbrainz_albumid%, [%musicbrainz_albumid%])/,)
$if($gt(%totaldiscs%,1),%discnumber%-,)
$num(%tracknumber%,2) 
$if(%_multiartist%,%artist% - ,)
%title%
```

## Mp3tag

### Filename
```
..\Sorted\$if(%albumartist%,%albumartist%,UNKNOWN)\$if(%albumartist%,%albumartist%,UNKNOWN) - $if(%album%,%album%,UNKNOWN)\$if(%discnumber%,$num(%discnumber%,2)-,)$num(%track%,2) - %title%
```

### Action: Clean Tags
* Remove fields except: `ALBUM; ALBUMARTIST; ARTIST; DISCNUMBER; GENRE; PICTURE; TITLE; TRACK; YEAR; PUBLISHER; CATALOGID`
