# 🚀 Blog_CMS - PHP Native Project

Un système de gestion de contenu (CMS) complet, développé en PHP procédural (sans framework) pour gérer un blog dynamique.

## 🌟 Fonctionnalités

### 🌍 Partie Publique
* Affichage des articles avec pagination (Accueil).
* Lecture détaillée des articles.
* Système de commentaires (nécessite une connexion).
* Navbar dynamique (Login/Dashboard).

### 🔐 Authentification
* Inscription et Connexion sécurisées.
* Hachage des mots de passe (Bcrypt).
* Gestion des sessions (Admin, Author, Visitor).

### ⚙️ Dashboard (Admin & Auteurs)
* **Statistiques :** Vue d'ensemble (Total articles, catégories...).
* **Gestion Articles :** Créer, Modifier, Supprimer (CRUD) avec Upload d'images.
* **Gestion Catégories :** (Admin seulement) Ajouter et supprimer des catégories.
* **Gestion Utilisateurs :** (Admin seulement) Modifier les rôles (Admin/Author/Visitor).

## 🛠️ Technologies Utilisées
* **Backend :** PHP 8 (PDO, Prepared Statements).
* **Frontend :** HTML5, Tailwind CSS (CDN).
* **Database :** MySQL.

## 📦 Installation

1. **Cloner le projet :**
   Placez le dossier `Blog_CMS` dans votre dossier serveur (ex: `htdocs`).

2. **Base de Données :**
   * Créez une base de données nommée `blog` dans phpMyAdmin.
   * Importez le fichier `database.sql` situé à la racine du projet.

3. **Configuration :**
   * Vérifiez les paramètres dans `config/db.php` :
     ```php
     $user = 'root';
     $pass = '';
     ```

4. **Lancement :**
   * Accédez à : `http://localhost/Blog_CMS`

## 👤 Comptes de Test

* **Admin :** `admin@blog.com` / `123456`
* **Auteur :** `author@test.com` / `123456`
   * Not woreking yet 