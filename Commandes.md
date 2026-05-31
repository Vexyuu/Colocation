# 1. Création de la BDD MySQL WampServer
symfony php bin/console doctrine:database:create

# 2. Mise à jour de la BDD MySQL WampServer
symfony php bin/console doctrine:migrations:migrate --no-interaction

# 3. Création des données de test
symfony php bin/console doctrine:fixtures:load
