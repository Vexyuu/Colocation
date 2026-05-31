# 🏡 CoLive — Guide & Informations de Référence

Ce document répertorie toutes les étapes et informations indispensables pour installer, configurer, exécuter et tester la plateforme **CoLive** localement.

---

## 🛠️ Guide d'Installation Étape par Étape

### 1. 📦 Installation des dépendances
Avant tout, commencez par installer les dépendances PHP requises par le projet :
```bash
composer install
```

### 2. 💾 Configuration de la Base de Données (MySQL / WampServer)
Exécutez les commandes suivantes dans l'ordre pour initialiser et configurer proprement votre base de données locale.

#### A. Création de la base de données
```bash
symfony php bin/console doctrine:database:create
```

#### B. Application des migrations (Schéma de table)
```bash
symfony php bin/console doctrine:migrations:migrate --no-interaction
```

#### C. Chargement du jeu de données initial (Fixtures)
> [!IMPORTANT]
> Cette commande purge la base de données existante et charge le jeu d'essai complet (propriétaire, locataires, appartement, chambres, factures et corvées hebdomadaires).
```bash
symfony php bin/console doctrine:fixtures:load --no-interaction
```

### 3. 🚀 Lancement du Serveur de Développement
Pour lancer le serveur web local de développement et accéder à l'application :
```bash
symfony server:start
```
L'application est ensuite accessible sur votre navigateur à l'adresse locale indiquée par la console (généralement `http://127.0.0.1:8000`).

---

## 🔑 Comptes de Test (Jeu de Données)

Le jeu de données (Fixtures) génère automatiquement **4 comptes** prêts à l'emploi (avec le mot de passe générique `password`).

| Rôle / Type | Prénom & Nom | Adresse Email | Mot de passe | Tantième |
| :--- | :--- | :--- | :--- | :--- |
| **Propriétaire (Bailleur)** | Jean Dupond | `proprietaire@colive.fr` | `password` | *N/A (Gestionnaire)* |
| **Locataire 1** | Alex A | `alex@colive.fr` | `password` | **15.00 %** (Chambre A - 15m²) |
| **Locataire 2** | Blake B | `blake@colive.fr` | `password` | **12.00 %** (Chambre B - 12m²) |
| **Locataire 3** | Charlie C | `charlie@colive.fr` | `password` | **18.00 %** (Chambre C - 18m²) |

---

## 🌐 Système de Traduction (Internationalisation i18n)

CoLive est entièrement bilingue (Français/Anglais). Utilisez les commandes suivantes pour gérer et mettre à jour les fichiers de traduction.

### Extraction automatique des nouvelles clés
Analyse les templates Twig pour identifier les filtres `|trans` et met à jour les fichiers de dictionnaires YAML sans écraser vos traductions existantes.
```bash
# Pour le français
symfony php bin/console translation:extract fr --force --format=yaml

# Pour l'anglais
symfony php bin/console translation:extract en --force --format=yaml
```

### Synchronisation forcée des dictionnaires
```bash
# Pour le français
symfony php bin/console translation:update fr --force --format=yaml

# Pour l'anglais
symfony php bin/console translation:update en --force --format=yaml
```

---

## 🧪 Tests Automatisés (PHPUnit)

Pour lancer la suite de tests unitaires et fonctionnels (contrôle des tantièmes, formulaires et sécurité) :

### 1. Migrer la base de données de test
```bash
symfony php bin/console doctrine:migrations:migrate --env=test --no-interaction
```

### 2. Charger les données de test dans l'environnement de test
```bash
symfony php bin/console doctrine:fixtures:load --env=test --no-interaction
```

### 3. Lancer la suite de tests
```bash
symfony php vendor/bin/phpunit
```

---

## 🍃 Optimisations Éco-conception (Green IT)
- **Zéro JS lourd** : Le planning (Semainier) utilise une grille CSS native ultra-légère.
- **Cache HTTP** : Activé pour 60 secondes sur la page d'accueil pour soulager le serveur.
- **Requêtes optimisées** : Jointures Doctrine SQL explicites pour éradiquer les requêtes N+1.
- **Impression Éco-PDF** : Utilise les CSS `@media print` pour les quittances, évitant le recours à une lourde bibliothèque PHP.
