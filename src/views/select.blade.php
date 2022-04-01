<div class="{{ $styles }}">
    @if($title ?? false)
        <label id="listbox-label" class="block mb-1 text-sm font-medium text-gray-700">
            {{ $title }}
        </label>
    @endif
    <div x-data="{show: $wire.entangle('isOpen')}"
         x-on:click.away="show = false" class="relative">
        @if ($simpleForm)
            @if($multiselect)
                @foreach($selectedItems as $item)
                    <input id="{{ $item }}" name="{{ $name }}[]" type="hidden" value="{{ $item }}"/>
                @endforeach
            @else
                <input name="{{ $name }}" type="text" class="hidden" value="{{ $selectedItems->first() }}"/>
            @endif
        @endif
        <button x-on:click="show = !show; $nextTick(() => $refs[`search_{{ $name }}`].focus());"
                x-on:keydown.escape.stop="show = false"
                type="button" class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label">
            <span class="flex items-center truncate" x-show="!show">
                {{  $this->original }}
            </span>
            <input class="w-full outline-none" wire:model="search" x-ref="search_{{ $name }}" x-show="show"/>
            <span class="ml-3 absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </span>
        </button>
        <ul x-show="show" style="display: none" class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-56 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm" tabindex="-1" role="listbox" aria-labelledby="listbox-label" aria-activedescendant="listbox-option-3">
            @if($showEmptyOption && !$multiselect)
                <li wire:key="select_wire-{{ $name }}-empty" wire:click="select('')" class="{{ $selectedItems->isEmpty() ? 'bg-indigo-500 text-white ' : 'hover:bg-indigo-500 hover:text-white text-gray-900 ' }} cursor-default select-none cursor-pointer relative py-2 pl-3 pr-9" id="listbox-option-0" role="option">
                    {{ __('Nothing selected') }}
                </li>
            @endunless
            @foreach($this->filteredOptions as $option)
                <li wire:key="select_wire-{{ $name }}-{{ $option[$trackBy] }}" wire:click="select('{{ $option[$trackBy] ?? null }}')" class="{{ $selectedItems->contains($option[$trackBy]) ? 'bg-indigo-500 text-white ' : 'hover:bg-indigo-500 hover:text-white text-gray-900 ' }} cursor-default cursor-pointer select-none relative py-2 pl-3 pr-9" id="listbox-option-0" role="option">
                    {{ $option[$label] }}
                </li>
            @endforeach
        </ul>
    </div>
</div>
