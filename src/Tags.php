<?php

namespace Admin\Fields\Tags;

use App\Models\Location;
use InWeb\Admin\App\Fields\Text;

class Tags extends Text
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'tags-field';

    public $size = 'w-full';

    public function __construct(string $name, ?string $attribute = 'tags', $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->original(true);

        $this->displayUsing(function ($value) {
            if (! is_array($value))
                return $value;

            return implode(', ', $value[0]);
        });

        $this->resolveUsing(function ($value) {
            return $value;
        });
    }
}
