<?php

namespace Edrisa\OnchangeFieldData\Traits;

use Illuminate\Support\Str;

trait ReoccuringFiltersTrait
{

    protected function dropdown_filter($_name, $_options){
        // dropdown filter
        $name = $_name . '_id';
        $this->crud->addFilter([
            'name'  => $name,
            'type'  => 'dropdown',
            'label' =>  ucwords(Str::replace('_', ' ', $_name)),
        ],
        $_options,
        function($value) use($name) {
            debug($value, $name);
             $this->crud->addClause('where', $name, $value);
        });
    }
}
