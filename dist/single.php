<?php
    /*
    * Template Name: Single post
    */

    $context = Timber::get_context();
    $post = new Timber\Post();

    $post->terms = get_the_terms( $post->id, 'category' );
    $post->date_published = get_the_date('d F Y', $post->ID);
    $post->thumbnail = get_the_post_thumbnail_url($post->ID);
    

    /* -------------------------- */
    /* ---- Parsing content ----- */
    /* -------------------------- */
    $output = '';
    $index = 0;
    $blocks = parse_blocks($post->post_content);

    foreach ( $blocks as $block ) {
        $tempOutput = render_block( $block );
        if( $block['blockName'] == "core/heading") {
            if($index != 0){
                $tempOutput = '</div><div class="content-section">'.$tempOutput;
            }
        }
        if( $block['blockName'] == "core/embed") {
            $not_embedded =  $block['attrs']['url'];
            $tempOutput = str_replace( $not_embedded, wp_oembed_get($not_embedded), $tempOutput );
        }
        
        $index ++;
        $output .= $tempOutput;
    }
    
    $post->content_output = $output;


    /* -------------------------- */
    /* ----- Posts associés ----- */
    /* -------------------------- */
    $temp_terms = wp_get_post_terms($post->id, 'category');
    foreach ($temp_terms as $key => $term) {
       $post_terms[] = $term->slug;
    }
    //Posts
    $posts_associes = new WP_Query(array(
        'post_type'      => 'post',
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $post_terms,
            ),
        ),
        'post__not_in' => array($post->id),
        'posts_per_page' => 10,
        'post_status'  => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ));

    foreach ($posts_associes->posts as $key => $art) {
        $art->thumbnail = get_the_post_thumbnail_url($art->ID);
        $art->link = get_permalink($art->ID);
        $art->date_published = get_the_date('d F Y', $art->ID);
        //$art->custom_field = get_field('curstom_field', $art->ID);
    }

    /* -------------------------- */
    /* --- Pass datas to view --- */
    /* -------------------------- */
    $context['page'] = $post;
    $context['posts_associes'] = $posts_associes->posts;
    
    Timber::render( 'actus-single.twig', $context );
?>