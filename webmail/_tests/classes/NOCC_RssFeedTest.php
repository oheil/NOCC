<?php
/**
 * Test cases for NOCC_RssFeed.
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
 * @version    SVN: $Id: NOCC_RssFeedTest.php 2373 2011-01-04 15:06:58Z gerundt $
 */

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../classes/nocc_rssfeed.php';

/**
 * Test class for NOCC_RssFeed.
 */
class NOCC_RssFeedTest extends PHPUnit_Framework_TestCase {
    /**
     * @var NOCC_RssFeed
     */
    protected $rssFeed1;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->rssFeed1 = new NOCC_RssFeed;
        $this->rssFeed1->setTitle('Title');
        $this->rssFeed1->setDescription('Description');
        $this->rssFeed1->setLink('http://nocc.sf.net/');
    }

    /**
     * Test case for getTitle().
     */
    public function testGetTitle() {
        $this->assertEquals('Title', $this->rssFeed1->getTitle());
    }

    /**
     * @todo Implement testSetTitle().
     */
    public function testSetTitle() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * Test case for getDescription().
     */
    public function testGetDescription() {
        $this->assertEquals('Description', $this->rssFeed1->getDescription());
    }

    /**
     * @todo Implement testSetDescription().
     */
    public function testSetDescription() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * Test case for getLink().
     */
    public function testGetLink() {
        $this->assertEquals('http://nocc.sf.net/', $this->rssFeed1->getLink());
    }

    /**
     * @todo Implement testSetLink().
     */
    public function testSetLink() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testAddItem().
     */
    public function testAddItem() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testSendToBrowser().
     */
    public function testSendToBrowser() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * Test case for getIso8601Date().
     */
    public function testGetIso8601Date() {
        $timestamp = mktime(22, 26, 11, 2, 19, 2010);
        $tzd = substr(date('O', $timestamp), 0, 3) . ':' . substr(date('O', $timestamp), -2);

        $this->assertStringStartsWith('2010-02-19T22:26:11', NOCC_RssFeed::getIso8601Date($timestamp), 'starts with');
        $this->assertStringEndsWith($tzd, NOCC_RssFeed::getIso8601Date($timestamp), 'ends with');
        $this->assertEquals(25, strlen(NOCC_RssFeed::getIso8601Date($timestamp)), 'length');
    }
}
?>
