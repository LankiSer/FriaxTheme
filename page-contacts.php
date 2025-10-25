<?php
/**
 * Template Name: Контакты
 * 
 * Шаблон страницы контактов с информацией из настроек темы
 */

get_header();

$phone = get_theme_phone();
$phone_display = get_theme_phone_display();
$address = get_theme_address();
$working_hours = get_theme_working_hours();
$working_hours_display = get_theme_working_hours_display();
?>

<div class="contacts-page">
    <div class="container">
        <div class="contacts-header">
            <h1 class="contacts-title">КОНТАКТЫ</h1>
            <div class="contacts-description">
                Получить консультацию по стоимости и возможностям приобретения климатического оборудования компании FRIAX:
            </div>
        </div>
        
        <div class="contacts-info">
            <div class="contact-item">
                <div class="contact-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 16.92V19.92C22.0011 20.1985 21.9441 20.4742 21.8325 20.7293C21.7209 20.9844 21.5573 21.2136 21.3521 21.4019C21.1468 21.5901 20.9046 21.7335 20.6407 21.8227C20.3769 21.9119 20.0974 21.9451 19.82 21.92C16.7428 21.5856 13.787 20.5341 11.19 18.85C8.77382 17.3147 6.72533 15.2662 5.18999 12.85C3.49997 10.2412 2.44824 7.27099 2.11999 4.18C2.095 3.90347 2.12787 3.62476 2.21649 3.36162C2.30512 3.09849 2.44756 2.85669 2.63476 2.65162C2.82196 2.44655 3.0498 2.28271 3.30379 2.17052C3.55777 2.05833 3.83233 2.00026 4.10999 2H7.10999C7.59531 1.99522 8.06679 2.16708 8.43376 2.48353C8.80073 2.79999 9.03997 3.23945 9.10999 3.72C9.23662 4.68007 9.47144 5.62273 9.80999 6.53C9.94454 6.88792 9.97366 7.27691 9.89391 7.65088C9.81415 8.02485 9.62886 8.36811 9.35999 8.64L8.08999 9.91C9.51355 12.4135 11.5865 14.4864 14.09 15.91L15.36 14.64C15.6319 14.3711 15.9751 14.1858 16.3491 14.1061C16.7231 14.0263 17.1121 14.0554 17.47 14.19C18.3773 14.5286 19.3199 14.7634 20.28 14.89C20.7658 14.9605 21.2094 15.2032 21.5265 15.5715C21.8437 15.9399 22.0122 16.4091 22 16.89V16.92Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="contact-content">
                    <div class="contact-label">Телефон для звонков из Российской Федерации:</div>
                    <div class="contact-value">
                        <a href="tel:<?= $phone ?>"><?= $phone_display ?></a>
                    </div>
                </div>
            </div>
            
            <div class="contact-item">
                <div class="contact-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                        <polyline points="12,6 12,12 16,14" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
                <div class="contact-content">
                    <div class="contact-label">График работы:</div>
                    <div class="contact-value"><?= $working_hours ?></div>
                </div>
            </div>
            
            <div class="contact-item">
                <div class="contact-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 10C21 17 12 23 12 23S3 17 3 10C3 7.61305 3.94821 5.32387 5.63604 3.63604C7.32387 1.94821 9.61305 1 12 1C14.3869 1 16.6761 1.94821 18.364 3.63604C20.0518 5.32387 21 7.61305 21 10Z" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
                <div class="contact-content">
                    <div class="contact-label">Адрес:</div>
                    <div class="contact-value"><?= $address ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.contacts-page {
    padding: 60px 0;
    background: #f8f9fa;
}

.contacts-header {
    text-align: center;
    margin-bottom: 60px;
}

.contacts-title {
    font-size: 48px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
    text-transform: uppercase;
}

.contacts-description {
    font-size: 18px;
    color: #666;
    max-width: 800px;
    margin: 0 auto;
    line-height: 1.6;
}

.contacts-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
    max-width: 1200px;
    margin: 0 auto;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.contact-item:hover {
    transform: translateY(-5px);
}

.contact-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    background: #007bff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.contact-content {
    flex: 1;
}

.contact-label {
    font-size: 14px;
    color: #666;
    margin-bottom: 10px;
    font-weight: 500;
}

.contact-value {
    font-size: 18px;
    color: #333;
    font-weight: 600;
}

.contact-value a {
    color: #007bff;
    text-decoration: none;
}

.contact-value a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .contacts-title {
        font-size: 32px;
    }
    
    .contacts-description {
        font-size: 16px;
    }
    
    .contacts-info {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .contact-item {
        padding: 20px;
    }
}
</style>

<?php get_footer(); ?>
