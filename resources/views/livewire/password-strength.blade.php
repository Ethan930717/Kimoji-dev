<div style="display: contents; background-color: inherit;">
    <p class="form__group">
        <input
                id="new_password"
                class="form__text"
                autocomplete="new-password"
                minlength="12"
                name="new_password"
                placeholder=" "
                required
                type="password"
                value="{{ old('new_password') }}"
                wire:model="password"
        >
        <label class="form__label form__label--floating" for="new_password">新密码</label>
    </p>
    <p class="form__group">
        <input
                id="new_password_confirmation"
                class="form__text"
                autocomplete="new-password"
                minlength="12"
                name="new_password_confirmation"
                placeholder=" "
                required
                type="password"
                value="{{ old('new_password') }}"
        >
        <label class="form__label form__label--floating" for="new_password_confirmation">再次输入新密码</label>
    </p>
    <p class="form__group">
        <label class="form__label" for="password_strength">
            密码强度: <b>{{ $strengthLevels[$strengthScore] ?? '弱' }}</b>
        </label>
        <meter
            id="password_strength"
            class="form__meter"
            min="0"
            max="4"
            value="{{ $strengthScore }}"
        >
            {{ $strengthLevels[$strengthScore] ?? '弱' }}
        </meter>
    </p>
</div>
