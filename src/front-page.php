<?php
/*
* Template Name: Home Template
*/
$context = Timber::context();
$page = new Timber\Post();


/* -------------------------- */
/* --- Pass datas to view --- */
/* -------------------------- */
$context['page'] = $page;
$context['test'] = 'home';

Timber::render('home.twig', $context);