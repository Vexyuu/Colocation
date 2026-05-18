# 🏡 CoLive — Plateforme Collaborative de Gestion de Colocation

> Le couteau suisse numérique d'éco-conception pour propriétaires, gestionnaires et colocataires. Propulsé par **Symfony** & **API Platform**.

---

## 🌟 Vision du Projet

**CoLive** est une application web moderne conçue pour simplifier la vie en colocation. Elle fluidifie la communication entre **locataires (colocataires)** et **propriétaires**, automatise les calculs complexes (notamment la répartition des charges d'électricité au prorata de l'espace occupé), planifie les corvées ménagères et sécurise les échanges financiers et administratifs.

Dans le strict respect des principes du **Green IT**, CoLive propose une interface ultra-rapide, épurée et éco-conçue, garantissant une empreinte carbone minimale tout en offrant une expérience utilisateur (UX) haut de gamme, fluide et adaptative.

---

## 🛠️ Architecture Technique & Engagements Qualité

```mermaid
graph TD
    Client[🎨 Frontend Twig & Vanilla CSS / Modern JS] -->|Requêtes REST / JSON| API[🔌 API Platform / Symfony]
    API -->|ORM Doctrine| DB[(🗄️ Base de Données PostgreSQL / MySQL)]
    API -->|Sécurisation JWT/Session| Security[🔒 Firewalls Symfony & CSRF]
    API -->|Validation & Tests| Tests[🧪 PHPUnit / Unit & Integration]
```

### 1. ⚙️ Stack Technique Principale
*   **Framework Backend :** Symfony 7.x (PHP 8.3+)
*   **Moteur d'API :** API Platform (Exposition des ressources au format JSON-LD/HAL)
*   **Moteur de Rendu Frontend :** Twig avec un **CSS sur mesure (Vanilla CSS)** moderne, fluide et sans surpoids.
*   **Base de Données :** PostgreSQL / MySQL géré par Doctrine ORM.

### 2. 🌍 Internationalisation (i18n ready)
L'application est conçue dès le départ avec une structure internationale. Bien que le français soit la seule langue proposée à l'initialisation, toutes les chaînes de caractères, dates, devises et traductions passent par le composant `Translation` de Symfony. L'URL intègre le paramètre locale (ex: `/fr/login`, `/en/login`), facilitant l'extension future à d'autres langues sans refactorisation.

### 3. 🍃 Engagement Green IT (Éco-conception)
*   **Performance brute :** Minimisation du nombre de requêtes SQL (grâce aux jointures Doctrine optimisées) et mise en cache agressive.
*   **Frontend Léger :** Pas de frameworks JS lourds inutiles. Utilisation de Vanilla CSS hautement optimisé (pas de Tailwind inutile, réduction du poids des pages à moins de 100 Ko par chargement).
*   **Zéro page vide :** La première page (`index`) affiche du contenu statique utile immédiatement, évitant les requêtes asynchrones bloquantes ou les "loaders" infinis qui consomment de l'énergie serveur/client inutilement.
*   **Images & Médias :** Optimisation systématique au format WebP avec dimensions explicites pour éviter le Cumulative Layout Shift (CLS).

### 4. 📈 Référencement (SEO) & Indexabilité
*   **Rendu côté serveur (SSR) :** Indispensable pour que les robots de recherche (Google, Bing) indexent parfaitement la page d'accueil et la FAQ.
*   **Balises Sémantiques :** Structure stricte HTML5 (`<header>`, `<main>`, `<section>`, `<article>`, `<footer>`). Un unique `<h1>` par page.
*   **Métadonnées dynamiques :** OpenGraph et Twitter Cards pour un partage optimal sur les réseaux sociaux.
*   **Sitemap dynamique & Robots.txt :** Générés automatiquement pour orienter les moteurs de recherche tout en protégeant les espaces privés (Locataire/Propriétaire).

### 5. 🔒 Sécurité (Securitisation)
*   **Authentification Robuste :** Hachage des mots de passe avec l'algorithme `sodium` ou `bcrypt`.
*   **Protection des routes :** Utilisation des annotations de sécurité Symfony (`IsGranted('ROLE_USER')`) pour isoler hermétiquement l'Espace Locataire de l'Espace Propriétaire.
*   **Prévention des failles :** Protection native contre les injections SQL (via Doctrine), les failles XSS (via Twig) et les attaques CSRF (via les formulaires sécurisés Symfony).

---

## 📋 Spécifications Fonctionnelles (Les 5 Modules Majeurs)

L'application s'articule autour des 5 interfaces clés présentées ci-dessous :

```
┌────────────────────────────────────────────────────────────────────────┐
│                                CO-LIVE                                 │
├─────────────┬─────────────┬─────────────┬──────────────┬───────────────┤
│   PAGE 1    │   PAGE 2    │   PAGE 3    │    PAGE 4    │    PAGE 5     │
│   Accueil   │  Locataire  │Propriétaire │    Login     │   Tâches      │
│   & FAQ     │   & Loyer   │ & Recettes  │  & Sécurité  │  Ménagères    │
└─────────────┴─────────────┴─────────────┴──────────────┴───────────────┘
```

### 🏠 Page 1 : Page d'Accueil & FAQ
La vitrine publique du site. Elle doit être chaleureuse, explicative et s'afficher instantanément.
*   **Présentation :** Pitch de la colocation, avantages pour les locataires et pour les propriétaires.
*   **FAQ Dynamique :** Accordéons CSS fluides répondant aux questions courantes (gestion des charges, règles de vie, bail).
*   **Navigation Intuitive :** Un header moderne permettant d'accéder rapidement à l'espace de connexion.
*   > [!IMPORTANT]
    > **Règle d'éco-conception :** La page d'accueil ne doit pas être une coquille vide avec un chargeur JS. Elle affiche directement son contenu textuel et s'indexe parfaitement.

### 👤 Page 2 : Espace Locataire / Colocataire
L'interface privée dédiée au locataire pour piloter son quotidien et ses finances.
*   **Paiement du Loyer :** Suivi des paiements, bouton de paiement ou informations de virement.
*   **Quittance (Loyer + Charges) :** Téléchargement automatique des quittances au format PDF une fois le paiement validé.
*   **Calcul du Tantième de Charges :** Visualisation claire de sa quote-part sur les factures communes (ex: électricité) avec historique.
*   **Messagerie :** Fil de discussion direct avec le propriétaire pour signaler des incidents ou échanger.

### 🔑 Page 3 : Espace Propriétaire / Gestionnaire
Le tableau de bord du bailleur pour administrer son appartement et suivre la rentabilité.
*   **Budget Recettes & Dépenses :** Suivi des flux financiers globaux (Eau, Électricité, Internet, Taxes de copropriété).
*   **Administration Loyer + Tantièmes :** Saisie des factures globales reçues et ventilation automatique des tantièmes de charges pour chaque locataire en un clic.
*   **Messagerie :** Centralisation des messages de tous les locataires de l'appartement.

### 🔐 Page 4 : Authentification & Sécurité
Le portail d'accès ultra-sécurisé de la plateforme.
*   **Enregistrement :** Formulaire d'inscription optimisé avec indicateur de force du mot de passe.
*   **Changement de mot de passe :** Espace sécurisé dans le profil utilisateur pour modifier ses accès.
*   **Oubli de mot de passe :** Processus sécurisé de réinitialisation de mot de passe par envoi d'un token sécurisé temporaire par email (validité limitée à 1 heure).

### 🧹 Page 5 : Répartition des Tâches Ménagères (Le Semainier)
L'outil indispensable pour maintenir l'harmonie et la propreté au sein de la colocation.
*   **Tableau de Bord des Tâches :** Attribution claire des tâches ménagères récurrentes (Vaisselle, Ménage, Entretien des poubelles/jardin).
*   **Le Semainier :** Calendrier visuel hebdomadaire indiquant qui doit faire quoi et quel jour.
*   **Rôles & Validation :** Possibilité de marquer une tâche comme "Faite", avec notification ou validation visuelle pour le reste de la colocation.

---

## ⚡ Focus Métier : Le Tantième de Charge

Le **tantième de charge** représente la quote-part (le pourcentage) qu'un locataire doit payer sur une facture globale (ex: électricité, eau, internet) en fonction de l'espace qu'il occupe privativement dans l'appartement par rapport à la surface totale disponible.

### 📐 Formule Mathématique
$$Tantième = \left( \frac{\text{Surface de la Chambre du Locataire } (m^2)}{\text{Surface Totale de l'Appartement } (m^2)} \right) \times 100$$

### 💸 Exemple Concret
Soit un appartement de **100 m²** avec 3 chambres :
*   **Chambre A (Locataire A) :** 15 m² $\rightarrow$ Tantième = $15\%$
*   **Chambre B (Locataire B) :** 12 m² $\rightarrow$ Tantième = $12\%$
*   **Chambre C (Locataire C) :** 18 m² $\rightarrow$ Tantième = $18\%$
*   *Note : Le reste (55 m²) représente les parties communes (salon, cuisine, salle de bain) dont les charges sont généralement réparties équitablement ou intégrées dans le calcul global.*

Si le propriétaire reçoit une facture d'électricité de **200 €** :
*   **Locataire A** paie : $200 \times 15\% = 30\text{ €}$
*   **Locataire B** paie : $200 \times 12\% = 24\text{ €}$
*   **Locataire C** paie : $200 \times 18\% = 36\text{ €}$

---

## 🗄️ Modélisation de la Base de Données (Doctrine ERD)

Voici le schéma relationnel optimisé pour Symfony permettant de gérer les locataires, les appartements, les calculs de tantièmes et les tâches :

```mermaid
erDiagram
    USER {
        int id PK
        string email UK
        string password
        string first_name
        string last_name
        array roles "['ROLE_TENANT', 'ROLE_LANDLORD']"
    }
    
    APPARTEMENT {
        int id PK
        string nom
        string adresse
        float superficie_totale "Surface totale en m²"
        int landlord_id FK
    }
    
    CHAMBRE {
        int id PK
        string nom
        float superficie "Surface privée en m²"
        int appartement_id FK
        int tenant_id FK "Peut être NULL si vacante"
    }
    
    FACTURE {
        int id PK
        string type_charge "Electricité, Eau, Internet, Taxe"
        float montant_total
        date date_facture
        int appartement_id FK
    }
    
    QUITTANCE {
        int id PK
        float montant_loyer_hors_charges
        float montant_charges_tantieme "Calculé automatiquement"
        date date_emission
        string statut_paiement "En attente, Payé"
        int tenant_id FK
        int facture_reference_id FK "Optionnel"
    }
    
    TACHE_MENAGERE {
        int id PK
        string nom "Vaisselle, Ménage, Entretien"
        string jour_semaine "Lundi, Mardi..."
        string statut "A faire, Fait"
        int appartement_id FK
        int assignee_id FK "Locataire désigné"
    }
    
    MESSAGE {
        int id PK
        text contenu
        datetime date_envoi
        int sender_id FK
        int receiver_id FK
    }

    USER ||--o| APPARTEMENT : "gère (si propriétaire)"
    APPARTEMENT ||--o{ CHAMBRE : "contient"
    USER ||--o| CHAMBRE : "occupe (si locataire)"
    APPARTEMENT ||--o{ FACTURE : "concerne"
    USER ||--o{ QUITTANCE : "reçoit"
    APPARTEMENT ||--o{ TACHE_MENAGERE : "organise"
    USER ||--o{ TACHE_MENAGERE : "effectue"
    USER ||--o{ MESSAGE : "envoie/reçoit"
```

---

## 🚀 Guide de Démarrage Rapide

### Prerequis
*   PHP 8.3 ou supérieur
*   Composer
*   Symfony CLI
*   Base de données (PostgreSQL / MySQL)

### Installation
1.  **Cloner le projet :**
    ```bash
    git clone https://github.com/votre-compte/colocation.git
    cd colocation
    ```
2.  **Installer les dépendances PHP :**
    ```bash
    composer install
    ```
3.  **Configurer l'environnement :**
    Copier le fichier `.env` en `.env.local` et ajuster la ligne de connexion à la base de données :
    ```env
    DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:5432/colocation?serverVersion=16&charset=utf8"
    ```
4.  **Créer la base de données et appliquer les migrations :**
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate --no-interaction
    ```
5.  **Lancer les tests unitaires et d'intégration :**
    ```bash
    php bin/phpunit
    ```
6.  **Lancer le serveur de développement :**
    ```bash
    symfony server:start
    ```

---

## 🧪 Stratégie de Tests & Qualité
*   **Tests Unitaires (PHPUnit) :** Validation des formules de calcul de tantièmes dans les entités (`Chambre`, `Facture`) et des services associés.
*   **Tests d'Intégration :** Vérification des API endpoints exposés par API Platform et de la bonne sécurisation des accès (ex: s'assurer qu'un locataire ne peut pas modifier le budget du propriétaire).
*   **Qualité de Code :** Utilisation de `PHPStan` (niveau 6+) et `PHP-CS-Fixer` pour assurer un code propre, standardisé et hautement maintenable.

---

> Proposé avec passion pour le projet **CoLive**. Convivial, Performant, Éco-conçu. 🍃