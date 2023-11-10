@if (config('other.freeleech') == true || config('other.invite-only') == false || config('other.doubleup') == true)
    <div class="alert alert-info" x-data="timer()" x-init="start()">
        <div class="text-center">
            <span>
                @if (config('other.freeleech') == true)🌐 {{ __('common.freeleech_activated') }} 🌐@endif
                @if (config('other.invite-only') == false)🌐 {{ __('common.openreg_activated') }} 🌐@endif
                @if (config('other.doubleup') == true)🌐 {{ __('common.doubleup_activated') }} 🌐@endif
            </span>
        </div>
    </div>
@endif
