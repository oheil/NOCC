<?php
/**
 * Test cases for NOCC_InternetMediaType.
 *
 * Copyright 2010-2011 Tim Gerundt <tim@gerundt.de>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @subpackage Tests
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: NOCC_InternetMediaTypeTest.php 2373 2011-01-04 15:06:58Z gerundt $
 */

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../classes/nocc_internetmediatype.php';

/**
 * Test class for NOCC_InternetMediaType.
 */
class NOCC_InternetMediaTypeTest extends PHPUnit_Framework_TestCase {
    /**
     * @var NOCC_InternetMediaType
     */
    protected $internetMediaTypeNull;

    /**
     * @var NOCC_InternetMediaType
     */
    protected $internetMediaTypeBug;

    /**
     * @var NOCC_InternetMediaType
     */
    protected $internetMediaType0;

    /**
     * @var NOCC_InternetMediaType
     */
    protected $internetMediaType1;

    /**
     * @var NOCC_InternetMediaType
     */
    protected $internetMediaType2;

    /**
     * @var NOCC_InternetMediaType
     */
    protected $internetMediaType3;

    /**
     * @var NOCC_InternetMediaType
     */
    protected $internetMediaType4;

    /**
     * @var NOCC_InternetMediaType
     */
    protected $internetMediaType5;

    /**
     * @var NOCC_InternetMediaType
     */
    protected $internetMediaType6;

    /**
     * @var NOCC_InternetMediaType
     */
    protected $internetMediaType7;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->internetMediaTypeNull = new NOCC_InternetMediaType(null, null);
        $this->internetMediaTypeBug = new NOCC_InternetMediaType('bug', 'bug');
        $this->internetMediaType0 = new NOCC_InternetMediaType(0, 'plain');
        $this->internetMediaType1 = new NOCC_InternetMediaType(1, 'ALTERNATIVE');
        $this->internetMediaType2 = new NOCC_InternetMediaType(2, 'RFC822');
        $this->internetMediaType3 = new NOCC_InternetMediaType(3, 'Pdf');
        $this->internetMediaType4 = new NOCC_InternetMediaType(4, 'mpeg');
        $this->internetMediaType5 = new NOCC_InternetMediaType(5, 'PNG');
        $this->internetMediaType6 = new NOCC_InternetMediaType(6, 'quicktime');
        $this->internetMediaType7 = new NOCC_InternetMediaType(7, 'Test');
    }

    /**
     * Test case for getSubtype().
     */
    public function test__GetSubtype() {
        $this->assertEquals('', $this->internetMediaTypeNull->getSubtype(), 'null, null');
        $this->assertEquals('', $this->internetMediaTypeBug->getSubtype(), 'bug, bug');
        $this->assertEquals('plain', $this->internetMediaType0->getSubtype(), '0, plain');
        $this->assertEquals('alternative', $this->internetMediaType1->getSubtype(), '1, ALTERNATIVE');
        $this->assertEquals('rfc822', $this->internetMediaType2->getSubtype(), '2, RFC822');
        $this->assertEquals('pdf', $this->internetMediaType3->getSubtype(), '3, Pdf');
        $this->assertEquals('mpeg', $this->internetMediaType4->getSubtype(), '4, mpeg');
        $this->assertEquals('png', $this->internetMediaType5->getSubtype(), '5, PNG');
        $this->assertEquals('quicktime', $this->internetMediaType6->getSubtype(), '6, quicktime');
        $this->assertEquals('test', $this->internetMediaType7->getSubtype(), '7, Test');
    }

    /**
     * Test case for isText().
     */
    public function testIsText() {
        $htmlText = new NOCC_InternetMediaType(0, 'HTML');

        $this->assertFalse($this->internetMediaTypeNull->isText(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isText(), 'bug, bug');
        $this->assertTrue($this->internetMediaType0->isText(), '0, plain');
        $this->assertFalse($this->internetMediaType1->isText(), '1, ALTERNATIVE');
        $this->assertFalse($this->internetMediaType2->isText(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isText(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isText(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isText(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isText(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isText(), '7, Test');

        $this->assertTrue($htmlText->isText(), '0, HTML');
    }

    /**
     * Test case for isPlainText().
     */
    public function testIsPlainText() {
        $htmlText = new NOCC_InternetMediaType(0, 'HTML');

        $this->assertFalse($this->internetMediaTypeNull->isPlainText(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isPlainText(), 'bug, bug');
        $this->assertTrue($this->internetMediaType0->isPlainText(), '0, plain');
        $this->assertFalse($this->internetMediaType1->isPlainText(), '1, ALTERNATIVE');
        $this->assertFalse($this->internetMediaType2->isPlainText(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isPlainText(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isPlainText(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isPlainText(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isPlainText(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isPlainText(), '7, Test');

        $this->assertFalse($htmlText->isPlainText(), '0, HTML');
    }

    /**
     * Test case for isHtmlText().
     */
    public function testIsHtmlText() {
        $htmlText = new NOCC_InternetMediaType(0, 'HTML');

        $this->assertFalse($this->internetMediaTypeNull->isHtmlText(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isHtmlText(), 'bug, bug');
        $this->assertFalse($this->internetMediaType0->isHtmlText(), '0, plain');
        $this->assertFalse($this->internetMediaType1->isHtmlText(), '1, ALTERNATIVE');
        $this->assertFalse($this->internetMediaType2->isHtmlText(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isHtmlText(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isHtmlText(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isHtmlText(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isHtmlText(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isHtmlText(), '7, Test');

        $this->assertTrue($htmlText->isHtmlText(), '0, HTML');
    }

    /**
     * Test case for isPlainOrHtmlText().
     */
    public function testIsPlainOrHtmlText() {
        $htmlText = new NOCC_InternetMediaType(0, 'HTML');

        $this->assertFalse($this->internetMediaTypeNull->isPlainOrHtmlText(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isPlainOrHtmlText(), 'bug, bug');
        $this->assertTrue($this->internetMediaType0->isPlainOrHtmlText(), '0, plain');
        $this->assertFalse($this->internetMediaType1->isPlainOrHtmlText(), '1, ALTERNATIVE');
        $this->assertFalse($this->internetMediaType2->isPlainOrHtmlText(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isPlainOrHtmlText(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isPlainOrHtmlText(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isPlainOrHtmlText(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isPlainOrHtmlText(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isPlainOrHtmlText(), '7, Test');

        $this->assertTrue($htmlText->isPlainOrHtmlText(), '0, HTML');
    }

    /**
     * Test case for isMultipart().
     */
    public function testIsMultipart() {
        $this->assertFalse($this->internetMediaTypeNull->isMultipart(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isMultipart(), 'bug, bug');
        $this->assertFalse($this->internetMediaType0->isMultipart(), '0, plain');
        $this->assertTrue($this->internetMediaType1->isMultipart(), '1, ALTERNATIVE');
        $this->assertFalse($this->internetMediaType2->isMultipart(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isMultipart(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isMultipart(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isMultipart(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isMultipart(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isMultipart(), '7, Test');
    }

    /**
     * Test case for isAlternativeMultipart().
     */
    public function testIsAlternativeMultipart() {
        $relatedMultipart = new NOCC_InternetMediaType(1, 'related');

        $this->assertFalse($this->internetMediaTypeNull->isAlternativeMultipart(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isAlternativeMultipart(), 'bug, bug');
        $this->assertFalse($this->internetMediaType0->isAlternativeMultipart(), '0, plain');
        $this->assertTrue($this->internetMediaType1->isAlternativeMultipart(), '1, ALTERNATIVE');
        $this->assertFalse($this->internetMediaType2->isAlternativeMultipart(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isAlternativeMultipart(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isAlternativeMultipart(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isAlternativeMultipart(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isAlternativeMultipart(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isAlternativeMultipart(), '7, Test');

        $this->assertFalse($relatedMultipart->isAlternativeMultipart(), '1, related');
    }

    /**
     * Test case for isRelatedMultipart().
     */
    public function testIsRelatedMultipart() {
        $relatedMultipart = new NOCC_InternetMediaType(1, 'related');

        $this->assertFalse($this->internetMediaTypeNull->isRelatedMultipart(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isRelatedMultipart(), 'bug, bug');
        $this->assertFalse($this->internetMediaType0->isRelatedMultipart(), '0, plain');
        $this->assertfalse($this->internetMediaType1->isRelatedMultipart(), '1, ALTERNATIVE');
        $this->assertFalse($this->internetMediaType2->isRelatedMultipart(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isRelatedMultipart(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isRelatedMultipart(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isRelatedMultipart(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isRelatedMultipart(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isRelatedMultipart(), '7, Test');

        $this->assertTrue($relatedMultipart->isRelatedMultipart(), '1, related');
    }

    /**
     * Test case for isMessage().
     */
    public function testIsMessage() {
        $this->assertFalse($this->internetMediaTypeNull->isMessage(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isMessage(), 'bug, bug');
        $this->assertFalse($this->internetMediaType0->isMessage(), '0, plain');
        $this->assertFalse($this->internetMediaType1->isMessage(), '1, ALTERNATIVE');
        $this->assertTrue($this->internetMediaType2->isMessage(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isMessage(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isMessage(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isMessage(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isMessage(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isMessage(), '7, Test');
    }

    /**
     * Test case for isRfc822Message().
     */
    public function testIsRfc822Message() {
        $testMessage = new NOCC_InternetMediaType(2, 'test');

        $this->assertFalse($this->internetMediaTypeNull->isRfc822Message(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isRfc822Message(), 'bug, bug');
        $this->assertFalse($this->internetMediaType0->isRfc822Message(), '0, plain');
        $this->assertFalse($this->internetMediaType1->isRfc822Message(), '1, ALTERNATIVE');
        $this->assertTrue($this->internetMediaType2->isRfc822Message(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isRfc822Message(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isRfc822Message(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isRfc822Message(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isRfc822Message(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isRfc822Message(), '7, Test');

        $this->assertFalse($testMessage->isRfc822Message(), '2, test');
    }

    /**
     * Test case for isApplication().
     */
    public function testIsApplication() {
        $this->assertFalse($this->internetMediaTypeNull->isApplication(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isApplication(), 'bug, bug');
        $this->assertFalse($this->internetMediaType0->isApplication(), '0, plain');
        $this->assertFalse($this->internetMediaType1->isApplication(), '1, ALTERNATIVE');
        $this->assertFalse($this->internetMediaType2->isApplication(), '2, RFC822');
        $this->assertTrue($this->internetMediaType3->isApplication(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isApplication(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isApplication(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isApplication(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isApplication(), '7, Test');
    }

    /**
     * Test case for isAudio().
     */
    public function testIsAudio() {
        $this->assertFalse($this->internetMediaTypeNull->isAudio(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isAudio(), 'bug, bug');
        $this->assertFalse($this->internetMediaType0->isAudio(), '0, plain');
        $this->assertFalse($this->internetMediaType1->isAudio(), '1, ALTERNATIVE');
        $this->assertFalse($this->internetMediaType2->isAudio(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isAudio(), '3, Pdf');
        $this->assertTrue($this->internetMediaType4->isAudio(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isAudio(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isAudio(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isAudio(), '7, Test');
    }

    /**
     * Test case for isImage().
     */
    public function testIsImage() {
        $this->assertFalse($this->internetMediaTypeNull->isImage(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isImage(), 'bug, bug');
        $this->assertFalse($this->internetMediaType0->isImage(), '0, plain');
        $this->assertFalse($this->internetMediaType1->isImage(), '1, ALTERNATIVE');
        $this->assertFalse($this->internetMediaType2->isImage(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isImage(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isImage(), '4, mpeg');
        $this->assertTrue($this->internetMediaType5->isImage(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isImage(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isImage(), '7, Test');
    }

    /**
     * Test case for isVideo().
     */
    public function testIsVideo() {
        $this->assertFalse($this->internetMediaTypeNull->isVideo(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isVideo(), 'bug, bug');
        $this->assertFalse($this->internetMediaType0->isVideo(), '0, plain');
        $this->assertFalse($this->internetMediaType1->isVideo(), '1, ALTERNATIVE');
        $this->assertFalse($this->internetMediaType2->isVideo(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isVideo(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isVideo(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isVideo(), '5, PNG');
        $this->assertTrue($this->internetMediaType6->isVideo(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isVideo(), '7, Test');
    }

    /**
     * Test case for isOther().
     */
    public function testIsOther() {
        $this->assertFalse($this->internetMediaTypeNull->isOther(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isOther(), 'bug, bug');
        $this->assertFalse($this->internetMediaType0->isOther(), '0, plain');
        $this->assertFalse($this->internetMediaType1->isOther(), '1, ALTERNATIVE');
        $this->assertFalse($this->internetMediaType2->isOther(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isOther(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isOther(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isOther(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isOther(), '6, quicktime');
        $this->assertTrue($this->internetMediaType7->isOther(), '7, Test');
    }

    /**
     * Test case for isAlternative().
     */
    public function testIsAlternative() {
        $relatedMultipart = new NOCC_InternetMediaType(1, 'related');
        $alternativeOther = new NOCC_InternetMediaType(7, 'alternative');
        $relatedOther = new NOCC_InternetMediaType(7, 'RELATED');

        $this->assertFalse($this->internetMediaTypeNull->isAlternative(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isAlternative(), 'bug, bug');
        $this->assertFalse($this->internetMediaType0->isAlternative(), '0, plain');
        $this->assertTrue($this->internetMediaType1->isAlternative(), '1, ALTERNATIVE');
        $this->assertFalse($this->internetMediaType2->isAlternative(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isAlternative(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isAlternative(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isAlternative(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isAlternative(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isAlternative(), '7, Test');

        $this->assertFalse($relatedMultipart->isAlternative(), '1, related');
        $this->assertTrue($alternativeOther->isAlternative(), '7, alternative');
        $this->assertFalse($relatedOther->isAlternative(), '7, RELATED');
    }

    /**
     * Test case for isRelated().
     */
    public function testIsRelated() {
        $relatedMultipart = new NOCC_InternetMediaType(1, 'related');
        $alternativeOther = new NOCC_InternetMediaType(7, 'alternatvie');
        $relatedOther = new NOCC_InternetMediaType(7, 'RELATED');

        $this->assertFalse($this->internetMediaTypeNull->isRelated(), 'null, null');
        $this->assertFalse($this->internetMediaTypeBug->isRelated(), 'bug, bug');
        $this->assertFalse($this->internetMediaType0->isRelated(), '0, plain');
        $this->assertFalse($this->internetMediaType1->isRelated(), '1, ALTERNATIVE');
        $this->assertFalse($this->internetMediaType2->isRelated(), '2, RFC822');
        $this->assertFalse($this->internetMediaType3->isRelated(), '3, Pdf');
        $this->assertFalse($this->internetMediaType4->isRelated(), '4, mpeg');
        $this->assertFalse($this->internetMediaType5->isRelated(), '5, PNG');
        $this->assertFalse($this->internetMediaType6->isRelated(), '6, quicktime');
        $this->assertFalse($this->internetMediaType7->isRelated(), '7, Test');

        $this->assertTrue($relatedMultipart->isRelated(), '1, related');
        $this->assertFalse($alternativeOther->isRelated(), '7, alternative');
        $this->assertTrue($relatedOther->isRelated(), '7, RELATED');
    }

    /**
     * Test case for __toString().
     */
    public function test__toString() {
        $this->assertEquals('', $this->internetMediaTypeNull->__toString(), 'null, null');
        $this->assertEquals('', $this->internetMediaTypeBug->__toString(), 'bug, bug');
        $this->assertEquals('text/plain', $this->internetMediaType0->__toString(), '0, plain');
        $this->assertEquals('multipart/alternative', $this->internetMediaType1->__toString(), '1, ALTERNATIVE');
        $this->assertEquals('message/rfc822', $this->internetMediaType2->__toString(), '2, RFC822');
        $this->assertEquals('application/pdf', $this->internetMediaType3->__toString(), '3, Pdf');
        $this->assertEquals('audio/mpeg', $this->internetMediaType4->__toString(), '4, mpeg');
        $this->assertEquals('image/png', $this->internetMediaType5->__toString(), '5, PNG');
        $this->assertEquals('video/quicktime', $this->internetMediaType6->__toString(), '6, quicktime');
        $this->assertEquals('other/test', $this->internetMediaType7->__toString(), '7, Test');
    }
}
?>
