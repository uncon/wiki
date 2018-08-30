# Tips for Office for Mac 2011

## Font Sizing
Outlook 2011 (Mac) defines fonts size by px rather than pt.  When Outlook for Windows renders the email, it will convert the px to pt.  This will cause slight variations, as there is not always an exact match.  However, for most purposes, it will be close enough.  As an example, Calibri 14 px becomes 10.5 pt on Windows.

## Clear Settings
It is often desirable to clear all Office preferences and databases (to resolve various issues).

1. Kill all Office processes 

2. Remove files

		mv "${HOME}/Library/Preferences/com.microsoft.*" "${HOME}/Documents/Microsoft User Data" "${HOME}/.Trash"
