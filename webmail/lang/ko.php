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
 * @version    SVN: $Id: ko.php 2937 2021-03-12 06:05:32Z translatewiki $
 */
/** Korean (한국어)
 * 
 * See the qqq 'language' for message documentation incl. usage of parameters
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Gusdud25
 * @author Hym411
 * @author Klutzy
 * @author Kwj2772
 * @author Mhha
 * @author Revi
 * @author Roh,Kyoung-Min <rohbin@dreamwiz.com>
 * @author Wtspout
 * @author Ykhwong
 * @author Yknok29
 * @author 그냥기여자
 * @author 아라
 */

$lang_locale = 'ko_KR.UTF-8';
$lang_dir = 'ltr';
$default_date_format = '%Y-%m-%d';
$no_locale_date_format = '%Y-%m-%d';
$default_time_format = '%I:%M %p';
$err_user_empty = '로그인 필드가 비어 있습니다';
$err_passwd_empty = '비밀번호 필드가 비어 있습니다';
$alt_delete = '선택된 메시지 삭제';
$alt_delete_one = '메시지 삭제';
$alt_new_msg = '새 메시지';
$alt_reply = '보낸이에게 답장';
$alt_reply_all = '모두 답장';
$alt_forward = '전달';
$alt_next = '다음';
$alt_prev = '이전';
$title_next_page = '다음 페이지';
$title_prev_page = '이전 페이지';
$title_next_msg = '다음 메시지';
$title_prev_msg = '이전 메시지';
$html_theme_label = '테마:';
$html_welcome = '%1$s에 오신 것을 환영합니다';
$html_login = '로그인';
$html_user_label = '사용자:';
$html_passwd_label = '비밀번호:';
$html_submit = '제출';
$html_help = '도움말';
$html_server_label = '서버:';
$html_wrong = '로그인 이름이나 비밀번호가 잘못되었습니다';
$html_retry = '다시 시도';
$html_remember = '기억하기';
$html_lang_label = '언어:';
$html_msgperpage_label = '페이지마다 메시지 수:';
$html_preferences = '환경 설정';
$html_full_name_label = '성명:';
$html_email_address_label = '이메일 주소:';
$html_bccself = '숨은 참조 자신';
$html_hide_addresses = '숨은 주소';
$html_outlook_quoting = '아웃룩 형식 인용';
$html_reply_to = '답장 보낼이';
$html_reply_to_label = '답장 보낼이:';
$html_use_signature = '서명 사용';
$html_signature = '서명';
$html_signature_label = '서명:';
$html_reply_leadin_label = '답장 선두자:';
$html_prefs_updated = '환경 설정이 업데이트되었습니다';
$html_manage_folders_link = 'IMAP 폴더 관리';
$html_manage_filters_link = '이메일 필터 관리';
$html_use_graphical_smilies = '그림 이모티콘 사용';
$html_sent_folder_label = '보낸 이메일을 전용 폴더 안으로 복사:';
$html_trash_folder_label = '삭제된 이메일을 전용 폴더 안으로 이동:';
$html_colored_quotes = '색이 있는 인용';
$html_display_struct = '구문된 텍스트 표시';
$html_send_html_mail = 'HTML 형식으로 이메일 보내기';
$html_folders = '폴더';
$html_folders_create_failed = '폴더를 만들 수 없습니다!';
$html_folders_sub_failed = '폴더를 구독할 수 없습니다!';
$html_folders_unsub_failed = '폴더에서 구독 취소할 수 없습니다!';
$html_folders_rename_failed = '폴더 이름을 바꿀 수 없습니다!';
$html_folders_updated = '폴더가 업데이트되었습니다';
$html_folder_subscribe = '구독할 곳';
$html_folder_rename = '이름 바꾸기';
$html_folder_create = '만드려는 새 폴더 이름';
$html_folder_remove = '구독 취소할 곳';
$html_folder_delete = '삭제';
$html_folder_to = '받는이';
$html_filter_remove = '삭제';
$html_filter_body = '메시지 내용';
$html_filter_subject = '메시지 제목';
$html_filter_to = '받는이 필드';
$html_filter_cc = '참조 필드';
$html_filter_from = '보낸이 필드';
$html_filter_change_tip = '필터를 바꾸려면 간단히 그것을 덮어쓰세요.';
$html_reapply_filters = '모든 필터를 다시 적용';
$html_filter_contains = '포함';
$html_filter_name = '필터 이름';
$html_filter_action = '필터 동작';
$html_filter_moveto = '이동할 곳';
$html_select_one = '--하나 선택--';
$html_and = '그리고';
$html_new_msg_in = '새 메시지가 있는 곳';
$html_or = '또는';
$html_move = '이동';
$html_copy = '복사';
$html_messages_to = '선택한 메시지를 보낼 곳';
$html_gotopage = '페이지로 가기';
$html_gotofolder = '폴더로 가기';
$html_other_folders = '폴더 목록';
$html_page = '페이지';
$html_of = '의';
$html_view_header = '헤더 보기';
$html_remove_header = '헤더 숨기기';
$html_inbox = '받은 편지함';
$html_new_msg = '쓰기';
$html_reply = '답장';
$html_reply_short = '답장:';
$html_reply_all = '모두 답장';
$html_forward = '전달';
$html_forward_short = '전달:';
$html_forward_info = '다음 메시지가 첨부 파일과 함께 받는이에게 보내질 것입니다.';
$html_delete = '삭제';
$html_new = '새로';
$html_mark = '삭제';
$html_att_label = '첨부:';
$html_atts_label = '첨부:';
$html_unknown = '[알 수 없음]';
$html_part_x = '부분 %s';
$html_attach = '첨부';
$html_attach_forget = '메시지를 보내기 전에 파일을 첨부해야 합니다!';
$html_attach_delete = '선택한 것을 제거';
$html_attach_none = '첨부할 파일을 선택해야 합니다!';
$html_sort_by = '정렬 기준';
$html_sort = '정렬';
$html_from = '보낸이';
$html_from_label = '보내는이:';
$html_subject = '제목';
$html_subject_label = '제목:';
$html_date = '날짜';
$html_date_label = '날짜:';
$html_sent_label = '보내기:';
$html_wrote = '쓰여짐';
$html_quote_wrote = '%1$s %2$s에, %3$s님이 씀:';
$html_size = '크기';
$html_totalsize = '전체 크기';
$html_kb = 'kB';
$html_mb = 'MB';
$html_gb = 'GB';
$html_bytes = '바이트';
$html_filename = '파일 이름';
$html_to = '받는이';
$html_to_label = '받는이:';
$html_cc = '참조';
$html_cc_label = '참조:';
$html_bcc_label = '숨은 참조:';
$html_nosubject = '제목 없음';
$html_send = '보내기';
$html_cancel = '취소';
$html_no_mail = '메시지가 없습니다.';
$html_logout = '로그아웃';
$html_msg = '메시지';
$html_msgs = '메시지';
$html_configuration = '이 서버는 제대로 설정되어 있지 않습니다!';
$html_priority = '우선 순위';
$html_priority_label = '우선 순위:';
$html_lowest = '가장 낮음';
$html_low = '낮음';
$html_normal = '보통';
$html_high = '높음';
$html_highest = '가장 높음';
$html_flagged = '플래그됨';
$html_spam = '스팸';
$html_spam_warning = '이 메시지는 스팸으로 분류되었습니다.';
$html_receipt = '답신 요청하기';
$html_select = '선택';
$html_select_all = '선택 반전';
$html_select_contacts = '연락처 선택';
$html_loading_image = '그림을 불러오는 중';
$html_send_confirmed = '이메일의 배달이 받아졌습니다';
$html_no_sendaction = '지정한 작업이 없습니다. 자바스크립트를 활성화하세요.';
$html_error_occurred = '오류가 발생했습니다';
$html_prefs_file_error = '쓰기를 위한 환경 설정을 열 수 없습니다.';
$html_wrap = '보내는 메시지를 랩하는 글자 수:';
$html_wrap_none = '랩하지 않음';
$html_usenet_separator = '서명하기 전에 유즈넷 구분자 ("-- \n")';
$html_mark_as = '다음으로 표시';
$html_read = '읽음';
$html_unread = '읽지 않음';
$html_encoding_label = '문자 인코딩:';
$html_add = '추가';
$html_contacts = '%1$s 연락처';
$html_modify = '수정';
$html_back = '뒤로';
$html_contact_add = '새 연락처 추가';
$html_contact_mod = '연락처 수정';
$html_contact_first = '이름';
$html_contact_last = '성';
$html_contact_nick = '별명';
$html_contact_mail = '이메일';
$html_contact_list = '%1$s의 연락처 목록';
$html_contact_del = '연락처 목록에서';
$html_contact_count = '연락처 %1$d개';
$html_contact_err1 = '최대 받는이의 수는 "%1$d"입니다';
$html_contact_err2 = '새 연락처를 추가할 수 없습니다';
$html_contact_err3 = '연락처 목록에 접근할 권한이 없습니다.';
$html_contact_none = '연락처를 찾을 수 없습니다.';
$html_contact_ruler_top = '위로';
$html_contact_listcheck_title = '이메일을 목록에 추가하려면 선택하세요.';
$html_contact_list_add = '목록에 추가';
$html_contact_listname = '목록 이름';
$html_contact_listonly = '목록만';
$html_contact_all = '모두 보기';
$html_contact_add_confirm = '이메일을 존재하는 목록에 추가하시겠습니까?';
$html_del_msg = '선택한 메시지를 삭제하겠습니까?';
$html_down_mail = '다운로드';
$original_msg = '-- 원래 메시지 --';
$to_empty = '\'받는이\' 필드는 비어 있으면 안 됩니다!';
$html_images_warning = '보안을 위해, 원격 사진을 보여주지 않습니다.';
$html_images_display = '사진 표시';
$html_smtp_error_no_conn = 'SMTP 연결을 열 수 없습니다';
$html_smtp_error_unexpected = '예상치 못한 SMTP 응답:';
$lang_could_not_connect = '서버에 연결할 수 없습니다';
$lang_invalid_msg_num = '잘못된 메시지 번호';
$html_file_upload_attack = '가능한 파일 올리기 공격';
$html_invalid_email_address = '잘못된 이메일 주소';
$html_invalid_msg_per_page = '잘못된 페이지마다 메시지 수';
$html_invalid_wrap_msg = '잘못된 편지 랩 폭';
$html_seperate_msg_win = '별도 창에서 메시지';
$html_err_file_contacts = '쓰기를 위한 연락처 파일을 열 수 없습니다.';
$html_session_file_error = '쓰기를 위한 세션 파일을 열 수 없습니다.';
$html_login_not_allowed = '이 로그인은 연결에 사용할 수 없습니다.';
$lang_err_send_delay = '두 이메일 사이를 기다려야 합니다 (%1$d초)';
$html_search = '검색';
$html_new_session = '다음 세션';
$html_fd_filename = '%1$s 다운로드';
$html_fd_mailcount = '폴더에 {{PLURAL:$1|0=이메일이 없습니다|1=이메일 %1$d개가 있습니다|이메일 %1$d개가 있습니다}}.';
$html_fd_mailskip = '다음 이메일은 php memory_limit을 초과할 것인, 우편함 파일의 부분이지 않을 것입니다:';
$html_fd_filesize = '크기 %1$d';
$html_fd_skipcount = '와 이메일 %1$d개';
$html_fd_largefolder = '다운로드 속도에 따라, 이 다운로드가 스크립트 시간 초과 때문에 실패할 수 있습니다.<br />완전히 다운로드되는지 확인하거나 php.ini에 max_execution_time을 더 높은 값으로 설정하시기 바랍니다.';
$reset_clicked = '정말로 이 양식을 지우겠습니까?';
$html_send_recover = '잃은 초안을 되살리려면 로그인하세요!';
$html_send_discard = '저장된 초안을 버리려면 여기를 클릭하세요.';
$html_collect_label = '이메일 주소 자동 수집:';
$html_collect_option0 = '안 함';
$html_collect_option1 = '나가는 이메일에서만';
$html_collect_option2 = '열어 본 이메일에서만';
$html_collect_option3 = '항상';
$html_version_message1 = '최신 버전을 사용 중입니다';
$html_version_message2 = '최신 버전을 찾을 수 없습니다';
$html_version_message3 = '새 버전을 사용할 수 있습니다';
$html_session_expired = '이 세션은 만료되었습니다';
$html_session_ip_changed = '클라이언트의 IP 변경으로 인해';
$html_session_expire_time = '이 세션은 다음 시간에 자동으로 만료됩니다:';
$html_inbox_changed = '받은 편지함의 내용이 변경되었습니다';
$html_inbox_show_alert = '받은 편지함의 이메일의 수가 변경되면 경고 상자를 표시합니다';
$lang_strong_encryption_required = '안전하지 않은 암호화는 허용되지 않습니다';
