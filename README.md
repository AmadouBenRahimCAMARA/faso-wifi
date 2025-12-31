# ✝️ LA BIBLE DU PROGRAMMEUR : FASO-WIFI (WILINK TICKETS)

> **Rôle** : Architecte Logiciel Senior & Lead Developer  
> **Mission** : Transmettre la maîtrise ABSOLUE de ce projet à un développeur, du Junior au Senior.  
> **Objectif** : ZÉRO question à poser après lecture.

---

## ━━━━━━━━━━━━━━━━━━
## 1️⃣ VISION GLOBALE DU PROJET
## ━━━━━━━━━━━━━━━━━━

### 🎯 Le Problème Métier
En Afrique de l'Ouest, la vente de WiFi se fait souvent manuellement (tickets papier).  
**Ce projet automatise tout.** Il permet à n'importe quel propriétaire de routeur (Vendeur) de vendre ses codes WiFi via Mobile Money (Orange/Moov) sans être présent.

### 👤 Les Acteurs
1.  **Le Vendeur (Client SaaS)** : Il s'inscrit, configure ses zones WiFi, importe ses tickets Excel. Il veut voir son solde monter.
2.  **Le Client Final** : Il scanne un QR code, paye, et reçoit son login/mdp WiFi instantanément par SMS et PDF.
3.  **L'Admin (Super-God)** : Il voit tout, bloque les fraudeurs, et prend **25% de commission** sur chaque retrait d'argent.

### 🔄 Flux de Données (The Big Picture)
`Vendeur (Excel)` -> `Serveur (Stock)` -> `Client (Paiement)` -> `Ligdicash (Validation)` -> `Serveur (Délivrance)` -> `Vendeur (Crédit Solde)`.

---

## ━━━━━━━━━━━━━━━━━━
## 2️⃣ ARCHITECTURE GÉNÉRALE
## ━━━━━━━━━━━━━━━━━━

### 🏗️ Monolithe Modulaire MVC
Nous utilisons **Laravel 9** avec une architecture MVC stricte.

-   **Pourquoi ?** : Robustesse. Laravel gère nativement l'auth, la base de données, les files d'attente et la sécurité. Pas de réinvention de la roue.
-   **Le Rôle de la Vue** : Affichage BÊTE. Aucune requête BDD dedans.
-   **Le Rôle du Contrôleur** : Chef d'orchestre. Il ne contient pas de HTML. Il passe les variables à la Vue.
-   **Le Rôle du Modèle** : La Vérité. Il contient les règles business (Relations `hasMany`, etc.).

### ⚠️ Règles de Dépendance
1.  Le **Frontend** dépend du **Backend** (Blade a besoin des variables PHP).
2.  Le **Backend** dépend de la **Base de Données**.
3.  **JAMAIS** : La Base de Données ne dépend du code (Migration First).

---

## ━━━━━━━━━━━━━━━━━━
## 3️⃣ ORGANISATION DES DOSSIERS
## ━━━━━━━━━━━━━━━━━━

- 📂 **`app/`** : Le code source PHP.
    - 📂 **`Console/`** : Commandes Artisan (quasi vide ici, pas de cron jobs complexes).
    - 📂 **`Exceptions/`** : Gestionnaire d'erreurs (Standard Laravel).
    - 📂 **`Http/`** : Le cœur Web.
        - 📂 **`Controllers/`** : Ta logique business.
            - 📂 **`Auth/`** : Inscription/Connexion (Logique framework).
        - 📂 **`Middleware/`** : Les douaniers (Sécurité `admin`, `guest`).
    - 📂 **`Imports/`** : `TicketsImport.php` (Logique Excel spécifique).
    - 📂 **`Models/`** : Tes objets métier (User, Wifi, Ticket...).
    - 📂 **`Providers/`** : Configuration au démarrage (Pagination Bootstrap ici).
- 📂 **`database/migrations/`** : L'historique de tes tables SQL.
- 📂 **`public/`** : Le seul dossier accessible par le web (css, js, images, index.php).
- 📂 **`resources/views/`** : Tes fichiers `.blade.php`.
- 📂 **`routes/`** : `web.php` (La carte routière).

> **INTERDIT** : Ne touche jamais à `vendor/` ou `node_modules/`.

---

## ━━━━━━━━━━━━━━━━━━
## 4️⃣ MODULE PAR MODULE (ANALYSE PROFONDE)
## ━━━━━━━━━━━━━━━━━━

### 🔐 Module A : Authentification (`Auth/`)
**Responsabilité** : Sécuriser l'accès.
- **Subtilité** : L'inscription locale (`RegisterController`) crée un utilisateur avec `is_admin = 0` par défaut. Il devient donc un "Vendeur".
- **Interaction** : Une fois connecté, redirige vers `/home` (`RouteServiceProvider::HOME`).

### 📶 Module B : Gestion WiFi (`WifiController`, `TarifController`)
**Responsabilité** : Permettre au vendeur de définir son "Catalogue".
- **Logique** : Un `Wifi` a plusieurs `Tarifs`. Un `Tarif` a un prix et une durée.
- **Sécurité** : Chaque requête vérifie implicitement `Auth::user()->wifis()` pour ne pas voir les wifis du voisin.

### 🎟️ Module C : Stock & Import (`TicketController`, `TicketsImport`)
**Responsabilité** : Approvisionnement.
- **Le Hack Excel** : Comme l'import se fait en arrière-plan, on passe le `tarif_id` (choisi en liste déroulante) via la **Session PHP** (`Session::put('tarif_id')`) pour que la classe `TicketsImport` puisse le récupérer (`Session::get('tarif_id')`) et lier les tickets au bon tarif.

### 💳 Module D : Achat Public (`Controller`, `Paiement`)
**Responsabilité** : Le Tunnel de Vente.
- **Étape 1** : `acheter($slug)` -> Affiche les tarifs.
- **Étape 2** : `apiPaiement` -> Prépare la transaction.
- **Étape 3 (`payin`)** : Appelle Ligdicash (CURL).
- **Étape 4 (`recu`)** : **CRITIQUE**. C'est ici que l'argent est créé. Si Ligdicash dit OK, on crée le `Paiement` et le `Solde`.

### 💰 Module E : Finance (`Solde`, `RetraitController`)
**Responsabilité** : La banque interne.
- **Le Ledger** : On n'écrase jamais le solde. On ajoute une ligne `type=PAIEMENT`.
- **Commission** : Le `HomeController` affiche le solde. Le `RetraitController` calcule le montant "net vendeur" (Solde - 25%).

---

## ━━━━━━━━━━━━━━━━━━
## 5️⃣ FICHIER PAR FICHIER (INVENTAIRE TOTAL)
## ━━━━━━━━━━━━━━━━━━

Voici l'inventaire exhaustif de `app/`. Si un fichier n'est pas ici, c'est qu'il est standard Laravel et ne contient pas de logique métier modifiée.

### 📂 `app/Console`
- `Kernel.php` : Vide. Pas de tâches planifiées (Cron) dans ce projet.

### 📂 `app/Exceptions`
- `Handler.php` : Standard. Gère l'affichage des erreurs 404/500 techniques.

### 📂 `app/Http/Controllers/Auth`
- `ConfirmPasswordController.php` : Pour les zones ultra-sensibles (non utilisé activement).
- `ForgotPasswordController.php` : Envoie les emails de reset (Standard).
- `LoginController.php` : Gère la connexion. Redirige vers `/home`.
- `RegisterController.php` : Gère l'inscription. Utilise `User::create` avec les champs `nom`, `prenom`, `pays`, `phone`.
- `ResetPasswordController.php` : Traite le retour du lien email (Standard).
- `VerificationController.php` : Gère la vérification email (activé mais pas bloquant par défaut).

### 📂 `app/Http/Controllers` (Métier)
- `AdminController.php` : Dashboard Super-Admin. Calcule le revenu TOTAL de la plateforme (`Paiement::sum()`). Gère le bannissement (`toggleUserStatus`).
- `Controller.php` : **LE PLUS IMPORTANT**. Contient `recu($slug)` et `downloadRecu($slug)`. C'est le contrôleur "Public" qui gère la fin du tunnel d'achat.
- `HomeController.php` : Dashboard Vendeur. Affiche `daily_sales` et `solde`. Contient la logique de calcul de commission (25%) pour l'affichage.
- `PaiementController.php` : Liste l'historique des transactions (`admin.paiement-liste`).
- `RetraitController.php` : Gère les demandes de virement des vendeurs. Vérifie si le solde est suffisant.
- `TarifController.php` : CRUD des tarifs (`create`, `store`, `edit`...). Lie un tarif à un Wifi.
- `TicketController.php` : CRUD des tickets. Mais surtout : gère l'upload du fichier Excel et appelle `Excel::import`.
- `WifiController.php` : CRUD des points d'accès.

### 📂 `app/Http/Middleware`
- `AdminMiddleware.php` : **CUSTOM**. Vérifie `Auth::user()->isAdmin()`. Si faux, redirect `/home`.
- `RedirectIfAuthenticated.php` : Si on va sur `/login` alors qu'on est connecté, renvoie sur `/home`.
- `VerifyCsrfToken.php` : Protège les formulaires POST. (Standard).

### 📂 `app/Imports`
- `TicketsImport.php` : Le traducteur Excel -> DB. Lit les colonnes 0, 1, 2. Utilise `Auth::user()->id` pour lier le ticket au vendeur.

### 📂 `app/Models`
- `Paiement.php` : Lien `belongsTo(Ticket)`.
- `Retrait.php` : Lien `belongsTo(User)`. Gère le statut des demandes.
- `Solde.php` : La table comptable. `fillable` : `solde`, `type`, `paiement_id`.
- `Tarif.php` : `hasMany(Ticket)`. Le prix.
- `Ticket.php` : `owner()` (User), `tarif()`. L'objet vendu.
- `User.php` : Le centre du monde. `isAdmin()`, `isBanned()`. A des Wifis, des Tickets, des Soldes.
- `Wifi.php` : `hasMany(Tarif)`. Le point d'accès.

---

## ━━━━━━━━━━━━━━━━━━
## 6️⃣ ANALYSE DU CODE (FOCUS)
## ━━━━━━━━━━━━━━━━━━

### L'Importation des Tickets (`TicketsImport.php`)
```php
public function model(array $row) {
    // Cette fonction est appelée pour CHAQUE ligne du fichier Excel
    return new Ticket([
        'user'     => $row[0], // Identifiant Routeur
        'password' => $row[1], // MDP Routeur
        'dure'     => $row[2], // Durée
        'tarif_id' => Session::get("tarif_id"), // Magie de la Session
        'user_id'  => Auth::user()->id, // Le vendeur connecté
    ]);
}
```
**Pourquoi Session ?** L'importateur est un service déconnecté de la requête HTTP principale. Il ne connaît pas le formulaire POST précédent. La Session sert de pont.

### La Validation d'Achat (`Controller.php`)
```php
public function recu($slug){
    // ...
    if($data->etat_ticket != "VENDU"){
        // ACIDITÉ TRANSACTIONNELLE SIMULÉE
        $data->update(['etat_ticket' => 'VENDU']);
        Paiement::create([...]);
        Solde::create(['solde' => ..., 'type' => 'PAIEMENT']);
    }
}
```
**Logique** : On vérifie l'état AVANT de créer le paiement. Cela évite qu'un rafraîchissement de page ne crée de l'argent factice.

---

## ━━━━━━━━━━━━━━━━━━
## 7️⃣ RÈGLES D’OR DU PROJET
## ━━━━━━━━━━━━━━━━━━

1.  **On ne touche pas à l'argent** : La table `soldes` est sacrée. On ne fait JAMAIS de `DELETE` ou `UPDATE` dessus. On ajoute seulement des lignes qui s'annulent si besoin.
2.  **Le Slug est Roi** : Les routes publiques utilisent toujours `/{slug}`. Jamais `/{id}`. C'est la seule sécurité contre l'énumération.
3.  **Commission Hardcodée** : Le taux de 25% est écrit en dur dans le code (`HomeController`). Si tu le changes, tu dois le changer PARTOUT. (Idéalement, il faudrait le mettre en config).

---

## ━━━━━━━━━━━━━━━━━━
## 8️⃣ ERREURS CLASSIQUES DES JUNIORS
## ━━━━━━━━━━━━━━━━━━

1.  **"Je ne trouve pas ma route"** : Tu as oublié de mettre ta route dans le groupe `middleware => auth` dans `web.php`.
2.  **"Erreur 419 Page Expired"** : Tu as oublié `@csrf` dans ton formulaire Blade.
3.  **"Class not found"** : Tu as oublié le `use App\Models\MonModele;` en haut de ton contrôleur.
4.  **"Mon CSS ne change pas"** : Tu modifies `public/css/app.css` au lieu de `resources/sass/app.scss` et tu as oublié de lancer `npm run dev`.

---

## ━━━━━━━━━━━━━━━━━━
## 9️⃣ CHECKLIST DE MAÎTRISE
## ━━━━━━━━━━━━━━━━━━

Le Junior est validé s'il sait :

- [ ] Créer un nouveau contrôleur CRUD complet.
- [ ] Ajouter une colonne `promo_code` à la table `paiements` et la gérer.
- [ ] Expliquer pourquoi `TicketsImport` utilise `Session::get`.
- [ ] Retrouver la ligne de code exacte qui envoie les données à Ligdicash.
- [ ] Mettre en production sans supprimer la base de données existante (`migrate` vs `migrate:fresh`).

---
*Fin de transmission. Bonne chance, Architecte.*
