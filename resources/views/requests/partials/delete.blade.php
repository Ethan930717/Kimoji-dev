<li class="form__group form__group--short-horizontal">
    <form
        action="{{ route("requests.destroy", ['torrentRequest' => $torrentRequest]) }}"
        method="POST"
        x-data
        style="display: contents"
    >
        @csrf
        @method('DELETE')
        <button
            x-on:click.prevent="Swal.fire({
                title: '请确认',
                text: '是否确认删除该求种（悬赏金将被扣除）',
                icon: 'warning',
                showConfirmButton: true,
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $root.submit();
                }
            })"
            class="form__button form__button--outlined form__button--centered"
        >
            {{ __('common.delete') }}
        </button>
    </form>
</li>
