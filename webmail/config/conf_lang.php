<?php
/**
 * Language configuration for NOCC
 *
 * Copyright 2001 Nicolas Chalanset <nicocha@free.fr>
 * Copyright 2001 Olivier Cahagne <cahagn_o@epita.fr>
 * Copyright 2008-2011 Tim Gerundt <tim@gerundt.de>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @subpackage Configuration
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: conf_lang.php 2967 2021-12-10 14:24:34Z oheil $
 */

// ################### Language Array  ################### //
// If you add language files in 'lang/' folder, please list them here

class lang {
  var $filename="";
  var $label="";
}

//TODO: Move to "lang" class?
if (!isset($lang_dir)) { //if NO language direction defined...
  $lang_dir = 'ltr';
}

$i = 0;

// Afrikaans
//$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'af';
$lang_array[$i]->label = 'Afrikaans';

// Aragonese
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'an';
$lang_array[$i]->label = 'Aragonés';

// Arabic
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ar';
$lang_array[$i]->label = 'العربية';

// Egyptian Spoken Arabic
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'arz';
$lang_array[$i]->label = 'مصرى';

// Belarusian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'be-tarask';
$lang_array[$i]->label = 'Беларуская (тарашкевіца)';

// Bulgarian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'bg';
$lang_array[$i]->label = 'Български';

// Breton
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'br';
$lang_array[$i]->label = 'Brezhoneg';

// Bosnian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'bs';
$lang_array[$i]->label = 'Bosanski';

// Catalan
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ca';
$lang_array[$i]->label = 'Català';

// Czech
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'cs';
$lang_array[$i]->label = 'Česky';

// Welsh
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'cy';
$lang_array[$i]->label = 'Cymraeg';

// Danish
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'da';
$lang_array[$i]->label = 'Dansk';

// Lower Sorbian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'dsb';
$lang_array[$i]->label = 'Dolnoserbski';

// German
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'de';
$lang_array[$i]->label = 'Deutsch';

// Swiss German
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'gsw';
$lang_array[$i]->label = 'Deutsch (Schweiz)';

// English
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'en';
$lang_array[$i]->label = 'English';

// Greek
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'el';
$lang_array[$i]->label = 'Ελληνικά';

// Spanish
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'es';
$lang_array[$i]->label = 'Español';

// Basque
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'eu';
$lang_array[$i]->label = 'Euskara';

// Persian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'fa';
$lang_array[$i]->label = 'فارسی';

// French
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'fr';
$lang_array[$i]->label = 'Français';

// Irish
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ga';
$lang_array[$i]->label = 'Gaeilge';

// Galician
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'gl';
$lang_array[$i]->label = 'Galego';

// Hawaiian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'haw';
$lang_array[$i]->label = 'Hawai`i';

// Hebrew
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'he';
$lang_array[$i]->label = 'עברית';

// Croatian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'hr';
$lang_array[$i]->label = 'Hrvatski';

// Upper Sorbian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'hsb';
$lang_array[$i]->label = 'Hornjoserbsce';

// Interlingua
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ia';
$lang_array[$i]->label = 'Interlingua';

// Indonesian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'id';
$lang_array[$i]->label = 'Bahasa Indonesia';

// Icelandic
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'is';
$lang_array[$i]->label = 'Íslenska';

// Italian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'it';
$lang_array[$i]->label = 'Italiano';

// Latvian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'lv';
$lang_array[$i]->label = 'Latviesu';

// Luxembourgish
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'lb';
$lang_array[$i]->label = 'Lëtzebuergesch';

// Hungarian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'hu';
$lang_array[$i]->label = 'Magyar';

// Macedonian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'mk';
$lang_array[$i]->label = 'Македонски';

// Malay
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ms';
$lang_array[$i]->label = 'Bahasa Melayu';

// Nepali
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ne';
$lang_array[$i]->label = 'नेपाली';

// Dutch
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'nl';
$lang_array[$i]->label = 'Nederlands';

// Norwegian nynorsk
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'nn';
$lang_array[$i]->label = 'Norsk nynorsk';

// Occitan
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'oc';
$lang_array[$i]->label = 'Occitan';

// Polish
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'pl';
$lang_array[$i]->label = 'Polski';

// Pashto
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ps';
$lang_array[$i]->label = 'پښتو';

// Portuguese
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'pt';
$lang_array[$i]->label = 'Português';

// Brazilian Portuguese
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'pt-br';
$lang_array[$i]->label = 'Português Brasileiro';

// Ripoarisch
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ksh';
$lang_array[$i]->label = 'Ripoarisch';

// Romanian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ro';
$lang_array[$i]->label = 'Română';

// Russian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ru';
$lang_array[$i]->label = 'Русский';

// Slovak
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'sk';
$lang_array[$i]->label = 'Slovenčina';

// Slovene
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'sl';
$lang_array[$i]->label = 'Slovenščina';

// Serbian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'sr';
$lang_array[$i]->label = 'Srpski';

// Serbian Cyrillic ekavian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'sr-ec';
$lang_array[$i]->label = 'Српски (ћирилица)';

// Finnish
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'fi';
$lang_array[$i]->label = 'Suomi';

// Swedish
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'sv';
$lang_array[$i]->label = 'Svenska';

// Tagalog
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'tl';
$lang_array[$i]->label = 'Tagalog';

// Thai
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'th';
$lang_array[$i]->label = 'ไทย';

// Turkish
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'tr';
$lang_array[$i]->label = 'Türkçe';

// Ukrainian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'uk';
$lang_array[$i]->label = 'Українська';

// Japanese
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ja';
$lang_array[$i]->label = '日本語';

// Korean
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ko';
$lang_array[$i]->label = '한국어';

// Chinese (Simplified)
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'zh-hans';
$lang_array[$i]->label = '中文';

// Chinese (Traditional)
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'zh-hant';
$lang_array[$i]->label = '‪中文（繁體）';

// Tamil
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ta';
$lang_array[$i]->label = '‪தமிழ்';

// Georgian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ka';
$lang_array[$i]->label = 'ქართული';

// Aramaic
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'arc';
$lang_array[$i]->label = 'ܐܪܡܝܐ';

// Asturian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ast';
$lang_array[$i]->label = 'asturianu';

// Bangla
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'bn';
$lang_array[$i]->label = 'বাংলা';

// Chechen
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ce';
$lang_array[$i]->label = 'нохчийн';

// Zazaki
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'diq';
$lang_array[$i]->label = 'Zazakî';

// British English
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'en-gb';
$lang_array[$i]->label = 'British English';

// Esperanto
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'eo';
$lang_array[$i]->label = 'Esperanto';

// Tornedalen Finnish
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'fit';
$lang_array[$i]->label = 'meänkieli';

// Faroese
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'fo';
$lang_array[$i]->label = 'føroyskt';

// Kabyle
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'kab';
$lang_array[$i]->label = 'Taqbaylit';

// Kannada
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'kn';
$lang_array[$i]->label = 'ಕನ್ನಡ';

// Laki
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'lki';
$lang_array[$i]->label = 'لەکی';

// Lingala
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ln';
$lang_array[$i]->label = 'lingála';

// Lithuanian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'lt';
$lang_array[$i]->label = 'lietuvių';

// Malagasy
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'mg';
$lang_array[$i]->label = 'Malagasy';

// Malayalam
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ml';
$lang_array[$i]->label = 'മലയാളം';

// Mon
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'mnw';
$lang_array[$i]->label = 'ဘာသာ မန်';

// Marathi
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'mr';
$lang_array[$i]->label = 'मराठी';

// Burmese
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'my';
$lang_array[$i]->label = 'မြန်မာဘာသာ';

// Norwegian Bokmål
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'nb';
$lang_array[$i]->label = 'norsk bokmål';

// Punjabi
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'pa';
$lang_array[$i]->label = 'ਪੰਜਾਬੀ';

// Rusyn
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'rue';
$lang_array[$i]->label = 'русиньскый';

// Sindhi
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'sd';
$lang_array[$i]->label = 'سنڌي';

// Shan
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'shn';
$lang_array[$i]->label = 'ၽႃႇသႃႇတႆး ';

// Saraiki (Arabic script)
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'skr-arab';
$lang_array[$i]->label = 'سرائیکی';

// Uyghur (Arabic script)
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'ug-arab';
$lang_array[$i]->label = 'ئۇيغۇرچە';

// Vietnamese
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'vi';
$lang_array[$i]->label = 'Tiếng Việt';

// Mingrelian
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'xmf';
$lang_array[$i]->label = 'მარგალური';

// Standard Moroccan Tamazight
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'zgh';
$lang_array[$i]->label = 'ⵜⴰⵎⴰⵣⵉⵖⵜ ⵜⴰⵏⴰⵡⴰⵢⵜ';


usort($lang_array, function($a,$b){return($a->filename <=> $b->filename);});


/* Message documentation (translatewiki.net specific)
$i++;
$lang_array[$i] = new lang();
$lang_array[$i]->filename = 'qqq';
$lang_array[$i]->label = 'Message documentation';
*/
