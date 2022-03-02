<?php

namespace Admin\Fields\Tags;

use InWeb\Admin\App\Fields\Text;
use InWeb\Admin\App\Http\Requests\AdminRequest;

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

        $this->nullable();

        $this->resolveUsing(function ($value) {
            return $value->map(function($item) {
                return $item->name;
            });
        });

        $this->fillUsing(function (AdminRequest $request, $model, $attribute, $requestAttribute) {
            $value = $request[$requestAttribute];

            $model->{$attribute} = $this->isNullValue($value) ? [] : $value;
        });
    }
}
