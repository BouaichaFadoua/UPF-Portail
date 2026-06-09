# UPF Portail - Documentation API REST

Cette documentation présente l'ensemble des points d'accès (endpoints) de l'API REST de la plateforme universitaire UPF.
L'authentification de l'API est sécurisée via **Laravel Sanctum**.

---

## 1. Authentification

### Authentification de la session (Login)
Permet d'obtenir un jeton d'accès (token) pour authentifier les requêtes futures.

- **URL :** `/api/login`
- **Méthode :** `POST`
- **En-têtes :**
  - `Accept: application/json`
  - `Content-Type: application/json`
- **Corps de la requête (JSON) :**
  ```json
  {
    "email": "etudiant1@upf.dz",
    "password": "password"
  }
  ```
- **Réponse de succès (200 OK) :**
  ```json
  {
    "token": "1|abcdef1234567890...",
    "user": {
      "id": 6,
      "name": "Yassine Kamal",
      "email": "etudiant1@upf.dz",
      "role": "etudiant"
    }
  }
  ```
- **Réponse d'erreur (422 Unprocessable Content) :**
  ```json
  {
    "message": "Les identifiants fournis sont incorrects.",
    "errors": {
      "email": [
        "Ces identifiants ne correspondent pas à nos enregistrements."
      ]
    }
  }
  ```

---

### Déconnexion (Logout)
Révoque le jeton d'accès actuel de l'utilisateur connecté.

- **URL :** `/api/logout`
- **Méthode :** `POST`
- **En-têtes :**
  - `Accept: application/json`
  - `Authorization: Bearer <votre_token>`
- **Réponse de succès (200 OK) :**
  ```json
  {
    "message": "Déconnexion réussie et token révoqué."
  }
  ```

---

### Informations de l'utilisateur connecté
Retourne les informations du profil de l'utilisateur associé au jeton fourni.

- **URL :** `/api/user`
- **Méthode :** `GET`
- **En-têtes :**
  - `Accept: application/json`
  - `Authorization: Bearer <votre_token>`
- **Réponse de succès (200 OK) :**
  ```json
  {
    "id": 6,
    "name": "Yassine Kamal",
    "email": "etudiant1@upf.dz",
    "role": "etudiant",
    "actif": true,
    "etudiant": {
      "id": 1,
      "matricule": "ET001",
      "filiere_id": 1,
      "groupe_id": 1,
      "telephone": "+213 555 00 99 00"
    }
  }
  ```

---

## 2. Notes (§3.6)
Permet à l'étudiant connecté de consulter ses notes de l'année universitaire en cours.

- **URL :** `/api/notes`
- **Méthode :** `GET`
- **En-têtes :**
  - `Accept: application/json`
  - `Authorization: Bearer <votre_token>`
- **Réponse de succès (200 OK) :**
  ```json
  [
    {
      "id": 1,
      "module": {
        "id": 1,
        "nom": "Programmation Web PHP",
        "code": "GI-301",
        "coefficient": 4
      },
      "cc1": 14.5,
      "cc2": 15.0,
      "examen": 16.0,
      "note_finale": 15.4,
      "mention": "Bien"
    },
    {
      "id": 2,
      "module": {
        "id": 2,
        "nom": "Bases de Données Relationnelles",
        "code": "GI-302",
        "coefficient": 3
      },
      "cc1": 12.0,
      "cc2": 11.5,
      "examen": 13.0,
      "note_finale": 12.4,
      "mention": "Assez Bien"
    }
  ]
  ```

---

## 3. Emploi du Temps (§3.6)
Permet de consulter l'emploi du temps (les séances de cours) selon le rôle de l'utilisateur connecté.
- **Étudiant :** Retourne l'emploi du temps de son groupe.
- **Professeur :** Retourne l'emploi du temps de ses enseignements.
- **Administration :** Retourne l'ensemble de l'emploi du temps global.

- **URL :** `/api/edt`
- **Méthode :** `GET`
- **En-têtes :**
  - `Accept: application/json`
  - `Authorization: Bearer <votre_token>`
- **Paramètres optionnels :**
  - `semaine` (ex: `23`) : Filtre les séances pour une semaine spécifique de l'année (par défaut la semaine actuelle).
- **Réponse de succès (200 OK) :**
  ```json
  [
    {
      "id": 1,
      "date": "2026-06-08",
      "heure_debut": "08:30:00",
      "heure_fin": "10:30:00",
      "type": "Cours",
      "module": {
        "nom": "Programmation Web PHP",
        "code": "GI-301"
      },
      "salle": {
        "nom": "Salle 101 (TD)",
        "batiment": "Batiment B"
      },
      "groupe": {
        "nom": "GI - Groupe 1"
      },
      "professeur": {
        "nom": "Prof. Ahmed Alami"
      }
    }
  ]
  ```

---

## 4. Absences (§3.6)
Permet à l'étudiant connecté de consulter l'historique et le récapitulatif de ses absences.

- **URL :** `/api/absences`
- **Méthode :** `GET`
- **En-têtes :**
  - `Accept: application/json`
  - `Authorization: Bearer <votre_token>`
- **Réponse de succès (200 OK) :**
  ```json
  {
    "total_absences": 2,
    "justifiees": 0,
    "non_justifiees": 2,
    "absences": [
      {
        "id": 1,
        "date": "2026-06-08",
        "heure": "08:30:00 - 10:30:00",
        "module": "Programmation Web PHP",
        "justifiee": false,
        "statut_justificatif": "aucun"
      },
      {
        "id": 3,
        "date": "2026-06-09",
        "heure": "08:30:00 - 10:30:00",
        "module": "Bases de Données Relationnelles",
        "justifiee": false,
        "statut_justificatif": "en_attente"
      }
    ]
  }
  ```

---

## 5. Modules / Cours (§3.6)
Permet de lister tous les modules ou d'obtenir le détail d'un module spécifique (suivi par l'étudiant ou enseigné par le professeur).

### Liste des modules
- **URL :** `/api/modules`
- **Méthode :** `GET`
- **En-têtes :**
  - `Accept: application/json`
  - `Authorization: Bearer <votre_token>`
- **Réponse de succès (200 OK) :**
  ```json
  [
    {
      "id": 1,
      "nom": "Programmation Web PHP",
      "code": "GI-301",
      "coefficient": 4,
      "semestre": 1,
      "volume_horaire": 45,
      "professeur": "Prof. Ahmed Alami"
    },
    {
      "id": 2,
      "nom": "Bases de Données Relationnelles",
      "code": "GI-302",
      "coefficient": 3,
      "semestre": 1,
      "volume_horaire": 40,
      "professeur": "Prof. Ahmed Alami"
    }
  ]
  ```

### Détails d'un module
- **URL :** `/api/modules/{id}`
- **Méthode :** `GET`
- **En-têtes :**
  - `Accept: application/json`
  - `Authorization: Bearer <votre_token>`
- **Réponse de succès (200 OK) :**
  ```json
  {
    "id": 1,
    "nom": "Programmation Web PHP",
    "code": "GI-301",
    "coefficient": 4,
    "semestre": 1,
    "volume_horaire": 45,
    "professeur": {
      "nom": "Prof. Ahmed Alami",
      "specialite": "Informatique / Web"
    },
    "filiere": {
      "nom": "Génie Informatique",
      "code": "GI"
    }
  }
  ```

---

## Codes de statut HTTP standards utilisés
- `200 OK` : Requête traitée avec succès.
- `401 Unauthorized` : Authentification requise ou token manquant/expiré.
- `403 Forbidden` : Droits d'accès insuffisants pour le rôle de l'utilisateur.
- `404 Not Found` : Ressource introuvable.
- `422 Unprocessable Content` : Données de formulaire invalides.
- `500 Internal Server Error` : Erreur interne du serveur.
