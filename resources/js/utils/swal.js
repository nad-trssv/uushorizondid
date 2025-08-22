import Swal from 'sweetalert2';
import { warn } from 'vue';
window.Swal = Swal; 
export const swal = {
    install: (app) => {
        const alert = {
            success(message, title = 'Успешно') {
                Swal.fire({
                    icon: 'success',
                    title: title,
                    text: message,
                    timer: 3000,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                });
            },
            error(message, title = 'Ошибка') {
                Swal.fire({
                    icon: 'error',
                    title: title,
                    text: message,
                    timer: 3000,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                });
            },
            warning(message, title = 'Внимание') {
                Swal.fire({
                    icon: 'warning',
                    title: title,
                    text: message,
                    timer: 3000,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                });
            },
            confirm(message, title = 'Вы уверены?') {
              return Swal.fire({
                  icon: 'warning',
                  title: title,
                  html: message,
                  showCancelButton: true,
                  confirmButtonText: 'Да, удалить',
                  cancelButtonText: 'Отмена',
                  confirmButtonColor: '#d33',
                  customClass: {
                    popup: 'modern-toast'
                  }
              });
          }
      };

      app.config.globalProperties.$alert = alert;
  }};