<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait ReoccuringColumnsTrait
{
    protected function name_column(
        $_name = 'name'): void
    {
        $this->crud->addColumn([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'text',
        ]);
    }

    protected function text_column(
        $_name = 'name',
        $_label = null,
        $_encrypted = false,
        $_tab = null,
        $_limit = null
    ): void
    {
        $this->crud->addColumn([
            'name' => $_name,
            'label' => ($_label == null)? ucwords(Str::replace('_', ' ', $_name)) : $_label,
            'type' => 'text',
            'searchLogic' => function($_query, $_column, $_searchTerm) use ($_encrypted, $_name) {
                if ($_encrypted) {
                    $_query->orWhereEncrypted($_name, 'like', '%' . $_searchTerm . '%');
                }else{
                    if ($_name == 'parent_full_name'){
                        $_searchTerm = explode(' ', $_searchTerm);
                        foreach ($_searchTerm as $term){
                            $_query->orWhereEncrypted('first_name', 'like', '%' . $term . '%')
                                ->orWhereEncrypted('middle_name', 'like', '%' . $term . '%')
                                ->orWhereEncrypted('last_name', 'like', '%' . $term . '%')
                                ->orWhereEncrypted('second_last_name', 'like', '%' . $term . '%');
                        }
                    }else {
                        $_query->orWhere($_name, 'like', '%' . $_searchTerm . '%');
                    }
                }
            },
            'tab' => $_tab,
            'limit' => ($_limit) ? 100 : 100000,
        ]);
    }
    protected  function main_view_link_column($_name = 'name'): void
    {
        $this->crud->addColumn([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'text',
        ]);
    }
    protected function verification_status_column(
        $_name = 'name'): void
    {
        $this->crud->addColumn([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'text',
            'wrapper' =>[
                'element' => 'span',
                'class' => function ($crud, $column, $entry, $related_key) {
                    if($entry->{$column['name']} == 'Verification - Incomplete')
                        return 'badge badge-danger';
                    elseif($entry->{$column['name']} == 'Verification - Pending')
                        return 'badge badge-warning';
                    elseif($entry->{$column['name']} == 'Verification - Complete')
                        return 'badge badge-info';
                    else
                        return 'badge badge-success';
                }
            ],
        ]);
    }


    protected function slug_column(
        $_name = 'slug',): void
    {
        $this->crud->addColumn([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'text',
        ]);
    }
    protected function editable_switch_column(
        $_name = 'status',
        $_label = null,
        $_onLabel = '✓',
        $_offLabel = '✕'): void
    {
        $this->crud->addColumn([
            'name'  => $_name,
            'label' => ($_label) ? $_label : ucwords(Str::replace('_', ' ', $_name)),
            'type'  => 'editable_switch',

            // Optionals
            // All the options available on editable_checkbox are available here too, plus;
            'color'   => 'success',
            'onLabel' => $_onLabel,
            'offLabel' => $_offLabel,
        ]);
    }

    protected function boolean_column(
        $_name = 'status',
        $_onLabel = '✓',
        $_offLabel = '✕'): void
    {
        $this->crud->addColumn([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'boolean',

            // optional
            'options' => [ 0 => $_offLabel, 1 => $_onLabel],
            'wrapper' =>[
                'element' => 'span',
                'class' => function ($crud, $column, $entry, $related_key) {
                    return $entry->{$column['name']} ? 'badge badge-success' : 'badge badge-danger';
                }
            ]
        ]);
    }

    protected function relationship_column(
        $_name,
        $_attribute = '',
        $_label = null,
    ){
        $this->crud->addColumn([
            'name' => $_name,
            'label' =>($_label) ? $_label : ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'relationship',
            'attribute' => $_attribute,
            'entity' => $_name,
        ]);
    }

    protected function relationship_count_column($_name){
        $this->crud->query->withCount($_name); // this will add a tags_count column to the results
        $this->crud->addColumn([
            'name'      => $_name . '_count', // name of relationship method in the model
            'type'      => 'text',
            'label'     => ucwords(Str::replace('_', ' ', $_name)), // Table column heading
            'suffix'    => ' ' . $_name . 's', // to show "123 tags" instead of "123"
        ]);
    }

    protected function select_column($_name, $_model, $_attribute){
        $this->crud->addColumn([
            'name'    => $_name. '_id',
            'label'   => ucwords(Str::replace('_', ' ', $_name)),
            'type'    => 'select',
            'entity'  => $_name,
            'attribute' => $_attribute,
            'model'   => $_model,
            'searchLogic' => function($query, $_column, $_searchTerm) use ($_name, $_attribute) {
                $query->orWhereHas($_name, function ($query) use ($_searchTerm, $_attribute) {
                    $query->where(($_attribute) ?: 'name', 'like', '%' . $_searchTerm . '%');
                });
                Log::info($query->toSql());
            }
        ]);
    }
    protected function editable_select_column(
        $_name,
        $_options,
        $_label = null
    ){
        $this->crud->addColumn([
            'name'    => $_name,
            'label'   => ($_label) ? $_label : ucwords(Str::replace('_', ' ', $_name)),
            'type'    => 'editable_select',
            'options' => $_options,
            // Optionals
            'underlined'       => true, // show a dotted line under the editable column for differentiation? default: true
            'save_on_focusout' => true, // if user clicks out, the value should be saved (instead of greyed out)
            'save_on_change'   => true,
            'on_error' => [
                'text_color'          => '#df4759', // set a custom text color instead of the red
                'text_color_duration' => 0, // how long (in miliseconds) should the text stay that color (0 for infinite, aka until page refresh)
                'text_value_undo'     => false, // set text to the original value (user will lose the value that was recently input)
            ],
            'on_success' => [
                'text_color'          => '#42ba96', // set a custom text color instead of the green
                'text_color_duration' => 5000, // how long (in miliseconds) should the text stay that color (0 for infinite, aka until page refresh)
            ],
            'auto_update_row' => true, // update related columns in same row, after the AJAX call?
        ]);
    }

    protected function editable_select_by_id_column($_name, $_options, $_label = null){
        $this->crud->addColumn([
            'name'    => $_name . '_id',
            'label'   => ($_label) ? $_label : ucwords(Str::replace('_', ' ', $_name)),
            'type'    => 'editable_select',
            'options' => array_merge(
                [
                    0 => __('-- Please Select --')
                ],
                $_options,
            ),
            // Optionals
            'underlined'       => true, // show a dotted line under the editable column for differentiation? default: true
            'save_on_focusout' => true, // if user clicks out, the value should be saved (instead of greyed out)
            'save_on_change'   => true,
            'on_error' => [
                'text_color'          => '#df4759', // set a custom text color instead of the red
                'text_color_duration' => 0, // how long (in miliseconds) should the text stay that color (0 for infinite, aka until page refresh)
                'text_value_undo'     => false, // set text to the original value (user will lose the value that was recently input)
            ],
            'on_success' => [
                'text_color'          => '#42ba96', // set a custom text color instead of the green
                'text_color_duration' => 3000, // how long (in miliseconds) should the text stay that color (0 for infinite, aka until page refresh)
            ],
            'auto_update_row' => true, // update related columns in same row, after the AJAX call?
        ]);
    }

    protected function editable_text_column($_name){
        $this->crud->addColumn([
            'name'             => $_name,
            'type'             => 'editable_text',
            'label'            => ucwords(Str::replace('_', ' ', $_name)),

            // Optionals
            'underlined'       => true, // show a dotted line under the editable column for differentiation? default: true
            'min_width'        => '120px', // how wide should the column be?
            'select_on_click'  => false, // select the entire text on click? default: false
            'save_on_focusout' => false, // if user clicks out, the value should be saved (instead of greyed out)
            'on_error' => [
                'text_color'          => '#df4759', // set a custom text color instead of the red
                'text_color_duration' => 0, // how long (in miliseconds) should the text stay that color (0 for infinite, aka until page refresh)
                'text_value_undo'     => false, // set text to the original value (user will lose the value that was recently input)
            ],
            'on_success' => [
                'text_color'          => '#42ba96', // set a custom text color instead of the green
                'text_color_duration' => 3000, // how long (in miliseconds) should the text stay that color (0 for infinite, aka until page refresh)
            ],
            'auto_update_row' => true, // update related columns in same row, after the AJAX call?
        ]);
    }

    protected function relationship_link_column($_name, $_attribute, $_label = null){
        $this->crud->addColumn([
            'name' => $_name,
            'label' => $_label ? $_label : ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'select',
            'attribute' => $_attribute,
            'entity' => $_name,
            'wrapper' => [
                'element' => 'a',
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url($column['entity'] . '/' . $related_key . '/show');
                },
            ],
            'searchLogic' => function ($query, $column, $searchTerm) use($_attribute) {

                $query->orWhereHas($column['entity'], function ($_query) use ($searchTerm, $_attribute) {
                    $_searchTerm = $searchTerm;
                    if ($_attribute == 'full_name'){
                        $_query->orWhereEncrypted('first_name', 'like', '%' . $_searchTerm . '%')
                            ->orWhereEncrypted('middle_name', 'like', '%' . $_searchTerm . '%')
                            ->orWhereEncrypted('last_name', 'like', '%' . $_searchTerm . '%')
                            ->orWhereEncrypted('second_last_name', 'like', '%' . $_searchTerm . '%');
                    }else{
                        $_query->where($_attribute, 'LIKE', '%' . $_searchTerm . '%');
                    }
                });
            },
        ]);
    }

    protected function relationship_list_column($_name, $_attribute, $_label = null){
        $this->crud->addColumn([
            'name' => $_name,
            'label' => ($_label == null)? ucwords(Str::replace('_', ' ', $_name)) : $_label,
            'type' => 'select_multiple',
            'attribute' => $_attribute,
            'entity' => $_name,
//            'wrapper' => [
//                'element' => 'badge',
//                'class' => 'badge-primary',
//            ],
            'searchLogic' => function ($query, $column, $searchTerm) use($_attribute) {
                $query->orWhereHas($column['entity'], function ($q) use ($searchTerm, $_attribute) {
                    $q->where($_attribute, 'LIKE', '%' . $searchTerm . '%');
                });
            },
        ]);
    }
    protected function redirect_filter_column(
        $_name,
        $_attribute,
        $_query,
        $_second_related_key,
        $_label = null
    ){
        $this->crud->addColumn([
            'name' => $_name,
            'label' => ($_label == null)? ucwords(Str::replace('_', ' ', $_name)) : $_label,
            'type' => 'select',
            'attribute' => $_attribute,
            'entity' => $_name,
            'wrapper' => [
                'element' => 'a',
                'href' => function ($crud, $column, $entry, $related_key) use ($_query, $_second_related_key){
                    return backpack_url(
                        $column['entity'] .
                        '?' .
                        $_query . '='
                        .$related_key .
                        '&' .
                        $_second_related_key .
                        '=' .
                        $related_key
                    );
                },
            ],
            'searchLogic' => function ($query, $column, $searchTerm) use($_attribute) {
                $query->orWhereHas($column['entity'], function ($q) use ($searchTerm, $_attribute) {
                    $q->where($_attribute, 'LIKE', '%' . $searchTerm . '%');
                });
            },
        ]);
    }

    protected function text_filter_column(
        $_name,

        $_query,
        $_second_query,
        $_value,
        $_label = null,
    ){
        $this->crud->addColumn([
            'name' => $_name,
            'label' => ($_label == null)? ucwords(Str::replace('_', ' ', $_name)) : $_label,
            'type' => 'text',
            'wrapper' => [
                'element' => 'a',
                'href' => function ($crud, $column, $entry, $related_key) use ($_name, $_query, $_second_query, $_value){
                    return backpack_url(
                        '/' . $this->crud->entity_name .
                        '?' .
                        $_query . '='
                        .$_value->parent_id.
                        '&' .
                        $_second_query .
                        '=' .
                        $_value->parent_id
                    );
                },
            ]
        ]);
    }

    protected function json_column($_name){
        $this->crud->addColumn([
            'name' => $_name,
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'type' => 'json',
        ]);
    }

    protected function thumbnail_image_column($_name): void
    {
        $this->crud->addColumn([
            'type' => 'image',
            'label' => ucwords(Str::replace('_', ' ', $_name)),
            'name' => 'thumbnail_' . $_name,
        ]);
    }

}
