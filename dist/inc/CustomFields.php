<?php 

namespace WS_2020\inc;

use WS_2020\inc\core\AbstractController;

class CustomFields extends AbstractController
{
    public function load()
    {
        $this->register_field(self::$options_analytics_config);
    }

    public static $options_analytics_config = [
        'key' => 'group_5e27622fbb89a',
        'title' => 'Configuration',
        'fields' => array(
            array(
                'key' => 'field_5e2769960d7f0',
                'label' => 'Outils',
                'name' => 'analytics',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'row',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5e2769ae0d7f1',
                        'label' => 'Google Analytics',
                        'name' => 'ga',
                        'type' => 'text',
                        'instructions' => 'Identifiant du site',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'ws-options-analytics',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ];
}