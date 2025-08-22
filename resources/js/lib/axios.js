import axios from 'axios';
import router from '../routes/router';

const api = axios.create({
    baseURL: '/api/v1',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'Accept-Language': localStorage.getItem('locale') || 'en',
    }
});

api.interceptors.request.use((config) => {
    const authData = localStorage.getItem('auth');
    if (authData) {
        const parsedData = JSON.parse(authData);
        const token = parsedData.access_token;
        try {
            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }
        } catch (e) {
            axios.post('/api/v1/auth/logout', {}, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            }).then(() => {
                localStorage.removeItem('auth');
                router.push({ name: 'authLogin' });
            }).catch((err) => {
                console.error("Error during logout", err);
            });
        }
    }
    return config;
}, (error) => {
    return Promise.reject(error);
});

api.interceptors.response.use(
    response => response,
    async (error) => {
      const originalRequest = error.config;
  
      // Проверка на наличие ответа от сервера
      if (error.response) {
        const status = error.response.status;
        const message = error.response.data.message;
  
        // Попытка обновить токен, если он истёк
        if (status === 401 && message === 'Token has expired' && !originalRequest._retry) {            
          originalRequest._retry = true;
  
          try {
            const authData = localStorage.getItem('auth');
            const parsedData = JSON.parse(authData);
            const oldToken = parsedData?.access_token;
  
            const response = await axios.post('/api/v1/auth/refresh', {}, {
              headers: {
                Authorization: `Bearer ${oldToken}`,
              },
            });
  
            const newToken = response.data.access_token;
            const user = response.data.user;
  
            // Сохраняем новый токен
            localStorage.setItem('auth', JSON.stringify({ access_token: newToken, user }));
  
            // Повторяем оригинальный запрос с новым токеном
            originalRequest.headers.Authorization = `Bearer ${newToken}`;
            return api(originalRequest);
  
          } catch (refreshError) {
            localStorage.removeItem('auth');
            router.push({ name: 'home' });
            return Promise.reject(refreshError);
          }
        }
  
        // Если ошибка 401 и это не токен, то редирект
        if (status === 401) {
          localStorage.removeItem('auth');
          router.push({ name: 'home' });
        }
      }
  
      return Promise.reject(error);
    }
  );
  

export default api;
