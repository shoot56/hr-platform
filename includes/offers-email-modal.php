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
                    <textarea rows="12" class="hr-email-modal__input" name="message" required>Hi [name],
We're thrilled to offer you the opportunity to join our team and embark on an exciting journey through the world of technology. At ProCoders, we believe in the power of collaboration and growth, and with your help, we're ready to reach new heights!
Attached, you'll find our job offer, which contains all the details about our future collaboration. If you have any questions, feel free to reach out — we're always here and happy to discuss them.</textarea>
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
        $message = wp_kses_post(str_replace('[name]', get_field('field_hr_name', $post_id), $_POST['message']));

        $upload_dir = wp_upload_dir();
        $attachment = $upload_dir['basedir'] . '/offers/' . $post_id . '/offer.pdf';

        if (!file_exists($attachment)) {
            wp_send_json_error('Attachment not found.');
            return;
        }

        $headers = array('From: ' . $sender_email);
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



