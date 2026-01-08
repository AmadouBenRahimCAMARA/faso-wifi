# Faso-Wifi (Wilink Tickets) - Résumé Global du Projet

## 📋 1. Présentation Générale
**Faso-Wifi** est une plateforme SaaS permettant aux propriétaires de routeurs WiFi (Vendeurs) de commercialiser automatiquement leurs tickets d'accès via Mobile Money (Orange Money, Moov Money).

*   **Problème résolu** : Automatisation de la vente de tickets WiFi (suppression de la vente manuelle/papier).
*   **Modèle Économique** : Commission de **25%** prélevée par la plateforme sur chaque retrait vendeur.
*   **Flux Principal** : Import Tickets (Excel) → Vente Publique (Ligdicash) → Crédit Solde (Virtuel) → Retrait (Mobile Money).

---

## 🏗️ 2. Stack Technique & Architecture
Le projet repose sur une architecture **Monolithe MVC** standard et robuste.

*   **Backend** : Laravel 9 (PHP 8.0+)
*   **Frontend** : Blade (Moteur de templates) + Bootstrap 5
*   **Base de Données** : MySQL
*   **Paiement** : API Ligdicash
*   **PDF** : `barryvdh/laravel-dompdf` (Génération des reçus)
*   **Excel** : `maatwebsite/excel` (Import en masse)

### Architecture des Dossiers Clés
*   `app/Http/Controllers` : Logique métier (Vente, Stock, Finance).
*   `app/Models` : Représentation des données (User, Wifi, Ticket, Paiement).
*   `resources/views` : Interface utilisateur (Aucune logique métier ici).
*   `routes/web.php` : Définition de toutes les URLs.

---

## ⚙️ 3. Fonctionnalités & Logique Métier

### A. Gestion des Stocks (Import Excel)
*   **Processus** : Le vendeur télécharge un fichier Excel contenant ses tickets (Login, MDP, Durée).
*   **Point Technique** : Utilisation de `TicketsImport.php`. L'ID du tarif sélectionné est passé via la session PHP car l'import est un processus asynchrone décorrélé du formulaire initial.

### B. Tunnel d'Achat (Publique)
1.  **Sélection** : Le client scanne un QR Code accédant à la page `/acheter-mon-ticket/{slug}`.
2.  **Paiement** : Intégration de Ligdicash via `Controller::apiPaiement`.
3.  **Validation** : Le callback redirige vers `Controller::recu`.
    *   **CRITIQUE** : C'est ici que le ticket passe à `VENDU` et que le `Solde` est crédité.
    *   *Sécurité* : Vérification stricte de l'état du ticket pour éviter le double crédit au rafraîchissement.

### C. Finance & Commission
*   **Ledger** : La table `soldes` fonctionne en "append-only" (ajout de lignes). On ne modifie jamais une ligne existante.
*   **Commission** : Fixée à **25%** (hardcodée dans `HomeController`).
*   **Retrait** : Le vendeur demande un retrait. L'admin valide et paie manuellement ou via API (à implémenter), marquant le retrait comme effectué.

### D. Rôles Utilisateurs
*   **Vendeur** : Utilisateur par défaut (`is_admin = 0`). Gère ses WiFi et Tickets.
*   **Super Admin** : Utilisateur privilégié (`is_admin = 1`). Accès au Dashboard global, gestion des utilisateurs (Bannissement), et vue sur toutes les transactions.

---

## 🚀 4. Installation & Déploiement

### Pré-requis
*   PHP 8.0+
*   Composer
*   MySQL
*   Node.js & NPM

### Commandes de Démarrage
```bash
# 1. Cloner et installer les dépendances
git clone [repo]
composer install
npm install && npm run build

# 2. Configuration
cp .env.example .env
php artisan key:generate
# (Configurer la BDD dans .env)

# 3. Initialisation de la Base de Données (Remise à zéro)
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

### Erreur d'envoi d'email (Connection refused / mailpit)
Si vous rencontrez une erreur `Connection could not be established with host "mailpit:1025"`, cela signifie que le serveur de mail n'est pas configuré.

**Solution temporaire (Logs) :**
Modifier le fichier `.env` pour utiliser le driver `log`. Les emails seront écrits dans `storage/logs/laravel.log` au lieu d'être envoyés.
```env
MAIL_MAILER=log
```

**Solution production (SMTP) :**
Configurer un vrai serveur SMTP (Gmail, Sendgrid, etc.) dans le fichier `.env` :
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=votre_user
MAIL_PASSWORD=votre_mdp
MAIL_ENCRYPTION=tls
```

Après modification du `.env`, n'oubliez pas de vider le cache : `php artisan config:clear`.

---
*Document généré automatiquement - Dernière mise à jour : Janvier 2026*
