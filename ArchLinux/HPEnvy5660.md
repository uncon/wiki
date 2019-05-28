# HP Envy 5660 Series

1. Install HPLIP

        sudo pacman -Sy hplip

1. Add Printer to CUPS via [CUPS Administration](http://localhost:631/admin/)

    * **Name**: HP_ENVY_5660
    * **Description**: HP ENVY 5660
    * **Connection**: socket://envy5660.lan
