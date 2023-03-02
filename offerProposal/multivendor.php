<?php
// show multvendor
function global_notice_meta_box() {

    add_meta_box(
        'multi_quota_vendor',
        __( 'Multi Quota Vendor', 'sitepoint' ),
        'global_notice_meta_box_callback',
        'product'
    );
}

add_action( 'add_meta_boxes', 'global_notice_meta_box' );



function global_notice_meta_box_callback( $post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'global_notice_nonce', 'global_notice_nonce' );
global $post;
    
    $sellers = (dokan_get_sellers());
    echo '<div style="margin:10px;" >
                <button class="button" type="button" onclick="check_vendor()">check all</button>
                <button class="button" type="button" onclick="uncheck_vendor()">uncheck all</button>
            </div>
            <script>
                function check_vendor(){ jQuery(".multi_vendor").prop("checked", true); return false; }
                function uncheck_vendor(){ jQuery(".multi_vendor").prop("checked", false); return false; }
            </script>';
            
            $checked_arr = get_post_meta($post->ID,'multi_vendor_request');
            //var_dump($checked_arr);
    foreach($sellers['users'] as $seller){
        $checked = in_array($seller->ID,$checked_arr)?' checked="checked" ' :'' ;
        echo ' <span style="margin:0 10px ;" >
        <input type="hidden" name="multi_vendor['.$seller->ID.']" value="0">
            <input type="checkbox" class="multi_vendor" '.$checked.' name="multi_vendor['.$seller->ID.']" value="1" style="margen:010px;">'.$seller->user_login.'</span>';    
    }
    
}

add_action( 'save_post_product', 'save_global_notice_meta_box_data' );
function save_global_notice_meta_box_data($post_id){
    
    $checked_arr = get_post_meta($post_id,'multi_vendor_request');
    $sellers = (dokan_get_sellers());
    if(isset($_POST['multi_vendor'])){
        foreach($sellers['users'] as $seller){
            delete_post_meta($post_id,'multi_vendor_request',$seller->ID);
            delete_user_meta($seller->ID,'multi_vendor_request',$post_id);
            
            if (isset($_POST['multi_vendor'][$seller->ID]) && $_POST['multi_vendor'][$seller->ID] == 1 ){
                
                add_post_meta($post_id,'multi_vendor_request',$seller->ID);
                add_user_meta($seller->ID,'multi_vendor_request',$post_id);
            } 
        }    
    }
    
    //die ; 
    
}

add_filter ( 'woocommerce_account_menu_items', 'xrgty37_new_menu_link', 40 );
function xrgty37_new_menu_link( $menu_links ){
    
  
    $menu_links = array_slice( $menu_links, 0, 2, true ) 
    + array( 'request-quota' => 'Request Quota' )
    + array_slice( $menu_links, 2, NULL, true );
 
    if ( current_user_can('administrator')) {
        $menu_links = array_slice( $menu_links, 0, 2, true ) 
        + array( 'admin-users' => 'Users' )
        + array_slice( $menu_links, 2, NULL, true );
    }
  
    return $menu_links;

}
add_action( 'init', 'xrgty37_add_endpoint' );
function xrgty37_add_endpoint() {    // Check WP_Rewrite
    add_rewrite_endpoint( 'request-quota', EP_PAGES );
    add_rewrite_endpoint( 'admin-users', EP_PAGES );

}
add_action( 'woocommerce_account_request-quota_endpoint', 'xrgty37_my_account_endpoint_content' );
function xrgty37_my_account_endpoint_content() {


    // Content for new page
    require_once( plugin_dir_path( __FILE__ ) . 'account_request_quota.php');

}
add_action( 'woocommerce_account_admin-users_endpoint', 'xrgty37_my_account_admin_users_endpoint_content' );
function xrgty37_my_account_admin_users_endpoint_content() {

    // Content for new page
    require_once( plugin_dir_path( __FILE__ ) . 'showing/users/admin_users.php');

}