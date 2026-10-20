@if(config('services.web_push.public_key'))
<style>
.push-toggle{position:fixed;right:16px;bottom:16px;z-index:50;display:flex;align-items:center;gap:8px;border:1px solid #d7deea;border-radius:999px;padding:10px 14px;background:#fff;color:#1e3a8a;font:600 13px system-ui,sans-serif;box-shadow:0 8px 28px rgba(15,23,42,.16);cursor:pointer}.push-toggle[disabled]{cursor:wait;opacity:.7}.push-toggle.is-active{background:#ecfdf5;color:#047857;border-color:#a7f3d0}.push-toggle-status{width:8px;height:8px;border-radius:50%;background:currentColor}.push-prompt{position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;padding:20px;background:rgba(15,23,42,.55);backdrop-filter:blur(3px)}.push-prompt[hidden]{display:none}.push-prompt-card{width:min(100%,390px);padding:26px;border-radius:22px;background:#fff;color:#0f172a;text-align:center;box-shadow:0 24px 70px rgba(15,23,42,.3);font-family:system-ui,sans-serif}.push-prompt-icon{display:grid;place-items:center;width:58px;height:58px;margin:0 auto 14px;border-radius:18px;background:#eef2ff;font-size:28px}.push-prompt-card h2{margin:0 0 8px;font-size:20px}.push-prompt-card p{margin:0 0 20px;color:#64748b;font-size:14px;line-height:1.55}.push-prompt-actions{display:grid;grid-template-columns:1fr 1fr;gap:10px}.push-prompt-actions button{border:0;border-radius:12px;padding:12px;font:700 14px system-ui,sans-serif;cursor:pointer}.push-prompt-no{background:#f1f5f9;color:#475569}.push-prompt-yes{background:#224086;color:#fff}
</style>
<button type="button" id="pushToggle" class="push-toggle" hidden><span class="push-toggle-status"></span><span id="pushToggleText">Bật thông báo</span></button>
<div id="pushPrompt" class="push-prompt" hidden role="dialog" aria-modal="true" aria-labelledby="pushPromptTitle">
    <div class="push-prompt-card">
        <div class="push-prompt-icon" aria-hidden="true">🔔</div>
        <h2 id="pushPromptTitle">Bạn có muốn nhận thông báo?</h2>
        <p>Cho phép chúng tôi gửi những thông tin và cập nhật mới nhất đến thiết bị của bạn.</p>
        <div class="push-prompt-actions">
            <button type="button" id="pushPromptNo" class="push-prompt-no">Để sau</button>
            <button type="button" id="pushPromptYes" class="push-prompt-yes">Đồng ý</button>
        </div>
    </div>
</div>
<script>
(() => {
    const button = document.getElementById('pushToggle');
    const label = document.getElementById('pushToggleText');
    const prompt = document.getElementById('pushPrompt');
    const promptYes = document.getElementById('pushPromptYes');
    const promptNo = document.getElementById('pushPromptNo');
    const publicKey = {{ Illuminate\Support\Js::from(config('services.web_push.public_key')) }};
    const csrfToken = {{ Illuminate\Support\Js::from(csrf_token()) }};
    let registration;
    if (!('serviceWorker' in navigator) || !('PushManager' in window) || !('Notification' in window)) return;
    const keyBytes = value => {
        const padding = '='.repeat((4 - value.length % 4) % 4);
        const base64 = (value + padding).replace(/-/g, '+').replace(/_/g, '/');
        return Uint8Array.from(atob(base64), character => character.charCodeAt(0));
    };
    const request = (url, body) => fetch(url, {
        method: 'POST', headers: {'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken}, body: JSON.stringify(body),
    }).then(async response => {
        if (!response.ok) throw new Error((await response.json().catch(() => ({}))).message || 'Không thể cập nhật đăng ký.');
        return response.json();
    });
    const render = subscription => {
        button.classList.toggle('is-active', Boolean(subscription));
        label.textContent = subscription ? 'Tắt thông báo' : 'Bật thông báo';
        button.hidden = false;
    };
    navigator.serviceWorker.register('/sw.js').then(async value => {
        registration = value;
        const subscription = await registration.pushManager.getSubscription();
        render(subscription);
        if (!subscription && Notification.permission === 'default') prompt.hidden = false;
    }).catch(() => {});

    const enablePush = async () => {
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') throw new Error('Bạn chưa cấp quyền hiển thị thông báo.');
        const subscription = await registration.pushManager.subscribe({userVisibleOnly: true, applicationServerKey: keyBytes(publicKey)});
        try { await request({{ Illuminate\Support\Js::from(route('push.subscribe')) }}, subscription.toJSON()); }
        catch (error) { await subscription.unsubscribe(); throw error; }
        render(subscription);
    };

    button.addEventListener('click', async () => {
        if (!registration) return;
        button.disabled = true;
        try {
            let subscription = await registration.pushManager.getSubscription();
            if (subscription) {
                await request({{ Illuminate\Support\Js::from(route('push.unsubscribe')) }}, {endpoint: subscription.endpoint});
                await subscription.unsubscribe();
                render(null);
            } else {
                await enablePush();
            }
        } catch (error) {
            window.alert(error.message || 'Không thể cập nhật cài đặt thông báo.');
        } finally { button.disabled = false; }
    });

    promptNo.addEventListener('click', () => { prompt.hidden = true; });
    promptYes.addEventListener('click', async () => {
        promptYes.disabled = true;
        promptNo.disabled = true;
        try {
            await enablePush();
            prompt.hidden = true;
        } catch (error) {
            prompt.hidden = true;
            window.alert(error.message || 'Không thể bật thông báo.');
        } finally {
            promptYes.disabled = false;
            promptNo.disabled = false;
        }
    });
})();
</script>
@endif
