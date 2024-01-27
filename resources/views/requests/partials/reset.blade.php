<li class="form__group form__group--short-horizontal">
    <form
        method="POST"
        action="{{ route('requests.approved_fills.destroy', ['torrentRequest' => $torrentRequest]) }}"
        x-data="confirmation"
        style="display: contents"
    >
        @csrf
        @method('DELETE')
        <button
            x-on:click.prevent="confirmAction"
            data-b64-deletion-message="{{ base64_encode('您确认要取消批准此求种，并收回补种人所获的魔力奖赏') }}"
            class="form__button form__button--outlined form__button--centered"
        >
            Revoke Approval
        </button>
    </form>
</li>
