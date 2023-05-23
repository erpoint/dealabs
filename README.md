Docker
--------------
*  Copier l'ensemble des fichiers relatifs à Docker à la racine de votre projet Symfony

*  Builder l'image : `docker-compose build`
*  Lancer les conteneurs : `make start_dev`
*  Arrêter les conteneurs : `make stop_dev`
*  Accéder au container PHP en tant que root : `docker exec -it -u root lpa_sf6_php bash`

*  Apache : `http://localhost:8081`
*  PhpMyAdmin : `http://localhost:8090`
*  Mailcatcher - Interface web : `http://localhost:1081`, Port SMTP : `1026`
*  MySQL : Port `3310`