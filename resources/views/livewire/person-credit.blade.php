<section class="panelV2" x-data="{ tab: @entangle('occupationId') }">
    <h2 class="panel__heading">{{ __('torrent.torrents') }}</h2>
    <menu class="panel__tabs">
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::CREATOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::CREATOR->value }}"
            x-show="{{ $createdCount }} > 0"
        >
            {{ __('mediahub.Creator') }} ({{ $createdCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::DIRECTOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::DIRECTOR->value }}"
            x-show="{{ $directedCount }} > 0"
        >
            {{ __('mediahub.Director') }} ({{ $directedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::WRITER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::WRITER->value }}"
            x-show="{{ $writtenCount }} > 0"
        >
            {{ __('mediahub.Writer') }} ({{ $writtenCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::PRODUCER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::PRODUCER->value }}"
            x-show="{{ $producedCount }} > 0"
        >
            {{ __('mediahub.Producer') }} ({{ $producedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::COMPOSER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::COMPOSER->value }}"
            x-show="{{ $composedCount }} > 0"
        >
            {{ __('mediahub.Composer') }} ({{ $composedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::CINEMATOGRAPHER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::CINEMATOGRAPHER->value }}"
            x-show="{{ $cinematographedCount }} > 0"
        >
            {{ __('mediahub.Cinematographer') }} ({{ $cinematographedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::EDITOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::EDITOR->value }}"
            x-show="{{ $editedCount }} > 0"
        >
            {{ __('mediahub.Editor') }} ({{ $editedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::PRODUCTION_DESIGNER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::PRODUCTION_DESIGNER->value }}"
            x-show="{{ $productionDesignedCount }} > 0"
        >
            {{ __('mediahub.ProductionDesigner') }} ({{ $productionDesignedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::ART_DIRECTOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::ART_DIRECTOR->value }}"
            x-show="{{ $artDirectedCount }} > 0"
        >
            {{ __('mediahub.artdirector') }} ({{ $artDirectedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::ACTOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::ACTOR->value }}"
            x-show="{{ $actedCount }} > 0"
        >
            {{ __('mediahub.actor') }} ({{ $actedCount }})
        </li>
    </menu>
    <div class="panel__body">
        @forelse ($medias as $media)
            @switch($media->meta)
                @case('movie')
                    <x-movie.card :media="$media" :personalFreeleech="$personalFreeleech" />

                    @break
                @case('tv')
                    <x-tv.card :media="$media" :personalFreeleech="$personalFreeleech" />

                    @break
            @endswitch
        @empty
            {{ __('mediahub.nomedia') }}
        @endforelse
    </div>
</section>
