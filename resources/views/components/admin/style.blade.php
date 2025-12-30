<style>
    [data-theme="light"] body {
        background: white;
        color: black;
    }

    [data-theme="dark"] body {
        background: #111827;
        color: white;
    }

    [data-theme="light"] .card {
        background: #ffffff;
        color: #111827;
    }

    [data-theme="dark"] .card {
        background: #1f2937;
        /* abu gelap */
        color: #f9fafb;
    }

    [data-theme="light"] .list-group-item {
        background: #ffffff;
        color: #111827;
    }

    [data-theme="dark"] .list-group-item {
        background: #374151;
        color: #f9fafb;
    }

    [data-theme="light"] .badge {
        background-color: #2563eb;
        color: #ffffff;
    }

    [data-theme="dark"] .badge {
        background-color: #3b82f6;
        color: #ffffff;
    }

    [data-theme="light"] select option,
    [data-theme="light"] select optgroup {
        background-color: #ffffff;
        color: #111827;
    }

    [data-theme="dark"] select option,
    [data-theme="dark"] select optgroup {
        background-color: #1e293b;
        color: #f1f5f9;
    }

    .status-toggle {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .status-toggle input {
        display: none;
    }

    .status-toggle-track {
        width: 48px;
        height: 24px;
        border-radius: 999px;
        background: #d1d5db;
        position: relative;
        transition: background 0.2s ease;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.08);
    }

    .status-toggle-thumb {
        position: absolute;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #ffffff;
        top: 2px;
        left: 2px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s ease;
    }

    .status-toggle input:checked + .status-toggle-track {
        background: #22c55e;
    }

    .status-toggle input:checked + .status-toggle-track .status-toggle-thumb {
        transform: translateX(24px);
    }

    .status-toggle input:disabled + .status-toggle-track {
        background: #9ca3af;
        opacity: 0.8;
    }

    .status-toggle input:disabled + .status-toggle-track .status-toggle-thumb {
        box-shadow: none;
    }

    .status-toggle-hint {
        font-size: 0.75rem;
        color: #6b7280;
    }

    [data-theme="dark"] .status-toggle-track {
        background: #4b5563;
        box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.6);
    }

    [data-theme="dark"] .status-toggle-thumb {
        background: #0f172a;
    }

    .notification-trigger {
        position: relative;
        width: 40px;
        height: 40px;
        border-radius: 999px;
        border: none;
        background: #f1f5f9;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #111827;
    }

    .notification-trigger .badge-dot {
        position: absolute;
        top: 6px;
        right: 6px;
        min-width: 18px;
        height: 18px;
        border-radius: 999px;
        background: #ef4444;
        color: #fff;
        font-size: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    [data-theme="dark"] .notification-trigger {
        background: #1f2937;
        color: #f9fafb;
    }

    .admin-toast-container {
        position: fixed;
        top: 1.25rem;
        right: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 12px;
        z-index: 1085;
        pointer-events: none;
    }

    .admin-toast {
        pointer-events: auto;
        min-width: 280px;
        max-width: 360px;
        padding: 12px 16px;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.18);
        display: flex;
        align-items: flex-start;
        gap: 12px;
        animation: adminToastIn 0.3s ease;
    }

    .admin-toast-icon {
        color: #16a34a;
        font-size: 1.5rem;
        line-height: 1;
    }

    .admin-toast-title {
        font-weight: 600;
        margin-bottom: 2px;
        color: #111827;
    }

    .admin-toast-subtitle {
        margin: 0;
        font-size: 0.85rem;
        color: #6b7280;
    }

    .admin-toast-close {
        border: none;
        background: transparent;
        color: #9ca3af;
        margin-left: auto;
    }

    .admin-toast.hide {
        opacity: 0;
        transform: translateX(16px);
        transition: all 0.2s ease;
    }

    @keyframes adminToastIn {
        from {
            opacity: 0;
            transform: translateX(16px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    [data-theme="dark"] .admin-toast {
        background: #1f2937;
        color: #f9fafb;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.6);
    }

    [data-theme="dark"] .admin-toast-title {
        color: #f9fafb;
    }

    [data-theme="dark"] .admin-toast-subtitle {
        color: #cbd5f5;
    }

    @stack('style')
</style>