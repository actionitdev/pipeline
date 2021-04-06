<?php
defined( 'ABSPATH' ) or exit;

add_filter( 'wpml_translatable_user_meta_fields', 'authorship_add_user_meta_fields_to_wpml' );
function authorship_add_user_meta_fields_to_wpml( $user_meta_fields )
{
    $user_meta_fields[] = 'user_url'; // from users table
    $user_meta_fields[] = 'molongui_author_phone';
    $user_meta_fields[] = 'molongui_author_job';
    $user_meta_fields[] = 'molongui_author_company';
    $user_meta_fields[] = 'molongui_author_company_link';
    $user_meta_fields[] = 'molongui_author_short_bio';

    return $user_meta_fields;
}


