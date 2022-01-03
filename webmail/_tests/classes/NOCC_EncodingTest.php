<?php
/**
 * Test cases for NOCC_Encoding.
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
 * @version    SVN: $Id: NOCC_EncodingTest.php 2373 2011-01-04 15:06:58Z gerundt $
 */

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../classes/nocc_encoding.php';

/**
 * Test class for NOCC_Encoding.
 */
class NOCC_EncodingTest extends PHPUnit_Framework_TestCase {
    /**
     * @var NOCC_Encoding
     */
    protected $encodingNull;

    /**
     * @var NOCC_Encoding
     */
    protected $encodingBug;

    /**
     * @var NOCC_Encoding
     */
    protected $encoding0;

    /**
     * @var NOCC_Encoding
     */
    protected $encoding1;

    /**
     * @var NOCC_Encoding
     */
    protected $encoding2;

    /**
     * @var NOCC_Encoding
     */
    protected $encoding3;

    /**
     * @var NOCC_Encoding
     */
    protected $encoding4;

    /**
     * @var NOCC_Encoding
     */
    protected $encoding5;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->encodingNull = new NOCC_Encoding(null);
        $this->encodingBug = new NOCC_Encoding('bug');
        $this->encoding0 = new NOCC_Encoding(0);
        $this->encoding1 = new NOCC_Encoding(1);
        $this->encoding2 = new NOCC_Encoding(2);
        $this->encoding3 = new NOCC_Encoding(3);
        $this->encoding4 = new NOCC_Encoding(4);
        $this->encoding5 = new NOCC_Encoding(5);
    }

    /**
     * Test case for __toString().
     */
    public function test__toString() {
        $this->assertEquals('', $this->encodingNull->__toString(), 'null');
        $this->assertEquals('', $this->encodingBug->__toString(), 'bug');
        $this->assertEquals('7BIT', $this->encoding0->__toString(), '0');
        $this->assertEquals('8BIT', $this->encoding1->__toString(), '1');
        $this->assertEquals('BINARY', $this->encoding2->__toString(), '2');
        $this->assertEquals('BASE64', $this->encoding3->__toString(), '3');
        $this->assertEquals('QUOTED-PRINTABLE', $this->encoding4->__toString(), '4');
        $this->assertEquals('OTHER', $this->encoding5->__toString(), '5');
    }
}
?>
