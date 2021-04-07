<?php

use Molongui\Authorship\Includes\Post;
defined( 'ABSPATH' ) or exit;
add_filter( 'get_the_author_nickname', function()
{
    $post = new Post();
    return $post->filter_name();
});

