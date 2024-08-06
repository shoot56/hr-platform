jQuery(document).ready(function($) {
    // Открыть модальное окно при клике на кнопку Send email
    $(document).on('click', '.send-email-button', function(e) {
        e.preventDefault();

        // Получаем ID поста
        var post_id = $(this).data('id');
        $('#hr-email-modal input[name="post_id"]').val(post_id);

        // Открываем модальное окно
        $('#hr-email-modal').show();
    });

    // Закрыть модальное окно
    $(document).on('click', '.hr-email-modal-close, #hr-email-modal', function(e) {
        if ($(e.target).is('#hr-email-modal, .hr-email-modal-close')) {
            $('#hr-email-modal').hide();
        }
    });

    // Остановить закрытие модального окна при клике внутри формы
    $(document).on('click', '#hr-email-modal .hr-email-modal-content', function(e) {
        e.stopPropagation();
    });

    // AJAX запрос для отправки email
    $('#hr-email-modal-form').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: formData,
            success: function(response) {
                alert('Email sent successfully!');
                $('#hr-email-modal').hide();
            },
            error: function(response) {
                alert('Error sending email.');
            }
        });
    });
});
