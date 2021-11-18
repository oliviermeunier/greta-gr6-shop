#Installation

- Cloner le dépôt avec la commande `git clone https://github.com/oliviermeunier/greta-gr6-shop.git Shop`
- Se placer dansle dossier créé `cd Shop`
- Installer les dépendances `composer install` ou `composer update` si besoin. Vous devez avoir la version 7.4 de PHP au minimum.
- Créer un fichier **.env.local** à la racine du projet avec la variable d'environnement `DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"`. Remplacer les informations de connexion à la base de données. 
- Créer la base de données `symfony console doctrine:database:create`
- Lancer les migrations `symfony console doctrine:migrations:migrate`
- Lancer les fixtures `symfony console doctrine:fixtures:load`
- Lancer le serveur `symfony serve` et se rendre sur `127.0.0.1:8000` 

