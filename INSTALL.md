# Installation de la version 1.0.0

1. Sauvegardez votre site avant toute mise à jour.
2. Ouvrez **Administration du site → Plugins → Installer des plugins**.
3. Sélectionnez `local_pptxbook-1.0.0.zip` et terminez la mise à jour.
4. Les réglages sont sous **Plugins → Plugins locaux → Images de diapositives vers Livre**.
5. Dans un Livre, ouvrez **Plus → Importer des images**.

PHP ZIP et GD doivent être activés. LibreOffice n'est pas nécessaire.
Exportez d'abord vos diapositives en PNG/JPEG, puis compressez les images en ZIP.

Pour une installation manuelle, le dossier `pptxbook` va dans `local/` sous Moodle
4.5–5.0, ou `public/local/` sous Moodle 5.1–5.2. Remplacez entièrement l'ancien
dossier avant de visiter les notifications Moodle. Ne désinstallez pas d'abord.

Consultez [la documentation](README.md) pour les limites et
[le rapport de tests](TESTING.md) pour l'état exact de validation.

English versions: [installation guide](INSTALL.en.md),
[documentation](README.en.md) and [test report](TESTING.en.md).
