# Vérification — version ZIP 0.2.0

## Contrôles exécutés

- Analyse de syntaxe de tous les fichiers PHP sous PHP 8.4.6 : réussie.
- `php tests/standalone.php` : **41 vérifications réussies**.
- Archives PNG/JPEG réelles générées par GD : tri numérique, conservation des octets
  JPEG, MIME et extension, métadonnées système ignorées.
- Refus : archives non valides, traversée de dossiers, chemins absolus/Windows,
  liens symboliques, doublons, fausses images, mauvaise extension, taille,
  dimensions excessives, nombre d’images excessif, fichiers non autorisés.
- Suppression des images temporaires partielles en cas d’erreur.
- Callback de navigation exercé avec des doublures : Livre uniquement, permissions,
  identifiant d’activité correct et placement forcé dans le menu Plus.

Ces contrôles n’exécutent pas Moodle. La vérification du callback avec des doublures
ne démontre pas le rendu du menu dans un thème réel.

## Tests Moodle fournis, non exécutés ici

Sur une installation Moodle réservée aux tests, initialisée pour PHPUnit :

```sh
# Moodle 4.5 / 5.0, depuis la racine Moodle
vendor/bin/phpunit local/pptxbook/tests/importer_test.php
# Moodle 5.1 / 5.2, depuis la racine du dépôt
vendor/bin/phpunit public/local/pptxbook/tests/importer_test.php
```

Ces tests vérifient l’ajout à un Livre existant, la conservation du premier chapitre,
les titres, les fichiers, l’absence de nouvelle activité, la conservation de la
visibilité, les imports successifs et le refus d’accès d’un étudiant.

## Recette à exécuter sur Moodle 4.5, 5.0, 5.1 et 5.2

1. Installer le plugin ou mettre à jour la version 0.1.0 ; vérifier les réglages
   et la disparition des options LibreOffice/Poppler.
2. Créer un Livre avec un chapitre existant. Comme enseignant éditeur, vérifier
   **Plus → Importer des images (ZIP)** dans le Livre ; aucune commande ne doit
   apparaître dans une autre activité ou pour un étudiant.
3. Importer un ZIP contenant Slide1.png, Slide2.jpg, Slide10.png. Vérifier les trois
   nouveaux chapitres dans cet ordre, après le contenu existant, et afficher les images.
4. Vérifier le français, l’anglais, la visibilité du Livre et les permissions
   mod/book:edit/local/pptxbook:import retirées individuellement.
5. Tester un Livre vide, un Livre contenant des sous-chapitres et un deuxième import.
6. En mode étudiant, vérifier l’accès aux images du Livre visible et le refus
   d’accès au Livre masqué ; tester également les URL des images hors connexion.
7. Tester les limites et un ZIP corrompu : aucun nouveau chapitre ne doit apparaître.
8. Sauvegarder/restaurer le Livre dans un autre cours ; vérifier les images.
9. Supprimer un chapitre importé et vérifier le nettoyage natif des fichiers.
10. Tester une panne d’écriture au milieu d’un import et le rollback des chapitres
    et fichiers, ainsi que deux imports simultanés dans le même Livre.

## Conformité de code

Les en-têtes GPL complets et les principales documentations PHPDoc ont été ajoutés.
Le passage avec moodlehq/moodle-cs et PHPDoc reste à réaliser : il n’a pas été exécuté
ici. Aucun résultat de conformité automatique complète n’est revendiqué.
