Tethys (Beta)
=============

Version 0.17.16

Database-Version: 22

TODO:
* Widgets
* LDAP-Authentifizierung
* Upload-UI
* Generische members_direct-Tabelle

BUGS:
* Hauptmenü-Datei bei der Installation falsch angegeben->Installer nicht mehr zu retten
* config_start in shared-verzeichnis!

Installation unter Linux
------------------------
cd /var/www
sudo chown -Rf [OWNER]:www-data html
git clone https://github.com/GitFabian/TethysBeta.git html/tethys
sudo chown -Rf [OWNER]:www-data html/tethys
