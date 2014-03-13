# Mp3tag Settings

## Filename
1. Albums

		..\Sorted\$if(%albumartist%,%albumartist%,UNKNOWN)\$if(%year%,$left(%year%,4) - ,)$if(%album%,%album%,UNKNOWN)\$if(%discnumber%,$num(%discnumber%,0)-,)$num(%track%,2) %title%

1. Singles

		..\Singles\%artist% - %title%

1. Record Labels

		..\Record Labels\%publisher%\%catalogid% - %album%\$if(%discnumber%,$num(%discnumber%,0)-,)$num(%track%,2) %title%

## Action
1. Clean Tags
    - Remove fields except

			ALBUM; ALBUMARTIST; ARTIST; DISCNUMBER; GENRE; PICTURE; TITLE; TRACK; YEAR; PUBLISHER; CATALOGID

