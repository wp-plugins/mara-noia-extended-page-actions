<?php
/**
 * @package Mara_Noia_Extended_Page_Actions
 * @version 1.7
 */
/*
Plugin Name: Mara Noia Extended Page Actions
Plugin URI: http://maranoia.dk/
Description: Create a sibling or child  and get a list of all the siblings of the current page. This will make it faster to add and/or edit pages.
Author: Mara Noia
Version: 1.7
Author URI: http://maranoia.dk/
*/

function create_sibling_child_buttons(){
    //We need an Id to be able to set the future parent or sibling
    if( !isset($_GET['post']) || empty( $_GET['post'] ) || get_post_type( $siblingid ) != 'page') 
            return;

    $action = 'post-new.php?post_type=page&parent_id=';

    $siblingid = $_GET['post'];
    
    $parentid = wp_get_post_parent_id($siblingid);



    $html  = '<div id="major-publishing-actions" style="overflow:hidden; white-space: nowrap;">';
    $html .= '<div id="publishing-action">';
    $html .= '<a class="button-primary" href="' . $action . $siblingid . '">Create new child</a>';
    $html .= '&nbsp;';
    $html .= '<a class="button-primary" href="' . $action . $parentid . '">Create new sibling</a>';
    $html .= '</div>';
    $html .= '</div>';
    echo $html;
}


function list_siblings(){
    //We need an Id to be able to set the future parent or sibling
    if( !isset($_GET['post']) || empty( $_GET['post'] )  || get_post_type( $siblingid ) != 'page') 
            return;

    $action = 'post-new.php?post_type=page&post=';

    $id = $_GET['post'];
    
    $html  = '<div class="misc-pub-section curtime misc-pub-curtime">';
    $html .= '<p><strong>Siblings</strong></p>';
        
    $siblings = wp_list_pages(array(
    'title_li'=>'',
    'child_of'=> wp_get_post_parent_id($id), 
    'walker'=>new MaraNoia_page_sibling_list_walker(),
    'exclude'=> $id,
    'depth'=>1,
    'echo'=>0
    ));
    if($siblings)
    {
        $html .= $siblings;
    }
    else {
        $html .= "This page has no siblings";
    }
    $html .= '</div>';
    echo $html;
}

class MaraNoia_page_sibling_list_walker extends Walker_page {

    function start_el(&$output, $page, $depth, $args, $current_page) {
    
        $action = 'post.php?action=edit&post=';

        if ( $depth )
            $indent = str_repeat("\t", $depth);
        else
            $indent = '';
 
        extract($args, EXTR_SKIP);
        
        $output .= $indent . '<p><a href="' . $action . $page->ID . '">';
        $output .= $indent . $link_before . get_the_title($page->ID) . $link_after;
        $output .= $indent . '</a></p>';      
    }
}

add_action( 'post_submitbox_misc_actions', 'create_sibling_child_buttons' );
add_action( 'post_submitbox_misc_actions', 'list_siblings' );

?>