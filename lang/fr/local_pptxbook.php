<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <https://www.gnu.org/licenses/>.

/**
 * Import a ZIP of slide images into a Moodle Book.
 *
 * @package    local_pptxbook
 * @copyright  2026 PowerPoint to Book contributors
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Images de diapositives vers Livre';
$string['pptxbook:import'] = 'Importer des images de diapositives dans un Livre';
$string['import'] = 'Importer des images (ZIP)';
$string['bookname'] = 'Nom du Livre';
$string['section'] = 'Section du cours';
$string['presentation'] = 'Archive ZIP d’images PNG/JPEG';
$string['importnotice'] = 'Les images sont triées par nom de fichier, dans l’ordre naturel (Diapositive2 avant Diapositive10). Utilisez des noms cohérents et placez les images dans un même dossier. Chaque nom devient un titre de chapitre. Les images sont ajoutées à la fin de ce Livre. Les chapitres existants sont conservés. Si le Livre est visible, les nouveaux chapitres le seront immédiatement. Ajoutez si nécessaire des descriptions accessibles. Gardez cette page ouverte jusqu’à la fin de l’import.';
$string['invalidname'] = 'Saisissez un nom de Livre de 1 à 255 caractères.';
$string['maxslides'] = 'Nombre maximal d’images';
$string['maxslides_desc'] = 'Nombre maximal d’images par import (1 à 200). Par défaut : 50.';
$string['maxmb'] = 'Taille maximale du ZIP (Mo)';
$string['maxmb_desc'] = 'Limite d’envoi du ZIP (1 à 100 Mo). Les limites Moodle, du cours et de PHP s’appliquent aussi. Par défaut : 25 Mo.';
$string['notconfigured'] = 'Les extensions PHP ZIP et GD sont nécessaires. Demandez leur activation à votre hébergeur.';
$string['invalidzip'] = 'Archive ZIP invalide, endommagée ou non sûre. Créez un nouveau ZIP contenant uniquement des images PNG/JPEG.';
$string['archivelimit'] = 'Le ZIP dépasse 5 000 entrées ou 128 Mo de contenu décompressé.';
$string['unsupportedfile'] = 'Le ZIP contient un fichier autre qu’une image PNG ou JPEG. Retirez les autres documents avant l’import.';
$string['duplicatename'] = 'Le ZIP contient des chemins identiques, sans distinction de majuscules. Renommez les fichiers avant l’import.';
$string['imagelimit'] = 'Une image est vide, chiffrée ou dépasse 10 Mo.';
$string['invalidimage'] = 'Une image est endommagée, porte une extension incorrecte ou dépasse 8 millions de pixels ou 16 000 pixels sur un côté. Exportez des images PNG/JPEG plus petites.';
$string['writefailed'] = 'Impossible d’écrire les fichiers temporaires. Vérifiez le stockage temporaire Moodle et l’espace disque.';
$string['slidelimit'] = 'Le ZIP doit contenir entre 1 et {$a} images.';
$string['invalidsection'] = 'Sélectionnez une section ordinaire existante du cours.';
$string['busy'] = 'Un autre ZIP est en cours d’import dans ce Livre. Réessayez dans un instant.';
$string['slide'] = 'Diapositive {$a}';
$string['success'] = '{$a} chapitres ont été ajoutés à la fin du Livre.';
$string['privacy:metadata'] = 'Le plugin ne possède pas de stockage distinct de données personnelles. Les fichiers brouillons relèvent du système de fichiers Moodle ; les chapitres et images relèvent de mod_book. Les images temporaires sont supprimées après l’import.';
