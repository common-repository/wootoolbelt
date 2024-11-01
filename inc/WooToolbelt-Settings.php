<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(isset($_POST['WooToolbelt-Settings'])){

    if ( ! wp_verify_nonce( $_POST['WooToolbelt_nonce_settings'], plugin_basename( __FILE__ ) ) )
    {
        die( 'Security check failed' );
    }
    else
    {
        $WooToolbelt_options_arr  =   array();
        foreach ($_POST as $key => $val):
            $key    =   esc_html(sanitize_text_field($key));
            $WooToolbelt_options_arr[$key]    =   esc_html(sanitize_text_field($val));
        endforeach;

        $WooToolbelt_options_arr_serialized   =   serialize($WooToolbelt_options_arr);
        update_option('WooToolbelt_Settings', $WooToolbelt_options_arr_serialized);
        $WooToolbelt_success     =   'Settings update successfully';
    }




}

$WooToolbelt_Settings  = get_option('WooToolbelt_Settings');
if(is_serialized($WooToolbelt_Settings))
{
    $WooToolbelt_Settings  =   unserialize($WooToolbelt_Settings);
}
//print_r($WX_get_pp_options);
$return     =  '';
if(isset($WooToolbelt_error))
{
    $return     .=  '<div class="notice notice-error"><p>'.$WooToolbelt_error.'</p></div>';
}
if(isset($WooToolbelt_success))
{
    $return     .=  '<div class="notice notice-success"><p>'.$WooToolbelt_success.'</p></div>';
}
echo $return;
?>
<table width="100%">
    <tr>
        <td width="70%" valign="top">
            <form action="<?php echo menu_page_url('WooToolbelt',false ); ?>" name="WooToolbelt-setting" id="WooToolbelt-setting" method="post">
                <input type="hidden" name="WooToolbelt-Settings">
                <?php echo wp_nonce_field( plugin_basename( __FILE__ ), 'WooToolbelt_nonce_settings',true,false); ?>
                <div id="accordion">
                    <h3><?php _e('Cart Redirect',WOOTOOLBELT_TEXT_DOMAIN); ?></h3>
                    <div>
                        <table>
                            <tbody>
                            <tr>
                                <td colspan="2"><p><?php _e('Redirect customers to your cart, checkout or other URL after any product has been added to the cart.', WOOTOOLBELT_TEXT_DOMAIN); ?></p></td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="WooToolbelt_cr_activate"><?php _e('Activate', WOOTOOLBELT_TEXT_DOMAIN); ?></label>
                                </th>
                                <td colspan="2">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="WooToolbelt_cr_activate" class="onoffswitch-checkbox" id="WooToolbelt_cr_activate" <?php if(isset($WooToolbelt_Settings['WooToolbelt_cr_activate'])){echo 'checked="checked"';} ?>>
                                        <label class="onoffswitch-label" for="WooToolbelt_cr_activate">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="WooToolbelt_cr_choice"><?php _e('Redirect Choice', WOOTOOLBELT_TEXT_DOMAIN); ?></label>
                                </th>
                                <td>
                                    <select id="WooToolbelt_cr_choice" name="WooToolbelt_cr_choice">
                                        <option value="cart" <?php if(isset($WooToolbelt_Settings['WooToolbelt_cr_choice']) && $WooToolbelt_Settings['WooToolbelt_cr_choice'] == 'cart' ){echo 'selected="selected"';} ?>><?php _e('Cart', WOOTOOLBELT_TEXT_DOMAIN); ?></option>
                                        <option value="checkout" <?php if(isset($WooToolbelt_Settings['WooToolbelt_cr_choice']) && $WooToolbelt_Settings['WooToolbelt_cr_choice'] == 'checkout' ){echo 'selected="selected"';} ?>><?php _e('Checkout', WOOTOOLBELT_TEXT_DOMAIN); ?></option>
                                        <option value="url" <?php if(isset($WooToolbelt_Settings['WooToolbelt_cr_choice']) && $WooToolbelt_Settings['WooToolbelt_cr_choice'] == 'url' ){echo 'selected="selected"';} ?>><?php _e('Other Url', WOOTOOLBELT_TEXT_DOMAIN); ?></option>
                                    </select>
                                </td>
                            </tr>

                            <tr class="other-url-tr" <?php if(isset($WooToolbelt_Settings['WooToolbelt_cr_choice']) && $WooToolbelt_Settings['WooToolbelt_cr_choice'] == 'url'): ?>style="display: table-row;" <?php endif; ?>>
                                <th scope="row">
                                    <label id="WooToolbelt_cr_url"><?php _e('Other Url', WOOTOOLBELT_TEXT_DOMAIN); ?></label>
                                </th>
                                <td><input type="url" name="WooToolbelt_cr_url" id="WooToolbelt_cr_url" value="<?php if(isset($WooToolbelt_Settings['WooToolbelt_cr_url'])){echo $WooToolbelt_Settings['WooToolbelt_cr_url'];} ?>"> </td>
                            </tr>

                            <tr>
                                <td><?php echo get_submit_button( 'Save' ); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <h3><?php _e('Button Text',WOOTOOLBELT_TEXT_DOMAIN); ?></h3>
                    <div>
                        <table>
                            <tbody>
                            <tr>
                                <td colspan="2"><p><?php _e('Change the default text shown on your Buy Now buttons to anything that you like.', WOOTOOLBELT_TEXT_DOMAIN); ?></p></td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="WooToolbelt_bt_activate"><?php _e('Activate', WOOTOOLBELT_TEXT_DOMAIN); ?></label>
                                </th>
                                <td colspan="2">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="WooToolbelt_bt_activate" class="onoffswitch-checkbox" id="WooToolbelt_bt_activate" <?php if(isset($WooToolbelt_Settings['WooToolbelt_bt_activate'])){echo 'checked="checked"';} ?>>
                                        <label class="onoffswitch-label" for="WooToolbelt_bt_activate">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label id="WooToolbelt_prod_desc_btn"><?php _e('Product Description Buy Now Button', WOOTOOLBELT_TEXT_DOMAIN); ?></label>
                                </th>
                                <td><input type="text" name="WooToolbelt_prod_desc_btn" id="WooToolbelt_prod_desc_btn" value="<?php if(isset($WooToolbelt_Settings['WooToolbelt_prod_desc_btn'])){echo $WooToolbelt_Settings['WooToolbelt_prod_desc_btn'];} ?>"> </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label id="WooToolbelt_other_btn"><?php _e('Other Buy Now Buttons', WOOTOOLBELT_TEXT_DOMAIN); ?></label>
                                </th>
                                <td><input type="text" name="WooToolbelt_other_btn" id="WooToolbelt_other_btn" value="<?php if(isset($WooToolbelt_Settings['WooToolbelt_other_btn'])){echo $WooToolbelt_Settings['WooToolbelt_other_btn'];} ?>"> </td>
                            </tr>
                            <tr>
                                <td><?php echo get_submit_button( 'Save' ); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <h3><?php _e('Cart Total (in menu)',WOOTOOLBELT_TEXT_DOMAIN); ?></h3>
                    <div>
                        <table>
                            <tbody>
                            <tr>
                                <td colspan="2"><p><?php _e(' Display the total of your users cart within your site menu.', WOOTOOLBELT_TEXT_DOMAIN); ?></p></td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="WooToolbelt_ct_activate"><?php _e('Activate', WOOTOOLBELT_TEXT_DOMAIN); ?></label>
                                </th>
                                <td colspan="2">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="WooToolbelt_ct_activate" class="onoffswitch-checkbox" id="WooToolbelt_ct_activate" <?php if(isset($WooToolbelt_Settings['WooToolbelt_ct_activate'])){echo 'checked="checked"';} ?>>
                                        <label class="onoffswitch-label" for="WooToolbelt_ct_activate">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo get_submit_button( 'Save' ); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <h3><?php _e('Disable Related Products',WOOTOOLBELT_TEXT_DOMAIN); ?></h3>
                    <div>
                        <table>
                            <tbody>
                            <tr>
                                <td colspan="2"><p><?php _e('Disable related products from displaying within a product description.', WOOTOOLBELT_TEXT_DOMAIN); ?></p></td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="WooToolbelt_drp_activate"><?php _e('Activate', WOOTOOLBELT_TEXT_DOMAIN); ?></label>
                                </th>
                                <td colspan="2">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="WooToolbelt_drp_activate" class="onoffswitch-checkbox" id="WooToolbelt_drp_activate" <?php if(isset($WooToolbelt_Settings['WooToolbelt_drp_activate'])){echo 'checked="checked"';} ?>>
                                        <label class="onoffswitch-label" for="WooToolbelt_drp_activate">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo get_submit_button( 'Save' ); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <h3><?php _e('Display Ordered Items',WOOTOOLBELT_TEXT_DOMAIN); ?></h3>
                    <div>
                        <table>
                            <tbody>
                            <tr>
                                <td colspan="2"><p><?php _e('Create a column within your WooCommerce Orders page which will clearly display the product that your customer has ordered.', WOOTOOLBELT_TEXT_DOMAIN); ?></p></td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="WooToolbelt_doi_activate"><?php _e('Activate', WOOTOOLBELT_TEXT_DOMAIN); ?></label>
                                </th>
                                <td colspan="2">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="WooToolbelt_doi_activate" class="onoffswitch-checkbox" id="WooToolbelt_doi_activate" <?php if(isset($WooToolbelt_Settings['WooToolbelt_doi_activate'])){echo 'checked="checked"';} ?>>
                                        <label class="onoffswitch-label" for="WooToolbelt_doi_activate">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo get_submit_button( 'Save' ); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <h3><?php _e('Unlink Product Image',WOOTOOLBELT_TEXT_DOMAIN); ?></h3>
                    <div>
                        <table>
                            <tbody>
                            <tr>
                                <td colspan="2"><p><?php _e('Remove the link from your product images, so that only the product title and buttons contain a link.', WOOTOOLBELT_TEXT_DOMAIN); ?></p></td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="WooToolbelt_upi_activate"><?php _e('Activate', WOOTOOLBELT_TEXT_DOMAIN); ?></label>
                                </th>
                                <td colspan="2">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="WooToolbelt_upi_activate" class="onoffswitch-checkbox" id="WooToolbelt_upi_activate" <?php if(isset($WooToolbelt_Settings['WooToolbelt_upi_activate'])){echo 'checked="checked"';} ?>>
                                        <label class="onoffswitch-label" for="WooToolbelt_upi_activate">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo get_submit_button( 'Save' ); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </td>
        <td width="30%" valign="top">
            <div class="WooToolbelt-header-feed">
                <p><strong>Thank you for using WooToolbelt</strong></p>
                <p>WooToolbelt is created by <a target="_blank" href="https://watwebdev.com/?utm_source=wootoolbelt&utm_medium=sidebar&utm_campaign=wootoolbelt" title="Watkinson Website Development" target="_blank">WatWebDev.com</a> and <a href="https://perfectsynergie.com/?utm_source=wootoolbelt&utm_medium=sidebar&utm_campaign=wootoolbelt" title="Watkinson Website Development" target="_blank">PerfectSynergie.com</a></p>
                <p><a target="_blank" href="https://watwebdev.com/subscribe/?utm_source=wootoolbelt&utm_medium=sidebar&utm_campaign=wootoolbelt" title="Subscribe to our newsletter" target="_blank">Subscribe to our newsletter</a> for the latest updates, announcements and discounts</p>
                <h2>Our Other Plugins</h2>
                <a target="_blank" href="https://watwebdev.com/wooclick/?utm_source=wootoolbelt&utm_medium=sidebar&utm_campaign=wootoolbelt"><img src="<?php echo plugins_url( '/assets/WooClick-Vendor-v3-box.jpg', dirname(__FILE__) ); ?>" alt="Our Other Plugins"></a>
                
            </div>
        </td>
    </tr>
</table>


