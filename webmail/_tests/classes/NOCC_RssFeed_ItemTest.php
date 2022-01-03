<?php
/**
 * Test cases for NOCC_RssFeed_Item.
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
 * @version    SVN: $Id: NOCC_RssFeed_ItemTest.php 2373 2011-01-04 15:06:58Z gerundt $
 */

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../classes/nocc_rssfeed.php';

/**
 * Test class for NOCC_RssFeed_Item.
 */
class NOCC_RssFeed_ItemTest extends PHPUnit_Framework_TestCase {
    /**
     * @var NOCC_RssFeed_Item
     */
    protected $rssFeedItem1;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->rssFeedItem1 = new NOCC_RssFeed_Item;
        $this->rssFeedItem1->setTitle('Title');
        $this->rssFeedItem1->setDescription('Description');
        $this->rssFeedItem1->setContent('...');
        $this->rssFeedItem1->setLink('http://nocc.sf.net/');
        $this->rssFeedItem1->setCreator('Tim Gerundt');
    }

    /**
     * Test case for getTitle().
     */
    public function testGetTitle() {
        $this->assertEquals('Title', $this->rssFeedItem1->getTitle());
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
        $this->assertEquals('Description', $this->rssFeedItem1->getDescription());
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
     *Test case for getContent().
     */
    public function testGetContent() {
        $this->assertEquals('...', $this->rssFeedItem1->getContent());
    }

    /**
     * @todo Implement testSetContent().
     */
    public function testSetContent() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * Test case for getLink().
     */
    public function testGetLink() {
        $this->assertEquals('http://nocc.sf.net/', $this->rssFeedItem1->getLink());
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
     * Test case for getCreator().
     */
    public function testGetCreator() {
        $this->assertEquals('Tim Gerundt', $this->rssFeedItem1->getCreator());
    }

    /**
     * @todo Implement testSetCreator().
     */
    public function testSetCreator() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
}
?>
