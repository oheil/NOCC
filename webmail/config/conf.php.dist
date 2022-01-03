<?php
/**
 * Main configuration for NOCC
 *
 * Copyright 2001 Nicolas Chalanset <nicocha@free.fr>
 * Copyright 2001 Olivier Cahagne <cahagn_o@epita.fr>
 * Copyright 2002 Mike Rylander <mrylander@mail.com>
 * Copyright 2008-2011 Tim Gerundt <tim@gerundt.de>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @subpackage Configuration
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: conf.php.dist 2967 2021-12-10 14:24:34Z oheil $
 */

// ################### This is the main configuration for NOCC ########## //

// ###
// ### Required parameters
// ###

$conf = new stdClass();

// Will be checked by html/*.php file. If it's not available, these files won't
// be loaded.
$conf->loaded = true;

// List of domains people can log in
// You can have as many domains as you need

// $conf->domains[$i]->show_as = 'sn';
//  a short name for the domain name e.g 'sn' for 'sourceforge.net'.
//  This field is shown in the domain list at the login page to hide the
//  real domain to the world when more than one domain are used.
//  If empty the domain is shown.
//
// $conf->domains[$i]->domain = 'sourceforge.net';
//  domain name e.g 'sourceforge.net'. This field is used when sending message
//
// $conf->domains[$i]->in = 'mail.sourceforge.net:110/pop3';
//  imap or pop3 server name + port + protocol (only if not imap)
//  [server_name]:[port number]/[protocol]/[options]
//  ex for an imap server:
//    mail.sourceforge.net:143
//  ex for an imap server with explicit TLS/SSL negociation desactivated:
//    mail.sourceforge.net:143/notls (may be useful for some courier-imap
//    installation).
//  ex for an ssl imap server:
//    mail.sourceforge.net:993/ssl
//  ex for an ssl imap server with a self-signed certificate:
//    mail.sourceforge.net:993/ssl/novalidate-cert
//  ex for a pop3 server:
//    mail.sourceforge.net:110/pop3
//  ex for a pop3 server with explicit TLS/SSL negociation desactivated:
//    mail.sourceforge.net:110/pop3/notls (may be useful for some courier-imap
//    installation).
//  ex for an ssl pop3 server:
//    mail.sourceforge.net:995/pop3/ssl
//  ex for an ssl pop3 server with a self-signed certificate:
//    mail.sourceforge.net:995/pop3/ssl/novalidate-cert
//  protocol can only be pop3 (imap is implicit)
//
// $conf->domains[$i]->smtp = 'smtp.isp.com';
//  Optional: smtp server name or IP address
//  Leave empty to send mail via sendmail
//
// $conf->domains[$i]->smtp_port = 25;
//  Port number to connect to smtp server (usually 25)
//
// $conf->domains[$i]->login_with_domain = false;
//  Set value to true for 'user<char>domain.com' style logins
//
// $conf->domains[$i]->login_with_domain_character = '@';
//  Select character to use for login_with_domain option
//
// Note : an other way to get proper domain detection with 'user@domain'
// style logins and without setting domain into conf.php file is to
// set 'login_with_domain' to true, 'login_with_domain_character' to ''
// and 'domains[$i]->domain' to ''. Then, user and domain will be automatically
// set. This setting is ideal when NOCC webmail serves many domains and if you
// don't want to set each domain into this file.
//
//
// $conf->domains[$i]->login_prefix = '';
//  Fill in if you require login prefixes for your mail server
//
// $conf->domains[$i]->login_suffix = '';
//  Fill in if you require login suffixes for your mail server
//
// $conf->domains[$i]->login_aliases = array();
//  Uncomment for login aliases and use the following syntax:
//   login_aliases = array('alias1' => 'real_login_1',
//                         'alias2' => 'real_login_2');
//  If you want to use an external file, use the following syntax:
//   login_aliases = '@/path/to/file/';
//  See login_alias.sample file for example.
//
// $conf->domains[$i]->login_allowed = array();
//  Uncomment for allowed logins and use the following syntax:
//   login_allowed = array('login_1' => '', 'login_2' => '');
//  If you want to use an external file, use the following syntax:
//   login_allowed = '@/path/to/file/';
//  See login_allowed.sample file for example.
//
// $conf->domains[$i]->smtp_auth_method = '';
//  Select SMTP AUTH method.
//  Supported AUTH methods are :
//   '' : no authentification method
//   'PLAIN' : AUTH PLAIN method
//   'LOGIN' : AUTH LOGIN method
//   'TLS' : STARTTLS with normal login
//
// $conf->domains[$i]->imap_namespace = 'INBOX.';
//  Select IMAP Namespace
//
// $conf->domains[$i]->have_ucb_pop_server = false;
//  For old UCB POP server, change this setting to true to enable
//  new mail detection. Recommended: leave it to false for any other POP or
//  IMAP server.
//  See FAQ for more details.
//
// $conf->domains[$i]->quota_enable=false;
//  Enable quota checks.
//  Works only with c-client2000 or more recent, and IMAP inbox
//
// $conf->domains[$i]->quota_type='STORAGE';
//  Quota types.
//  Possible values are STORAGE or MESSAGE
//
// $conf->domains[$i]->smtp_allow_self_signed=false;
//  if smtp server connection using ssl/tls allow self signed certificates
//  true or false
//
// $conf->domains[$i]->smtp_verify_peer=true;
//  verify the smtp servers certificate
//  true or false
//
// $conf->domains[$i]->smtp_verify_peer_name=true;
//  verify the smtp servers name
//  true or false
//
// $conf->domains[$i]->smtp_user = '';
//  the user name, if your smtp server is configured to have a fixed, user independent, user/password authentication
//  if empty, the login credentials of the NOCC user are used
//
// $conf->domains[$i]->smtp_password = '';
//  the password, if your smtp server is configured to have a fixed, user independent, user/password authentication
//
// $conf->domains[$i]->smtp_user_without_domain = false;
//  if you login to your IMAP/POP server with domain (e.g. user@domain.org) but your SMTP server
//  only wants a user name without domain, set this value to true. It will remove the @domain.org
//  part from the user login before authentication to the SMTP server. If true this will also
//  happen if you provide the user name with $conf->domains[$i]->smtp_user option.
//  
// $conf->domains[$i]->horde_imap_client = false;
//  Use Horde/Imap_Client Library instead of PHP's imap (c-client) extension for this domain
//  See below
//    $conf->horde_imap_client = false;
//  for a system wide configuration option. The system wide option will be overwritten by domain specific options.
//   https://dev.horde.org/imap_client/
//   https://dev.horde.org/imap_client/install.php
//  additional to /ssl and /tls you can use (see above)
//   /sslv2,/sslv3,/tlsv1,/false (for no encryption, default),/true (TLS if available)
//  
// $conf->domains[$i]->allow_address_change = true;
//  Is the user allowed to change his "From:" address? (true/false)
//  This domain specific option overrides the global option
//   $conf->allow_address_change (see below)
//  if set true or false. Comment out this line if you want to use the global option
//
// $conf->domains[$i]->from_part = 'ad\(.*)';
//  This optional setting lets you define the part of the users login, which is also
//  part of the from adress in user@domain.com.
//  Example:
//    your user login with 'ad\user' and his password, but the From:-address is user@domain.com
//    without the ad\, than above setting is used to extract the part in the parantheses to form
//    the From:-address.
//
// $conf->domains[$i]->allow_rss = false;
//  This optional setting allows RSS feed for this domain if set to true
//  See global setting $conf->allow_rss
//


$i = 0;

$conf->domains[$i] = new stdClass();
$conf->domains[$i]->show_as = '';
$conf->domains[$i]->domain = '';
$conf->domains[$i]->in = '';
$conf->domains[$i]->smtp = '';
$conf->domains[$i]->smtp_port = 25;
$conf->domains[$i]->login_with_domain = false;
$conf->domains[$i]->login_with_domain_character = '@';
$conf->domains[$i]->login_prefix = '';
$conf->domains[$i]->login_suffix = '';
$conf->domains[$i]->login_aliases = array();
$conf->domains[$i]->login_allowed = array();
$conf->domains[$i]->smtp_auth_method = '';
$conf->domains[$i]->imap_namespace = 'INBOX.';
$conf->domains[$i]->have_ucb_pop_server = false;
$conf->domains[$i]->quota_enable = false;
$conf->domains[$i]->quota_type = 'STORAGE';
$conf->domains[$i]->smtp_allow_self_signed = false;
$conf->domains[$i]->smtp_verify_peer = true;
$conf->domains[$i]->smtp_verify_peer_name = true;
$conf->domains[$i]->smtp_user = '';
$conf->domains[$i]->smtp_password = '';
$conf->domains[$i]->smtp_user_without_domain = false;
$conf->domains[$i]->horde_imap_client = false;
$conf->domains[$i]->allow_address_change = true;
$conf->domains[$i]->from_part = '';
$conf->domains[$i]->allow_rss = false;

// If you want to add more domains, uncomment the following
// lines and fill them in

//$i++;
//$conf->domains[$i] = new stdClass();
//$conf->domains[$i]->show_as = '';
//$conf->domains[$i]->domain = '';
//$conf->domains[$i]->in = '';
//$conf->domains[$i]->smtp = '';
//$conf->domains[$i]->smtp_port = 25;
//$conf->domains[$i]->login_with_domain = false;
//$conf->domains[$i]->login_with_domain_character = '@';
//$conf->domains[$i]->login_prefix = '';
//$conf->domains[$i]->login_suffix = '';
//$conf->domains[$i]->login_aliases = array();
//$conf->domains[$i]->login_allowed = array();
//$conf->domains[$i]->smtp_auth_method = '';
//$conf->domains[$i]->imap_namespace = 'INBOX.';
//$conf->domains[$i]->have_ucb_pop_server = false;
//$conf->domains[$i]->quota_enable = false;
//$conf->domains[$i]->quota_type = 'STORAGE';
//$conf->domains[$i]->smtp_allow_self_signed = false;
//$conf->domains[$i]->smtp_verify_peer = true;
//$conf->domains[$i]->smtp_verify_peer_name = true;
//$conf->domains[$i]->smtp_user = '';
//$conf->domains[$i]->smtp_password = '';
//$conf->domains[$i]->allow_address_change = true;
//$conf->domains[$i]->from_part = '';
//$conf->domains[$i]->allow_rss = false;

//$i++;
//$conf->domains[$i] = new stdClass();
//$conf->domains[$i]->show_as = '';
//$conf->domains[$i]->domain = '';
//$conf->domains[$i]->in = '';
//$conf->domains[$i]->smtp = '';
//$conf->domains[$i]->smtp_port = 25;
//$conf->domains[$i]->login_with_domain = false;
//$conf->domains[$i]->login_with_domain_character = '@';
//$conf->domains[$i]->login_prefix = '';
//$conf->domains[$i]->login_suffix = '';
//$conf->domains[$i]->login_aliases = array();
//$conf->domains[$i]->login_allowed = array();
//$conf->domains[$i]->smtp_auth_method = '';
//$conf->domains[$i]->imap_namespace = 'INBOX.';
//$conf->domains[$i]->have_ucb_pop_server = false;
//$conf->domains[$i]->quota_enable = false;
//$conf->domains[$i]->quota_type = 'STORAGE';
//$conf->domains[$i]->smtp_allow_self_signed = false;
//$conf->domains[$i]->smtp_verify_peer = true;
//$conf->domains[$i]->smtp_verify_peer_name = true;
//$conf->domains[$i]->smtp_user = '';
//$conf->domains[$i]->smtp_password = '';
//$conf->domains[$i]->allow_address_change = true;
//$conf->domains[$i]->from_part = '';
//$conf->domains[$i]->allow_rss = false;

// $conf->utf8_decode = true;
//  if you have login problems because of special or language specific characters
//  you may try to set this to false.
$conf->utf8_decode = true;

// Column order from the messages list
//  1 = From
//  2 = To
//  3 = Subject
//  4 = Date
//  5 = Size
//  6 = Read/Unread
//  7 = Attachment
//  8 = Priority Text
//  9 = Priority Number
// 10 = Flagged
// 11 = SPAM
$conf->column_order = array('6', '1', '7', '2', '3', '4', '5');

// Master key for session password encryption. Longer is better.
// It must not be left empty.
$conf->master_key = '';

// Preferences, sessions and contacts data directory
// IMPORTANT: This directory must exist and be writable by the user
// the webserver is running as (e.g. 'apache', or 'nobody'). For
// Apache, see the User directive in the httpd.conf file.
// See README for more about this.
// This should be something like 'profiles/' on Unix System
// or 'prefs\\' on Win32 (note that we must escape "\").
// You should not use a subfolder within your Nocc installation, as it will
// be readable by everybody, and will contain sensible information as email
// addresses and names.
// If left empty, preferences, contacts and session saving will be disabled.
$conf->prefs_dir = '';

// Prune session files
// NOCC removes session files which are older than 4 weeks automatically.
// On large systems this can be time consuming and harm the user experience
// when using NOCC. If you disable this, you should prune the
// directory specified with $conf->prefs_dir on your own by removing all
// files named NOCCLI_* and which are older than 4 weeks.
// The default value is 1
// Switch automatic pruning off by setting the value 0
$conf->prune_sessions = 1;

// Default tmp directory (where to store temporary uploaded files)
// This should be something like '/tmp' on Unix System
// And 'c:\\temp' on Win32 (note that we must escape "\")
$conf->tmpdir = '';

// minimum session lifetime in seconds
//  if "remember me" on login screen is NOT checked the session expires
//    - after this time of no user input or
//    - user clicks on logoff or
//    - user closes the browser
//    - the max_session_lifetime is reached
$conf->min_session_lifetime = 60*60*6;   // = 6 hours (default)

// maximum session lifetime in seconds
//  if "remember me" on login screen IS checked the session expires
//    - after this time in seconds or
//    - user clicks on logoff
$conf->max_session_lifetime = 60*60*24*7*4;   // = 4 weeks (default)

// close session if client IP has changed
//  if the clients IP changes during a valid session the server
//  closes the session and the client has to login again.
//  Set this to true if you want this.
$conf->check_client_ip=true;

// time in seconds to check the inbox for new or changed number of messages
//  don't poll too frequently, the mail server may block
//  set to 0 to switch off
$conf->check_inbox_timer=600;

// ###
// ### Optional parameters ###
// ###
// ### The following parameters can be changed but it's not necessary to
// ### get a working version of nocc
// ###

// ##
// ## PHP options
// ##

// Error reporting
// Display all errors (including IMAP connection errors, such as
// 'host not found' or 'invalid login')
$conf->debug_level = E_ALL & ~E_NOTICE;

// PHP error reporting for this application
error_reporting($conf->debug_level);

if (version_compare(PHP_VERSION, '5.3.0', '<')) { //if older as PHP 5.3.0...
  // Prevent mangling of uploaded attachments
  set_magic_quotes_runtime(0);
}

// Allow more memory than default setting in order to handle correctly
// large mails attachments. Try to find correct setting (about 2.5x total
// attachment size)
$conf->memory_limit="20M";

// Use Horde/Imap_Client Library instead of PHP's imap (c-client) extension.
// https://dev.horde.org/imap_client/
// https://dev.horde.org/imap_client/install.php
$conf->horde_imap_client = false;

// Optional path to the Horde/Imap client library, only needed if library is
// in some unusual place
//$conf->horde_imap_client_path = "/usr/local/php72/lib/php/Horde";

// ##
// ## Server options
// ##

// Base URL where NOCC is hosted (only needed for Xitami servers, see #463390)
// (NOTE: should end in a slash). Leave blank to detect it automagically.
//$conf->base_url = 'http://www.yoursite.com/webmail/';
$conf->base_url = '';

// Select the CRLF to use.
// According to rfc-822 CRLF is "\r\n"
// OS independent, this is a MTA problem
// not ours.
$conf->crlf = "\r\n";

// Default smtp server and smtp_port (default is 25)
// If a domain has no smtp server, this one will be used
// If no smtp server is provided, Nocc will default to the mail() function,
// and try to use Sendmail or any other MTA (Postfix)
$conf->default_smtp_server = '';
$conf->default_smtp_port = 25;

//Uncomment this to allow secure typed domain logins
//$conf->typed_domain_login = '1';

// ##
// ## Login options
// ##

// Allow only specified characters for login. The format of this configuration
// variable is any valid regular expression.
// Example: '^[a-zA-Z0-9_]+$' : login only with letters (upper and lower case),
// numbers and '_' character
// Set to '' to disable
$conf->allowed_char='';

// the user can logout or not (if nocc is used within your website
// enter 'false' here else leave 'true')
$conf->enable_logout = true;

// If you use many mail domains, the one used will be we one of the HTTP host,
// and the user won't be asked for the domain to connect.
// Set to true to enable.
$conf->vhost_domain_login = false;

// ##
// ## Presentation options
// ##

// Default folder to go first
$conf->default_folder = 'INBOX';

// if browser has no preferred language, we use the default language
// This is only needed for browsers that don't send any preferred
// language such as W3 Amaya
$conf->default_lang = 'en';

// By default the messages are sorted by date 
$conf->default_sort = '4';

// By default the most recent is in top ('1' --> sorting top to bottom,
// '0' --> bottom to top)
$conf->default_sortdir = '1';

// Default theme
$conf->default_theme = 'standard';

// If you don't want to display images (GIF, JPEG and PNG) sent as attachements
// set it to 'false'
$conf->display_img_attach = true;

// If you don't want to display text/plain attachments set it to 'false'
$conf->display_text_attach = true;

// force default language to be set, rather than browser prefered language
$conf->force_default_lang = false;

// hide the language selection from the login page
$conf->hide_lang_select_from_login_page = false;

// hide the theme selection from the login page
$conf->hide_theme_select_from_login_page = false;

// How many messages to display in the inbox (devel only)
$conf->max_msg_num = 1;

// This sets the number of messages per page to display from a imap folder or
// pop mailbox
$conf->msg_per_page = '25';

// If you wanna make your own theme and force people to use that one, 
// set $conf->use_theme to false and fill in the $conf->default_theme to 
// the theme name you want to use
// Theme handling: allows users to choose a theme on the login page
$conf->use_theme = true;

// Use ckeditor5 for composing html mail if set to true
// otherwise use ckeditor4
//   this is an experimental feature
$conf->ckeditor5 = false;

// Allow RSS feeds globally if set to true, default is false
//  if set to false, it overwrites an optional domain specific setting
//  if set to true, an optional domain specific setting overwrites this global setting
$conf->allow_rss = false;

// ##
// ## Message management options
// ##

// This message is added to every message, the user cannot delete it
// Be careful if you modify this, do not forget to write '\r\n' to switch
// to the next line !
$conf->ad = "___________________________________\r\nNOCC, http://nocc.sourceforge.net";

// Use old-style forwarding (quote original message, and attach original
// attachments).
// This is discouraged, because it mangles the original message, removing
// important headers etc.
$conf->broken_forwarding = false;

// Default encoding charset to use to display email which does not include one.
$conf->default_charset = 'ISO-8859-1';                                                                                
// Delay between 2 mail send (in second)
$conf->send_delay = 30;

// ##
// ## User options
// ##

// Is the user allowed to change his "From:" address? (true/false)
$conf->allow_address_change = true;

// the user can change their 'reply leadin' string
$conf->enable_reply_leadin = false;

// let user see the header of a message
$conf->use_verbose = true;

// Number of contacts per user, 0 to disable contacts list
$conf->contact_number_max = 10;

// activate 'group' contacts via LDAP
$conf->contact_ldap = false;

// ##
// ## LDAP options
// ##

// LDAP hostname
$conf->contact_ldap_options['host'] = '';

// use LDAP SSL connection
$conf->contact_ldap_options['ssl'] = false;

// use LDAP authentication, leave it true if you are unsure
$conf->contact_ldap_options['anonymous'] = true;

// use LDAP authentication, leave it empty if you are unsure
$conf->contact_ldap_options['bind_dn'] = '';

// use LDAP authentication, leave it empty if you are unsure
$conf->contact_ldap_options['bind_pass'] = '';

// LDAP port, leave it empty if you are unsure
$conf->contact_ldap_options['port'] = '';

// Display 0-9, A-Z menu entry in contact list
$conf->contact_ldap_options['alphanum'] = true;

// LDAP DN (Distinguished Name)
$conf->contact_ldap_options['dn'] = 'dc=nocc, dc=sourceforge, dc=net';

// LDAP search filter
$conf->contact_ldap_options['filter'] = '(|(uid=%s))';

// Field order must be: uid (username), cn (common name) (or first name), FALSE (or last name), FALSE (or email)
$conf->contact_ldap_options['attributes'] = array("uid", "cn", false, false);

// LDAP enable search
$conf->contact_ldap_options['search'] = true;

// LDAP search options
$conf->contact_ldap_options['search_options'] = array('uid' => 'Nickname', 'cn' => 'Full Name');
//!!! FUZZY !!! $conf->contact_ldap_options['search_options'] = array('uid' => $html_contact_nick, 'cn' => $html_full_name);

// LDAP sort search by 'attribute' (example: uid )
$conf->contact_ldap_options['search_sortby'] = 'uid';

// LDAP 'group contacts' title
$conf->contact_ldap_options['group_title'] = 'NOCC';

// LDAP mail suffix for contacts, if needed, otherwise it will take the default domain
$conf->contact_ldap_options['suffix'] = '';

// ##
// ## Auto update option
// ##

// list of user@domain who are allowed to trigger the automatic update feature
//   php.ini "allow_url_fopen" must be set to On
//
// examples for:
//   conf->domains[$i]->domain = 'domain.de';
//
//   $conf->auto_update['user'] = array('user1@domain.de','user2@domain.de');
//     user1 and user2 will be informed about new NOCC version and can trigger the server update
//   $conf->auto_update['user'] = array();
//     empty list => no autoupdate, no information about new version
//   $conf->auto_update['user'] = array('all');
//     all logged in users can trigger a server update
//
// examples for:
//   conf->domains[$i]->domain = '';
//
//   $conf->auto_update['user'] = array('user1@');
//     user1 will be informed if your domain is empty
//
$conf->auto_update['user'] = array();

// ###################     End of Configuration     ####################
?>
