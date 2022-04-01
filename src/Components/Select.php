<?php

namespace LivewireMultiselect\Components;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Livewire\Component;

class Select extends Component
{
    /**
     * @var string
     */
    public string $name;

    /**
     * Component id which contains this select.
     * Should be used when has more 1 parents depth level.
     *
     * @var string|null
     */
    public ?string $parentId = null;

    /**
     * @var string
     */
    public string $styles = 'col-span-1';

    /**
     * @var Collection|null
     */
    public ?Collection $selectedItems = null;

    /**
     * @var string
     */
    public string $trackBy = 'id';

    /**
     * @var string
     */
    public string $label = 'name';

    /**
     * @var bool
     */
    public bool $showEmptyOption = true;

    /**
     * @var bool
     */
    public bool $multiselect = false;

    /**
     * @var bool
     */
    public bool $simpleForm = false;

    /**
     * @var string|null
     */
    public ?string $title = null;

    /**
     * @var Collection
     */
    public Collection $options;

    /**
     * @var string|null
     */
    public ?string $search = null;

    /**
     * @var bool
     */
    public bool $isOpen = false;

    protected $listeners = ['refresh', 'refreshOptions'];

    public function mount($selected = null)
    {
        $this->selectedItems = collect($selected ?: null);
    }

    public function select($value)
    {
        if ($this->multiselect) {
            if (($key = $this->selectedItems->search($value)) === false) {
                $this->selectedItems->push($value);
            } else {
                $this->selectedItems->forget($key);
            }
        } else {
            $this->selectedItems = collect($value ?: null);
        }

        $this->emitSelect();

        if (!$this->multiselect) {
            $this->reset('search', 'isOpen');
        }
    }

    public function updatedIsOpen($value)
    {
        if ($value === false) {
            $this->reset('search');
        }
    }

    /**
     * @return mixed|string
     */
    public function getOriginalProperty()
    {
        $result = $this->options->whereIn($this->trackBy, $this->selectedItems)->pluck($this->label)->implode(', ');

        return $result ?: __('Nothing selected');
    }

    public function getFilteredOptionsProperty()
    {
        return $this->options
            ->when($this->search, function (Collection $options) {
                return $options->filter(fn ($option, $key) => stristr($option[$this->label], $this->search) !== false);
            });
    }

    public function refresh($data)
    {
        if ($this->parentId !== null && $this->parentId !== $data['id']) {
            return;
        }

        $this->selectedItems = collect(Arr::get($data, $this->name));
    }

    public function refreshOptions(array $options, string $name, ?string $parentId = null)
    {
        if ($this->parentId !== null && $this->parentId !== $parentId) {
            return;
        }

        if ($this->name === $name) {
            $this->options = collect($options);
        }
    }

    public function render()
    {
        return view('multiselect::select');
    }

    protected function emitSelect()
    {
        if (!$this->simpleForm) {
            $this->emitUp('select', [
                'name'      => $this->name,
                'value'     => $this->multiselect ? $this->selectedItems : $this->selectedItems->first(),
                'parent_id' => $this->parentId,
            ]);
        }
    }
}
