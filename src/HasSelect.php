<?php

namespace LivewireMultiselect;

use Illuminate\Database\Eloquent\Model;

trait HasSelect
{
    /**
     * @param $event
     */
    public function select($event)
    {
        // this component should not listen events for another one
        if (!empty($event['parent_id']) && $this->id !== $event['parent_id']) {
            return;
        }

        $object = $this;
        foreach (explode('.', $event['name']) as $segment) {
            if ($object->{$segment} instanceof Model) {
                $object = $object->{$segment};
            } else {
                $object->{$segment} = $event['value'];

                if (method_exists($this, 'selected')) {
                    $this->selected($event['name'], $event['value']);
                }

                break;
            }
        }
    }
}
