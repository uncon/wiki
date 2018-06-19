# Mp3tag Settings

## Filename

	..\Sorted\$if(%albumartist%,%albumartist%,UNKNOWN)\$if(%albumartist%,%albumartist%,UNKNOWN) - $if(%album%,%album%,UNKNOWN)\$if(%discnumber%,$num(%discnumber%,2)-,)$num(%track%,2) - %title%

## Action
### Clean Tags
Remove fields except

	ALBUM; ALBUMARTIST; ARTIST; DISCNUMBER; GENRE; PICTURE; TITLE; TRACK; YEAR; PUBLISHER; CATALOGID
