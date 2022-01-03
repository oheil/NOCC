<?php
/**
 * Test cases for NOCC_Languages.
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
 * @version    SVN: $Id: NOCC_LanguagesTest.php 2373 2011-01-04 15:06:58Z gerundt $
 */

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../classes/nocc_languages.php';

/**
 * Test class for NOCC_Languages.
 */
class NOCC_LanguagesTest extends PHPUnit_Framework_TestCase {
    /**
     * @var string
     */
    protected $rootPath;

    /**
     * @var NOCC_Languages
     */
    protected $languages1;

    /**
     * @var NOCC_Languages
     */
    protected $languages2;

    /**
     * @var NOCC_Languages
     */
    protected $languages3;

    /**
     * @var NOCC_Languages
     */
    protected $languages4;

    /**
     * @var NOCC_Languages
     */
    protected $languages5;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->rootPath = dirname(__FILE__) . '/../';

        $this->languages1 = new NOCC_Languages('');
        $this->languages2 = new NOCC_Languages($this->rootPath . './lang', 'de');
        $this->languages3 = new NOCC_Languages($this->rootPath . './lang/', 'DE');
        $this->languages4 = new NOCC_Languages(array('bug'));
        $this->languages5 = new NOCC_Languages($this->rootPath . './lang/', array('bug'));
    }

    /**
     * Test case for count().
     */
    public function testCount() {
        $this->assertEquals(0, $this->languages1->count());
        $this->assertEquals(3, $this->languages2->count(), './lang, de');
        $this->assertEquals(3, $this->languages3->count(), './lang/, DE');
        $this->assertEquals(0, $this->languages4->count(), 'array(bug)');
        $this->assertEquals(3, $this->languages5->count(), './lang/, array(bug)');
    }

    /**
     * Test case for exists().
     */
    public function testExists() {
        $languages = new NOCC_Languages($this->rootPath . './lang');

        $this->assertFalse(@$languages->exists(), 'exists()');
        $this->assertFalse($languages->exists(array('bug')), 'exists(array("bug"))');
        $this->assertFalse($languages->exists(''), 'exists("")');
        $this->assertFalse($languages->exists('notexists'), 'exists("notexists")');
        $this->assertTrue($languages->exists('de'), 'exists("de")');
        $this->assertTrue($languages->exists('DE'), 'exists("DE")');
    }

    /**
     * @todo Implement testDetectFromBrowser().
     */
    public function testDetectFromBrowser() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * Test case for getDefaultLangId().
     */
    public function testGetDefaultLangId() {
        $this->assertEquals('en', $this->languages1->getDefaultLangId());
        $this->assertEquals('de', $this->languages2->getDefaultLangId(), './lang, de');
        $this->assertEquals('de', $this->languages3->getDefaultLangId(), './lang/, DE');
        $this->assertEquals('en', $this->languages4->getDefaultLangId(), 'array(bug)');
        $this->assertEquals('en', $this->languages5->getDefaultLangId(), './lang/, array(bug)');
    }

    /**
     * Test case for setDefaultLangId().
     */
    public function testSetDefaultLangId() {
        $languages = new NOCC_Languages($this->rootPath . './lang');

        $this->assertFalse(@$languages->setDefaultLangId(), 'setDefaultLangId()');
        $this->assertEquals('en', $languages->getDefaultLangId(), 'getDefaultLangId()');
        $this->assertFalse($languages->setDefaultLangId(array('bug')), 'setDefaultLangId(array("bug"))');
        $this->assertEquals('en', $languages->getDefaultLangId(), 'getDefaultLangId()');
        $this->assertFalse($languages->setDefaultLangId(''), 'setDefaultLangId("")');
        $this->assertEquals('en', $languages->getDefaultLangId(), 'getDefaultLangId()');
        $this->assertFalse($languages->setDefaultLangId('notexists'), 'setDefaultLangId("notexists")');
        $this->assertEquals('en', $languages->getDefaultLangId(), 'getDefaultLangId()');
        $this->assertFalse($languages->setDefaultLangId('../../../../../../../etc/passwd%00'), 'setDefaultLangId("passwd")');
        $this->assertEquals('en', $languages->getDefaultLangId(), 'getDefaultLangId()');
        $this->assertFalse($languages->setDefaultLangId('<script>alert(document.cookie)</script>'), 'setDefaultLangId("alert()")');
        $this->assertEquals('en', $languages->getDefaultLangId(), 'getDefaultLangId()');
        $this->assertTrue($languages->setDefaultLangId('se'), 'setDefaultLangId("se")');
        $this->assertEquals('se', $languages->getDefaultLangId(), 'getDefaultLangId()');
        $this->assertTrue($languages->setDefaultLangId('DE'), 'setDefaultLangId("DE")');
        $this->assertEquals('de', $languages->getDefaultLangId(), 'getDefaultLangId()');
    }

    /**
     * Test case for getSelectedLangId().
     */
    public function testGetSelectedLangId() {
        $this->assertEquals('en', $this->languages1->getSelectedLangId());
        $this->assertEquals('de', $this->languages2->getSelectedLangId(), './lang, de');
        $this->assertEquals('de', $this->languages3->getSelectedLangId(), './lang/, DE');
        $this->assertEquals('en', $this->languages4->getSelectedLangId(), 'array(bug)');
        $this->assertEquals('en', $this->languages5->getSelectedLangId(), './lang/, array(bug)');
    }

    /**
     * Test case for setSelectedLangId().
     */
    public function testSetSelectedLangId() {
        $languages = new NOCC_Languages($this->rootPath . './lang');

        $this->assertFalse(@$languages->setSelectedLangId(), 'setSelectedLangId()');
        $this->assertEquals('en', $languages->getSelectedLangId(), 'getSelectedLangId()');
        $this->assertFalse($languages->setSelectedLangId(array('bug')), 'setSelectedLangId(array("bug"))');
        $this->assertEquals('en', $languages->getSelectedLangId(), 'getSelectedLangId()');
        $this->assertFalse($languages->setSelectedLangId(''), 'setSelectedLangId("")');
        $this->assertEquals('en', $languages->getSelectedLangId(), 'getSelectedLangId()');
        $this->assertFalse($languages->setSelectedLangId('../../../../../../../etc/passwd%00'), 'setSelectedLangId("passwd")');
        $this->assertEquals('en', $languages->getSelectedLangId(), 'getSelectedLangId()');
        $this->assertFalse($languages->setSelectedLangId('<script>alert(document.cookie)</script>'), 'setSelectedLangId("alert()")');
        $this->assertEquals('en', $languages->getSelectedLangId(), 'getSelectedLangId()');
        $this->assertFalse($languages->setSelectedLangId('notexists'), 'setSelectedLangId("notexists")');
        $this->assertEquals('en', $languages->getSelectedLangId(), 'getSelectedLangId()');
        $this->assertTrue($languages->setSelectedLangId('se'), 'setSelectedLangId("se")');
        $this->assertEquals('se', $languages->getSelectedLangId(), 'getSelectedLangId()');
        $this->assertTrue($languages->setSelectedLangId('DE'), 'setSelectedLangId("DE")');
        $this->assertEquals('de', $languages->getSelectedLangId(), 'getSelectedLangId()');
    }

    /**
     * Test case for parseAcceptLanguageHeader().
     */
    public function testParseAcceptLanguageHeader() {
        $this->assertEquals(0, count(@NOCC_Languages::parseAcceptLanguageHeader()), 'parseHttpAcceptLanguage()');
        $this->assertEquals(0, count(NOCC_Languages::parseAcceptLanguageHeader(array('bug'))), 'parseHttpAcceptLanguage(array("bug"))');
        $this->assertEquals(0, count(NOCC_Languages::parseAcceptLanguageHeader('')), 'parseHttpAcceptLanguage("")');
        $this->assertEquals(1, count(NOCC_Languages::parseAcceptLanguageHeader('de')), 'parseHttpAcceptLanguage("de")');
        $this->assertEquals(1, count(NOCC_Languages::parseAcceptLanguageHeader('de-de')), 'parseHttpAcceptLanguage("de-de")');
        $this->assertEquals(2, count(NOCC_Languages::parseAcceptLanguageHeader('de,de-de')), 'parseHttpAcceptLanguage("de,de-de")');
        $this->assertEquals(1, count(NOCC_Languages::parseAcceptLanguageHeader('de-de;q=0.5')), 'parseHttpAcceptLanguage("de-de;q=0.5")');
        $this->assertEquals(4, count(NOCC_Languages::parseAcceptLanguageHeader('de-de,de;q=0.8,en-us;q=0.5,en;q=0.3')), 'parseHttpAcceptLanguage("de-de,de;q=0.8,en-us;q=0.5,en;q=0.3")');
        $this->assertEquals(1, count(NOCC_Languages::parseAcceptLanguageHeader('De-De ; Q = 0.5')), 'parseHttpAcceptLanguage("de-de;q=0.5")');
        $this->assertEquals(4, count(NOCC_Languages::parseAcceptLanguageHeader('   de-de , de; q=0.8, en-us;q=0.5, en;q=0.3')), 'parseHttpAcceptLanguage("   de-de , de; q=0.8, en-us;q=0.5, en;q=0.3")');
        $this->assertEquals(0, count(NOCC_Languages::parseAcceptLanguageHeader(',,,;;;')), 'parseHttpAcceptLanguage(",,,;;;")');
    }
}
?>
