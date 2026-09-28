# Exécuter les tests sur GitHub

La configuration se trouve dans `.github/workflows/moodle-tests.yml`.
Elle utilise l'outil officiel `moodlehq/moodle-plugin-ci`.

## Créer la configuration dans un autre dépôt

1. Ouvrir le dépôt, onglet **Code**.
2. Choisir **Add file → Create new file**.
3. Saisir `.github/workflows/moodle-tests.yml` comme nom complet.
4. Copier le contenu du fichier de configuration de ce dépôt.
5. Choisir **Commit changes**, saisir un message, puis enregistrer.
6. Ouvrir **Actions → Tests Moodle** : le commit lance une exécution.

L'envoi du même fichier par **Add file → Upload files** dans le dossier
`.github/workflows` produit le même résultat.

## Ce que contient la configuration

- `on: push` : lancement à chaque envoi de code.
- `pull_request` : lancement lors d'une proposition de modification.
- `workflow_dispatch` : bouton de lancement manuel.
- `permissions: contents: read` : lecture du dépôt seulement.
- `matrix` : quatre environnements indépendants (4.5/PHP 8.1, 5.0/PHP 8.2,
  5.1/PHP 8.3 et 5.2/PHP 8.3).
- `services` : une base PostgreSQL temporaire pour chaque environnement.
- `steps` : récupération du plugin, préparation de PHP, installation des outils
  et de Moodle, puis contrôles et tests.
- `fail-fast: false` : une version en échec n'arrête pas les autres.
- `timeout-minutes: 30` : limite de durée par environnement.
- `concurrency` : une nouvelle exécution sur la même branche remplace l'ancienne.

Ces bases et installations sont sur les machines GitHub. Elles n'utilisent ni
les identifiants ni les données du Moodle LWS.

## Lancer manuellement

1. Ouvrir **Actions → Tests Moodle**.
2. Cliquer sur **Run workflow**.
3. Choisir la branche `main` puis confirmer **Run workflow**.
4. Ouvrir la nouvelle exécution et **Show all jobs** si nécessaire.

## Lire les résultats

Ouvrir une tâche, par exemple **Moodle 4.5 / PHP 8.1**, puis développer une étape :

- **Syntaxe PHP** : fichiers PHP lisibles par l'interpréteur.
- **Standards Moodle** : règles de présentation et conventions de code.
- **Documentation PHPDoc** : documentation des classes et méthodes.
- **Structure du plugin** : fichiers et métadonnées attendus par Moodle.
- **Points de mise à jour** : cohérence des étapes de migration.
- **Tests PHPUnit du plugin** : exécution de `archive_test.php` et `importer_test.php`.

Une coche verte signifie que l'étape a réussi. Une croix rouge demande de lire
le journal. Une étape ignorée n'est pas un succès : par exemple, si l'installation
Moodle échoue, PHPUnit n'est pas lancé. La dernière ligne « exit code 1 » indique
l'échec ; sa cause se trouve généralement plus haut dans le journal.

Après une correction et un nouveau commit, les tests redémarrent automatiquement.
Pour retenter le même code après un incident temporaire, ouvrir l'exécution
terminée et choisir **Re-run jobs**, puis les tâches en échec ou toutes les tâches.
Relancer sans corriger ne résout pas une erreur de code reproductible.

## Portée

Ces tests automatiques ne remplacent pas la recette visuelle du menu Plus,
les essais de sauvegarde/restauration ou l'évaluation Marketplace.
