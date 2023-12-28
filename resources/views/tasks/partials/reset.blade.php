<li class="form__group form__group--short-horizontal">
    <form
        method="POST"
        action="{{ route("requests.approved_fills.destroy", ['torrentRequest' => $torrentRequest]) }}"
        x-data
        style="display: contents"
    >
        @csrf
        @method('DELETE')
        <button
            x-on:click.prevent="Swal.fire({
                title: '请确认',
                text: '您确认要取消批准此求种，并收回补种人所获的魔力奖赏吗？',
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
            Revoke Approval
        </button>
    </form>
</li>

