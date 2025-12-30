@php
    $notifications = $penawaranApprovedNotifications ?? collect();
@endphp

@if($notifications->isNotEmpty())
<div class="admin-toast-container" id="adminToastContainer">
    @foreach ($notifications as $notification)
    @php
        $data = $notification->data;
    @endphp
    <div class="admin-toast" data-notification-id="{{ $notification->id }}">
        <div class="admin-toast-icon">
            <iconify-icon icon="ph:check-circle-duotone"></iconify-icon>
        </div>
        <div>
            <div class="admin-toast-title">Penawaran Disetujui</div>
            <p class="admin-toast-subtitle mb-0">
                {{ $data['message'] ?? 'Penawaran telah disetujui.' }}
            </p>
        </div>
        <button type="button" class="admin-toast-close" aria-label="Close">
            <iconify-icon icon="mdi:close"></iconify-icon>
        </button>
    </div>
    @endforeach
</div>

@push('script')
<script>
    (function () {
        const toasts = document.querySelectorAll('#adminToastContainer .admin-toast');

        toasts.forEach((toast, index) => {
            const closeBtn = toast.querySelector('.admin-toast-close');
            const hideToast = () => {
                toast.classList.add('hide');
                setTimeout(() => toast.remove(), 200);
            };
            closeBtn?.addEventListener('click', hideToast);
            setTimeout(hideToast, 4000 + (index * 200));
        });
    })();
</script>
@endpush
@endif
