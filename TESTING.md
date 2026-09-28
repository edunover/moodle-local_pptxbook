# Rapport de validation

## GitHub Actions — 28 septembre 2026

[Exécution réussie](https://github.com/edunover/moodle-local_pptxbook/actions/runs/36445221979), commit
`8bca96ea130be424432b1eee28a8e877971074b3`.

| Moodle | PHP | Base | Résultat |
|---|---|---|---|
| 4.5 | 8.1 | PostgreSQL 17 | Réussi |
| 5.0 | 8.2 | PostgreSQL 17 | Réussi |
| 5.1 | 8.3 | PostgreSQL 17 | Réussi |
| 5.2 | 8.3 | PostgreSQL 17 | Réussi |
| 5.2 | 8.3 | MariaDB 11 | Réussi |

Chaque tâche a exécuté : installation Moodle, syntaxe PHP, Moodle CodeSniffer
sans avertissement accepté, PHPDoc sans avertissement accepté, validation de la
structure, cohérence des points de mise à jour et PHPUnit.

## Tests PHPUnit exécutés

- `archive_test.php` : 2 tests couvrant ordre naturel, titres, formats PNG/JPEG,
  préservation des octets, métadonnées système, fichiers interdits, chemins dangereux,
  doublons, corruption, limites, symlinks et nettoyage après erreur.
- `importer_test.php` : 2 tests couvrant ajout des chapitres, conservation du contenu
  et de la visibilité, stockage natif des images, imports successifs et refus d'un étudiant.

## Vérifications manuelles complémentaires — 28 septembre 2026

- Installation ZIP, réglages et import de trois images : réussis.
- Ordre naturel, navigation et traductions EN/FR/NL : réussis.
- Refus d'accès pour un étudiant : réussi.
- Mise à niveau depuis une version précédente : réussie.
- Rejet d'un ZIP contenant un fichier non pris en charge : réussi, sans ajout de chapitre.
- Sauvegarde/restauration du Livre et suppression des fichiers importés : réussies.

## Vérifications restant à effectuer

- Accès aux images d'un Livre masqué et accès hors connexion.
- Erreur d'écriture pendant l'import, rollback et deux imports simultanés.

Les résultats automatisés et manuels ne constituent pas une approbation Marketplace.

## Relancer

Voir [le guide détaillé en français](GITHUB_TESTS.fr.md) ou
[sa version anglaise](GITHUB_TESTS.en.md).
