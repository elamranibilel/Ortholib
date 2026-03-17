# Ortholib

**Ortholib** est une application web de gestion de cabinet d'orthophonie. Le nom combine **Ortho** (orthophoniste) et **lib** (inspiré de Doctolib). Elle permet de gérer les orthophonistes, les patients, les séances et les exercices de rééducation.

Projet personnel réalisé pour monter en compétences sur Symfony et le développement d'une application métier complète.

## Fonctionnalités

### Côté orthophoniste
- Gestion du profil et association à un cabinet
- Suivi des patients assignés
- Planification des séances (présentiel ou visioconférence)
- Respect automatique de la limite des 35h/semaine
- Messagerie avec les patients

### Côté patient
- Espace personnel avec liste des exercices assignés
- 3 types d'exercices : **vocabulaire**, **orthographe** et **mémoire**
- Suivi des scores et historique des résultats
- Messagerie avec l'orthophoniste

### Administration
- Panel admin (EasyAdmin) pour gérer les utilisateurs, cabinets et séances
- Gestion des spécialisations (surdité, communication, écriture)
- Workflow de confirmation des comptes patients

## Stack technique

| Technologie | Rôle |
|---|---|
| **Symfony 6.4** | Framework back-end principal |
| **PHP 8.1** | Langage |
| **Doctrine ORM** | Gestion base de données + 26 migrations |
| **Twig** | Templating (78 templates) |
| **EasyAdmin 4** | Panel d'administration |
| **Vich Uploader** | Upload d'images (patients, orthophonistes, cabinets) |
| **Stimulus.js / Turbo** | Interactivité front-end |
| **Chart.js** | Visualisation des résultats |
| **KNP Paginator** | Pagination |

## Architecture

L'application repose sur **12 entités Doctrine** avec une hiérarchie d'héritage pour les utilisateurs :

```
User (base)
├── Patient        → niveau d'apprentissage, difficultés, séances
├── Orthophoniste  → heures travaillées, cabinet, spécialisations
└── Administrateur
```

Autres entités : `Cabinet`, `Seances`, `Exercice`, `Signification`, `ResultatExercice`, `Commentaire`, `Specialisation`, `Image`

## Installation

### Prérequis
- PHP 8.1+
- Composer
- MySQL / MariaDB
- Node.js (pour les assets)

### Étapes

```bash
git clone https://github.com/elamranibilel/Ortholib.git
cd Ortholib/symfony
composer install
```

Configurer la base de données dans `.env.local` :
```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/ortholib"
```

Puis :
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load  # données de test
php bin/console asset-map:compile
symfony server:start
```

## Structure

```
symfony/
├── src/
│   ├── Controller/              # 17 contrôleurs
│   ├── Remote/Secours/Entity/   # 12 entités
│   ├── Form/                    # 11 types de formulaires
│   └── Repository/              # Couche d'accès aux données
├── templates/                   # 78 templates Twig
├── migrations/                  # 26 migrations
└── public/                      # Racine web
```

## Ce que j'ai appris

- Modélisation d'un domaine métier réel (santé / cabinet médical)
- Héritage d'entités avec Doctrine (JOINED inheritance)
- Authentification et RBAC avec Symfony Security
- Upload de fichiers avec VichUploader
- Mise en place d'un panel admin avec EasyAdmin
- Gestion de règles métier (limite 35h/semaine)

## Auteur

[elamranibilel](https://github.com/elamranibilel) — projet réalisé dans le cadre de la recherche d'une alternance.
