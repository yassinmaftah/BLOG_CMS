# 📝 BlogCMS - PHP Native Project

Un système de gestion de contenu (CMS) simple et performant développé en PHP natif. Ce projet permet aux utilisateurs de créer des articles, gérer des images et interagir via des commentaires, avec un système de permissions avancé.

## 🚀 Fonctionnalités Clés (Key Features)

### 1. Authentification & Utilisateurs
* **Inscription sécurisée :** Hachage des mots de passe (`password_hash`), vérification d'email unique.
* **Auto-Login :** Connexion automatique après l'inscription.
* **Système de Session :** Gestion sécurisée des sessions utilisateurs.

### 2. Gestion des Articles (CRUD)
* **Création & Édition :** Possibilité de rédiger des articles avec titres et catégories.
* **Image Upload :** Gestion complète de l'upload d'images (validation d'extension, déplacement depuis `tmp`, renommage unique).
* **Affichage :** Pagination et formatage des dates.
* **Préservation des données :** Si un auteur est supprimé, ses articles restent (Auteur devient "Unknown" via `ON DELETE SET NULL`).

### 3. Système de Commentaires & Permissions
* Les utilisateurs peuvent commenter les articles.
* **Logique de Suppression Avancée :**
    * L'Admin peut tout supprimer.
    * L'auteur du commentaire peut supprimer son propre commentaire.
    * L'auteur de l'article peut modérer (supprimer) les commentaires sur son post.

### 4. Sécurité & Architecture
* **Protection XSS :** Utilisation de `htmlspecialchars()` pour l'affichage.
* **Protection SQL Injection :** Utilisation exclusive de **PDO** avec requêtes préparées.
* **Base de Données :** Architecture relationnelle optimisée (`INNER JOIN`, `LEFT JOIN`).

---

## 🛠️ Technologies Utilisées

* **Backend :** PHP 8+ (Native)
* **Database :** MySQL
* **Frontend :** TailwindCSS (pour le design), HTML5
* **Outils :** XAMPP

---

## ⚙️ Installation & Configuration

Suivez ces étapes pour lancer le projet en local :

1.  **Cloner ou Télécharger** le projet dans votre dossier serveur (ex: `htdocs`).
2.  **Base de Données :**
    * Ouvrez phpMyAdmin.
    * Créez une nouvelle base de données nommée `blog`.
    * Importez le fichier `database.sql` (fourni dans le dossier racine).
3.  **Configuration :**
    * Ouvrez le fichier `config/db.php`.
    * Vérifiez les identifiants (Host, User, Password, DB Name).
4.  **Lancement :**
    * Ouvrez votre navigateur et allez sur `http://localhost/BLOG_CMS`.

---

## 🗄️ Structure de la Base de Données

Le projet repose sur 4 tables principales :
* `utilisateur` (id, name, email, password, role...)
* `article` (id, title, content, image, id_author...)
* `category` (id, name...)
* `commentair` (id, content, id_user, id_article...)

---

## 👤 Auteur
**[Ton Nom Complet]**
*Projet réalisé dans le cadre de la formation [Nom de ta filière/formation].*