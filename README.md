Tethys (Beta)
=============

Version 0.18.1

Database-Version: 23

TODO:
* Widgets (in arbeit)
* LDAP-Authentifizierung
* Upload-UI
* Generische members_direct-Tabelle
* Feeds
* Form-Check (Prüf-Funktion übergeben)

BUGS:
(keine)


Installation unter Linux
------------------------
cd /var/www
sudo chown -Rf [OWNER]:www-data html
git clone https://github.com/GitFabian/TethysBeta.git html/tethys
sudo chown -Rf [OWNER]:www-data html/tethys
Installer aufrufen: http://localhost/tethys
Berechtigungen für neu angelegte Verzeichnisse: sudo chown -Rf [OWNER]:www-data html/tethys
