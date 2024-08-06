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
                <div>
                    <label for="candidate_email">Candidate Email:</label>
                    <input type="email" name="candidate_email" required>
                </div>
                <div>
                    <label for="sender_email">Sender Email:</label>
                    <select name="sender_email">
                        <option value="noreply@procoders.tech">noreply@procoders.tech</option>
                        <option value="info@procoders.tech">info@procoders.tech</option>
                    </select>
                </div>
                <div>
                    <label for="subject">Subject:</label>
                    <input type="text" name="subject" value="Job Offer">
                </div>
                <div>
                    <label for="message">Message Body:</label>
                    <textarea name="message">Hello [name],</textarea>
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



