Tethys (Beta)
=============

Version 0.25.1

Database-Version: 25

TODO:
* Widgets (in arbeit)
* LDAP-Authentifizierung
* Upload-UI
* Generische members_direct-Tabelle
* Feeds
* Form-Check (Prüf-Funktion übergeben)

TODO Version 1.0.0:
- Build-in gantt raus
- Wap css
- Demo Modul: (Beispiel)php in die GUI
- Liste der aufzuräumenden Dateien

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
