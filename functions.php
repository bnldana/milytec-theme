<?php

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

function my_theme_enqueue_styles() {
    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}

wp_enqueue_style( ‘style’, get_template_directory_uri() . '/style.css' );

add_action('woocommerce_before_mini_cart_contents','add_title_before_mini_cart_contents', 10);

function add_title_before_mini_cart_contents() {
	//echo '<div class="panier-pop-title" style="margin-bottom:20px">OKIDOKI'.__('Actuellement dans le panier','astra-child').'.</div>';
	$freeShipping = 40;
    $totalAmount = WC()->cart->cart_contents_total;
    $totalTaxes = WC()->cart->get_taxes_total();
    $totalCart = $totalAmount + $totalTaxes;
    $progress = $totalCart * 100 / $freeShipping;
    if($progress > 100) {
        $progress = 100;
    }
    //echo '<link rel="stylesheet" href="/wp-content/themes/astra_child/css/w3.css">';
    echo '<h1 class="panier-pop-title" style="margin-bottom:20px">Votre panier</h1>';
    if($progress >= 100) {
        echo '<div class="panier-pop-total" style="margin-bottom:20px;"><svg style="position:relative;top:5px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M10.0007 15.1709L19.1931 5.97852L20.6073 7.39273L10.0007 17.9993L3.63672 11.6354L5.05093 10.2212L10.0007 15.1709Z" fill="#96b125"></path></svg> Livraison gratuite</div>';
    } else {
        echo '<div class="panier-pop-total not-completed" style="margin-bottom:20px">Plus que <b>'. wc_price($freeShipping - $totalCart) .'</b> pour la livraison gratuite !</div>';
        echo '<div class="truck-progress-shipping"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="31" height="31"><path d="M8.96456 18C8.72194 19.6961 7.26324 21 5.5 21C3.73676 21 2.27806 19.6961 2.03544 18H1V6C1 5.44772 1.44772 5 2 5H16C16.5523 5 17 5.44772 17 6V8H20L23 12.0557V18H20.9646C20.7219 19.6961 19.2632 21 17.5 21C15.7368 21 14.2781 19.6961 14.0354 18H8.96456ZM15 7H3V15.0505C3.63526 14.4022 4.52066 14 5.5 14C6.8962 14 8.10145 14.8175 8.66318 16H14.3368C14.5045 15.647 14.7296 15.3264 15 15.0505V7ZM17 13H21V12.715L18.9917 10H17V13ZM17.5 19C18.1531 19 18.7087 18.5826 18.9146 18C18.9699 17.8436 19 17.6753 19 17.5C19 16.6716 18.3284 16 17.5 16C16.6716 16 16 16.6716 16 17.5C16 17.6753 16.0301 17.8436 16.0854 18C16.2913 18.5826 16.8469 19 17.5 19ZM7 17.5C7 16.6716 6.32843 16 5.5 16C4.67157 16 4 16.6716 4 17.5C4 17.6753 4.03008 17.8436 4.08535 18C4.29127 18.5826 4.84689 19 5.5 19C6.15311 19 6.70873 18.5826 6.91465 18C6.96992 17.8436 7 17.6753 7 17.5Z" fill="#96b125"></path></svg></div>';
        echo '<div class="progress-container"><div class="w3-border" style="border:none !important; background-color:#d9d9d9 !important"><div class="w3-grey" style="background-color:#96b125 !important;height:7px;width:'.$progress.'%"></div></div></div>';
        echo '<div class="after-progress-shipping"><div class="container-after-shipping"><span class="ship-price">'.wc_price($freeShipping).'</span><span class="ship-price-after">Livraison gratuite</span></div></div>';
    }
};

add_filter( 'woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 100 );

function my_hide_shipping_when_free_is_available( $rates ) {
	$free = array();
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}
	return ! empty( $free ) ? $free : $rates;
}



