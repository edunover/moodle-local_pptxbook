# Images de diapositives vers Livre — Moodle

Version **0.2.3 bêta**, composant `local_pptxbook`, cible Moodle **4.5 à 5.2**.
Le nom technique reste inchangé pour permettre la mise à jour de la version 0.1.0.

Code source et suivi des problèmes :
[github.com/EDUNOVER/moodle-local_pptxbook](https://github.com/EDUNOVER/moodle-local_pptxbook).

Cette version importe un **ZIP d’images PNG/JPEG dans un Livre existant**.
Elle n’utilise ni LibreOffice, ni Poppler, ni commande système, ni service externe.
L’interface est disponible en français, anglais et néerlandais.

## Installation sur un hébergement partagé

1. Dans Moodle : **Administration du site → Plugins → Installer des plugins**.
2. Envoyez **local_pptxbook-0.2.3.zip** et suivez les étapes d’installation.
3. Vérifiez que les extensions PHP **ZIP et GD** sont activées sur votre hébergement.
   Il s’agit d’extensions PHP, pas de programmes à installer comme LibreOffice.
4. Réglez les limites dans **Administration du site → Plugins → Plugins locaux →
   Images de diapositives vers Livre**.

Si vous utilisez un transfert de fichiers plutôt que l’installateur Moodle,
placez le dossier `pptxbook` dans `local/` pour Moodle 4.5–5.0, ou dans
`public/local/` pour Moodle 5.1–5.2, puis ouvrez les notifications d’administration.
Lors d’une mise à jour manuelle, remplacez le dossier du plugin en entier pour
supprimer les anciens fichiers de conversion. Ne désinstallez pas d’abord le plugin.
Les anciens paramètres LibreOffice/Poppler sont supprimés lors de la mise à jour.

Le plugin ne modifie pas les Livres déjà créés par la version précédente.

## Utilisation

1. Exportez les diapositives depuis PowerPoint en **PNG** ou **JPEG**.
2. Placez les images ensemble et nommez-les de manière cohérente, par exemple
   `Diapositive1.png`, `Diapositive2.png`, `Diapositive10.png`.
3. Compressez-les dans un ZIP. Le ZIP peut contenir un dossier enveloppant les images.
4. Ouvrez le **Livre Moodle** auquel ajouter les diapositives.
5. Choisissez **Plus → Importer des images (ZIP)**.
6. Sélectionnez le ZIP et lancez l’import.

Chaque image devient un chapitre principal ajouté à la fin du Livre.
Les noms des fichiers, sans extension, servent de titres ; les traits de
soulignement deviennent des espaces. Les titres restent modifiables dans Moodle.
Le tri est naturel, sans distinction de majuscules : `2` précède `10`.
Il porte sur le chemin relatif complet : utilisez un seul dossier pour un ordre
prévisible. Les fichiers de métadonnées courants de macOS et Windows sont ignorés.

**Les chapitres existants et la visibilité du Livre sont conservés.** Les nouveaux
chapitres sont visibles dès l’import si le Livre est visible. Pour préparer un
contenu sans le montrer aux étudiants, masquez préalablement le Livre dans Moodle.
Un nouvel import du même ZIP ajoute de nouveaux chapitres ; ce n’est pas une mise
à jour des chapitres déjà importés.

Si votre thème personnalise le menu Plus, l’action reste ajoutée aux paramètres
de l’activité Livre. Accès direct possible :
`https://VOTRE-MOODLE/local/pptxbook/index.php?id=ID_ACTIVITE_LIVRE`
(utilisez l’identifiant `id` dans l’adresse `/mod/book/view.php?id=...`, pas l’ID du cours).

## Pré-requis et limites

- Moodle 4.5, 5.0, 5.1 ou 5.2 avec sa version de PHP prise en charge ; syntaxe PHP 8.1+.
- Extensions PHP ZIP et GD, et module Livre activé.
- Permissions `local/pptxbook:import` et `mod/book:edit` dans le Livre.
  La permission d’import est accordée par défaut aux enseignants éditeurs et gestionnaires.
- Par défaut : **25 Mo par ZIP**, **50 images**. Paramétrables jusqu’à 100 Mo et 200 images.
  Les limites PHP, de Moodle et du cours restent prioritaires. Une mise à jour
  conserve les limites d’envoi et de nombre d’images déjà configurées.
- Maximum fixe : **10 Mo et 8 millions de pixels par image**, 16 000 pixels par côté.
  Une image 1920 × 1080 convient. Le plugin ne redimensionne pas les images.
- Maximum fixe : 128 Mo de contenu décompressé et 5 000 entrées par archive.
- Les fichiers autres que PNG/JPEG sont refusés, sauf les métadonnées explicitement ignorées.
  Les PPTX, PDF, SVG, archives imbriquées et images chiffrées ne sont pas acceptés.
- Les images ne sont pas envoyées à un service externe et leur contenu est conservé.
- L’import se déroule pendant la requête web : pour les gros lots, utilisez plusieurs ZIP.
  Après une interruption réseau, vérifiez le Livre avant de recommencer.

## Sécurité, stockage et accessibilité

Le plugin vérifie les permissions et le jeton de session. Il inspecte les noms et
la taille des entrées, rejette les liens symboliques et les chemins dangereux,
contrôle le type réel des images et vérifie leur décodage avec GD. Il écrit les
fichiers temporaires sous des noms générés, sans extraire les chemins de l’archive.
Toutes les images sont validées avant modification du Livre.

Les chapitres et fichiers sont ajoutés dans une transaction Moodle. Les imports
simultanés par ce plugin dans le même Livre utilisent un verrou. Ce verrou ne
bloque pas les modifications manuelles faites avec l’éditeur natif : évitez
l’édition simultanée pendant l’import.

Les images utilisent la zone native `mod_book/chapter` : les règles d’accès,
sauvegardes/restaurations et suppressions du Livre s’appliquent. Aucun stockage
personnel distinct ni table propre au plugin n’est créé. Les fichiers brouillons
suivent la rétention de Moodle. Les fichiers temporaires sont supprimés à la fin
d’une requête normale ; après une interruption brutale, le nettoyage Moodle s’applique.

Le nom du fichier sert de texte alternatif initial. Il ne remplace pas une
description accessible du contenu : ajoutez du texte ou une description dans
les chapitres pour les graphiques, schémas et diapositives contenant du texte.
Aucune reconnaissance de texte n’est réalisée.

## État de validation

Les contrôles GitHub Actions ont réussi le 28 septembre 2026 sur Moodle 4.5/PHP 8.1,
5.0/PHP 8.2, 5.1/PHP 8.3 et 5.2/PHP 8.3, avec PostgreSQL 17 : syntaxe PHP,
standards Moodle, PHPDoc, structure du plugin, points de mise à jour et tests PHPUnit.
[Consulter l'exécution réussie](https://github.com/edunover/moodle-local_pptxbook/actions/runs/36421802131).

Les tests portent sur les archives et l'importation dans un Livre. Ils ne remplacent
pas les essais visuels, de sauvegarde/restauration ou de concurrence.
La version reste bêta ; aucune approbation Marketplace n'est revendiquée.
Voir [TESTING.md](TESTING.md) et [le guide GitHub Actions](GITHUB_TESTS.fr.md).

Licence : GPL v3 ou ultérieure.
