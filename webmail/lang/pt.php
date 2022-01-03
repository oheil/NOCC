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
 * @version    SVN: $Id: pt.php 2937 2021-03-12 06:05:32Z translatewiki $
 */
/** Portuguese (português)
 * 
 * See the qqq 'language' for message documentation incl. usage of parameters
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Athena in Wonderland
 * @author Crazymadlover
 * @author Hamilton Abreu
 * @author JS <jorge.silva@ciberlink.pt>
 * @author Luckas
 * @author Luckas Blade
 * @author Malafaya
 * @author Mansil
 * @author Mansil alfalb
 * @author MokaAkashiyaPT
 * @author Ntunzine
 * @author Paulo Matos <paulo.matos@fct.unl.pt>
 * @author Ti4goc
 * @author Vitorvicentevalente
 * @author Waldir
 * @author Waldyrious
 * @author sena <sena@smux.net>
 */

$lang_locale = 'pt_PT.UTF-8';
$default_date_format = '%d/%m/%Y';
$no_locale_date_format = '%d/%m/%Y';
$default_time_format = '%I:%M %p';
$err_user_empty = 'O campo de autenticação está em branco';
$err_passwd_empty = 'O campo da palavra-passe está em branco';
$alt_delete = 'Apagar as mensagens selecionadas';
$alt_delete_one = 'Apagar a mensagem';
$alt_new_msg = 'Mensagens novas';
$alt_reply = 'Responder ao autor';
$alt_reply_all = 'Responder a todas';
$alt_forward = 'Reencaminhar';
$alt_next = 'Seguinte';
$alt_prev = 'Anterior';
$title_next_page = 'Página seguinte';
$title_prev_page = 'Página anterior';
$title_next_msg = 'Próxima mensagem';
$title_prev_msg = 'Mensagem anterior';
$html_theme_label = 'Tema:';
$html_welcome = 'Bem-vindo ao %1$s';
$html_login = 'Iniciar sessão';
$html_user_label = 'Utilizador:';
$html_passwd_label = 'Palavra-passe:';
$html_submit = 'Submeter';
$html_help = 'Ajuda';
$html_server_label = 'Servidor:';
$html_wrong = 'O utilizador ou a palavra-passe estão incorretos';
$html_retry = 'Repetir';
$html_remember = 'Guardar dados';
$html_lang_label = 'Idioma:';
$html_msgperpage_label = 'Mensagens por página:';
$html_preferences = 'Preferências';
$html_full_name_label = 'Nome completo:';
$html_email_address_label = 'Endereço de \'\'e-mail\'\':';
$html_bccself = 'Enviar cópia oculta para si mesmo';
$html_hide_addresses = 'Ocultar endereços';
$html_outlook_quoting = 'Citação ao estilo do Outlook';
$html_reply_to = 'Responder a';
$html_reply_to_label = 'Responder a:';
$html_use_signature = 'Utilizar assinatura';
$html_signature = 'Assinatura';
$html_signature_label = 'Assinatura:';
$html_reply_leadin_label = 'Início da resposta:';
$html_prefs_updated = 'Preferências atualizadas';
$html_manage_folders_link = 'Gerir Pastas IMAP';
$html_manage_filters_link = 'Gerir Filtros de Correio Eletrónico';
$html_use_graphical_smilies = 'Utilizar sorrisos gráficos';
$html_sent_folder_label = 'Copiar as mensagens enviadas para uma pasta específica:';
$html_trash_folder_label = 'Mover as mensagens apagadas para uma pasta específica:';
$html_inbox_folder_label = 'Ligar a entrada do menu da caixa de entrada à pasta:';
$html_colored_quotes = 'Citações coloridas';
$html_display_struct = 'Exibir texto estruturado';
$html_send_html_mail = 'Enviar mensagem no formato HTML';
$html_folders = 'Pastas';
$html_folders_create_failed = 'Não foi possível criar a pasta!';
$html_folders_sub_failed = 'Não foi possível subscrever a pasta!';
$html_folders_unsub_failed = 'Não foi possível cancelar a subscrição da pasta!';
$html_folders_rename_failed = 'Não foi possível renomear a pasta!';
$html_folders_updated = 'Pastas atualizadas';
$html_folder_subscribe = 'Subscrever';
$html_folder_rename = 'Renomear';
$html_folder_create = 'Criar pasta com o nome';
$html_folder_remove = 'Cancelar subscrição de';
$html_folder_delete = 'Apagar';
$html_folder_to = 'para';
$html_filter_remove = 'Apagar';
$html_filter_body = 'Corpo da mensagem';
$html_filter_subject = 'Assunto da Mensagem';
$html_filter_to = 'Campo \'Para\'';
$html_filter_cc = 'Campo \'Cc\'';
$html_filter_from = 'Campo \'De\'';
$html_filter_change_tip = 'Para alterar um filtro, basta substituí-lo.';
$html_reapply_filters = 'Reaplicar todos os filtros';
$html_filter_contains = 'contém';
$html_filter_name = 'Nome do Filtro';
$html_filter_action = 'Ação do Filtro';
$html_filter_moveto = 'Mover para';
$html_select_one = '--Selecionar--';
$html_and = 'E';
$html_new_msg_in = 'Novas mensagens em';
$html_or = 'ou';
$html_move = 'Mover';
$html_copy = 'Copiar';
$html_messages_to = 'mensagens selecionadas para';
$html_gotopage = 'Ir para a Página';
$html_gotofolder = 'Ir para a Pasta';
$html_other_folders = 'Lista de Pastas';
$html_page = 'Página';
$html_of = 'de';
$html_view_header = 'Ver cabeçalho';
$html_remove_header = 'Ocultar cabeçalho';
$html_inbox = 'Caixa de entrada';
$html_new_msg = 'Escrever';
$html_reply = 'Responder';
$html_reply_short = 'Resp.:';
$html_reply_all = 'Responder a todas';
$html_forward = 'Reencaminhar';
$html_forward_short = 'Reenc.:';
$html_forward_info = 'A mensagem reencaminhada será enviada como um anexo a esta.';
$html_delete = 'Apagar';
$html_new = 'Nova';
$html_mark = 'Apagar';
$html_att_label = 'Anexo:';
$html_atts_label = 'Anexos:';
$html_unknown = '[desconhecido]';
$html_part_x = 'Parte %s';
$html_attach = 'Anexar';
$html_attach_forget = 'Deve anexar o seu ficheiro antes de enviar a sua mensagem!';
$html_attach_delete = 'Remover Selecionadas';
$html_attach_none = 'Deve selecionar um ficheiro para anexar!';
$html_sort_by = 'Ordenar por';
$html_sort = 'Ordenar';
$html_from = 'De';
$html_from_label = 'De:';
$html_subject = 'Assunto';
$html_subject_label = 'Assunto:';
$html_date = 'Data';
$html_date_label = 'Data:';
$html_sent_label = 'Enviada:';
$html_wrote = 'escreveu';
$html_quote_wrote = 'Em %1$s %2$s, %3$s escreveu:';
$html_size = 'Tamanho';
$html_totalsize = 'Tamanho Total';
$html_kb = 'kB';
$html_mb = 'MB';
$html_gb = 'GB';
$html_bytes = 'bytes';
$html_filename = 'Nome do ficheiro';
$html_to = 'Para';
$html_to_label = 'Para:';
$html_cc = 'Cc';
$html_cc_label = 'Cc:';
$html_bcc_label = 'Bcc:';
$html_nosubject = 'Sem assunto';
$html_send = 'Enviar';
$html_cancel = 'Cancelar';
$html_no_mail = 'Sem mensagens.';
$html_logout = 'Sair';
$html_msg = 'Mensagem';
$html_msgs = 'Mensagens';
$html_configuration = 'Este servidor não está configurado corretamente!';
$html_priority = 'Prioridade';
$html_priority_label = 'Prioridade:';
$html_lowest = 'Mais baixa';
$html_low = 'Baixa';
$html_normal = 'Normal';
$html_high = 'Alta';
$html_highest = 'Mais alta';
$html_flagged = 'Marcada';
$html_spam = 'Spam';
$html_spam_warning = 'Esta mensagem foi classificada como spam.';
$html_receipt = 'Pedir aviso de receção';
$html_select = 'Selecionar';
$html_select_all = 'Inverter Seleção';
$html_select_contacts = 'Selecionar contactos';
$html_loading_image = 'A carregar a imagem';
$html_send_confirmed = 'A mensagem foi aceite para envio';
$html_no_sendaction = 'Nenhuma ação indicada. Tente ativar o JavaScript.';
$html_error_occurred = 'Ocorreu um erro';
$html_prefs_file_error = 'Não é possível abrir o ficheiro das preferências para gravação.';
$html_wrap = 'Número de carateres para forçar nova linha nas mensagens a enviar:';
$html_wrap_none = 'Não forçar nova linha';
$html_usenet_separator = 'Separador Usenet ("-- \n") antes da assinatura';
$html_mark_as = 'Marcar como';
$html_read = 'lida';
$html_unread = 'não lida';
$html_encoding_label = 'Codificação de carateres:';
$html_add = 'Adicionar';
$html_contacts = '%1$s Contactos';
$html_modify = 'Modificar';
$html_back = 'Recuar';
$html_contact_add = 'Adicionar novo contacto';
$html_contact_mod = 'Modificar um contacto';
$html_contact_first = 'Nome';
$html_contact_last = 'Apelido';
$html_contact_nick = 'Apelido';
$html_contact_mail = 'Correio eletrónico';
$html_contact_list = 'Lista de contactos de %1$s';
$html_contact_del = 'da lista de contactos';
$html_contact_count = '%1$d Contactos';
$html_contact_err1 = 'O número máximo de contactos é "%1$d"';
$html_contact_err2 = 'Não pode adicionar um novo contacto';
$html_contact_err3 = 'Não tem permissão de acesso à lista de contactos';
$html_contact_none = 'Não foram encontrados contactos.';
$html_contact_ruler_top = 'Topo';
$html_contact_listcheck_title = 'Marque para adicionar mensagens à lista.';
$html_contact_list_add = 'Adicionar à lista';
$html_contact_listname = 'Nome da lista';
$html_contact_listonly = 'Apenas listas';
$html_contact_all = 'Mostrar tudo';
$html_contact_add_confirm = 'Anexar mensagens à lista existente?';
$html_del_msg = 'Apagar as mensagens selecionadas?';
$html_down_mail = 'Transferir';
$original_msg = '-- Mensagem Original --';
$to_empty = 'O campo \'Para\' não deve estar em branco!';
$html_images_warning = 'Por razões de segurança, as imagens remotas não são exibidas.';
$html_images_display = 'Exibir imagens';
$html_smtp_error_no_conn = 'Não foi possível estabelecer a ligação ao servidor de SMTP';
$html_smtp_error_unexpected = 'Resposta inesperada do servidor de SMTP:';
$lang_could_not_connect = 'Não foi possível estabelecer a ligação ao servidor';
$lang_invalid_msg_num = 'Número de mensagem inválido';
$html_file_upload_attack = 'Possível ataque de \'envio\' de ficheiros';
$html_invalid_email_address = 'Endereço de correio eletrónico inválido';
$html_invalid_msg_per_page = 'Número de mensagens por página inválido';
$html_invalid_wrap_msg = 'Largura para forçar nova linha inválida';
$html_seperate_msg_win = 'Mensagens em janela separada';
$html_err_file_contacts = 'Não é possível abrir o ficheiro de contactos para gravação.';
$html_session_file_error = 'Não é possível abrir o ficheiro da sessão para gravação.';
$html_login_not_allowed = 'Este utilizador não é permitido para a ligação.';
$lang_err_send_delay = 'Deve aguardar entre duas mensagens (%1$d segundos)';
$html_search = 'Pesquisar';
$html_new_session = 'Próxima sessão';
$html_fd_filename = 'Transferir %1$s';
$html_fd_mailcount = '{{PLURAL:$1|0=Não há mensagens|1=Há %1$d mensagem|Há %1$d mensagens}} na pasta.';
$html_fd_mailskip = 'As seguintes mensagens não farão parte do ficheiro mbox, porque excedem o valor «memory_limit» de PHP:';
$html_fd_filesize = 'tamanho %1$d';
$html_fd_skipcount = 'com %1$d mensagens';
$html_fd_largefolder = 'Dependendo da sua velocidade de carregamento, pode ocorrer um erro nesta transferência, devido ao tempo de espera do ficheiro de comandos.<br />Por favor, veja a sua transferência por completa ou defina o max_execution_time a um valor mais alto em php.ini.';
$reset_clicked = 'Deseja limpar este formulário?';
$html_send_recover = 'Inicie a sessão para recuperar o rascunho perdido!';
$html_send_discard = 'Clique aqui para ignorar o rascunho guardado.';
$html_collect_label = 'Recolher automaticamente os endereços de correio eletrónico:';
$html_collect_option0 = 'Nunca';
$html_collect_option1 = 'Somente correios eletrónicos enviados';
$html_collect_option2 = 'Somente correios eletrónicos abertos';
$html_collect_option3 = 'Sempre';
$html_version_message1 = 'Estamos a usar a versão mais recente';
$html_version_message2 = 'Não é possível obter a versão mais recente';
$html_version_message3 = 'Está disponível uma nova versão';
$html_session_expired = 'Esta sessão expirou';
$html_session_ip_changed = 'porque o IP do cliente foi alterado';
$html_session_expire_time = 'Esta sessão expira automaticamente às';
$html_inbox_changed = 'O conteúdo da sua caixa de entrada foi alterado';
$html_inbox_show_alert = 'Mostrar um alerta quando houver alterações no número de correios eletrónicos da caixa de entrada';
$lang_horde_require_failed = 'Classe cliente pmap de \'\'horde\'\' não encontrada';
$lang_strong_encryption_required = 'Não é permitida encriptação insegura';
