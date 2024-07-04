<?php
/*
* Template Name: A propos Template
*/
$context = Timber::context();
$page = new Timber\Post();


/* -------------------------- */
/* --- Pass datas to view --- */
/* -------------------------- */
$context['page'] = $page;
$context['test'] = 'home';

Timber::render('a-propos.twig', $context);