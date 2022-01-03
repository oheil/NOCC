<?php
/**
 * Language configuration file for NOCC
 *
 * Copyright 2001 Nicolas Chalanset <nicocha@free.fr>
 * Copyright 2001 Olivier Cahagne <cahagn_o@epita.fr>
 *
 * This file is part of NOCC. NOCC is free software under the terms of the
 * GNU General Public License. You should have received a copy of the license
 * along with NOCC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NOCC
 * @subpackage Translations
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @version    SVN: $Id: bg.php 2937 2021-03-12 06:05:32Z translatewiki $
 */
/** Bulgarian (български)
 * 
 * See the qqq 'language' for message documentation incl. usage of parameters
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author DCLXVI
 * @author Evgeni Gechev <etg@setcom.bg>
 * @author StanProg
 * @author Vodnokon4e
 */

$lang_locale = 'bg_BG.UTF-8';
$default_date_format = '%d-%m-%Y';
$no_locale_date_format = '%d-%m-%Y';
$default_time_format = '%H:%M';
$err_user_empty = 'Не е въведено име';
$err_passwd_empty = 'Не е въведена парола';
$alt_delete = 'Изтриване на маркираните писма';
$alt_delete_one = 'Изтриване на писмото';
$alt_new_msg = 'Нови писма';
$alt_reply = 'Отговор до автора';
$alt_reply_all = 'Отговор до всички';
$alt_forward = 'Препращане';
$alt_next = 'Следващи';
$alt_prev = 'Предишни';
$title_next_page = 'Следваща страница';
$title_prev_page = 'Предишна страница';
$title_next_msg = 'Следващо писмо';
$title_prev_msg = 'Предишно писмо';
$html_theme_label = 'Облик:';
$html_welcome = 'Добре дошли в %1$s';
$html_login = 'Влизане';
$html_user_label = 'Потребител:';
$html_passwd_label = 'Парола:';
$html_submit = 'Потвърждение';
$html_help = 'Помощ';
$html_server_label = 'Сървър:';
$html_wrong = 'Името или паролата са грешни';
$html_retry = 'Опитайте отново';
$html_remember = 'Запомняне на настройките';
$html_lang_label = 'Език:';
$html_msgperpage_label = 'Съобщения на страница:';
$html_preferences = 'Настройки';
$html_full_name_label = 'Пълно име:';
$html_email_address_label = 'Адрес за е-поща:';
$html_bccself = 'Изпращане на копие до мен';
$html_hide_addresses = 'Скриване на адресите';
$html_outlook_quoting = 'Outlook формат';
$html_reply_to = 'Отговор до';
$html_reply_to_label = 'Отговор до:';
$html_use_signature = 'Използване на подпис';
$html_signature = 'Подпис';
$html_signature_label = 'Подпис:';
$html_prefs_updated = 'Настройките са обновени';
$html_manage_folders_link = 'Управляване на IMAP папки';
$html_manage_filters_link = 'Управление на филтри за е-поща';
$html_use_graphical_smilies = 'Използване на емотикони';
$html_colored_quotes = 'Оцветени цитати';
$html_display_struct = 'Показване на структуриран текст';
$html_send_html_mail = 'Изпращане на писмата в HTML формат';
$html_folders = 'Папки';
$html_folders_create_failed = 'Папката не може да бъде създадена!';
$html_folders_rename_failed = 'Папката не може да бъде преименувана!';
$html_folders_updated = 'Папките бяха обновени';
$html_folder_subscribe = 'Абониране за';
$html_folder_rename = 'Преименуване';
$html_folder_create = 'Създаване на нова папка с име';
$html_folder_remove = 'Отписване от';
$html_folder_delete = 'Изтриване';
$html_folder_to = 'на';
$html_filter_remove = 'Изтриване';
$html_filter_body = 'Текст на съобщението';
$html_filter_subject = 'Тема';
$html_filter_contains = 'съдържа';
$html_filter_name = 'Име на филтъра';
$html_filter_action = 'Действие на филтъра';
$html_filter_moveto = 'Преместване в';
$html_select_one = '--Избиране--';
$html_and = 'И';
$html_new_msg_in = 'Нови съобщения в';
$html_or = 'или';
$html_move = 'Преместване';
$html_copy = 'Копиране';
$html_messages_to = 'на избраните съобщения в';
$html_gotopage = 'Отиди на страница';
$html_gotofolder = 'Отиди в папка';
$html_other_folders = 'Списък на папките';
$html_page = 'Страница';
$html_of = 'от';
$html_view_header = 'Покажи служ. информация';
$html_remove_header = 'Скрий служ. информация';
$html_inbox = 'Получени писма';
$html_new_msg = 'Изпращане на писмо';
$html_reply = 'Отговаряне';
$html_reply_short = 'Отг:';
$html_reply_all = 'Отговор до всички';
$html_forward = 'Препращане';
$html_forward_short = 'Препр:';
$html_forward_info = 'Препратеното съобщение ще бъде изпратено като прикачен файл на това съобщение.';
$html_delete = 'Изтриване';
$html_new = 'Ново';
$html_mark = 'Изтриване';
$html_att_label = 'Прикачен файл:';
$html_atts_label = 'Прикачени файлове:';
$html_unknown = '[неизвестно]';
$html_attach = 'Прикачване';
$html_attach_forget = 'Прикачете файла преди да изпратите съобщението!';
$html_attach_delete = 'Изтриване на прикачения файл';
$html_attach_none = 'Необходимо е да бъде избран файл за прикачване!';
$html_sort_by = 'Сортиране по';
$html_sort = 'Сортиране';
$html_from = 'От';
$html_from_label = 'От:';
$html_subject = 'Тема';
$html_subject_label = 'Тема:';
$html_date = 'Дата';
$html_date_label = 'Дата:';
$html_sent_label = 'Изпратени:';
$html_wrote = 'написа';
$html_quote_wrote = 'На %1$s в %2$s %3$s написа:';
$html_size = 'Големина';
$html_totalsize = 'Общо';
$html_kb = 'КБ';
$html_bytes = 'байта';
$html_filename = 'име на файл';
$html_to = 'За';
$html_to_label = 'До:';
$html_cc = 'Копие';
$html_cc_label = 'Копие:';
$html_bcc_label = 'Скрито копие:';
$html_nosubject = 'Без тема';
$html_send = 'Изпращане';
$html_cancel = 'Отказ';
$html_no_mail = 'Няма писма';
$html_logout = 'Излизане';
$html_msg = 'Писмо';
$html_msgs = 'Писма';
$html_configuration = 'Сървърът не е конфигуриран!';
$html_priority = 'Приоритет';
$html_priority_label = 'Приоритет:';
$html_low = 'Нисък';
$html_normal = 'Нормален';
$html_high = 'Висок';
$html_spam = 'Спам';
$html_spam_warning = 'Това съобщение бе категоризирано като спам.';
$html_receipt = 'Изискване на обратна разписка';
$html_select = 'Маркирай';
$html_select_all = 'Обръщане на избора';
$html_loading_image = 'Зареждане на картинка';
$html_send_confirmed = 'Писмото е прието';
$html_no_sendaction = 'Не е посочено действие. Опитате да разрешите Javascript в браузъра.';
$html_error_occurred = 'Възникна грешка';
$html_prefs_file_error = 'Файлът с настройките не може да бъде отворен за запис.';
$html_usenet_separator = 'Добавяне на Usenet разделител ("-- \n") преди подписа';
$html_mark_as = 'Отбелязване като';
$html_read = 'прочетено';
$html_unread = 'непрочетено';
$html_encoding_label = 'Кодиране:';
$html_add = 'Добавяне';
$html_contacts = '%1$s Контакти';
$html_modify = 'Редактиране';
$html_back = 'Връщане';
$html_contact_add = 'Добавяне на нов контакт';
$html_contact_mod = 'Редактиране на контакт';
$html_contact_first = 'Име';
$html_contact_last = 'Фамилия';
$html_contact_mail = 'Е-поща';
$html_contact_del = 'от списъка с контакти';
$html_contact_err1 = 'Максималният брой контакти е „%1$d“';
$html_contact_err2 = 'Не можете да добавяте нов контакт';
$html_contact_err3 = 'Нямате права за достъп до списъка с контакти';
$html_contact_none = 'Не могат да бъдат намерени контакти.';
$html_del_msg = 'Изтриване на избраните съобщения?';
$html_down_mail = 'Изтегляне';
$original_msg = '-- Оригинално писмо --';
$to_empty = 'Полето \'За\' не трябва да е празно !';
$html_images_warning = 'За вашата сигурност, външните картинки не са показани.';
$html_images_display = 'Показване на картинките';
$html_smtp_error_no_conn = 'Грешка! Не може да бъда осъществена връзка със сървъра';
$html_smtp_error_unexpected = 'Грешка! Непознат отговор от сървъра:';
$lang_could_not_connect = 'Невъзможно е свързване със сървъра';
$lang_invalid_msg_num = 'Грешен брой съобщения';
$html_invalid_email_address = 'Невалиден адрес за електронна поща';
$html_invalid_msg_per_page = 'Невалиден брой съобщения на страница';
$html_seperate_msg_win = 'Съобщенията в отделен прозорец';
$html_err_file_contacts = 'Файлът с контакти не може да бъде творен за запис.';
$html_session_file_error = 'Сесийният файл не може да бъде отворен за запис.';
$lang_err_send_delay = 'Необходимо е изчакване между две писма (%1$d секунди)';
$html_search = 'Търсене';
$html_collect_option0 = 'Никога';
$html_collect_option1 = 'Само от изходящите писма';
$html_collect_option2 = 'Само от отворените писма';
$html_collect_option3 = 'Винаги';
