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
 * @version    SVN: $Id: ar.php 2937 2021-03-12 06:05:32Z translatewiki $
 */
/** Arabic (العربية)
 * 
 * See the qqq 'language' for message documentation incl. usage of parameters
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Hhaboh162002
 * @author Meno25
 * @author Mohamed Hadrouj <mohamed.hadrouj@wanadoo.co.ma>
 * @author Moud hosny
 * @author OsamaK
 * @author Tala Ali
 * @author ترجمان05
 * @author ديفيد
 * @author محمد أحمد عبد الفتاح
 */

$lang_locale = 'ar_AR.UTF-8';
$lang_dir = 'rtl';
$default_date_format = '%A %d %B %Y';
$no_locale_date_format = '%d-%m-%Y';
$default_time_format = '%H:%M';
$err_user_empty = 'حقل تسجيل الدخول فارغ';
$err_passwd_empty = ' كلمة السر غير صحيحة. أعد المحاولة';
$alt_delete = 'ءازالة  الرسا ئل المختارة';
$alt_delete_one = 'ءازالة الرسالة';
$alt_new_msg = 'رسالة جديدة';
$alt_reply = 'رد على الكاتب';
$alt_reply_all = 'الرد على الكل';
$alt_forward = 'إعادة توجيه';
$alt_next = 'التالي';
$alt_prev = 'السابق';
$title_next_page = 'الصفحة التالية';
$title_prev_page = 'الصفحة السابقة';
$title_next_msg = 'الرسالة التالية';
$title_prev_msg = 'الرسالة السابقة';
$html_theme_label = 'السمة:';
$html_welcome = 'مرحبا إلى %1$s';
$html_login = 'تسجيل الدخول';
$html_user_label = 'المستخدم:';
$html_passwd_label = 'كلمة السر:';
$html_submit = 'إرسال';
$html_help = 'مساعدة';
$html_server_label = 'الخادم:';
$html_wrong = 'ءأسم المستخدم أو كلمة السر غير صحيحة';
$html_retry = 'إعادة المحاولة';
$html_remember = 'تذكرني';
$html_lang_label = 'اللغة:';
$html_msgperpage_label = 'الرسائل لكل صفحة:';
$html_preferences = 'التفضيلات';
$html_full_name_label = 'الاسم الكامل:';
$html_email_address_label = 'عنوان البريد الإلكتروني:';
$html_bccself = 'إرسال نسخة مخفية إلى نفسي';
$html_hide_addresses = 'أخفِ العناوين';
$html_outlook_quoting = 'اقتباس على غرار التوقعات';
$html_reply_to = 'رد إلى';
$html_reply_to_label = 'رد على:';
$html_use_signature = 'استخدم التوقيع';
$html_signature = 'التوقيع';
$html_signature_label = 'التوقيع:';
$html_reply_leadin_label = 'الرد الأساسي:';
$html_prefs_updated = 'تم تحديث التفضيلات';
$html_manage_folders_link = 'أدر مجلدات IMAP';
$html_manage_filters_link = 'أدر مرشحات البريد الإلكتروني';
$html_use_graphical_smilies = 'استخدم الابتسامات الرسومية';
$html_sent_folder_label = 'أُرسلَت نسخة رسائل البريد الإلكتروني إلى مجلد مخصص:';
$html_trash_folder_label = 'نقل رسائل البريد الإلكتروني المحذفوفة إلى مجلد مخصص:';
$html_inbox_folder_label = 'ربط إدخال القائمة صندوق الوارد بالمجلد:';
$html_colored_quotes = 'اقتباسات ملوّنة';
$html_display_struct = 'عرض نص منظم';
$html_send_html_mail = 'أرسل البريد بهيئة HTML';
$html_folders = 'مجلدات';
$html_folders_create_failed = 'تعذّر إنشاء المجلد!';
$html_folders_sub_failed = 'تعذّر الاشتراك بالمجلد!';
$html_folders_unsub_failed = 'تعذّر إلغاء الاشتراك من المجلد!';
$html_folders_rename_failed = 'تعذر إعادة تسمية الملجد!';
$html_folders_updated = 'حُدّثت المجلدات';
$html_folder_subscribe = 'اشترك ب';
$html_folder_rename = 'إعادة تسمية';
$html_folder_create = 'أنشئ مجلدا جديدا يسمّى';
$html_folder_remove = 'ألغِ الاشتراك من';
$html_folder_delete = 'حذف';
$html_folder_to = 'إلى';
$html_filter_remove = 'حذف';
$html_filter_body = 'جسم الرسالة';
$html_filter_subject = 'موضوع الرسالة';
$html_filter_to = 'حقل إلى';
$html_filter_cc = 'حقل النسخة';
$html_filter_from = 'حقل من';
$html_filter_change_tip = 'لتغيير مرشّح ببساطة اكتب فوقه.';
$html_reapply_filters = 'أعد تفعيل كل المرشحات';
$html_filter_contains = 'يحتوي على';
$html_filter_name = 'اسم المرشح';
$html_filter_action = 'فعل المرشح';
$html_filter_moveto = 'نقل إلى';
$html_select_one = '--اختر واحدا--';
$html_and = 'و';
$html_new_msg_in = 'الرسائل الجديدة في';
$html_or = 'أو';
$html_move = 'نقل';
$html_copy = 'نسخ';
$html_messages_to = 'الرسالة المختارة إلى';
$html_gotopage = 'اذهب إلى الصفحة';
$html_gotofolder = 'اذهب إلى المجلد';
$html_other_folders = 'قائمة المجلدات';
$html_page = 'صفحة';
$html_of = 'ل';
$html_view_header = 'المقدمة';
$html_remove_header = 'ءاخفاء المقدمة';
$html_inbox = 'صندوق الرسائل';
$html_new_msg = 'رسالة جديدة';
$html_reply = 'رُد';
$html_reply_short = 'رد:';
$html_reply_all = 'ءاجابة للكل';
$html_forward = 'تحويل الرسالة ءالى';
$html_forward_short = 'تحويل:';
$html_forward_info = 'سيتم إرسال الرسالة المعاد توجيهها كمرفق لهذا.';
$html_delete = 'حذف';
$html_new = 'جديد';
$html_mark = 'حذف';
$html_att_label = 'مرفق:';
$html_atts_label = 'مرفقات:';
$html_unknown = '[غير معروف]';
$html_part_x = 'الجزء %s';
$html_attach = 'إرفاق';
$html_attach_forget = '!يجب ءاضافة الملف قبل ءارسال الرسالة';
$html_attach_delete = 'إزالة المرفقات المختارة';
$html_attach_none = 'يجب أن تختار ملفا للإرفاق!';
$html_sort_by = 'مرتبة حسب';
$html_sort = 'رتب';
$html_from = 'من';
$html_from_label = 'من:';
$html_subject = 'الموضوع';
$html_subject_label = 'الموضوع:';
$html_date = 'التاريخ';
$html_date_label = 'التاريخ:';
$html_sent_label = 'أرسل:';
$html_wrote = 'كتب';
$html_quote_wrote = 'في %1$s %2$s, %3$s كتب:';
$html_size = 'الحجم';
$html_totalsize = 'الحجم الكلي';
$html_kb = 'كيلوبايت';
$html_mb = 'ميجابايت';
$html_gb = 'جيجابايت';
$html_bytes = 'بايت';
$html_filename = 'ملف';
$html_to = 'إلى';
$html_to_label = 'إلى:';
$html_cc = 'نسخة';
$html_cc_label = 'سي سي:';
$html_bcc_label = 'بي سي سي:';
$html_nosubject = 'بدون موضوع';
$html_send = 'أرسل';
$html_cancel = 'إلغاء';
$html_no_mail = 'ليست هناك أية رسالة';
$html_logout = 'خروج';
$html_msg = 'رسالة';
$html_msgs = 'رسائل';
$html_configuration = 'لم يُعد هذا الخادوم بشكل صحيح!';
$html_priority = 'أولوية';
$html_priority_label = 'الأولوية:';
$html_lowest = 'الأقل';
$html_low = 'أقل';
$html_normal = 'عادي';
$html_high = 'أعلى';
$html_highest = 'الأعلى';
$html_flagged = 'معلم';
$html_spam = 'سخام';
$html_spam_warning = 'تم تصنيف هذه الرسالة كرسالة غير مرغوب فيها.';
$html_receipt = 'طلب إيصال عودة';
$html_select = 'اختر';
$html_select_all = 'اعكس الاختيار';
$html_select_contacts = 'اختر مراسلين';
$html_loading_image = 'تحميل الصورة';
$html_send_confirmed = 'تم قبول بريدك الإلكتروني للتسليم';
$html_no_sendaction = 'لا يوجد أي إجراء محدد; حاول تمكين جافا سكريبت.';
$html_error_occurred = 'صودف خطأ';
$html_prefs_file_error = 'غير قادر على فتح ملف تفضيلات للكتابة.';
$html_wrap = 'عدد الحروف لتغليف الرسائل الصادرة به:';
$html_wrap_none = 'لا لف';
$html_usenet_separator = 'ضع فاصل ("-- \n") قبل التوقيع';
$html_mark_as = 'علّم كـ';
$html_read = 'اقرأ';
$html_unread = 'غير مقروءة';
$html_encoding_label = 'ترميز المحارف:';
$html_add = 'أضف';
$html_contacts = 'معارف %1$s';
$html_modify = 'عدل';
$html_back = 'رجوع';
$html_contact_add = 'أضف مراسلا جديدا';
$html_contact_mod = 'عدّل مراسلا';
$html_contact_first = 'الاسم الأول';
$html_contact_last = 'الاسم الأخير';
$html_contact_nick = 'الكنية';
$html_contact_mail = 'البريد الإلكتروني';
$html_contact_list = 'قائمة معارف %1$s';
$html_contact_del = 'من قائمة المراسلين';
$html_contact_count = '%1$d اتصالات';
$html_contact_err1 = 'أقصى عدد للمراسلين هو "%1$d"';
$html_contact_err2 = 'لا تستطيع إضافة مراسل جديد';
$html_contact_err3 = 'لا تمتلك صلاحيات الوصول إلى قائمة المراسلين';
$html_contact_none = 'تعذّر إيجاد أي مراسلين.';
$html_contact_ruler_top = 'أعلى';
$html_contact_listcheck_title = 'تحقق لإضافة البريد الإلكتروني إلى القائمة.';
$html_contact_list_add = 'أضف إلى القائمة';
$html_contact_listname = 'اسم القائمة';
$html_contact_listonly = 'قوائم فقط';
$html_contact_all = 'عرض الكل';
$html_contact_add_confirm = 'إرفاق رسائل البريد الإلكتروني بالقائمة الحالية؟';
$html_del_msg = 'أأحذف الرسائل المختارة؟';
$html_down_mail = 'تنزيل';
$original_msg = '--  الرسالة الأصلية  --';
$to_empty = 'يجب ءاعطاء عنوان المرسل ءاليه !';
$html_images_warning = 'لأسباب أمنية، الصور البعيدة غير معروضة.';
$html_images_display = 'اعرض الصور';
$html_smtp_error_no_conn = 'تعذّر فتح اتصال SMTP';
$html_smtp_error_unexpected = 'رد SMTP غير متوقع:';
$lang_could_not_connect = 'لم يمكن الاتصال بالخادم';
$lang_invalid_msg_num = 'عدد الرسائل السيئة';
$html_file_upload_attack = 'هجوم تحميل الملفات ممكن';
$html_invalid_email_address = 'عنوان بريد إلكتروني غير صحيح';
$html_invalid_msg_per_page = 'عدد رسائل لكل صفحة غير صحيح';
$html_invalid_wrap_msg = 'عرض التفاف الرسالة غير صالح';
$html_seperate_msg_win = 'الرسائل في نافذة منفصلة';
$html_err_file_contacts = 'غير قادر على فتح ملف جهات الاتصال للكتابة.';
$html_session_file_error = 'غير قادر على فتح ملف جلسة للكتابة.';
$html_login_not_allowed = 'تسجيل الدخول هذا لا يسمح بالاتصال.';
$lang_err_send_delay = 'يجب أن تنتظر بين البريدين الإلكترونيين (%1$d ثانية)';
$html_search = 'بحث';
$html_new_session = 'الجلسة التالية';
$html_fd_filename = 'تنزيل %1$s';
$html_fd_mailcount = '{{PLURAL:$1|0=لا يوجد بريد إلكترونى|1= يوجد %1$d email بريد إلكترونى}} في المجلد.';
$html_fd_mailskip = 'رسائل البريد الإلكتروني التالية لن تكون جزءا من ملف mbox; لأنها سوف تتجاوز حد بي إتش بي memory_limit:';
$html_fd_filesize = 'حجم %1$d';
$html_fd_skipcount = 'ب %1$d رسائل بريد إلكتروني';
$html_fd_largefolder = 'اعتمادا على سرعة التحميل الخاصة بك، قد يفشل هذا التنزيل بسبب الوقت النصي به.<br /> يُرجَى التحقق من التنزيل للتأكد من اكتمالها أو إعداد max_execution_time إلى قيمة أعلى في ملف php.ini.';
$reset_clicked = 'هل تريد حقا أن تمسح هذا النموذج؟';
$html_send_recover = 'تسجيل الدخول لاستعادة المسودة المفقودة!';
$html_send_discard = 'انقر هنا لتجاهل المسودة المحفوظة.';
$html_collect_label = 'تجميع عناوين البريد الإلكتروني تلقائيا:';
$html_collect_option0 = 'أبدًا';
$html_collect_option1 = 'من رسائل البريد الإلكتروني الصادرة فقط';
$html_collect_option2 = 'من رسائل البريد الإلكتروني المفتوحة فقط';
$html_collect_option3 = 'دائمًا';
$html_version_message1 = 'نحن نستخدم أحدث نسخة';
$html_version_message2 = 'الإصدار الأخير لا يمكن استرجاعه';
$html_version_message3 = 'يتوفر إصدار جديد';
$html_session_expired = 'انتهت صلاحية هذه الدورة';
$html_session_ip_changed = 'بسبب تغيير أيبي العميل';
$html_session_expire_time = 'تنتهي هذه الدورة تلقائيا في';
$html_inbox_changed = 'تم تغيير محتوى البريد الوارد الخاص بك';
$html_inbox_show_alert = 'عرض صندوق تنبيه عندما يتغير عدد الرسائل الإلكترونية في البريد الوارد';
$lang_horde_require_failed = '.لم يتم العثور على فئة العميل';
$lang_strong_encryption_required = 'التشفير غير الآمن غير مسموح به';
