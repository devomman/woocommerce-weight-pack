<?php
/**
 * Plugin Name: Woocommerce Weight Pack
 * Plugin URI: https://github.com/omman/woocommerce-weight-pack
 * Description: Woocommerce Weight Pack For KG, Support For All the pages Ex, Shop, Cart, Checkout, Review, My Account, Email.
 * Version: 1.0
 * Author: Muhmmad Omman Parvez (devomman)
 * Author URI: https://github.com/omman/
 */


// Added By Omman - Start Here - 4
// Set Per KG Or Box Create a Custom field from "Add New Custom Field:" "unit_price" = Custom Price For this Level
/**
* Displays the custom text field input field in the WooCommerce product data meta box
*/
function cfwc_up_kg_create_custom_field() {
$args = array(
'id' => 'unit_price',
'label' => __( 'Unit Price For /Kg', 'cfwc_up_kg' ),
'class' => 'cfwc-up-kg-custom-field',
'desc_tip' => true,
'description' => __( 'Enter the Product Unit Price For /Kg ', 'ctwc_up_kg' ),
);
woocommerce_wp_text_input( $args );
}
add_action( 'woocommerce_product_options_general_product_data', 'cfwc_up_kg_create_custom_field' );
/**
* Saves the custom field data to product meta data
*/
function cfwc_up_kg_save_custom_field( $post_id ) {
$product = wc_get_product( $post_id );
$title = isset( $_POST['unit_price'] ) ? $_POST['unit_price'] : '';
$product->update_meta_data( 'unit_price', sanitize_text_field( $title ) );
$product->save();
}
add_action( 'woocommerce_process_product_meta', 'cfwc_up_kg_save_custom_field' );

// Change the shop / product prices if a unit_price is set
function qtf_change_product_html( $price_html, $product ) {
  $unit_price = get_post_meta( $product->id, 'unit_price', true );
  if ( ! empty( $unit_price ) ) {
    $price_html = '<span class="amount">' . wc_price( $unit_price ) . ' /Kg </span>'; 
  }
  return $price_html;
}
add_filter( 'woocommerce_get_price_html', 'qtf_change_product_html', 10, 2 );
// For Variationn Price Not Test If Need This Code Enable
// add_filter( 'woocommerce_get_variation_price_html', 'qtf_change_product_html', 10, 2 );

// Change the cart prices if a unit_price is set
function qtf_change_product_price_cart( $price, $cart_item, $cart_item_key ) {
  $unit_price = get_post_meta( $cart_item['product_id'], 'unit_price', true );
  if ( ! empty( $unit_price ) ) {
    $price = wc_price( $unit_price ) . ' /Kg '; 
  }
  return $price;
} 
add_filter( 'woocommerce_cart_item_price', 'qtf_change_product_price_cart', 10, 3 );

// Change the checkput prices with $unit_price kg amount
function qtf_change_checkout_review ( $quantity, $cart_item, $cart_item_key ) {
  $unit_price = get_post_meta( $cart_item['product_id'], 'unit_price', true );
  if ( ! empty( $unit_price ) ) {
	$unit_price = ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . ' Kg </strong>';
	return $unit_price;
  }
 return $quantity;
}
add_filter( 'woocommerce_checkout_cart_item_quantity', 'qtf_change_checkout_review', 10, 3 );


// Change the My account woocommerce_order_item_quantity_html 
function qtf_change_filter_woocommerce_order_item_quantity_html( $item_qty, $item ) { 
  $unit_price = get_post_meta( $item['product_id'], 'unit_price', true );
  if ( ! empty( $unit_price ) ) {
  $unit_price = ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['quantity'] ) . ' Kg </strong>';
  return $unit_price;
  }
  return $item_qty; 
} 
add_filter( 'woocommerce_order_item_quantity_html', 'qtf_change_filter_woocommerce_order_item_quantity_html', 10, 2 ); 


// Change the Email Order woocommerce_email_order_item_quantity callback 
function qtf_change_filter_woocommerce_email_order_item_quantity( $item_qty, $item ) { 
  $unit_price = get_post_meta( $item['product_id'], 'unit_price', true );
  if ( ! empty( $unit_price ) ) {
  $unit_price = ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['quantity'] ) . ' Kg </strong>';
  return $unit_price;
  }
  return $item_qty; 
}
add_filter( 'woocommerce_email_order_item_quantity', 'qtf_change_filter_woocommerce_email_order_item_quantity', 10, 2 ); 


// Translation - Add Wpml Lock Field and select copy in custom field type so that it synchornize
add_filter( 'wcml_js_lock_fields_ids', 'add_js_lock_fields_unit_price' );
function add_js_lock_fields_unit_price( $ids ){
    $ids[] = 'unit_price';
    return $ids;
}

// Added By Omman - End Here - 4
// ===================================================================
