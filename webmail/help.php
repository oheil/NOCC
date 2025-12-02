<?php
/**
 * Help
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
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: help.php 3187 2025-12-02 16:27:49Z oheil $
 */

require_once './common.php';
$lang = $_SESSION['nocc_lang'];

$theme = new NOCC_Theme($_SESSION['nocc_theme']);
?>
<!DOCTYPE html>
<html lang="<?php echo $lang ?>">
<head>
  <title>NOCC - Webmail - <?php echo $html_help ?></title>
  <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
  <link href="<?php echo $theme->getStylesheet(); ?>" rel="stylesheet" type="text/css" />
</head>
<body dir="<?php echo $lang_dir; ?>">

</body>
</html>
