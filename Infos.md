# 🏡 CoLive — Guide & Informations de Référence

Ce document répertorie toutes les informations indispensables pour configurer, exécuter et tester la plateforme **CoLive** localement.

---

## 📦 Installation des dépendances

Avant de configurer la base de données, installez les dépendances du projet :
```bash
composer install
```

---

## 🔑 Comptes de Test (Fixtures)

Le jeu de données fictif (fixtures) génère automatiquement **4 comptes** prêts à l'emploi (avec le mot de passe générique `password`).

| Rôle / Type | Prénom & Nom | Adresse Email | Mot de passe | Tantième |
| :--- | :--- | :--- | :--- | :--- |
| **Propriétaire (Bailleur)** | Jean Dupond | `proprietaire@colive.fr` | `password` | *N/A (Gestionnaire)* |
| **Locataire 1** | Alex A | `alex@colive.fr` | `password` | **15.00 %** (Chambre A - 15m²) |
| **Locataire 2** | Blake B | `blake@colive.fr` | `password` | **12.00 %** (Chambre B - 12m²) |
| **Locataire 3** | Charlie C | `charlie@colive.fr` | `password` | **18.00 %** (Chambre C - 18m²) |

---

## 💾 Commandes pour la BDD MySQL (WampServer)

Exécutez ces commandes dans l'ordre pour réinitialiser et configurer proprement votre base de données locale.

### 1. Création de la BDD
```bash
symfony php bin/console doctrine:database:create
```

### 2. Application des migrations (Schéma de table)
```bash
symfony php bin/console doctrine:migrations:migrate --no-interaction
```

### 3. Chargement des données de test (Fixtures)
> [!IMPORTANT]
> Cette commande purge la base de données existante et charge le jeu d'essai ci-dessus (1 propriétaire, 3 locataires, 1 appartement, chambres, factures et tâches hebdomadaires).
```bash
symfony php bin/console doctrine:fixtures:load --no-interaction
```

---

## 🌐 Système de Traduction (Internationalisation i18n)

CoLive est bilingue (Français/Anglais). Utilisez les commandes suivantes pour gérer les fichiers de traduction.

### Extraction automatique des nouvelles clés
Analyse les templates Twig pour identifier les filtres `|trans` et met à jour les fichiers de dictionnaires YAML sans supprimer les traductions existantes.
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

### 2. Lancer la suite de tests
```bash
symfony php vendor/bin/phpunit
```

---

## 🍃 Optimisations Éco-conception (Green IT)
- **Zéro JS lourd** : Le planning (Semainier) utilise une grille CSS native.
- **Cache HTTP** : Activé pour 60 secondes sur la page d'accueil pour soulager le serveur.
- **Requêtes optimisées** : Jointures Doctrine SQL explicites pour éradiquer les requêtes N+1.
- **Impression Éco-PDF** : Utilise les CSS `@media print` pour les quittances, évitant le recours à une lourde bibliothèque PHP.
