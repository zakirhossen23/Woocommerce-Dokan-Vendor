<!-- products  -->

<div>
   
</div>

<?php
    $user = wp_get_current_user();
    $role = array_keys($user->caps)[0];
    // $roles = ( array ) $user->roles;

?>
<?php

if ( current_user_can('administrator')) 
    require_once( plugin_dir_path( __FILE__ ) . 'showing/admin_request_quota.php');
    
else if( $role == 'seller')
    require_once( plugin_dir_path( __FILE__ ) . 'showing/vendor_request_quota.php');

else require_once( plugin_dir_path( __FILE__ ) . 'showing/customer_request_quota.php');



/*
for ($i=0;$i<50;$i++){
    $post_author = rand(4,8);
$post_id = wp_insert_post(array (
                    'post_type' => 'rfq_request',
                    'post_title' => 'request for qouta',
                    'post_content' => '',
                    'post_status' => 'publish',
                    'comment_status' => 'closed',   // if you prefer
                    'ping_status' => 'closed',      // if you prefer
                    'post_author'=> $post_author,
                    
                ));
                
if ($post_id) {
    // insert post meta
    add_post_meta($post_id, 'quantity', rand(1,100));
    add_post_meta($post_id, 'company', 'com_ '.generateRandomString(5));
    add_post_meta($post_id, 'address', 'add_ '.generateRandomString(8).' '.generateRandomString(8).' '.rand(1,100));
    add_post_meta($post_id, 'notes', 'note_ '.generateRandomString(24));
    add_post_meta($post_id, 'product', rand(30,40));
    add_post_meta($post_id, 'status', 'Waiting');
}    
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ';
    $charactersLength = strlen($characters);
    $randomString = '';
    $rand = rand(4,7);
    for ($i = 0; $i < $length; $i++) {
        if($i==$rand ) $randomString.=' ';
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}*/
