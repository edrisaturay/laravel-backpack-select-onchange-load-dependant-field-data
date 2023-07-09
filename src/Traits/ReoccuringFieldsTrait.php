<?php

namespace Edrisa\OnchangeFieldData\Traits;

use Illuminate\Support\Str;

trait ReoccuringFieldsTrait
{
    protected function slug_field(
        $_name = 'slug',
        $_target = 'name',
        $_wrapperClass = 'form-group col-12'): void
    {
        $this->crud->addField([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'slug',
            'target' => $_target,

            'hint' => __('This field will automatically be filled in'),
            'attributes' => [
                'placeholder' => __('Please enter the :name', ['name' => $_name]),
                'disabled' => 'disabled',
                'readonly' => 'readonly',
            ],
            'wrapper' => [
                'class' => $_wrapperClass,
            ],
        ]);
    }


    protected function switch_field(
        $_name = 'status',
        $_wrapperClass = 'form-group col-12',
        $_onLabel = '✓',
        $_offLabel = '✕'): void
    {
        $this->crud->addField([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'switch',
            'wrapper' => [
                'class' => $_wrapperClass,
            ],
            'onLabel' => $_onLabel,
            'offLabel' => $_offLabel,
            'color' => 'primary',

        ]);
    }

    protected function upload_field(string $_name): void
    {
        $this->crud->addField([
            'type' => 'upload',
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'name' => Str::slug($_name, '_'),
            'disk' => 'public',
            'upload' => true,
        ]);
    }

    protected function legal_pathway_question_field(
        $_name,
        $_label = null,
        $_tab = null,
    ){
        return $this->crud->addField([
            'name'            => $_name,
            'label' => ucwords(Str::replace('_', ' ', get_legal_pathway_question($_name))),
            'type'            => 'text',
            'tab'             => ($_tab) ? $_tab : 'Main',
        ]);

    }

    protected function select_2_from_array_field(
        $_name,
        $_options,
        $_allow_null = true,
    ){
        $this->crud->addField([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'select2_from_array',
            'options' => array_merge(($_allow_null) ? [0 => __('- None -')] : [], $_options),
            'allows_null' => $_allow_null,
            'hint' => __('Please select the :name', ['name' => $_name]),
            'tags' => true,
        ]);
    }

    protected function survey_select_field(
        $_name,
        $_label,
        $_options,
        $_tab,
        $_allowsNull = false
    ){
        return $this->crud->addField([
            // Select
            'name'        => $_name,
            'label'       => $_label,
            'type'        => 'select_from_array_custom',
            'options'     => $_options,
            'allows_null' => $_allowsNull,
            'tab'     => $_tab,
        ]);
    }

    protected function select_field(
        $_name,
        $_attribute = 'name',
        $_wrapperClass = 'col-12',
        $_tab = null,
        $_allows_null = false,
    ){
        return $this->crud->addField([
            'name'        => $_name . '_id',
            'label'       => ucwords(Str::replace('_', ' ', $_name)),
            'type'        => 'select',
            'entity'      => $_name,
            'attribute'   => $_attribute,
            'allows_null' => $_allows_null,
            'wrapper' => [
                'class' => 'form-group ' . $_wrapperClass,
            ],
            'tab' => $_tab,
        ]);
    }
    protected function select_from_array_field(
        $_name,
        $_options,
        $_wrapperClass = 'col-12',
        $_tab = null,
        $_currentEntry = null,
    ){
        $oldValue = null;
        if($_currentEntry){
            $oldValue = $_currentEntry->legal_pathway_host_country;
        }
        return $this->crud->addField([
            'name'        => $_name,
            'label'       => ucwords(Str::replace('_', ' ', $_name)),
            'type'        => 'select_from_array',
            'options'     => $_options,
            'allows_null' => false,
            'wrapper' => [
                'class' => 'form-group ' . $_wrapperClass,
            ],
            'tab'     => $_tab,
            'default' => $oldValue,
        ]);
    }

    protected function select2_from_array_tagged_field(
        string $_name,
        array $_options,
        string $_tab = null,
        bool $_allowsNull = false,
        array $_attributes = [],
        string$_label = null,
        bool $_allowsMultiple = false,
    ){
        $this->crud->addField([
            'name' => $_name,
            'label' => ($_label) ? $_label : ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'select2_from_array_tagged',
            'options' => $_options,
            'allows_null' =>  $_allowsNull,
            'attributes' => $_attributes,
            'allows_multiple' => $_allowsMultiple
        ]);
    }

    protected function survey_select_grouped_field(
        $_name,
        $_label,
        $_options,
        $_tab,
    ){
        return $this->crud->addField([
            'name'        => $_name,
            'label'       => $_label,
            'type'        => 'select_from_grouped_array',
            'options'     => $_options,
            'allows_null' => false,
            'tab'     => $_tab,
        ]);
    }

    protected function tiny_mce_field(
        $_name,
        $_tab,
        $_label = null,
    ){
        return $this->crud->addField([
            'name' => $_name,
            'label' =>  ($_label) ? $_label : ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'tinymce',
            'tab' => $_tab,
        ]);
    }

    protected function easy_mde_field(
        $_name,
        $_tab,
        $_label = null,
    ){
        return $this->crud->addField([
            'name' => $_name,
            'label' =>  ($_label) ? $_label : ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'easymde',
            'tab' => $_tab,
        ]);
    }
    protected function select2_multiple_field(
        $_name,
        $_attribute = 'name',
        $_wrapperClass = 'col-12',
        $_tab = null,
        $_options = null,
    ){
        return $this->crud->addField([
            'name'        => $_name,
            'label'       => ucwords(Str::replace('_', ' ', $_name)),
            'type'        => 'select2_multiple',
            'entity'      => $_name,
            'attribute'   => $_attribute,
            'wrapper' => [
                'class' => 'form-group ' . $_wrapperClass,
            ],
            'hint' => __('Please select the :name', ['name' => ucwords(Str::replace('_', ' ', $_name))]),
            'tab' => $_tab,
            'options' => function ($query) {
                return $query->where('status', 1)->get();
            },
        ]);

    }

    protected function text_field_with_tab(
        $_name,
        $_wrapperClass = 'col-12',
        $_required = false,
        $_hint = null,
        $_placeholder = null,
        $_disabled = false,
        $_readonly = false,
        $_tab = null,
    ){
        return $this->crud->addField([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group ' . $_wrapperClass,
            ],
            'attributes' => array_merge(
                [
                    'placeholder' => $_placeholder ?? __('Please enter the :name', ['name' => $_name])
                ],
                [
                    ($_disabled) ? 'disabled'  : null
                ],
                [
                    ($_readonly) ? 'readonly'  : null
                ],
                [
                    ($_required) ? 'required'  : null
                ]
            ),
            'hint' => $_hint,
            'tab' => ($_tab)? $_tab : "Main",
        ]);
    }

    protected function text_field(
        $_name = 'name',
        $_wrapperClass = 'col-12',
        $_required = false,
        $_hint = null,
        $_placeholder = null,
        $_disabled = false,
        $_readonly = false,
    ){
        return $this->crud->addField([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group ' . $_wrapperClass,
            ],
            'attributes' => array_merge(
                [
                    'placeholder' => $_placeholder ?? __('Please enter the :name', ['name' => $_name])
                ],
                [
                    ($_disabled) ? 'disabled'  : null
                ],
                [
                    ($_readonly) ? 'readonly'  : null
                ],
                [
                    ($_required) ? 'required'  : null
                ]
            ),
            'hint' => $_hint,
        ]);
    }

    protected function number_field(
        $_name = 'name',
        $_wrapperClass = 'col-12',
        $_required = false,
        $_hint = null,
        $_placeholder = null,
        $_disabled = false,
        $_readonly = false,){
        return $this->crud->addField([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'number',
            'wrapper' => [
                'class' => 'form-group ' . $_wrapperClass,
            ],
            'attributes' => array_merge(
                [
                    'placeholder' => $_placeholder ?? __('Please enter the :name', ['name' => $_name])
                ],
                [
                    ($_disabled) ? 'disabled'  : null
                ],
                [
                    ($_readonly) ? 'readonly'  : null
                ],
                [
                    ($_required) ? 'required'  : null
                ],
                [
                    'step' => 'any',
                ]
            ),
            'hint' => $_hint,
        ]);
    }

    protected function number_field_with_tab(
        $_name,
        $_wrapperClass = 'col-12',
        $_required = false,
        $_hint = null,
        $_placeholder = null,
        $_disabled = false,
        $_readonly = false,
        $_tab = null,
    ){
        return $this->crud->addField([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group ' . $_wrapperClass,
            ],
            'attributes' => array_merge(
                [
                    'placeholder' => $_placeholder ?? __('Please enter the :name', ['name' => $_name])
                ],
                [
                    ($_disabled) ? 'disabled'  : null
                ],
                [
                    ($_readonly) ? 'readonly'  : null
                ],
                [
                    ($_required) ? 'required'  : null
                ],
                [
                    'step' => 'any',
                ]
            ),
            'hint' => $_hint,
            'tab' => ($_tab)? $_tab : "Main",
        ]);
    }
    protected function phone_field($_name, $_wrapperClass = 'col-12'){
        return $this->crud->addField([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'phone',
            'wrapper' => [
                'class' => 'form-group ' . $_wrapperClass,
            ],
            'attributes' => [
                'placeholder' => __('Please enter the :name', ['name' => $_name]),
            ],
        ]);
    }

    protected function date_field(
        $_name,
        $_wrapperClass = 'col-12',
        $_hint = null,
    ){
        return $this->crud->addField([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'date',
            'hint' => $_hint,
            'wrapper' => [
                'class' => 'form-group '.$_wrapperClass,
            ],
        ]);

    }

    protected function time_field(
        $_name,
        $_wrapperClass = 'col-12',
        $_hint = null,
    ){
        $this->crud->addField([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'time',
            'hint' => $_hint,
            'wrapper' => [
                'class' => 'form-group '.$_wrapperClass,
            ],
        ]);
    }



}
