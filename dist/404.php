<?php

$context = Timber::context();
$page = new Timber\Post();



/* -------------------------- */
/* --- Pass datas to view --- */
/* -------------------------- */
$context['page'] = $page;

Timber::render( '404.twig', $context );