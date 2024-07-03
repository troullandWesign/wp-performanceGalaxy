<?php 

namespace WS_2020\inc;

use WS_2020\inc\core\AbstractController;

class CustomPostTypes extends AbstractController
{
    public function __construct()
    {
        //$this->register_post_type('badges', [$this, 'badges']);
    }

	public static function badges()
	{
        return [
            'labels'             => array(
                'name'               => _x('Badges', 'post type general name'),
                'singular_name'      => _x('Badge', 'post type singular name'),
                'add_new'            => _x('Ajouter un badge', 'book'),
                'add_new_item'       => __('Ajouter un nouveau badge'),
                'edit_item'          => __('Editer un badge'),
                'new_item'           => __('Nouveau badge'),
                'all_items'          => __('Tous les badges'),
                'view_item'          => __('Voir le badge'),
                'search_items'       => __('Rechercher un badge'),
                'not_found'          => __('Aucun badge trouvé'),
                'not_found_in_trash' => __('Aucun badge trouvé dans la corbeille'),
                'menu_name'          => __('Badges')
            ),
            'public'             => true,
            'publicly_queryable' => false,
            'show_in_rest'       => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-image-filter',
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
            'rewrite'            => false
        ];
    }
}