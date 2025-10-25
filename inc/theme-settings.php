<?php
/**
 * Настройки темы для админки WordPress
 * Управление данными хедера и футера
 */

// Добавляем страницу настроек в админку
add_action('admin_menu', 'theme_settings_menu');

function theme_settings_menu() {
    add_theme_page(
        'Настройки темы',           // Заголовок страницы
        'Настройки темы',           // Название в меню
        'manage_options',           // Права доступа
        'theme-settings',           // Slug страницы
        'theme_settings_page'       // Функция отображения
    );
}

// Функция отображения страницы настроек
function theme_settings_page() {
    // Сохранение данных
    if (isset($_POST['submit'])) {
        // Проверяем nonce для безопасности
        if (!wp_verify_nonce($_POST['theme_settings_nonce'], 'theme_settings_action')) {
            die('Ошибка безопасности');
        }

        // Сохраняем настройки
        update_option('theme_logo', sanitize_text_field($_POST['theme_logo']));
        update_option('theme_company_name', sanitize_text_field($_POST['theme_company_name']));
        update_option('theme_company_subtitle', sanitize_text_field($_POST['theme_company_subtitle']));
        update_option('theme_phone', sanitize_text_field($_POST['theme_phone']));
        update_option('theme_phone_display', sanitize_text_field($_POST['theme_phone_display']));
        update_option('theme_address', sanitize_textarea_field($_POST['theme_address']));
        update_option('theme_working_hours', sanitize_text_field($_POST['theme_working_hours']));
        update_option('theme_working_hours_display', sanitize_text_field($_POST['theme_working_hours_display']));
        update_option('theme_copyright', sanitize_text_field($_POST['theme_copyright']));
        update_option('theme_copyright_year', sanitize_text_field($_POST['theme_copyright_year']));

        echo '<div class="notice notice-success"><p>Настройки сохранены!</p></div>';
    }

    // Получаем текущие значения
    $logo = get_option('theme_logo', '');
    $company_name = get_option('theme_company_name', 'FRIA');
    $company_subtitle = get_option('theme_company_subtitle', 'INDUSTRIE');
    $phone = get_option('theme_phone', '7(499)677-24-40');
    $phone_display = get_option('theme_phone_display', '7(499)677-24-40');
    $address = get_option('theme_address', 'Россия, 105037, г. Москва, ул. Измайловский проезд, дом 14, корпус 2');
    $working_hours = get_option('theme_working_hours', 'ПН-ПТ С 10.00 ДО 18.30');
    $working_hours_display = get_option('theme_working_hours_display', 'По будням: с 10:00 до 18:00');
    $copyright = get_option('theme_copyright', 'Friax');
    $copyright_year = get_option('theme_copyright_year', '2024');
    ?>

    <div class="wrap">
        <h1>Настройки темы</h1>

        <form method="post" action="">
            <?php wp_nonce_field('theme_settings_action', 'theme_settings_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row">Логотип</th>
                    <td>
                        <input type="text" name="theme_logo" value="<?php echo esc_attr($logo); ?>" class="regular-text" id="logo_input" />
                        <button type="button" class="button" id="upload_logo_button">Выбрать из медиабиблиотеки</button>
                        <input type="file" id="logo_file_input" accept="image/*" style="display: none;" />
                        <button type="button" class="button" id="upload_file_button">Загрузить файл</button>
                        <button type="button" class="button" id="clear_logo_button" style="margin-left: 10px; color: #d63638;">Очистить</button>
                        <p class="description">
                            <strong>Способы добавления логотипа:</strong><br>
                            1. Вставьте URL изображения в поле выше<br>
                            2. Нажмите "Выбрать из медиабиблиотеки" (если доступно)<br>
                            3. Нажмите "Загрузить файл" для прямой загрузки<br>
                            4. Или введите ID изображения из медиабиблиотеки
                        </p>
                        <?php if (!empty($logo)): ?>
                            <div style="margin-top: 10px;">
                                <img src="<?php echo is_numeric($logo) ? wp_get_attachment_image_url($logo, 'medium') : esc_url($logo); ?>" style="max-width: 200px; max-height: 100px;" alt="Текущий логотип" />
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Название компании</th>
                    <td>
                        <input type="text" name="theme_company_name" value="<?php echo esc_attr($company_name); ?>" class="regular-text" />
                        <p class="description">Основное название компании (например: FRIA)</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Подзаголовок компании</th>
                    <td>
                        <input type="text" name="theme_company_subtitle" value="<?php echo esc_attr($company_subtitle); ?>" class="regular-text" />
                        <p class="description">Подзаголовок под логотипом (например: INDUSTRIE, HOLDING)</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Телефон</th>
                    <td>
                        <input type="text" name="theme_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" />
                        <p class="description">Телефон для ссылок (например: 7(499)677-24-40)</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Отображение телефона</th>
                    <td>
                        <input type="text" name="theme_phone_display" value="<?php echo esc_attr($phone_display); ?>" class="regular-text" />
                        <p class="description">Как отображается телефон на сайте</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Адрес</th>
                    <td>
                        <textarea name="theme_address" rows="3" cols="50" class="large-text"><?php echo esc_textarea($address); ?></textarea>
                        <p class="description">Полный адрес компании</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Часы работы (кратко)</th>
                    <td>
                        <input type="text" name="theme_working_hours" value="<?php echo esc_attr($working_hours); ?>" class="regular-text" />
                        <p class="description">Краткий формат (например: ПН-ПТ С 10.00 ДО 18.30)</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Часы работы (подробно)</th>
                    <td>
                        <input type="text" name="theme_working_hours_display" value="<?php echo esc_attr($working_hours_display); ?>" class="regular-text" />
                        <p class="description">Подробный формат (например: По будням: с 10:00 до 18:00)</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Копирайт</th>
                    <td>
                        <input type="text" name="theme_copyright" value="<?php echo esc_attr($copyright); ?>" class="regular-text" />
                        <p class="description">Название компании для копирайта</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Год копирайта</th>
                    <td>
                        <input type="text" name="theme_copyright_year" value="<?php echo esc_attr($copyright_year); ?>" class="regular-text" />
                        <p class="description">Год для копирайта</p>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>

    <script>
        jQuery(document).ready(function($) {
            // Медиабиблиотека WordPress
            $('#upload_logo_button').click(function(e) {
                e.preventDefault();

                if (typeof wp !== 'undefined' && wp.media) {
                    var custom_uploader = wp.media({
                        title: 'Выберите логотип',
                        button: {
                            text: 'Использовать это изображение'
                        },
                        multiple: false
                    });

                    custom_uploader.on('select', function() {
                        var attachment = custom_uploader.state().get('selection').first().toJSON();
                        $('#logo_input').val(attachment.id);
                    });

                    custom_uploader.open();
                } else {
                    alert('Медиабиблиотека WordPress недоступна. Используйте загрузку файла.');
                }
            });

            // Обычная загрузка файла
            $('#upload_file_button').click(function(e) {
                e.preventDefault();
                $('#logo_file_input').click();
            });

            $('#logo_file_input').change(function() {
                var file = this.files[0];
                if (file) {
                    var formData = new FormData();
                    formData.append('file', file);
                    formData.append('action', 'upload_logo_file');
                    formData.append('nonce', '<?php echo wp_create_nonce('upload_logo_nonce'); ?>');

                    $.ajax({
                        url: theme_settings_ajax.ajaxurl,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                $('#logo_input').val(response.data.url);
                                alert('Файл успешно загружен!');
                            } else {
                                alert('Ошибка загрузки: ' + response.data);
                            }
                        },
                        error: function() {
                            alert('Ошибка загрузки файла');
                        }
                    });
                }
            });

            // Очистка логотипа
            $('#clear_logo_button').click(function(e) {
                e.preventDefault();
                if (confirm('Вы уверены, что хотите очистить логотип?')) {
                    $('#logo_input').val('');
                }
            });
        });
    </script>
    <?php
}

// Функции для получения настроек в шаблонах
function get_theme_logo() {
    $logo_id = get_option('theme_logo', '');
    if (is_numeric($logo_id)) {
        return wp_get_attachment_image($logo_id, 'full');
    } elseif (!empty($logo_id)) {
        return '<img src="' . esc_url($logo_id) . '" alt="Logo">';
    }
    return '';
}

function get_theme_company_name() {
    return get_option('theme_company_name', 'FRIA');
}

function get_theme_company_subtitle() {
    return get_option('theme_company_subtitle', 'INDUSTRIE');
}

function get_theme_phone() {
    return get_option('theme_phone', '7(499)677-24-40');
}

function get_theme_phone_display() {
    return get_option('theme_phone_display', '7(499)677-24-40');
}

function get_theme_address() {
    return get_option('theme_address', 'Россия, 105037, г. Москва, ул. Измайловский проезд, дом 14, корпус 2');
}

function get_theme_working_hours() {
    return get_option('theme_working_hours', 'ПН-ПТ С 10.00 ДО 18.30');
}

function get_theme_working_hours_display() {
    return get_option('theme_working_hours_display', 'По будням: с 10:00 до 18:00');
}

function get_theme_copyright() {
    return get_option('theme_copyright', 'Friax');
}

function get_theme_copyright_year() {
    return get_option('theme_copyright_year', '2024');
}

// AJAX обработчик для загрузки файлов
add_action('wp_ajax_upload_logo_file', 'handle_logo_upload');

function handle_logo_upload() {
    // Проверяем nonce
    if (!wp_verify_nonce($_POST['nonce'], 'upload_logo_nonce')) {
        wp_die('Ошибка безопасности');
    }

    // Проверяем права пользователя
    if (!current_user_can('manage_options')) {
        wp_die('Недостаточно прав');
    }

    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    $uploadedfile = $_FILES['file'];
    $upload_overrides = array('test_form' => false);

    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

    if ($movefile && !isset($movefile['error'])) {
        wp_send_json_success(array('url' => $movefile['url']));
    } else {
        wp_send_json_error($movefile['error']);
    }
}

// Добавляем стили и скрипты для админки
add_action('admin_enqueue_scripts', 'theme_settings_scripts');

function theme_settings_scripts($hook) {
    if ($hook != 'appearance_page_theme-settings') {
        return;
    }

    // Подключаем медиабиблиотеку WordPress
    wp_enqueue_media();

    // Добавляем ajaxurl для JavaScript
    wp_localize_script('jquery', 'theme_settings_ajax', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));

    // Добавляем стили
    ?>
    <style>
        .form-table th {
            width: 200px;
        }
        .form-table td {
            padding: 15px 10px;
        }
        .description {
            font-style: italic;
            color: #666;
        }
        #upload_logo_button {
            margin-left: 10px;
        }
    </style>
    <?php
}
