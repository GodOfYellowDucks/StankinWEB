document.addEventListener('DOMContentLoaded', function() {
    // Проверяем, принял ли пользователь уведомление о куках
    if (!getCookie('cookie_notice_accepted')) {
        showCookieNotice();
    }
    
    // Функция для показа уведомления о куках
    function showCookieNotice() {
        // Создаем элемент уведомления
        var notice = document.createElement('div');
        notice.className = 'cookie-notice';
        notice.innerHTML = '<p>Наш сайт использует куки для улучшения вашего опыта. Продолжая использовать сайт, вы соглашаетесь с использованием куки.</p>' +
                          '<button id="accept-cookies">Принять</button>';
        
        // Добавляем уведомление на страницу
        document.body.appendChild(notice);
        
        // Добавляем обработчик события для кнопки принятия
        document.getElementById('accept-cookies').addEventListener('click', function() {
            // Устанавливаем куки, что пользователь принял уведомление (на 1 год)
            setCookie('cookie_notice_accepted', 'true', 365);
            
            // Скрываем уведомление
            notice.style.display = 'none';
        });
    }
    
    // Функция для установки куки
    function setCookie(name, value, days) {
        var expires = '';
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }
        document.cookie = name + '=' + (value || '') + expires + '; path=/';
    }
    
    // Функция для получения значения куки
    function getCookie(name) {
        var nameEQ = name + '=';
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
});