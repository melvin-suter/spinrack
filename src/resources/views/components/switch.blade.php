@php
    $currentValue = (string) old($name, $defaultValue);
@endphp

<input type="hidden" id="{{ $name }}" name="{{ $name }}" value="{{ $currentValue }}"/>

<div class="button-group" data-target="{{ $name }}">
    @foreach($options as $key => $val)
        <button
            type="button"
            class="switch-button {{ (string) $key === $currentValue ? 'active' : '' }}"
            data-target="{{ $name }}"
            data-value="{{ $key }}"
        >
            {{ $val }}
        </button>
    @endforeach
</div>

<script>
(() => {
    document.querySelectorAll('.button-group').forEach(group => {
        const buttons = group.querySelectorAll('.switch-button');

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const targetName = btn.dataset.target;
                const input = document.getElementById(targetName);
                if (!input) return;

                input.value = btn.dataset.value;

                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    });
})();
</script>