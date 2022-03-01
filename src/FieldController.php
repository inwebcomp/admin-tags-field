<?php

namespace Admin\Fields\Tags;

use Illuminate\Http\Request;
use InWeb\Admin\App\Fields\Select;
use InWeb\Admin\App\Http\Controllers\Controller;
use Spatie\Tags\Tag;

class FieldController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!strlen($query)) {
            return ['data' => []];
        }

        $tags = Tag::containing($query)->take(10)->get();

        return ['data' => Select::prepareOptions($tags, 'name', 'name')];
    }

    public function store(Request $request)
    {
        $value = $request->input('value');

        if (!strlen($value)) {
            return null;
        }

        $tag = Tag::findOrCreate($value);

        return [
            'message' => __('Тег был добавлен')
        ];
    }
}
