<?php
/**
 * plugin name: Payment GW selector
 * Author: Amila Upathissa
 */

add_action('init', 'coder_custom_user_role');

function coder_custom_user_role()
{
    global $wp_roles;
    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();

    $customer = $wp_roles->get_role('customer');
    $wp_roles->add_role('verified_customer', 'Verified Customer', $customer->capabilities);
}

//--- Filter for remove any payment gateway as per the user role selected --
add_filter('woocommerce_available_payment_gateways','coder_filter_gateways',1);
function coder_filter_gateways($gateways){
    global $woocommerce, $current_user;

    if ( is_user_logged_in() ) {        
        $userRole = implode(',',$current_user->roles);
        if($userRole == 'customer'){
            //-- Remove casho on delivery if following user have logged in
            unset($gateways['bacs']);        
        }   
    }else{
        //-- Hide COD if user not logged in 
        unset($gateways['bacs']);
    }           
 return $gateways;
}
