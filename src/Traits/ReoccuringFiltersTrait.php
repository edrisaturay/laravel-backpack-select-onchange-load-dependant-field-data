<?php

namespace Edrisa\OnchangeFieldData\Traits;

use Illuminate\Support\Str;

trait ReoccuringFiltersTrait
{

    protected function dropdown_filter(
        $_name,
        $_options,
        $_label = null,
    ){
        // dropdown filter
        $name = $_name . '_id';
        $this->crud->addFilter([
            'name'  => $name,
            'type'  => 'dropdown',
            'label' =>  ($_label == null)? ucwords(Str::replace('_', ' ', $_name)) : $_label,
        ],
        $_options,
        function($value) use($name) {
             $this->crud->addClause('where', $name, $value);
        });
    }
}
