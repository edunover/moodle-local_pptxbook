# Rapport de validation

## GitHub Actions — 28 septembre 2026

[Exécution réussie no 3](https://github.com/edunover/moodle-local_pptxbook/actions/runs/36421802131), commit
`ce43d15222a03659885a2d2d09a38d8b263daba4`.

| Moodle | PHP | Base | Résultat |
|---|---|---|---|
| 4.5 | 8.1 | PostgreSQL 17 | Réussi |
| 5.0 | 8.2 | PostgreSQL 17 | Réussi |
| 5.1 | 8.3 | PostgreSQL 17 | Réussi |
| 5.2 | 8.3 | PostgreSQL 17 | Réussi |

Chaque tâche a exécuté : installation Moodle, syntaxe PHP, Moodle CodeSniffer
sans avertissement accepté, PHPDoc sans avertissement accepté, validation de la
structure, cohérence des points de mise à jour et PHPUnit.

## Tests PHPUnit exécutés

- `archive_test.php` : 2 tests couvrant ordre naturel, titres, formats PNG/JPEG,
  préservation des octets, métadonnées système, fichiers interdits, chemins dangereux,
  doublons, corruption, limites, symlinks et nettoyage après erreur.
- `importer_test.php` : 2 tests couvrant ajout des chapitres, conservation du contenu
  et de la visibilité, stockage natif des images, imports successifs et refus d'un étudiant.

## Corrections révélées par les exécutions

La première exécution a détecté un ordre incorrect des clés des traductions.
Le tri a été corrigé dans les trois langues. Les métadonnées de couverture des tests
ont été adaptées à PHPUnit 9 et 11, puis leur disposition ajustée au vérificateur Moodle 4.5.
La troisième exécution a réussi sur les quatre versions.

## Vérifications restant à effectuer

- Menu Plus et formulaire dans les thèmes Moodle utilisés.
- Installation et migration depuis une version précédente sur un site existant.
- Accès aux images d'un Livre masqué et accès hors connexion.
- Sauvegarde/restauration et suppression des fichiers des chapitres.
- Erreur d'écriture pendant l'import, rollback et deux imports simultanés.
- Autres moteurs de base et autres versions PHP pris en charge.

L'utilisateur a déjà validé l'import et les noms de chapitres sur son Moodle 4.5.
Les résultats automatisés ne constituent pas une approbation Marketplace.

## Relancer

Voir [le guide détaillé en français](GITHUB_TESTS.fr.md).
