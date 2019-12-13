# SnowTricks

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/5ac297342e0c4aa5b81df339cef44de2)](https://www.codacy.com/manual/damientabet/SnowTricks?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=damientabet/SnowTricks&amp;utm_campaign=Badge_Grade)
[![Maintainability](https://api.codeclimate.com/v1/badges/5d67cd3023b7a66e0d21/maintainability)](https://codeclimate.com/github/damientabet/SnowTricks/maintainability)  

## Processus d'installation

### Étape 1

Assurez-vous d'avoir Git installé et à jour sur votre machine  

www.git-scm.com  

### Étape 2

Cloner le repository sur votre serveur local  

``git clone https://github.com/damientabet/SnowTricks.git``  

### Étape 3

Bien s'assurer que composer est installé et à jour sur votre machine  

www.getcomposer.org/doc/00-intro.md  

### Étape 4

Après avoir installé composer, veuillez lancer ``composer install`` à la racine de votre projet.  
Toutes les dépendances vont s'installer et se stocker dans le dossier **/vendor**.  

### Étape 5

Assurez-vous que npm est installé sur votre machine.  

www.npmjs.com/get-npm  

### Étape 6

Se rendre, avec la console, dans le dossier **/public**.  
``cd public/``  

### Étape 7

Lancer ``npm install``  

### Étape 8

Créer la base de données en utilisant le fichier présent dans le dossier ``sql/install.sql``.  

### Étape 9

Modifier les accès à votre base de données dans le fichier ``.env DATABASE_URL (l.28)``.  

### Étape 10

Créer un nouvel utilisateur en utilisant le formulaire d'inscription du site ``/login``  
Ensuite, se rendre dans le base de données et modifier votre utilisateur.  
Mettre à jour le champ **roles** en modifiant ``["ROLE_USER"]`` en ``["ROLE_ADMIN"]``.  