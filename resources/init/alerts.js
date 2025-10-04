// Функция для показа уведомлений
export function showNotification(message, type = 'error') {
    const container = document.getElementById('notificationContainer');
    if (!container) {
        console.warn('Notification container not found');
        return;
    }

    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    const icons = {
        error: 'fa-exclamation-circle',
        success: 'fa-check-circle',
        warning: 'fa-exclamation-triangle'
    };
    
    notification.innerHTML = `
        <div class="notification-icon">
            <i class="fas ${icons[type]}"></i>
        </div>
        <div class="notification-content">
            <p class="notification-message">${message}</p>
        </div>
        <button class="notification-close">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(notification);
    
    // Добавляем обработчик для кнопки закрытия
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        removeNotification(notification);
    });
    
    // Автоматическое скрытие через 5 секунд
    setTimeout(() => {
        removeNotification(notification);
    }, 5000);
}

// Функция для удаления уведомления с анимацией
function removeNotification(notification) {
    if (notification.parentElement) {
        notification.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }
}

// Инициализация уведомлений из session данных
export function initSessionNotifications() {
    // Получаем данные из глобальной переменной (будет установлена в Blade)
    if (window.sessionNotifications) {
        if (window.sessionNotifications.error) {
            showNotification(window.sessionNotifications.error, 'error');
        }
        if (window.sessionNotifications.success) {
            showNotification(window.sessionNotifications.success, 'success');
        }
        if (window.sessionNotifications.warning) {
            showNotification(window.sessionNotifications.warning, 'warning');
        }
    }
}

// Глобальная функция для использования извне
window.showNotification = showNotification;