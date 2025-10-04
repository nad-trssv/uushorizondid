{{-- Телеграм-FAB (мобилки + десктоп) --}}
<a  class="tg-fab tg-fab--bl" 
    href="https://t.me/Keeleklubi" 
    target="_blank" 
    rel="noopener"
    aria-label="Написать в Telegram"
    title="Написать в Telegram">
    <i class="fab fa-telegram-plane"></i>
    <span class="tg-fab__label">Написать</span>
</a>

<style>
/* ========= Базовые токены ========= */
.tg-fab{
  --tg-bg: var(--primary, #7DA640);
  --tg-bg-hover: var(--primary-dark, #5a7a2e);
  --tg-shadow: rgba(0,0,0,.18);
  --tg-bottom: 16px; /* легко поднять/опустить при надобности */

  position: fixed;
  z-index: 1000;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 12px 14px;
  border-radius: 999px;
  background: var(--tg-bg);
  color: #fff;
  text-decoration: none;
  font-weight: 700;
  line-height: 1;
  box-shadow: 0 10px 20px var(--tg-shadow);
  transition: transform .15s ease, box-shadow .15s ease, background .15s ease, opacity .2s;
}

.tg-fab i{ font-size: 18px; }

/* ========= Размещения ========= */
/* bottom-left (по умолчанию) */
.tg-fab--bl{
  left: calc(16px + env(safe-area-inset-left, 0));
  bottom: calc(var(--tg-bottom) + env(safe-area-inset-bottom, 0));
}

/* Альтернатива: правый центр (на будущее)
.tg-fab--rm{
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
}
*/

/* ========= Ховеры / анимации ========= */
@keyframes tg-pulse {
  0% { box-shadow: 0 0 0 0 rgba(125,166,64,.35); }
  70%{ box-shadow: 0 0 0 16px rgba(125,166,64,0); }
  100%{ box-shadow: 0 0 0 0 rgba(125,166,64,0); }
}
.tg-fab{ animation: tg-pulse 10s infinite; }

.tg-fab:hover{ 
  background: var(--tg-bg-hover); 
  transform: translateY(-1px);
  box-shadow: 0 12px 24px var(--tg-shadow);
}

/* ========= Адаптив ========= */
/* На очень узких экранах — компактный круг, чтобы не загораживать UI */
@media (max-width: 420px){
  .tg-fab{ padding: 12px; gap: 0; }
  .tg-fab__label{ display: none; }
}

/* На >= 1200px — немного “воздуха” слева и снизу, и всегда видимый лейбл */
@media (min-width: 1200px){
  .tg-fab--bl{
    left: 24px;
    bottom: 24px;
  }
}

/* Уважение к prefers-reduced-motion */
@media (prefers-reduced-motion: reduce){
  .tg-fab{ animation: none; transition: none; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){
  var el = document.querySelector('.tg-fab');
  if(!el) return;
  el.addEventListener('click', function(e){
    var t = Date.now();
    window.location = 'tg://resolve?domain=Keeleklubi';
    setTimeout(function(){
      if(Date.now() - t < 1500){
        window.open('https://t.me/Keeleklubi', '_blank', 'noopener');
      }
    }, 800);
    e.preventDefault();
  });
});
</script>
