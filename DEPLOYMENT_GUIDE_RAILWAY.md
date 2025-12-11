# Guide de Déploiement sur Railway

Ce guide vous explique étape par étape comment héberger votre application Laravel sur Railway.

## 1. Préparation du projet (Déjà fait pour vous)

- Nous avons configuré l'application pour utiliser HTTPS en production (`AppServiceProvider.php`).
- Le fichier `composer.json` est prêt.

**IMPORTANT :** Assurez-vous que votre projet est poussé sur un dépôt GitHub (public ou privé). Railway déploie directement depuis GitHub.

## 2. Création du projet sur Railway

1. Allez sur [railway.app](https://railway.app/) et connectez-vous (avec GitHub c'est le plus simple).
2. Cliquez sur **"New Project"** -> **"Deploy from GitHub repo"**.
3. Sélectionnez votre dépôt `culture_final` (ou le nom que vous lui avez donné).
4. Cliquez sur **"Deploy Now"**. 
   *Note : Le premier déploiement échouera probablement car les variables d'environnement ne sont pas encore configurées, c'est normal.*

## 3. Ajout de la Base de Données

1. Dans votre tableau de bord projet Railway, cliquez sur le bouton **"New"** (ou "+") en haut à droite (ou sur le canvas).
2. Sélectionnez **"Database"** -> **"Add PostgreSQL"**.
3. Attendez quelques secondes que la base de données soit créée.

## 4. Configuration des Variables d'Environnement

1. Cliquez sur votre service **Laravel** (le bloc représentant votre application).
2. Allez dans l'onglet **"Variables"**.
3. Ajoutez les variables suivantes (copiez vos clés depuis votre `.env` local si nécessaire, mais adaptez pour la prod) :

| Variable | Valeur / Commentaire |
|----------|----------------------|
| `APP_NAME` | `CultureBenin` (ou votre choix) |
| `APP_ENV` | `production` |
| `APP_KEY` | Copiez celle de votre .env local ou générez-en une nouvelle |
| `APP_DEBUG` | `false` (IMPORTANT pour la sécurité) |
| `APP_URL` | L'URL que Railway vous donnera (dans l'onglet Settings > Domains) |
| `DB_CONNECTION` | `pgsql` |
| `DB_URL` | `${DATABASE_URL}` (Ceci va automatiquement récupérer l'URL de la base PostgreSQL ajoutée) |
| `FEDAPAY_SECRET_KEY` | Votre clé secrète FedaPay (Live ou Sandbox selon votre choix) |
| `FEDAPAY_PUBLIC_KEY` | Votre clé publique FedaPay |
| `FEDAPAY_ENVIRONMENT` | `live` (pour la prod) ou `sandbox` |

**Note sur la Base de données :** Grâce à la variable `DB_URL=${DATABASE_URL}`, vous n'avez pas besoin de remplir `DB_HOST`, `DB_PORT`, etc. Railway fera le lien automatiquement si vous avez ajouté le service PostgreSQL dans le même projet.

## 5. Stockage des fichiers (Images/Vidéos)

**ATTENTION :** Par défaut, le système de fichiers de Railway est "éphémère". Cela signifie que si vous redémarrez l'application, tous les fichiers uploadés (images, vidéos) seront PERDUS.

Pour la production, deux solutions :
1. **Utiliser un service externe (Recommandé)** : Configurez un bucket AWS S3 (ou compatible) et changez `FILESYSTEM_DISK=s3` dans les variables.
2. **Utiliser un Volume Railway (Plus simple au début)** :
   - Dans les réglages de votre service sur Railway, allez dans **"Volumes"**.
   - Ajoutez un volume monté sur `/app/storage/app/public`.
   - Cela permettra de conserver les fichiers uploadés.

## 6. Déploiement et Finition

1. Une fois les variables ajoutées, Railway devrait redémarrer un déploiement automatiquement. Sinon, cliquez sur "Redeploy".
2. Allez dans l'onglet **"Build"** ou **"Deployments"** pour voir les logs.
3. Si le déploiement est "Active" (vert), votre site est en ligne.

### Dernière étape : Base de données et liens
Il faut exécuter les migrations et créer le lien symbolique pour le stockage.
Allez dans l'onglet **"Settings"** de votre service, et cherchez la section **"Deploy"** -> **"Start Command"**.
Vous pouvez modifier la commande de démarrage pour inclure les migrations, mais attention, cela les exécutera à chaque redémarrage.

Le mieux est d'utiliser le CLI Railway ou d'ajouter une commande de "Build" personnalisée, mais pour faire simple, vous pouvez utiliser le **"Railway CLI"** sur votre machine locale pour lancer ces commandes une seule fois :

```bash
# Si vous installez le CLI Railway :
railway login
railway link
railway run php artisan migrate --force
railway run php artisan storage:link
```

Ou, plus simplement, utilisez la commande suivante comme **"Start Command"** dans les paramètres Railway (Settings > Service > Start Command) :
```bash
php artisan migrate --force && php artisan storage:link && php artisan config:cache && php artisan route:cache && php artisan view:cache && apache2-foreground
```
*(Si vous utilisez l'image docker par défaut ou heroku buildpacks. Si vous utilisez Nixpacks (défaut), la commande de démarrage est gérée automatiquement, mais vous pouvez la surcharger).*

Si vous utilisez Nixpacks (le défaut), mettez ceci en **Start Command** :
```bash
php artisan migrate --force && php artisan storage:link && python /app/start.py
```
*(Attention : la commande exacte de démarrage de Nixpacks peut varier, le plus sûr pour les migrations est de les faire via le CLI).*

Bonne chance !
