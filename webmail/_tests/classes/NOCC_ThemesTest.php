<?php
/**
 * Test cases for NOCC_Themes.
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
 * @version    SVN: $Id: NOCC_ThemesTest.php 2373 2011-01-04 15:06:58Z gerundt $
 */

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../classes/nocc_themes.php';

/**
 * Test class for NOCC_Themes.
 */
class NOCC_ThemesTest extends PHPUnit_Framework_TestCase {
    /**
     * @var string
     */
    protected $rootPath;

    /**
     * @var NOCC_Themes
     */
    protected $themes1;

    /**
     * @var NOCC_Themes
     */
    protected $themes2;

    /**
     * @var NOCC_Themes
     */
    protected $themes3;

    /**
     * @var NOCC_Themes
     */
    protected $themes4;

    /**
     * @var NOCC_Themes
     */
    protected $themes5;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->rootPath = dirname(__FILE__) . '/../';

        $this->themes1 = new NOCC_Themes('');
        $this->themes2 = new NOCC_Themes($this->rootPath . './themes', 'test1');
        $this->themes3 = new NOCC_Themes($this->rootPath . './themes/', 'TEST1');
        $this->themes4 = new NOCC_Themes(array('bug'));
        $this->themes5 = new NOCC_Themes($this->rootPath . './themes/', array('bug'));
    }

    /**
     * Test case for count().
     */
    public function testCount() {
        $this->assertEquals(0, $this->themes1->count());
        $this->assertEquals(2, $this->themes2->count(), './themes, test1');
        $this->assertEquals(2, $this->themes3->count(), './themes/ TEST1');
        $this->assertEquals(0, $this->themes4->count(), 'array(bug)');
        $this->assertEquals(2, $this->themes5->count(), './themes/, array(bug)');
    }

    /**
     * Test case for exists().
     */
    public function testExists() {
        $themes = new NOCC_Themes($this->rootPath . './themes');

        $this->assertFalse(@$themes->exists(), 'exists()');
        $this->assertFalse($themes->exists(array('bug')), 'exists(array("bug"))');
        $this->assertFalse($themes->exists(''), 'exists("")');
        $this->assertFalse($themes->exists('notexists'), 'exists("notexists")');
        $this->assertTrue($themes->exists('test1'), 'exists("test1")');
        $this->assertTrue($themes->exists('TEST1'), 'exists("TEST1")');
    }

    /**
     * Test case for getThemeNames().
     */
    public function testGetThemeNames() {
        $themes = new NOCC_Themes($this->rootPath . './themes');
        $themeNames = $themes->getThemeNames();

        $this->assertEquals(2, count($themeNames), 'count($themeNames)');
        $this->assertEquals('test1', $themeNames[0], '$themeNames[0]');
        $this->assertEquals('test2', $themeNames[1], '$themeNames[1]');
    }

    /**
     * Test case for getDefaultThemeName().
     */
    public function testGetDefaultThemeName() {
        $this->assertEquals('standard', $this->themes1->getDefaultThemeName());
        $this->assertEquals('test1', $this->themes2->getDefaultThemeName(), './themes, test1');
        $this->assertEquals('test1', $this->themes3->getDefaultThemeName(), './themes/ TEST1');
        $this->assertEquals('standard', $this->themes4->getDefaultThemeName(), 'array(bug)');
        $this->assertEquals('standard', $this->themes5->getDefaultThemeName(), './themes/, array(bug)');
    }

    /**
     * Test case for setDefaultThemeName().
     */
    public function testSetDefaultThemeName() {
        $themes = new NOCC_Themes($this->rootPath . './themes');

        $this->assertFalse(@$themes->setDefaultThemeName(), 'setDefaultThemeName()');
        $this->assertEquals('standard', $themes->getDefaultThemeName(), 'getDefaultThemeName()');
        $this->assertFalse($themes->setDefaultThemeName(array('bug')), 'setDefaultThemeName(array("bug"))');
        $this->assertEquals('standard', $themes->getDefaultThemeName(), 'getDefaultThemeName()');
        $this->assertFalse($themes->setDefaultThemeName(''), 'setDefaultThemeName("")');
        $this->assertEquals('standard', $themes->getDefaultThemeName(), 'getDefaultThemeName()');
        $this->assertFalse($themes->setDefaultThemeName('notexists'), 'setDefaultThemeName("notexists")');
        $this->assertEquals('standard', $themes->getDefaultThemeName(), 'getDefaultThemeName()');
        $this->assertFalse($themes->setDefaultThemeName('../../../../../../../etc/passwd%00'), 'setDefaultThemeName("passwd")');
        $this->assertEquals('standard', $themes->getDefaultThemeName(), 'getDefaultThemeName()');
        $this->assertFalse($themes->setDefaultThemeName('<script>alert(document.cookie)</script>'), 'setDefaultThemeName("alert()")');
        $this->assertEquals('standard', $themes->getDefaultThemeName(), 'getDefaultThemeName()');
        $this->assertTrue($themes->setDefaultThemeName('test1'), 'setDefaultThemeName("test1")');
        $this->assertEquals('test1', $themes->getDefaultThemeName(), 'getDefaultThemeName()');
        $this->assertTrue($themes->setDefaultThemeName('TEST2'), 'setDefaultThemeName("TEST2")');
        $this->assertEquals('test2', $themes->getDefaultThemeName(), 'getDefaultThemeName()');
    }

    /**
     * Test case for getSelectedThemeName().
     */
    public function testGetSelectedThemeName() {
        $this->assertEquals('standard', $this->themes1->getSelectedThemeName());
        $this->assertEquals('test1', $this->themes2->getSelectedThemeName(), './themes, test1');
        $this->assertEquals('test1', $this->themes3->getSelectedThemeName(), './themes/ TEST1');
        $this->assertEquals('standard', $this->themes4->getSelectedThemeName(), 'array(bug)');
        $this->assertEquals('standard', $this->themes5->getSelectedThemeName(), './themes/, array(bug)');
    }

    /**
     * Test case for setSelectedThemeName().
     */
    public function testSetSelectedThemeName() {
        $themes = new NOCC_Themes($this->rootPath . './themes');

        $this->assertFalse(@$themes->setSelectedThemeName(), 'setSelectedThemeName()');
        $this->assertEquals('standard', $themes->getSelectedThemeName(), 'getSelectedThemeName()');
        $this->assertFalse($themes->setSelectedThemeName(array('bug')), 'setSelectedThemeName(array("bug"))');
        $this->assertEquals('standard', $themes->getSelectedThemeName(), 'getSelectedThemeName()');
        $this->assertFalse($themes->setSelectedThemeName(''), 'setSelectedThemeName("")');
        $this->assertEquals('standard', $themes->getSelectedThemeName(), 'getSelectedThemeName()');
        $this->assertFalse($themes->setSelectedThemeName('../../../../../../../etc/passwd%00'), 'setSelectedThemeName("passwd")');
        $this->assertEquals('standard', $themes->getSelectedThemeName(), 'getSelectedThemeName()');
        $this->assertFalse($themes->setSelectedThemeName('<script>alert(document.cookie)</script>'), 'setSelectedThemeName("alert()")');
        $this->assertEquals('standard', $themes->getSelectedThemeName(), 'getSelectedThemeName()');
        $this->assertFalse($themes->setSelectedThemeName('notexists'), 'setSelectedThemeName("notexists")');
        $this->assertEquals('standard', $themes->getSelectedThemeName(), 'getSelectedThemeName()');
        $this->assertTrue($themes->setSelectedThemeName('test1'), 'setSelectedThemeName("test1")');
        $this->assertEquals('test1', $themes->getSelectedThemeName(), 'getSelectedThemeName()');
        $this->assertTrue($themes->setSelectedThemeName('TEST2'), 'setSelectedThemeName("TEST2")');
        $this->assertEquals('test2', $themes->getSelectedThemeName(), 'getSelectedThemeName()');
    }
}
?>
