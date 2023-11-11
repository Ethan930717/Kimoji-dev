<svg {{ $attributes }} viewBox="0 0 100 100">
    <circle fill="transparent" stroke="#ffffff" stroke-width="10" cx="50" cy="50" r="15">
        <animate attributeName="r" values="0;5;25;40;50" dur="1s" repeatCount="indefinite" />
        <animate attributeName="opacity" values="0;.1;.3;.5;0" dur="1s" repeatCount="indefinite" />
    </circle>
    <circle fill="#ffffff" cx="50" cy="50" r="15" />
</svg>