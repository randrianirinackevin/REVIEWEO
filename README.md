# Revieweo

## Description

Revieweo est une plateforme web dédiée aux critiques de jeux vidéo. Elle permet aux utilisateurs de consulter, publier et interagir avec des avis de manière simple et rapide.

Le projet vise à améliorer l’expérience utilisateur en proposant une interface claire, intuitive et interactive.

---

## Problématique

Les plateformes actuelles présentent plusieurs limites :

- Navigation complexe  
- Interfaces peu intuitives  
- Manque d’interactions  

Ces problèmes rendent la recherche d’avis moins efficace et moins agréable.

---

## Solution

Revieweo propose une solution centrée sur :

- La simplicité d’utilisation  
- L’accessibilité des critiques  
- L’interaction entre utilisateurs (likes)  

---

## Fonctionnalités

### Utilisateur
- Consulter les critiques  
- Liker des avis  
- Créer un compte et se connecter  

### Auteur
- Publier une critique (titre, contenu, note)  
- Modifier ses critiques  
- Supprimer ses critiques  

### Administrateur
- Gérer les utilisateurs  
- Supprimer des contenus  
- Mettre en avant certaines critiques  

---

## Technologies utilisées

### Front-end
- HTML  
- CSS / Bootstrap  
- JavaScript  

### Back-end
- PHP (Programmation Orientée Objet)  
- MySQL / MariaDB  

### Outils
- Visual Studio Code  
- XAMPP  
- Git / GitHub  

---

## Architecture

### Front-end
Interface utilisateur permettant :

- L’affichage des critiques  
- Le tri (date, popularité)  
- La recherche par catégorie  
- L’interaction via les likes  

### Back-end
Gestion de :

- L’authentification  
- Les critiques (CRUD)  
- Les catégories  
- La sécurité des données  

---

## Base de données

### Tables principales

- **User** : informations utilisateurs  
- **Critique** : contenu des critiques  
- **Categorie** : genres de jeux  
- **Like** : interactions  
- **Critique_Categorie** : relation  

### Relations

- Un utilisateur peut publier plusieurs critiques  
- Une critique peut appartenir à plusieurs catégories  
- Un utilisateur peut liker plusieurs critiques  

---

## Sécurité

- Hash des mots de passe (`password_hash`)  
- Sessions sécurisées  
- Requêtes SQL préparées  
- Validation des données côté client et serveur  

---

## Objectifs

- Accès rapide aux critiques  
- Interface simple et efficace  
- Interaction communautaire  

---

## Améliorations possibles

- Ajout de commentaires  
- Système de tri avancé  
- Optimisation mobile  
- Notifications utilisateurs  

---

## Conclusion

Revieweo propose une solution simple et efficace pour consulter et partager des avis sur les jeux vidéo.

Le projet met l’accent sur l’expérience utilisateur, la clarté de l’interface et l’interaction entre les membres.

---

Projet réalisé dans le cadre du bachelor cybersécurité.
