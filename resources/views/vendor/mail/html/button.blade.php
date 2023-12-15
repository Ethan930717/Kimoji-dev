<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <a href="{{ $url }}" class="button button-{{ $color ?? 'primary' }}" target="_blank" rel="noopener">
                {{ $slot }}
            </a>
        </td>
    </tr>
</table>