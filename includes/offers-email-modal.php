<?php 

function hr_offers_admin_footer() {
    ?>
    <div id="hr-email-modal" class="hr-email-modal">
        <div class="hr-email-modal-content">
            <span class="hr-email-modal-close">&times;</span>
            <h2>Send Email</h2>
            <form id="hr-email-modal-form">
                <input type="hidden" name="action" value="send_hr_offer_email">
                <input type="hidden" name="post_id">
                <div class="hr-email-modal__row">
                    <label class="hr-email-modal__label" for="candidate_email">Candidate Email:</label>
                    <input class="hr-email-modal__input" type="email" name="candidate_email" required>
                </div>
                <div class="hr-email-modal__row">
                    <label class="hr-email-modal__label" for="sender_email">Sender Email:</label>
                    <select class="hr-email-modal__input" name="sender_email" required>
                        <option value=""></option>
                        <option value="ksu@procoders.tech">Kseniya Pavichenko</option>
                        <option value="vic@procoders.tech">Victoria Zakordonets</option>
                        <option value="gogenkoli@procoders.tech">Liliya Gogenko</option>
                        <option value="belinat@procoders.tech">Belina Torba</option>
                        <option value="ok@procoders.tech">Oleg Kopachovets</option>
                    </select>
                </div>
                <div class="hr-email-modal__row">
                    <label class="hr-email-modal__label" for="subject">Subject:</label>
                    <input class="hr-email-modal__input" type="text" name="subject" value="Job Offer from ProCoders" required>
                </div>
                <div class="hr-email-modal__row">
                    <label class="hr-email-modal__label" for="message">Message Body:</label>
                    <textarea rows="12" class="hr-email-modal__input" name="message" required>I’m super excited to share an opportunity with you to join our team at ProCoders! 🚀✨ We’re all about collaboration and growth here, and I truly believe that with your help, we can achieve amazing things together.

Attached, you’ll find the job offer 📄 with all the details about our potential collaboration. If you have any questions or just want to chat 💬 about anything in the offer, don’t hesitate to reach out. I’m always here and happy to discuss it anytime.

Looking forward to hearing your thoughts! 😊
</textarea>
                </div>
                <button type="submit" class="button button-primary">Send</button>
            </form>
        </div>
    </div>
    <?php
}

add_action('admin_footer', 'hr_offers_admin_footer');


function hr_offers_send_email() {
    if (isset($_POST['post_id'], $_POST['candidate_email'], $_POST['sender_email'], $_POST['subject'], $_POST['message'])) {
        $post_id = intval($_POST['post_id']);
        $candidate_email = sanitize_email($_POST['candidate_email']);
        $sender_email = sanitize_email($_POST['sender_email']);
        $subject = sanitize_text_field($_POST['subject']);
        $message_wellcome = '<p>Hi <strong>' . get_field('field_hr_name', $post_id) . '</strong>,</p>';
        // $message_text = sanitize_text_field($_POST['message']);
        // $message_text = wp_kses_post($_POST['message']);
        $message_text = wpautop($_POST['message']);

        $email_template_start = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN"><html><head><meta name="viewport" content="width=device-width,initialscale=1"><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="color-scheme" content="light dark"><meta name="supported-color-schemes" content="light dark"><title>frontEgg</title></head><body class="bg" marginheight="0" bgcolor="#f8f9fe" marginwidth="0" leftmargin="0" topmargin="0" style="min-width:700px"><table class="bg" width="100%" bgcolor="#f8f9fe" class="body" cellspacing="0" cellpadding="0" border="0" style="color:#7c7474;background-color:#f8f9fe;font:14px/20px Arial,Helvetica,sans-serif;background-image:url(' . plugin_dir_url(dirname(__FILE__)) . 'assets/images/mail-bg.png);background-repeat:no-repeat;background-size:cover;background-position:50% 0;font:14px/20px Arial,Helvetica,sans-serif"><tr><!-- // Style section --><td height="1" style="padding:0;line-height:0"><style type="text/css">@import url(https://fonts.googleapis.com/css2?family=Manrope:wght@500;700;800&display=swap);:root{color-scheme:light dark;supported-color-schemes:light dark}html{-webkit-text-size-adjust:none;-ms-text-size-adjust:none;-webkit-text-resize:100%;text-resize:100%}*{outline:0}.btn{font-family:Manrope,Arial,Helvetica,sans-serif;font-weight:700}.main-title{font-family:Manrope,Arial,Helvetica,sans-serif;font-weight:800}.text{font-family:Manrope,Arial,Helvetica,sans-serif;font-weight:400}@media (prefers-color-scheme:dark){.btn{color:#fff!important}.title{color:#fff!important}.bg{background-color:#f8f9fe!important}.mainbg{background:#fff!important}.main-title{color:#2f334b!important}.text{color:#2f334b!important}.link{color:#204ff3!important}}</style><!-- // END Style section --></td></tr><tr><td align="center" style="padding:0;line-height:0;font-size:0"><table class="body" cellspacing="0" cellpadding="0" border="0" style="max-width:610px;min-width:610px"><tr><td colspan="3" height="45" style="padding:0"></td></tr><!-- HEADER --><tr><td width="15" valign="top" style="padding:0"></td><td style="padding:0"><table width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:580px"><tr><td height="25" style="padding:0"></td></tr><tr><td style="padding:0"><table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center" width="238" valign="middle" style="padding:0"><a href="https://procoders.tech/" target="_blank"><img style="max-width:478px;height:auto" border="0" src="' . plugin_dir_url(dirname(__FILE__)) . 'assets/images/mail-logo.png" width="auto" height="auto" alt=""></a></td></tr></table></td></tr><tr><td height="50" style="padding:0"></td></tr></table></td><td width="15" valign="top" style="padding:0"></td></tr><!-- END HEADER --><tr><td width="15" valign="top" style="padding:0"></td><td style="padding:0"><table class="mainbg" bgcolor="#ffffff" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:580px;background-color:#fff;box-shadow:0 0 15px 10px rgba(47,51,75,.1);border-radius:10px"><tr><td valign="top" style="padding:0"><table class="mainbg" bgcolor="#ffffff" class="body" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:580px;border-radius:10px"><tr><td height="23" style="padding:0"></td></tr><!-- MAIN IMAGE --><tr><td style="padding:0"><table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center" width="100%" valign="middle" style="padding:0"><img style="max-width:100%;height:auto" border="0" src="' . plugin_dir_url(dirname(__FILE__)) . 'assets/images/mail-image.png" width="auto" height="auto" alt=""></td></tr></table></td></tr><tr><td align="center" style="padding:0;line-height:0;font-size:0"><table width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:580px"><tr><td height="17" style="padding:0"></td></tr></table></td></tr><!-- END MAIN IMAGE --><tr><td align="center" style="padding:0;line-height:0;font-size:0"><table width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:600px"><tr><!-- CONTENT --><td width="25" style="padding:0"></td><td style="padding:0"><table cellspacing="0" cellpadding="0" border="0"><tr><td height="38" style="padding:0"></td></tr><tr><td valign="top" style="padding:0;line-height:40px"><font class="main-title" color="#2F334B" face="\'SF Pro Text\', -apple-system, BlinkMacSystemFont, \'Segoe UI\', Helvetica, Arial, sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\'" size="3" style="font-size:14px;line-height:1.4;letter-spacing:0;font-weight:400">';
        $email_template_end = '</font></td></tr><tr><td height="5" style="padding:0"></td></tr><!-- END CONTENT --><!-- SIGN --><tr><td valign="top" style="padding:0;line-height:22px"><font class="text" color="#4D5062" face="Arial, Helvetica, sans-serif" size="3" style="font-size:14px;line-height:22px;letter-spacing:0">Best regards,<br><b>The Procoders Team</b></font></td></tr><!-- END SIGN --></table></td><td width="20" style="padding:0"></td></tr></table></td></tr><tr><td height="27" style="padding:0"></td></tr></table></td></tr></table></td><td width="15" valign="top" style="padding:0"></td></tr><tr><td colspan="3" height="30" style="padding:0"></td></tr></table></td></tr></table></body></html>';

        $message = $email_template_start . $message_wellcome . $message_text . $email_template_end;

        $upload_dir = wp_upload_dir();
        $attachment = $upload_dir['basedir'] . '/offers/' . $post_id . '/offer.pdf';

        if (!file_exists($attachment)) {
            wp_send_json_error('Attachment not found.');
            return;
        }

        $headers = array('From: ' . $sender_email, 'Content-Type: text/html; charset=UTF-8');
        $attachments = array($attachment);
        
        if (wp_mail($candidate_email, $subject, $message, $headers, $attachments)) {
            wp_send_json_success('Email sent successfully.');
        } else {
            wp_send_json_error('Failed to send email.');
        }
    } else {
        wp_send_json_error('Invalid data.');
    }
}

add_action('wp_ajax_send_hr_offer_email', 'hr_offers_send_email');



