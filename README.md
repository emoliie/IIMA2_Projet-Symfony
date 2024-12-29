# E-commerce Symfony Project

## Description

Ce projet est une boutique e-commerce développée avec le framework Symfony. Il a été réalisé dans le cadre du cours de Symfony (MIIMI6214824). Il permet aux utilisateurs de :

- Parcourir une liste de produits
- Ajouter des produits à un panier
- Passer des commandes

Les administrateurs peuvent gérer les produits, et les super administrateurs disposent de fonctionnalités avancées.

---

## Fonctionnalités principales

### Utilisateurs
- Inscription et connexion.
- Affichage de l’historique des commandes.

### Administrateurs (`ROLE_ADMIN`)
- Ajout, modification et suppression de produits.

### Super Administrateurs (`ROLE_SUPER_ADMIN`)
- Gestion des produits comme les administrateurs.
- Affichage des paniers non achetés avec :
  - L'utilisateur associé
  - Le contenu du panier
- Liste des utilisateurs inscrits aujourd'hui (triée du plus récent au plus ancien).

### Produits
- Visualisation des détails d’un produit.
- Ajout de produits au panier.
- Gestion du stock.

### Panier
- Visualisation des produits ajoutés.
- Suppression de produits un par un.
- Finalisation des achats.

---

## Installation

### Étapes

1. **Cloner le projet** :
   ```bash
   git clone <url_du_projet>
   cd <nom_du_dossier>

2. **Installer les dépendances** :
    ```bash
    composer install

3. **Configurer l’accès à la base de données** :
- Dupliquez le fichier .env et renommez-le .env.local.
- Mettez à jour la variable DATABASE_URL avec vos informations de connexion.

4. **Créer la base de données** :
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:schema:update --force

5. **Charger des données de test (facultatif)** :
    ```bash
    php bin/console doctrine:fixtures:load

6. **Lancer le serveur de développement** :
    ```bash
    symfony server:start

---

## Utilisation
- Accédez à l’application via : http://localhost:8000.
- Connectez-vous ou inscrivez-vous pour accéder aux fonctionnalités.

---

## Contributeurs
- Okaramel ([Lien GitHub d'Okaramel](https://github.com/Okaramel))
- emoliie ([Lien GitHub d'emoliie](https://github.com/emoliie))

---
Si vous avez des questions, n'hésitez pas à nous contacter !