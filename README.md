# Faso-Wifi (Wilink Tickets)

> **Documentation Technique & Fonctionnelle**
> *Dernière mise à jour : 17 Janvier 2026*

## 📋 1. Présentation Générale
**Faso-Wifi** est une plateforme SaaS qui permet aux propriétaires de routeurs WiFi (Vendeurs) de monétiser leur connexion internet.
Le système automatise la vente de tickets d'accès (User/Pass stockés dans un fichier Excel) via des paiements mobiles (Ligdicash).

*   **Problème B2B** : Automatiser la distribution des tickets WiFi pour les gérants de Wifi Zone.
*   **Modèle Économique** : Commission de **25%** sur le chiffre d'affaires, prélevée lors du retrait des gains.
*   **Flux de Valeur** : Import Tickets (Stock) → Vente (Ligdicash) → Solde Virtuel (Ledger) → Retrait (Mobile Money).

---

## 🏗️ 2. Stack Technique
Architecture **Monolithe MVC** basée sur Laravel 9.

*   **Backend** : Laravel 9 (PHP 8.0+)
*   **Frontend** : Blade, Bootstrap 5, Vite (JS/SCSS)
*   **Base de Données** : MySQL
*   **Paiement** : API Ligdicash
*   **Dépendances Clés** :
    *   `maatwebsite/excel` : Import massif des tickets.
    *   `barryvdh/laravel-dompdf` : Génération des reçus PDF.

---

## ⚙️ 3. Fonctionnement Détaillé

### A. Gestion des Stocks (Import Excel)
C'est le point d'entrée du système.
1.  Le vendeur upload un fichier `.xlsx` (Colonnes : Login, Password, Durée).
2.  Il sélectionne un **Tarif** (ex: 1h = 200 FCFA).
3.  **Traitement Technique** (`TicketController`) :
    *   L'ID du Tarif est stocké en **Session PHP** temporaire.
    *   L'import Excel (`TicketsImport.php`) est lancé et lit cet ID de session pour associer chaque ticket au bon tarif.
    *   *Note : Les tickets sont importés avec un statut "LIBRE".*

### B. Tunnel d'Achat (Checkout)
1.  **Scan & Choix** : Le client scanne le QR code du routeur, arrive sur `/acheter-mon-ticket/{slug}` et choisit un tarif.
2.  **Paiement** : Redirection vers Ligdicash (Contrôleur : `Controller::apiPaiement`).
3.  **Callback & Livraison** :
    *   Ligdicash notifie le succès sur `/acheter-mon-ticket/recu/{slug}`.
    *   **Logique Critique** (`Controller::recu`) :
        *   Vérification qu'un ticket est disponible pour ce tarif.
        *   Passage du ticket à l'état `VENDU`.
        *   Création de l'enregistrement `Paiement`.
        *   Création de l'enregistrement `Solde` (Crédit du vendeur).
        *   Affichage du Login/Password à l'utilisateur.

### C. Finance & Ledger (Banque Interne)
Le système utilise un modèle de **Ledger Append-Only** pour la table `soldes`.
*   **Règle d'Or** : On ne modifie jamais une ligne `soldes`. On en ajoute toujours une nouvelle.
*   **Solde Courant** : Récupéré en prenant la **dernière ligne** insérée pour un utilisateur (`orderBy('id', 'desc')->first()`).
*   **Commission** : Calculée à la volée à l'affichage (Solde * 0.75). La base de données stocke le montant Brut.

---

## 🗄️ 4. Base de Données (Schéma Simplifié)

| Table | Description | Relations Clés |
| :--- | :--- | :--- |
| `users` | Vendeurs et Administrateurs (`is_admin`). | |
| `wifis` | Points de vente physiques (Routeurs). | `belongsTo(User)` |
| `tarifs` | Grille de prix par Wifi. | `belongsTo(Wifi)` |
| `tickets` | Stocks de coupons (User/Pass). | `belongsTo(User)`, `belongsTo(Tarif)` |
| `paiements` | Historique des transactions Ligdicash. | `belongsTo(Ticket)` |
| `soldes` | Journal financier (Crédits/Débits). | `belongsTo(User)` |

---

## ⚠️ 5. Dette Technique & Points de Vigilance
*Ce projet contient des choix d'implémentation spécifiques à connaître avant toute maintenance.*

1.  **Contrôleur "Dieu" (`Controller.php`)** : Une grande partie de la logique critique (Paiement, Génération de Reçu, Validation) réside directement dans la classe parente `App\Http\Controllers\Controller`. **Ne pas modifier sans tests approfondis.**
2.  **Sessions Hybrides** : Le code mélange parfois `Session::put()` (Laravel) et `session_start() / $_SESSION` (PHP Natif). Cela peut causer des bugs de session imprévisibles selon la config serveur.
3.  **Import Excel & Session** : L'association Import <-> Tarif repose sur la persistance de la session pendant l'upload. Si la session saute, l'import échoue ou lie les tickets à un tarif nul.
4.  **Calcul du Solde** : Basé sur la "dernière ligne". En cas d'écriture concurrente (deux paiements à la milliseconde exacte), risque de corruption du solde.

---

## 🚀 6. Installation Rapide

```bash
# 1. Installation
git clone [repo]
composer install
npm install && npm run build

# 2. Config
cp .env.example .env
php artisan key:generate
# Configurer DB_DATABASE, DB_USERNAME, DB_PASSWORD dans .env

# 3. Base de données
php artisan migrate:fresh --seed
```

### 🔑 Accès Administrateur par Défaut
Une fois le `check` (seed) effectué, le compte Super Admin est :
*   **Email** : `admin@wilink-ticket.com`
*   **Mot de passe** : `9yq571nR`

---

## ⚠️ 5. Points de Vigilance pour le Développeur
1.  **Session Import** : Ne jamais supprimer la gestion de session dans `TicketController` sans refondre l'import Excel, sinon les tickets perdront leur tarif.
2.  **Solde** : Toute modification directe en BDD sur la table `soldes` corrompt l'historique financier. Passer uniquement par le code.
3.  **Slugs** : Toujours utiliser les `slug` pour les URLs publiques pour éviter l'énumération des IDs.

## 🛠️ 6. Dépannage (Troubleshooting)

### Erreur "Vite manifest not found" (500 Internal Server Error)
Si cette erreur apparaît (ex: page de connexion, mot de passe oublié), cela signifie que les assets frontend n'ont pas été compilés.

**Solution :**
Exécuter la commande de build sur le serveur :
```bash
npm run build
```
Cette commande génère le fichier `public/build/manifest.json` requis par Laravel.

### Problème de traduction (Messages en Anglais)
Si les messages d'erreur restent en anglais malgré la configuration :
1. Vider le cache de configuration : `php artisan config:clear`
2. Vider le cache de l'application : `php artisan cache:clear`

---
*Dernière mise à jour : Janvier 2026*
