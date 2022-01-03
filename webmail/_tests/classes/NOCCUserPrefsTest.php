<?php
/**
 * Test cases for NOCCUserPrefs.
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
 * @version    SVN: $Id: NOCCUserPrefsTest.php 2488 2011-06-17 22:05:25Z gerundt $
 */

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../classes/user_prefs.php';
require_once dirname(__FILE__).'/../../classes/nocc_mailaddress.php';

/**
 * Test class for NOCCUserPrefs.
 */
class NOCCUserPrefsTest extends PHPUnit_Framework_TestCase {
    /**
     * @var string
     */
    protected $rootPath;

    /**
     * @var NOCCUserPrefs
     */
    protected $userPrefs1;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->rootPath = dirname(__FILE__) . '/../';

        $this->userPrefs1 = new NOCCUserPrefs('');
    }

    /**
     * Test case for getFullName().
     */
    public function testGetFullName() {
        $this->assertEquals('', $this->userPrefs1->getFullName());
    }

    /**
     * Test case for setFullName().
     */
    public function testSetFullName() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertEquals('', $userPrefs->getFullName(), 'default');
        $userPrefs->setFullName(true);
        $this->assertEquals('', $userPrefs->getFullName(), 'true');
        $userPrefs->setFullName(1);
        $this->assertEquals('', $userPrefs->getFullName(), '1');
        $userPrefs->setFullName('Full Name');
        $this->assertEquals('Full Name', $userPrefs->getFullName(), 'Full Name');
    }

    /**
     * Test case for getEmailAddress().
     */
    public function testGetEmailAddress() {
        $this->assertEquals('', $this->userPrefs1->getEmailAddress());
    }

    /**
     * Test case for getEmailAddress().
     */
    public function testSetEmailAddress() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertEquals('', $userPrefs->getEmailAddress(), 'default');
        $userPrefs->setEmailAddress(true);
        $this->assertEquals('', $userPrefs->getEmailAddress(), 'true');
        $userPrefs->setEmailAddress(1);
        $this->assertEquals('', $userPrefs->getEmailAddress(), '1');
        $userPrefs->setEmailAddress('foo@bar.org');
        $this->assertEquals('foo@bar.org', $userPrefs->getEmailAddress(), 'foo@bar.org');
    }

    /**
     * Test case for getMailAddress().
     */
    public function testGetMailAddress() {
        $mailAddress = $this->userPrefs1->getMailAddress();
        
        $this->assertEquals('', $mailAddress->getName(), 'getName()');
        $this->assertEquals('', $mailAddress->getAddress(), 'getAddress()');
    }

    /**
     * Test case for getBccSelf().
     */
    public function testgetBccSelf() {
        $this->assertFalse($this->userPrefs1->getBccSelf());
    }

    /**
     * Test case for setBccSelf().
     */
    public function testsetBccSelf() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertFalse($userPrefs->getBccSelf(), 'default');
        $userPrefs->setBccSelf(true);
        $this->assertTrue($userPrefs->getBccSelf(), 'true');
        $userPrefs->setBccSelf(false);
        $this->assertFalse($userPrefs->getBccSelf(), 'false');
        $userPrefs->setBccSelf(1);
        $this->assertTrue($userPrefs->getBccSelf(), '1');
        $userPrefs->setBccSelf(0);
        $this->assertFalse($userPrefs->getBccSelf(), '0');
        $userPrefs->setBccSelf('on');
        $this->assertTrue($userPrefs->getBccSelf(), 'on');
        $userPrefs->setBccSelf('off');
        $this->assertFalse($userPrefs->getBccSelf(), 'off');
        $userPrefs->setBccSelf('1');
        $this->assertTrue($userPrefs->getBccSelf(), '"1"');
        $userPrefs->setBccSelf('0');
        $this->assertFalse($userPrefs->getBccSelf(), '"0"');
        $userPrefs->setBccSelf('<invalid>');
        $this->assertFalse($userPrefs->getBccSelf(), '<invalid>');
        $userPrefs->setBccSelf(null);
        $this->assertFalse($userPrefs->getBccSelf(), 'NULL');
    }

    /**
     * Test case for getHideAddresses().
     */
    public function testGetHideAddresses() {
        $this->assertFalse($this->userPrefs1->getHideAddresses());
    }

    /**
     * Test case for setHideAddresses().
     */
    public function testSetHideAddresses() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertFalse($userPrefs->getHideAddresses(), 'default');
        $userPrefs->setHideAddresses(true);
        $this->assertTrue($userPrefs->getHideAddresses(), 'true');
        $userPrefs->setHideAddresses(false);
        $this->assertFalse($userPrefs->getHideAddresses(), 'false');
        $userPrefs->setHideAddresses(1);
        $this->assertTrue($userPrefs->getHideAddresses(), '1');
        $userPrefs->setHideAddresses(0);
        $this->assertFalse($userPrefs->getHideAddresses(), '0');
        $userPrefs->setHideAddresses('on');
        $this->assertTrue($userPrefs->getHideAddresses(), 'on');
        $userPrefs->setHideAddresses('off');
        $this->assertFalse($userPrefs->getHideAddresses(), 'off');
        $userPrefs->setHideAddresses('1');
        $this->assertTrue($userPrefs->getHideAddresses(), '"1"');
        $userPrefs->setHideAddresses('0');
        $this->assertFalse($userPrefs->getHideAddresses(), '"0"');
        $userPrefs->setHideAddresses('<invalid>');
        $this->assertFalse($userPrefs->getHideAddresses(), '<invalid>');
        $userPrefs->setHideAddresses(null);
        $this->assertFalse($userPrefs->getHideAddresses(), 'NULL');
    }

    /**
     * Test case for getOutlookQuoting().
     */
    public function testGetOutlookQuoting() {
        $this->assertFalse($this->userPrefs1->getOutlookQuoting());
    }

    /**
     * Test case for setOutlookQuoting().
     */
    public function testSetOutlookQuoting() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertFalse($userPrefs->getOutlookQuoting(), 'default');
        $userPrefs->setOutlookQuoting(true);
        $this->assertTrue($userPrefs->getOutlookQuoting(), 'true');
        $userPrefs->setOutlookQuoting(false);
        $this->assertFalse($userPrefs->getOutlookQuoting(), 'false');
        $userPrefs->setOutlookQuoting(1);
        $this->assertTrue($userPrefs->getOutlookQuoting(), '1');
        $userPrefs->setOutlookQuoting(0);
        $this->assertFalse($userPrefs->getOutlookQuoting(), '0');
        $userPrefs->setOutlookQuoting('on');
        $this->assertTrue($userPrefs->getOutlookQuoting(), 'on');
        $userPrefs->setOutlookQuoting('off');
        $this->assertFalse($userPrefs->getOutlookQuoting(), 'off');
        $userPrefs->setOutlookQuoting('1');
        $this->assertTrue($userPrefs->getOutlookQuoting(), '"1"');
        $userPrefs->setOutlookQuoting('0');
        $this->assertFalse($userPrefs->getOutlookQuoting(), '"0"');
        $userPrefs->setOutlookQuoting('<invalid>');
        $this->assertFalse($userPrefs->getOutlookQuoting(), '<invalid>');
        $userPrefs->setOutlookQuoting(null);
        $this->assertFalse($userPrefs->getOutlookQuoting(), 'NULL');
    }

    /**
     * Test case for getColoredQuotes().
     */
    public function testGetColoredQuotes() {
        $this->assertTrue($this->userPrefs1->getColoredQuotes());
    }

    /**
     * Test case for setColoredQuotes().
     */
    public function testSetColoredQuotes() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertTrue($userPrefs->getColoredQuotes(), 'default');
        $userPrefs->setColoredQuotes(false);
        $this->assertFalse($userPrefs->getColoredQuotes(), 'false');
        $userPrefs->setColoredQuotes(true);
        $this->assertTrue($userPrefs->getColoredQuotes(), 'true');
        $userPrefs->setColoredQuotes(0);
        $this->assertFalse($userPrefs->getColoredQuotes(), '0');
        $userPrefs->setColoredQuotes(1);
        $this->assertTrue($userPrefs->getColoredQuotes(), '1');
        $userPrefs->setColoredQuotes('off');
        $this->assertFalse($userPrefs->getColoredQuotes(), 'off');
        $userPrefs->setColoredQuotes('on');
        $this->assertTrue($userPrefs->getColoredQuotes(), 'on');
        $userPrefs->setColoredQuotes('0');
        $this->assertFalse($userPrefs->getColoredQuotes(), '"0"');
        $userPrefs->setColoredQuotes('1');
        $this->assertTrue($userPrefs->getColoredQuotes(), '"1"');
        $userPrefs->setColoredQuotes('<invalid>');
        $this->assertTrue($userPrefs->getColoredQuotes(), '<invalid>');
        $userPrefs->setColoredQuotes(null);
        $this->assertTrue($userPrefs->getColoredQuotes(), 'NULL');
    }

    /**
     * Test case for getDisplayStructuredText().
     */
    public function testGetDisplayStructuredText() {
        $this->assertFalse($this->userPrefs1->getDisplayStructuredText());
    }

    /**
     * Test case for setDisplayStructuredText().
     */
    public function testSetDisplayStructuredText() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertFalse($userPrefs->getDisplayStructuredText(), 'default');
        $userPrefs->setDisplayStructuredText(true);
        $this->assertTrue($userPrefs->getDisplayStructuredText(), 'true');
        $userPrefs->setDisplayStructuredText(false);
        $this->assertFalse($userPrefs->getDisplayStructuredText(), 'false');
        $userPrefs->setDisplayStructuredText(1);
        $this->assertTrue($userPrefs->getDisplayStructuredText(), '1');
        $userPrefs->setDisplayStructuredText(0);
        $this->assertFalse($userPrefs->getDisplayStructuredText(), '0');
        $userPrefs->setDisplayStructuredText('on');
        $this->assertTrue($userPrefs->getDisplayStructuredText(), 'on');
        $userPrefs->setDisplayStructuredText('off');
        $this->assertFalse($userPrefs->getDisplayStructuredText(), 'off');
        $userPrefs->setDisplayStructuredText('1');
        $this->assertTrue($userPrefs->getDisplayStructuredText(), '"1"');
        $userPrefs->setDisplayStructuredText('0');
        $this->assertFalse($userPrefs->getDisplayStructuredText(), '"0"');
        $userPrefs->setDisplayStructuredText('<invalid>');
        $this->assertFalse($userPrefs->getDisplayStructuredText(), '<invalid>');
        $userPrefs->setDisplayStructuredText(null);
        $this->assertFalse($userPrefs->getDisplayStructuredText(), 'NULL');
    }

    /**
     * Test case for getWrapMessages().
     */
    public function testGetWrapMessages() {
        $this->assertEquals(0, $this->userPrefs1->getWrapMessages());
    }

    /**
     * Test case for setWrapMessages().
     */
    public function testSetWrapMessages() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertEquals(0, $userPrefs->getWrapMessages(), 'default');
        $userPrefs->setWrapMessages(true);
        $this->assertEquals(0, $userPrefs->getWrapMessages(), 'true');
        $userPrefs->setWrapMessages(72);
        $this->assertEquals(72, $userPrefs->getWrapMessages(), '72');
        $userPrefs->setWrapMessages('80');
        $this->assertEquals(80, $userPrefs->getWrapMessages(), '"80"');
        $userPrefs->setWrapMessages(120);
        $this->assertEquals(0, $userPrefs->getWrapMessages(), '120');
    }

    /**
     * Test case for getSignature().
     */
    public function testGetSignature() {
        $this->assertEquals('', $this->userPrefs1->getSignature());
    }

    /**
     * Test case for getSignature().
     */
    public function testSetSignature() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertEquals('', $userPrefs->getSignature(), 'default');
        $userPrefs->setSignature(true);
        $this->assertEquals('', $userPrefs->getSignature(), 'true');
        $userPrefs->setSignature(1);
        $this->assertEquals('', $userPrefs->getSignature(), '1');
        $userPrefs->setSignature('This is a signature...');
        $this->assertEquals('This is a signature...', $userPrefs->getSignature(), 'This is a signature...');
    }

    /**
     * Test case for getUseSignatureSeparator().
     */
    public function testGetUseSignatureSeparator() {
        $this->assertFalse($this->userPrefs1->getUseSignatureSeparator());
    }

    /**
     * Test case for setUseSignatureSeparator().
     */
    public function testSetUseSignatureSeparator() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertFalse($userPrefs->getUseSignatureSeparator(), 'default');
        $userPrefs->setUseSignatureSeparator(true);
        $this->assertTrue($userPrefs->getUseSignatureSeparator(), 'true');
        $userPrefs->setUseSignatureSeparator(false);
        $this->assertFalse($userPrefs->getUseSignatureSeparator(), 'false');
        $userPrefs->setUseSignatureSeparator(1);
        $this->assertTrue($userPrefs->getUseSignatureSeparator(), '1');
        $userPrefs->setUseSignatureSeparator(0);
        $this->assertFalse($userPrefs->getUseSignatureSeparator(), '0');
        $userPrefs->setUseSignatureSeparator('on');
        $this->assertTrue($userPrefs->getUseSignatureSeparator(), 'on');
        $userPrefs->setUseSignatureSeparator('off');
        $this->assertFalse($userPrefs->getUseSignatureSeparator(), 'off');
        $userPrefs->setUseSignatureSeparator('1');
        $this->assertTrue($userPrefs->getUseSignatureSeparator(), '"1"');
        $userPrefs->setUseSignatureSeparator('0');
        $this->assertFalse($userPrefs->getUseSignatureSeparator(), '"0"');
        $userPrefs->setUseSignatureSeparator('<invalid>');
        $this->assertFalse($userPrefs->getUseSignatureSeparator(), '<invalid>');
        $userPrefs->setUseSignatureSeparator(null);
        $this->assertFalse($userPrefs->getUseSignatureSeparator(), 'NULL');
    }

    /**
     * Test case for getSendHtmlMail().
     */
    public function testGetSendHtmlMail() {
        $this->assertFalse($this->userPrefs1->getSendHtmlMail());
    }

    /**
     * Test case for setSendHtmlMail().
     */
    public function testSetSendHtmlMail() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertFalse($userPrefs->getSendHtmlMail(), 'default');
        $userPrefs->setSendHtmlMail(true);
        $this->assertTrue($userPrefs->getSendHtmlMail(), 'true');
        $userPrefs->setSendHtmlMail(false);
        $this->assertFalse($userPrefs->getSendHtmlMail(), 'false');
        $userPrefs->setSendHtmlMail(1);
        $this->assertTrue($userPrefs->getSendHtmlMail(), '1');
        $userPrefs->setSendHtmlMail(0);
        $this->assertFalse($userPrefs->getSendHtmlMail(), '0');
        $userPrefs->setSendHtmlMail('on');
        $this->assertTrue($userPrefs->getSendHtmlMail(), 'on');
        $userPrefs->setSendHtmlMail('off');
        $this->assertFalse($userPrefs->getSendHtmlMail(), 'off');
        $userPrefs->setSendHtmlMail('1');
        $this->assertTrue($userPrefs->getSendHtmlMail(), '"1"');
        $userPrefs->setSendHtmlMail('0');
        $this->assertFalse($userPrefs->getSendHtmlMail(), '"0"');
        $userPrefs->setSendHtmlMail('<invalid>');
        $this->assertFalse($userPrefs->getSendHtmlMail(), '<invalid>');
        $userPrefs->setSendHtmlMail(null);
        $this->assertFalse($userPrefs->getSendHtmlMail(), 'NULL');
    }

    /**
     * Test case for getUseGraphicalSmilies().
     */
    public function testGetUseGraphicalSmilies() {
        $this->assertFalse($this->userPrefs1->getUseGraphicalSmilies());
    }

    /**
     * Test case for setUseGraphicalSmilies().
     */
    public function testSetUseGraphicalSmilies() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertFalse($userPrefs->getUseGraphicalSmilies(), 'default');
        $userPrefs->setUseGraphicalSmilies(true);
        $this->assertTrue($userPrefs->getUseGraphicalSmilies(), 'true');
        $userPrefs->setUseGraphicalSmilies(false);
        $this->assertFalse($userPrefs->getUseGraphicalSmilies(), 'false');
        $userPrefs->setUseGraphicalSmilies(1);
        $this->assertTrue($userPrefs->getUseGraphicalSmilies(), '1');
        $userPrefs->setUseGraphicalSmilies(0);
        $this->assertFalse($userPrefs->getUseGraphicalSmilies(), '0');
        $userPrefs->setUseGraphicalSmilies('on');
        $this->assertTrue($userPrefs->getUseGraphicalSmilies(), 'on');
        $userPrefs->setUseGraphicalSmilies('off');
        $this->assertFalse($userPrefs->getUseGraphicalSmilies(), 'off');
        $userPrefs->setUseGraphicalSmilies('1');
        $this->assertTrue($userPrefs->getUseGraphicalSmilies(), '"1"');
        $userPrefs->setUseGraphicalSmilies('0');
        $this->assertFalse($userPrefs->getUseGraphicalSmilies(), '"0"');
        $userPrefs->setUseGraphicalSmilies('<invalid>');
        $this->assertFalse($userPrefs->getUseGraphicalSmilies(), '<invalid>');
        $userPrefs->setUseGraphicalSmilies(null);
        $this->assertFalse($userPrefs->getUseGraphicalSmilies(), 'NULL');
    }

    /**
     * Test case for getUseSentFolder().
     */
    public function testGetUseSentFolder() {
        $this->assertFalse($this->userPrefs1->getUseSentFolder());
    }

    /**
     * Test case for setUseSentFolder().
     */
    public function testSetUseSentFolder() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertFalse($userPrefs->getUseSentFolder(), 'default');
        $userPrefs->setUseSentFolder(true);
        $this->assertTrue($userPrefs->getUseSentFolder(), 'true');
        $userPrefs->setUseSentFolder(false);
        $this->assertFalse($userPrefs->getUseSentFolder(), 'false');
        $userPrefs->setUseSentFolder(1);
        $this->assertTrue($userPrefs->getUseSentFolder(), '1');
        $userPrefs->setUseSentFolder(0);
        $this->assertFalse($userPrefs->getUseSentFolder(), '0');
        $userPrefs->setUseSentFolder('on');
        $this->assertTrue($userPrefs->getUseSentFolder(), 'on');
        $userPrefs->setUseSentFolder('off');
        $this->assertFalse($userPrefs->getUseSentFolder(), 'off');
        $userPrefs->setUseSentFolder('1');
        $this->assertTrue($userPrefs->getUseSentFolder(), '"1"');
        $userPrefs->setUseSentFolder('0');
        $this->assertFalse($userPrefs->getUseSentFolder(), '"0"');
        $userPrefs->setUseSentFolder('<invalid>');
        $this->assertFalse($userPrefs->getUseSentFolder(), '<invalid>');
        $userPrefs->setUseSentFolder(null);
        $this->assertFalse($userPrefs->getUseSentFolder(), 'NULL');
    }

    /**
     * Test case for getSentFolderName().
     */
    public function testGetSentFolderName() {
        $this->assertEquals('', $this->userPrefs1->getSentFolderName());
    }

    /**
     * Test case for setSentFolderName().
     */
    public function testSetSentFolderName() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertEquals('', $userPrefs->getSentFolderName(), 'default');
        $userPrefs->setSentFolderName(true);
        $this->assertEquals('', $userPrefs->getSentFolderName(), 'true');
        $userPrefs->setSentFolderName(1);
        $this->assertEquals('', $userPrefs->getSentFolderName(), '1');
        $userPrefs->setSentFolderName('sent');
        $this->assertEquals('sent', $userPrefs->getSentFolderName(), 'sent');
    }

    /**
     * Test case for getUseTrashFolder().
     */
    public function testGetUseTrashFolder() {
        $this->assertFalse($this->userPrefs1->getUseTrashFolder());
    }

    /**
     * Test case for setUseTrashFolder().
     */
    public function testSetUseTrashFolder() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertFalse($userPrefs->getUseTrashFolder(), 'default');
        $userPrefs->setUseTrashFolder(true);
        $this->assertTrue($userPrefs->getUseTrashFolder(), 'true');
        $userPrefs->setUseTrashFolder(false);
        $this->assertFalse($userPrefs->getUseTrashFolder(), 'false');
        $userPrefs->setUseTrashFolder(1);
        $this->assertTrue($userPrefs->getUseTrashFolder(), '1');
        $userPrefs->setUseTrashFolder(0);
        $this->assertFalse($userPrefs->getUseTrashFolder(), '0');
        $userPrefs->setUseTrashFolder('on');
        $this->assertTrue($userPrefs->getUseTrashFolder(), 'on');
        $userPrefs->setUseTrashFolder('off');
        $this->assertFalse($userPrefs->getUseTrashFolder(), 'off');
        $userPrefs->setUseTrashFolder('1');
        $this->assertTrue($userPrefs->getUseTrashFolder(), '"1"');
        $userPrefs->setUseTrashFolder('0');
        $this->assertFalse($userPrefs->getUseTrashFolder(), '"0"');
        $userPrefs->setUseTrashFolder('<invalid>');
        $this->assertFalse($userPrefs->getUseTrashFolder(), '<invalid>');
        $userPrefs->setUseTrashFolder(null);
        $this->assertFalse($userPrefs->getUseTrashFolder(), 'NULL');
    }

    /**
     * Test case for getTrashFolderName().
     */
    public function testGetTrashFolderName() {
        $this->assertEquals('', $this->userPrefs1->getTrashFolderName());
    }

    /**
     * Test case for setTrashFolderName().
     */
    public function testSetTrashFolderName() {
        $userPrefs = new NOCCUserPrefs('');

        $this->assertEquals('', $userPrefs->getTrashFolderName(), 'default');
        $userPrefs->setTrashFolderName(true);
        $this->assertEquals('', $userPrefs->getTrashFolderName(), 'true');
        $userPrefs->setTrashFolderName(1);
        $this->assertEquals('', $userPrefs->getTrashFolderName(), '1');
        $userPrefs->setTrashFolderName('trash');
        $this->assertEquals('trash', $userPrefs->getTrashFolderName(), 'trash');
    }

    /**
     * @todo Implement testRead().
     */
    public function testRead() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * Test case for readFromFile().
     */
    public function testReadFromFile() {
        $defaultUserPrefs = new NOCCUserPrefs('');

        $userPrefs1 = NOCCUserPrefs::readFromFile($defaultUserPrefs, $this->rootPath . './prefs/test1.pref', $ev);
        
        $this->assertEquals('Full Name', $userPrefs1->getFullName(), 'getFullName()');
        $this->assertEquals('foo@bar.org', $userPrefs1->getEmailAddress(), 'getEmailAddress()');
        $this->assertEquals(30, $userPrefs1->msg_per_page, 'msg_per_page');
        $this->assertTrue($userPrefs1->getBccSelf(), 'getBccSelf()');
        $this->assertTrue($userPrefs1->getHideAddresses(), 'getHideAddresses()');
        $this->assertTrue($userPrefs1->getOutlookQuoting(), 'getOutlookQuoting()');
        $this->assertTrue($userPrefs1->getColoredQuotes(), 'getColoredQuotes()');
        $this->assertTrue($userPrefs1->getDisplayStructuredText(), 'getDisplayStructuredText()');
        $this->assertTrue($userPrefs1->seperate_msg_win, 'seperate_msg_win');
        $this->assertEquals('', $userPrefs1->reply_leadin, 'reply_leadin');
        $this->assertEquals(72, $userPrefs1->getWrapMessages(), 'getWrapMessages()');
        $this->assertEquals('This is a signature...', $userPrefs1->getSignature(), 'getSignature()');
        $this->assertTrue($userPrefs1->getUseSignatureSeparator(), 'getUseSignatureSeparator()');
        $this->assertTrue($userPrefs1->getSendHtmlMail(), 'getSendHtmlMail()');
        $this->assertTrue($userPrefs1->getUseGraphicalSmilies(), 'getUseGraphicalSmilies()');
        $this->assertTrue($userPrefs1->getUseSentFolder(), 'getUseSentFolder()');
        $this->assertEquals('Sent', $userPrefs1->getSentFolderName(), 'getSentFolderName()');
        $this->assertTrue($userPrefs1->getUseTrashFolder(), 'getUseTrashFolder()');
        $this->assertEquals('Trash', $userPrefs1->getTrashFolderName(), 'getTrashFolderName()');
        $this->assertEquals('de', $userPrefs1->lang, 'lang');
        $this->assertEquals('newlook', $userPrefs1->theme, 'theme');
        $this->assertEquals(0, $userPrefs1->dirty_flag, 'dirty_flag');

        $userPrefs2 = NOCCUserPrefs::readFromFile($defaultUserPrefs, $this->rootPath . './prefs/test2.pref', $ev);

        $this->assertEquals('Name Full', $userPrefs2->getFullName(), 'getFullName()');
        $this->assertEquals('bar@foo.org', $userPrefs2->getEmailAddress(), 'getEmailAddress()');
        $this->assertEquals(15, $userPrefs2->msg_per_page, 'msg_per_page');
        $this->assertFalse($userPrefs2->getBccSelf(), 'getBccSelf()');
        $this->assertFalse($userPrefs2->getHideAddresses(), 'getHideAddresses()');
        $this->assertFalse($userPrefs2->getOutlookQuoting(), 'getOutlookQuoting()');
        $this->assertFalse($userPrefs2->getColoredQuotes(), 'getColoredQuotes()');
        $this->assertFalse($userPrefs2->getDisplayStructuredText(), 'getDisplayStructuredText()');
        $this->assertFalse($userPrefs2->seperate_msg_win, 'seperate_msg_win');
        $this->assertEquals('', $userPrefs2->reply_leadin, 'reply_leadin');
        $this->assertEquals(0, $userPrefs2->getWrapMessages(), 'getWrapMessages()');
        $this->assertEquals('', $userPrefs2->getSignature(), 'getSignature()');
        $this->assertFalse($userPrefs2->getUseSignatureSeparator(), 'getUseSignatureSeparator()');
        $this->assertFalse($userPrefs2->getSendHtmlMail(), 'getSendHtmlMail()');
        $this->assertFalse($userPrefs2->getUseGraphicalSmilies(), 'getUseGraphicalSmilies()');
        $this->assertFalse($userPrefs2->getUseSentFolder(), 'getUseSentFolder()');
        $this->assertEquals('', $userPrefs2->getSentFolderName(), 'getSentFolderName()');
        $this->assertFalse($userPrefs2->getUseTrashFolder(), 'getUseTrashFolder()');
        $this->assertEquals('', $userPrefs2->getTrashFolderName(), 'getTrashFolderName()');
        $this->assertEquals('en', $userPrefs2->lang, 'lang');
        $this->assertEquals('standard', $userPrefs2->theme, 'theme');
        $this->assertEquals(0, $userPrefs2->dirty_flag, 'dirty_flag');
    }

    /**
     * @todo Implement testCommit().
     */
    public function testCommit() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testValidate().
     */
    public function testValidate() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testParseLeadin().
     */
    public function testParseLeadin() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
}
?>
