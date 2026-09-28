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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Import a ZIP of slide images into a Moodle Book.
 *
 * @package    local_pptxbook
 * @copyright  2026 EDUNOVER
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$string['archivelimit'] = 'Het ZIP-bestand bevat meer dan 5.000 items of meer dan 128 MB uitgepakte inhoud.';
$string['bookname'] = 'Naam van het boek';
$string['busy'] = 'Er wordt al een ander ZIP-bestand in dit boek geïmporteerd. Probeer het zo opnieuw.';
$string['duplicatename'] = 'Het ZIP-bestand bevat dubbele paden (zonder onderscheid tussen hoofdletters en kleine letters). Hernoem de bestanden vóór de import.';
$string['imagelimit'] = 'Een afbeelding is leeg, versleuteld of groter dan 10 MB.';
$string['import'] = 'Afbeeldingen importeren (ZIP)';
$string['importnotice'] = 'Afbeeldingen worden natuurlijk gesorteerd op hun volledige relatieve bestandsnaam (Dia2 vóór Dia10). Gebruik consistente namen en plaats de afbeeldingen in één map. Elke bestandsnaam wordt een hoofdstuktitel. Afbeeldingen worden aan dit boek toegevoegd; bestaande hoofdstukken blijven behouden. Als het boek zichtbaar is, zijn nieuwe hoofdstukken onmiddellijk zichtbaar. Voeg waar nodig zelf toegankelijke beschrijvingen toe. Houd deze pagina open totdat de import is voltooid.';
$string['invalidimage'] = 'Een afbeelding is beschadigd, heeft de verkeerde extensie of overschrijdt 8 miljoen pixels of 16.000 pixels aan een zijde. Exporteer kleinere PNG/JPEG-afbeeldingen.';
$string['invalidname'] = 'Voer een boeknaam in van 1 tot 255 tekens.';
$string['invalidsection'] = 'Selecteer een bestaande gewone cursussectie.';
$string['invalidzip'] = 'Ongeldig, beschadigd of onveilig ZIP-archief. Maak een nieuw ZIP-bestand met alleen PNG/JPEG-afbeeldingen.';
$string['maxmb'] = 'Maximale ZIP-grootte (MB)';
$string['maxmb_desc'] = 'Uploadlimiet voor ZIP-bestanden (1–100 MB). De limieten van Moodle, de cursus en PHP zijn ook van toepassing. Standaard: 25 MB.';
$string['maxslides'] = 'Maximumaantal afbeeldingen';
$string['maxslides_desc'] = 'Maximumaantal afbeeldingen per import (1–200). Standaard: 50.';
$string['notconfigured'] = 'De PHP-extensies ZIP en GD zijn vereist. Vraag uw hostingprovider om deze te activeren.';
$string['pluginname'] = 'Dia-afbeeldingen naar boek';
$string['pptxbook:import'] = 'Dia-afbeeldingen als hoofdstukken in een boek importeren';
$string['presentation'] = 'ZIP-archief met PNG/JPEG-afbeeldingen';
$string['privacy:metadata'] = 'De plugin heeft geen afzonderlijke opslag voor persoonsgegevens. Conceptuploads vallen onder de Moodle-bestanden; hoofdstukken en afbeeldingen vallen onder mod_book. Tijdelijke afbeeldingen worden na de import verwijderd.';
$string['section'] = 'Cursussectie';
$string['slide'] = 'Dia {$a}';
$string['slidelimit'] = 'Het ZIP-bestand moet tussen 1 en {$a} afbeeldingen bevatten.';
$string['success'] = '{$a} hoofdstukken zijn aan het einde van het boek toegevoegd.';
$string['unsupportedfile'] = 'Het ZIP-bestand bevat een ander bestand dan PNG of JPEG. Verwijder andere documenten vóór de import.';
$string['writefailed'] = 'De tijdelijke bestanden konden niet worden geschreven. Controleer de tijdelijke opslag van Moodle en de beschikbare schijfruimte.';
