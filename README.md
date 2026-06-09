# 🎓 UPF Portail — Plateforme Universitaire Centralisée

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.14-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tailwind_CSS-CDN-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind">
  <img src="https://img.shields.io/badge/AlpineJS-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js">
</p>

> Plateforme de gestion pédagogique et administrative pour établissements universitaires. Développée dans le cadre du **Projet de Fin de Module TW2 – UPF**.

---

## 📋 Description

**UPF Portail** est une application web full-stack Laravel permettant la gestion centralisée d'un établissement universitaire. Elle s'articule autour de **trois espaces utilisateurs** correspondant à des rôles distincts :

| Rôle | Espace | Accès |
|------|--------|-------|
| 🔴 Admin | Administration complète | `/admin/dashboard` |
| 🟡 Professeur | Gestion pédagogique | `/prof/dashboard` |
| 🟢 Étudiant | Consultation & demandes | `/etudiant/dashboard` |

---

## ✨ Fonctionnalités

### 👨‍💼 Espace Administration
- 📊 **Dashboard** avec statistiques et graphiques (Chart.js)
- 👥 **Gestion des utilisateurs** : CRUD complet (Admin, Prof, Étudiant, Personnel)
- 🏫 **Gestion des filières, groupes, modules et salles**
- 📅 **Emploi du temps** : création, modification avec détection des conflits (FullCalendar)
- 📝 **Notes & bulletins** : saisie et export PDF/Excel
- 🔔 **Demandes administratives** : validation avec génération PDF automatique
- 🏢 **Réservations de salles** : approbation / refus
- 📋 **Absences & justificatifs** : suivi et validation
- 📖 **Cahiers de textes** : consultation globale
- 🔔 **Système de notifications** en temps réel (polling toutes les 30s)

### 👨‍🏫 Espace Professeur
- ✏️ **Saisie des notes** : CC1, CC2, Examen → Calcul automatique de la moyenne
- 📋 **Appel de présence** : feuille d'émargement interactive par séance
- 📖 **Cahier de textes** : suivi du contenu des séances dispensées
- 📚 **Classroom** : dépôt de supports (Cours, TD, TP), annonces, commentaires étudiants
- 🏢 **Réservations de salles** : soumission de demandes avec vérification de disponibilité
- 📅 **Emploi du temps personnel** (FullCalendar)
- 📄 **Demandes administratives** : attestation de travail, ordres de mission

### 👨‍🎓 Espace Étudiant
- 📊 **Consultation des notes** : CC1, CC2, Examen, Moyenne finale, Mention
- 📚 **Classroom** : accès aux supports, annonces et possibilité de commenter
- 📅 **Emploi du temps** personnel (FullCalendar)
- ❌ **Absences** : suivi du nombre d'absences par module
- 📎 **Justificatifs** : dépôt de fichiers PDF / image
- 🤖 **Assistant IA (Chatbot)** : assistant universitaire virtuel répondant en langage naturel aux questions sur les notes, absences, modules, emploi du temps, ou à des questions générales
- 📄 **Demandes de documents** : attestation de scolarité, relevé de notes, certificat d'inscription

---

## 🛠️ Stack Technique

| Composant | Technologie |
|-----------|-------------|
| Backend | **Laravel 13** (PHP 8.2+) |
| Base de données | **MySQL 8.0** |
| Frontend | **Blade** + **Tailwind CSS** (CDN) |
| Réactivité UI | **Alpine.js 3.x** |
| Calendrier | **FullCalendar 6.x** |
| Graphiques | **Chart.js 4.x** |
| Génération PDF | **DomPDF** (barryvdh/laravel-dompdf) |
| Export Excel | **Laravel Excel** (maatwebsite) |

---

## ⚙️ Installation & Lancement

### Prérequis
- PHP ≥ 8.2
- Composer
- Node.js & NPM
- MySQL 8.0
- Serveur local (XAMPP, Laragon, WAMP...)

### 1. Cloner le projet


### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Modifier le fichier `.env` avec vos paramètres de base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=upf_platform
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Créer la base de données

```sql
CREATE DATABASE upf_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Migrer et peupler la base de données

```bash
php artisan migrate:fresh --seed
```

### 6. Créer le lien symbolique pour le stockage

```bash
php artisan storage:link
```

### 7. Lancer le serveur de développement

```bash
php artisan serve
```

L'application sera disponible sur **http://localhost:8000**

### 8. Configurer l'Assistant IA (Chatbot)

Pour activer les fonctionnalités d'intelligence artificielle du chatbot étudiant, ajoutez votre clé API OpenAI dans le fichier `.env` :

```env
OPENAI_API_KEY=sk-proj-votre_cle_ici
```

*Note: En local, si aucune clé valide n'est configurée (ou si elle contient `sk-xxxx...`), le chatbot s'exécute automatiquement en **mode démonstration**, interrogeant directement la base de données de l'étudiant pour formuler des réponses naturelles pré-formatées sans requérir de crédits OpenAI.*

---

## 🔐 Comptes de Test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| 🔴 Admin | `admin@gmail.com` | `password` |
| 🟡 Professeur | `prof.alami@gmail.com` | `password` |
| 🟡 Professeur | `prof.bennani@gmail.com` | `password` |
| 🟡 Professeur | `prof.idrissi@gmail.com` | `password` |
| 🟡 Professeur | `prof.haddad@gmail.com` | `password` |
| 🟡 Professeur | `prof.mansouri@gmail.com` | `password` |
| 🟢 Étudiant | `etudiant1@gmail.com` | `password` |
| 🟢 Étudiant | `etudiant2@gmail.com` | `password` |
| 🟢 Étudiant | `etudiant3@gmail.com` | `password` |
| 🟢 Étudiant | `etudiant4@gmail.com` | `password` |
| 🟢 Étudiant | `etudiant5@gmail.com` | `password` |
| ⚪ Personnel | `personnel@gmail.com` | `password` |

---

## 📁 Structure du Projet

```
examphp/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Contrôleurs Admin
│   │   │   ├── Prof/           # Contrôleurs Professeur
│   │   │   ├── Etudiant/       # Contrôleurs Étudiant
│   │   │   ├── Personnel/      # Contrôleurs Personnel
│   │   │   ├── Auth/           # Authentification & Profil
│   │   │   └── Api/            # API REST (mobile/externe)
│   │   └── Middleware/
│   ├── Models/                 # Modèles Eloquent
│   └── Policies/
├── database/
│   ├── migrations/             # Schéma de base de données
│   └── seeders/                # Données de test
├── resources/
│   └── views/
│       ├── admin/              # Vues administration
│       ├── prof/               # Vues professeur
│       ├── etudiant/           # Vues étudiant
│       ├── auth/               # Connexion, changement mdp
│       ├── layouts/            # Layout principal (app.blade.php)
│       └── pdf/                # Templates PDF
├── routes/
│   ├── web.php                 # Routes web (auth + rôles)
│   └── api.php                 # Routes API REST
└── storage/
    └── app/public/
        ├── supports/           # Fichiers supports cours
        ├── justificatifs/      # Justificatifs d'absence
        └── documents/          # Documents administratifs générés
```

---

## 🗄️ Modèle de Données

```
users ──────────────┬── professeurs
                    ├── etudiants ──── groupe_id ──── groupes ──── filiere_id ──── filieres
                    └── personnels

modules ──── filiere_id & professeur_id

seances ──── module_id, groupe_id, salle_id, professeur_id

notes ──── etudiant_id, module_id

absences ──── etudiant_id, seance_id
justificatifs ──── absence_id, etudiant_id

annonces ──── module_id, professeur_id
commentaires ──── annonce_id, user_id
supports ──── module_id, professeur_id

cahier_textes ──── professeur_id, module_id, seance_id

reservations ──── professeur_id, salle_id

demandes ──── user_id
documents ──── demande_id

notifications_upf ──── user_id
```

---

## 🔑 Fonctionnalités Techniques Avancées

| Fonctionnalité | Détail |
|----------------|--------|
| 🌙 **Mode sombre** | Toggle AlpineJS + Tailwind `dark:`, persisté en localStorage |
| 🌐 **Multi-langue** | Français / Anglais / Arabe (RTL) |
| 🔔 **Notifications** | Polling AJAX toutes les 30 secondes |
| 📊 **Graphiques** | Chart.js (barres, donut, courbe) |
| 📅 **Calendrier** | FullCalendar 6 avec chargement dynamique AJAX |
| 🔒 **Sécurité** | Middleware par rôle (`role:admin|professeur|etudiant`) |
| 📄 **Génération PDF** | Attestations, relevés, feuilles de présence (DomPDF) |
| 📥 **Export Excel** | Notes et absences (Laravel Excel) |
| 🔍 **Recherche globale** | Étudiants, professeurs, modules depuis la navbar |
| 🔐 **Changement mdp** | Formulaire sécurisé avec indicateur de force |
| 🚫 **Détection conflits** | EDT : salle / prof / groupe sur même créneau |
| 🤖 **Assistant IA** | Routage intelligent DB/OpenAI, prompt système strict, formatage JSON des données étudiant, contournement SSL en local, et mode démo hors ligne |

---

## 📝 Calcul de la Note Finale

```
Note Finale = (CC1 × 0.25) + (CC2 × 0.25) + (Examen × 0.50)
```

| Moyenne | Mention |
|---------|---------|
| ≥ 16 | Très Bien |
| ≥ 14 | Bien |
| ≥ 12 | Assez Bien |
| ≥ 10 | Passable |
| < 10 | Insuffisant |

---

## 🚀 Commandes Utiles

```bash
# Lancer le serveur de développement
php artisan serve

# Réinitialiser la base de données + jeu de données
php artisan migrate:fresh --seed

# Vider le cache de configuration
php artisan config:clear

# Vider le cache des routes
php artisan route:clear

# Vider le cache des vues
php artisan view:clear

# Lister toutes les routes
php artisan route:list

# Lancer les tests
php artisan test
```

---

## 📜 Licence

Ce projet est développé dans un cadre académique — **UPF, Module TW2 — Projet de Fin de Module**.

---

<p align="center">
  Développé avec ❤️ et Laravel · UPF · 2024–2025
</p>
