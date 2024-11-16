<?php

/**
 * Sözleşmeler
 *
 * @author            Emre Güler
 * @copyright         Copyright (c) 2024, Emre Güler
 * @license           GPL v3 or later
 *
 * @wordpress-plugin
 * Plugin Name:       Sözleşmeler
 * Plugin URI:        https://eguler.net/woocommerce-sozlesmeler-eklentisi/
 * Description:       Woocommerce sitenize mesafeli satış sözleşmesi ve ön bilgilendirme formu gibi yasal metinleri ekleyebileceğiniz sözleşmeler eklentisi
 * Version:           2.6.1
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Requires Plugins:	woocommerce
 * Tested up to:	  	6.8
 * Author:            Emre Güler
 * Author URI:        https://eguler.net
 * Text Domain:       sozlesmeler
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 *
 *
 * WC tested up to: 9.4.1
 *
 */

if (!defined('ABSPATH')) {
	exit;
}

define('WCTR_PATH', plugin_dir_path(__FILE__));
define('WCTR_URL', plugin_dir_url(__FILE__));
define('WCTR_VER', '2.6.1');

function woocontracts_activated()
{
	add_option('woocontracts_1a', '1');
	add_option('woocontracts_2a', '1');
	add_option('woocontracts_3a', '1');
	add_option('woocontracts_baslik', 'Sözleşmeler');
	add_option('woocontracts_1_baslik', 'Sözleşme 1');
	add_option('woocontracts_2_baslik', 'Sözleşme 2');
	add_option('woocontracts_3_baslik', 'Sözleşme 3');
	add_option('woocontracts_1_yaz', 'Birinci sözleşmenin içeriği...');
	add_option('woocontracts_2_yaz', 'İkinci sözleşmenin içeriği...');
	add_option('woocontracts_3_yaz', 'Üçüncü sözleşmenin içeriği...');
}
register_activation_hook(__FILE__, 'woocontracts_activated');

function woocontracts_uninstalled()
{
	delete_option('woocontracts_1a');
	delete_option('woocontracts_2a');
	delete_option('woocontracts_3a');
	delete_option('woocontracts_baslik');
	delete_option('woocontracts_1_baslik');
	delete_option('woocontracts_2_baslik');
	delete_option('woocontracts_3_baslik');
	delete_option('woocontracts_1_yaz');
	delete_option('woocontracts_2_yaz');
	delete_option('woocontracts_3_yaz');
}
register_uninstall_hook(__FILE__, 'woocontracts_uninstalled');

if (! function_exists('is_plugin_active_for_network')) {
	require_once(ABSPATH . '/wp-admin/includes/plugin.php');
}
$is_wc_active = false;
// multisite & woocontracts locally activated - WC can be network or locally activated
if (is_multisite() && is_plugin_active_for_network(plugin_basename(__FILE__))) {
	// this plugin is network activated - Woo must be network activated
	$is_wc_active = is_plugin_active_for_network('woocommerce/woocommerce.php') ? true : false;
	// this plugin runs on a single site | is locally activated
} else {
	$is_wc_active =  is_plugin_active('woocommerce/woocommerce.php') ? true : false;
}
if ($is_wc_active) {
	function woocontracts_js()
	{
		if (!wp_script_is('jquery', 'done')) {
			wp_enqueue_script('jquery');
		}
		if (is_checkout() && !is_wc_endpoint_url()) {
			wp_enqueue_script('woocontracts_js', plugins_url('/js/woocontracts.js', __FILE__), array(), WCTR_VER, array('strategy' => 'defer', 'in_footer' => true));
		}
		wp_enqueue_style('woocontracts_css', plugins_url('/css/stil.css', __FILE__), array(), filemtime(WCTR_PATH . 'css/stil.css'));
	}
	add_action('wp_enqueue_scripts', 'woocontracts_js');

	function woocontractsadmin_js()
	{
		if (!wp_script_is('jquery', 'done')) {
			wp_enqueue_script('jquery');
		}
		wp_enqueue_script('woocontractsadmin_js', plugins_url('/js/woocontractsadmin.js', __FILE__), array(), WCTR_VER, array('strategy' => 'defer', 'in_footer' => true));
		wp_enqueue_script('woocontnotify_js', plugins_url('/js/notify.min.js', __FILE__), array(), WCTR_VER, array('strategy' => 'defer', 'in_footer' => true));
		wp_enqueue_style('woocontracts_admin_css', plugins_url('/css/adminstil.css', __FILE__), array(), filemtime(WCTR_PATH . 'css/adminstil.css'));
	}
	add_action('admin_enqueue_scripts', 'woocontractsadmin_js');

	function woocontracts_kisa_kodlari_donustur($text)
	{
		$vergitutar = WC()->cart->get_total_tax();
		$kargotutar = WC()->cart->get_cart_shipping_total();
		$sepettutar = WC()->cart->get_cart_total();
		$toplamtutar = WC()->cart->get_total();
		$search = array("[fatura-isim]", "[fatura-firma]", "[fatura-adres]", "[tc-kimlik-no]", "[vergi-dairesi]", "[vergi-numarasi]", "[kargo-isim]", "[kargo-firma]", "[kargo-adres]", "[telefon]", "[eposta]", "[tarih]", "[urun-listesi]", "[toplam-tutar]", "[kargo-tutar]", "[vergi-tutar]", "[sepet-tutar]", "[odeme-yontemi]");
		$replace = array("<span class=musteriad></span> <span class=musterisoyad></span>", "<span class=\"musterifirma\"></span>", "<span class=\"musteriadres1\"></span> <span class=\"musteriadres2\"></span> <span class=\"musteriposta\"></span> <span class=\"musteriilce\"></span> <span class=\"musteriil\"></span>", "<span class=\"tckimlik\"></span>", "<span class=\"vergidairesi\"></span>", "<span class=\"vergino\"></span>", "<span class=kargoad></span> <span class=kargosoyad></span>", "<span class=\"kargofirma\"></span>", "<span class=\"kargoadres1\"></span> <span class=\"kargoadres2\"></span> <span class=\"kargoposta\"></span> <span class=\"kargoilce\"></span> <span class=\"kargoil\"></span>", "<span class=\"musteritel\"></span>", "<span class=\"musterieposta\"></span>", "<span class=\"wooctarih\"></span>", "<div class=\"urunlistesi\"></div>", $toplamtutar, $kargotutar, $vergitutar, $sepettutar, "<span style=\"font-size:smaller;font-style:italic;\">Sipariş tamamlandığında sözleşmeye eklenecektir.</span>");
		$new_text = str_replace($search, $replace, $text);
		return $new_text;
	}

	function woocontracts_isTcKimlik($tc)
	{
		if (strlen($tc) != 11 || !ctype_digit($tc)) {
			return false;
		}
		if ($tc[0] == '0') {
			return false;
		}
		$plus = ($tc[0] + $tc[2] + $tc[4] + $tc[6] + $tc[8]) * 7;
		$minus = $plus - ($tc[1] + $tc[3] + $tc[5] + $tc[7]);
		$mod = $minus % 10;
		if ($mod != $tc[9]) {
			return false;
		}
		$all = 0;
		for ($i = 0; $i < 10; $i++) {
			$all += intval($tc[$i]);
		}
		if ($all % 10 != intval($tc[10])) {
			return false;
		}
		return true;
	}

	function woocontracts_action_links($links)
	{
		$links = array_merge(array('<a href="' . esc_url(admin_url('admin.php?page=woocontracts-ayarlar')) . '">' . esc_html__('Ayarlar', 'sozlesmeler') . '</a>', '<a href="' . esc_url('https://shopier.com/19132664') . '" target="_blank" style="color:green;">' . esc_html__('Yükselt', 'sozlesmeler') . '</a>',), $links);
		return $links;
	}
	add_action('plugin_action_links_' . plugin_basename(__FILE__), 'woocontracts_action_links');

	function woocontracts_terms_fields($checkout)
	{
		if (!is_checkout()) {
			return;
		}
		$woocontracts1a = get_option("woocontracts_1a");
		$woocontracts2a = get_option("woocontracts_2a");
		$woocontracts3a = get_option("woocontracts_3a");
		$woocontractsbaslik = (!empty(get_option("woocontracts_baslik")) ? stripslashes(get_option("woocontracts_baslik")) : "Sözleşmeler");
		$woocontracts1baslik = (!empty(get_option("woocontracts_1_baslik")) ? stripslashes(get_option("woocontracts_1_baslik")) : "Sözleşme 1");
		$woocontracts2baslik = (!empty(get_option("woocontracts_2_baslik")) ? stripslashes(get_option("woocontracts_2_baslik")) : "Sözleşme 2");
		$woocontracts3baslik = (!empty(get_option("woocontracts_3_baslik")) ? stripslashes(get_option("woocontracts_3_baslik")) : "Sözleşme 3");
		$woocontracts1 = (!empty(get_option("woocontracts_1_yaz")) ? stripslashes(get_option("woocontracts_1_yaz")) : "Bu alanları Sözleşmeler kısmından düzenleyebilirsiniz.");
		$woocontracts2 = (!empty(get_option("woocontracts_2_yaz")) ? stripslashes(get_option("woocontracts_2_yaz")) : "Bu alanları Sözleşmeler kısmından düzenleyebilirsiniz.");
		$woocontracts3 = (!empty(get_option("woocontracts_3_yaz")) ? stripslashes(get_option("woocontracts_3_yaz")) : "Bu alanları Sözleşmeler kısmından düzenleyebilirsiniz.");
		$woocontracts1yaz = wp_kses_post(nl2br(woocontracts_kisa_kodlari_donustur($woocontracts1)));
		$woocontracts2yaz = wp_kses_post(nl2br(woocontracts_kisa_kodlari_donustur($woocontracts2)));
		$woocontracts3yaz = wp_kses_post(nl2br(woocontracts_kisa_kodlari_donustur($woocontracts3)));
		echo '<div id="sozlesmeler"><h2>';
		echo wp_kses_post($woocontractsbaslik);
		echo '</h2>';
		if (get_option("woocontracts_1a") == 1) {
			echo '<div style="width: 100% !important;"><h4>';
			echo wp_kses_post($woocontracts1baslik);
			echo '</h4><div id="woocontracts1" style="width: 100% !important;padding: 6px 40px 10px 8px;height: 110px;background-color:#F4F4F4;overflow:auto;font-size:small;">';
			echo wp_kses_post($woocontracts1yaz);
			echo '</div></div>';
		}
		if (get_option("woocontracts_2a") == 1) {
			echo '<div style="width: 100% !important;"><h4>';
			echo wp_kses_post($woocontracts2baslik);
			echo '</h4><div id="woocontracts2" style="width: 100% !important;padding: 6px 40px 10px 8px;height: 110px;background-color:#F4F4F4;overflow:auto;font-size:small;">';
			echo wp_kses_post($woocontracts2yaz);
			echo '</div></div>';
		}
		if (get_option("woocontracts_3a") == 1) {
			echo '<div style="width: 100% !important;"><h4>';
			echo wp_kses_post($woocontracts3baslik);
			echo '</h4><div id="woocontracts3" style="width: 100% !important;padding: 6px 40px 10px 8px;height: 110px;background-color:#F4F4F4;overflow:auto;font-size:small;">';
			echo wp_kses_post($woocontracts3yaz);
			echo '</div></div>';
		}
		echo '</div><div id="urunListesi" class="tg-wrap" style="display:none !important;"><table class="tg"><tr><th class="tg-hgcj">';
		esc_html_e('Cinsi/Türü', 'sozlesmeler');
		echo '</th><th class="tg-hgcj">';
		esc_html_e('Miktarı', 'sozlesmeler');
		echo '</th><th class="tg-hgcj">';
		esc_html_e('Birim Fiyatı', 'sozlesmeler');
		echo '</th><th class="tg-hgcj">';
		esc_html_e('Toplam Satış Bedeli', 'sozlesmeler');
		echo '</th></tr>';
		foreach (WC()->cart->get_cart() as $cart_item) {
			$item_name = $cart_item['data']->get_title();
			$quantity = $cart_item['quantity'];
			$price = $cart_item['data']->get_price();
			$totalprice = $price * $quantity;
			echo '<tr><td class="tg-s6z2">';
			echo esc_html($item_name);
			echo '</td><td class="tg-s6z2">';
			echo esc_html($quantity);
			echo '</td><td class="tg-s6z2">' . esc_html(get_woocommerce_currency_symbol()) . esc_html(number_format($price, 2)) . '</td><td class="tg-s6z2">' . esc_html(get_woocommerce_currency_symbol()) . esc_html(number_format($totalprice, 2)) . '</td></tr>';
		}
		echo '</table></div>';
	}
	add_action('woocommerce_checkout_terms_and_conditions', 'woocontracts_terms_fields');

	function woocontracts_checkout_fields($fields)
	{
		$fields['billing']['billing_tc'] = array(
			'label' => __('TC Kimlik No', 'sozlesmeler'),
			'priority' => 33,
			'required' => false,
			'class' => array('form-row form-row-wide'),
			'clear' => true,
		);
		$fields['billing']['billing_vergi_dairesi'] = array(
			'label' => __('Vergi Dairesi', 'sozlesmeler'),
			'priority' => 35,
			'required' => false,
			'class' => array('form-row form-row-first'),
			'clear' => true,
		);
		$fields['billing']['billing_vergi_no'] = array(
			'label' => __('Vergi Numarası', 'sozlesmeler'),
			'priority' => 35,
			'required' => false,
			'class' => array('form-row form-row-last'),
			'clear' => true,
		);
		return $fields;
	}
	add_filter('woocommerce_checkout_fields', 'woocontracts_checkout_fields');

	function woocontracts_order_preview_meta($data, $order)
	{
		if ($billing_tc = $order->get_meta('_billing_tc')) {
			$data['billing_tc'] = $billing_tc;
		}
		if ($billing_vergi_dairesi = $order->get_meta('_billing_vergi_dairesi')) {
			$data['billing_vergi_dairesi'] = $billing_vergi_dairesi;
		}
		if ($billing_vergi_no = $order->get_meta('_billing_vergi_no')) {
			$data['billing_vergi_no'] = $billing_vergi_no;
		}
		return $data;
	}
	add_filter('woocommerce_admin_order_preview_get_order_details', 'woocontracts_order_preview_meta', 10, 2);

	function woocontracts_admin_order_data()
	{
		echo '<div style="padding:1.5em 1.5em 0;"><b>' . esc_html__('TC Kimlik No', 'sozlesmeler') . ':</b> {{data.billing_tc}}
        <br><b>' . esc_html__('Vergi Dairesi', 'sozlesmeler') . ':</b> {{data.billing_vergi_dairesi}}
        <br><b>' . esc_html__('Vergi Numarası', 'sozlesmeler') . ':</b> {{data.billing_vergi_no}}</div>';
	}
	add_action('woocommerce_admin_order_preview_start', 'woocontracts_admin_order_data');

	function woocontracts_checkout_field_display_admin_order_meta($order)
	{
		echo '<div class="address"><p><strong>' . esc_html__('TC Kimlik No', 'sozlesmeler') . ':</strong> ';
		echo wp_kses_post($order->get_meta('_billing_tc') . '</p><p><strong>' . __('Vergi Dairesi', 'sozlesmeler') . ':</strong> ');
		echo wp_kses_post($order->get_meta('_billing_vergi_dairesi') . '</p><p><strong>' . __('Vergi Numarası', 'sozlesmeler') . ':</strong> ');
		echo wp_kses_post($order->get_meta('_billing_vergi_no') . '</p></div>');
		echo '<div class="edit_address">';
		woocommerce_wp_text_input(array('id' => '_billing_tc', 'label' => __('TC Kimlik No', 'sozlesmeler'), 'wrapper_class' => '_billing_company_field'));
		woocommerce_wp_text_input(array('id' => '_billing_vergi_dairesi', 'label' => __('Vergi Dairesi', 'sozlesmeler'), 'wrapper_class' => '_billing_company_field'));
		woocommerce_wp_text_input(array('id' => '_billing_vergi_no', 'label' => __('Vergi Numarası', 'sozlesmeler'), 'wrapper_class' => '_billing_company_field'));
		echo '</div>';
	}
	add_action('woocommerce_admin_order_data_after_billing_address', 'woocontracts_checkout_field_display_admin_order_meta', 10, 1);

	function woocontracts_edit_billing_custom_checkout_fields($order_id, $post)
	{
		$order = wc_get_order($order_id);
		if (isset($_POST['_billing_tc'])) {
			$order->update_meta_data('_billing_tc', sanitize_text_field(wp_unslash($_POST['_billing_tc'])));
		}
		if (isset($_POST['_billing_vergi_dairesi'])) {
			$order->update_meta_data('_billing_vergi_dairesi', sanitize_text_field(wp_unslash($_POST['_billing_vergi_dairesi'])));
		}
		if (isset($_POST['_billing_vergi_no'])) {
			$order->update_meta_data('_billing_vergi_no', sanitize_text_field(wp_unslash($_POST['_billing_vergi_no'])));
		}
		$order->save_meta_data();
	}
	add_action('woocommerce_process_shop_order_meta', 'woocontracts_edit_billing_custom_checkout_fields', 45, 2);

	function woocontracts_checkout_additional_checkboxes()
	{
		$woocontractsbaslik = (!empty(get_option("woocontracts_baslik")) ? get_option("woocontracts_baslik") : "Sözleşmeler");
		/* translators: %1$s: Label of link to contracts section */
		$checkbox1_text = sprintf(__('<a href="#sozlesmeler">%1$s</a> bölümünü okudum, anladım ve kabul ediyorum.', 'sozlesmeler'), $woocontractsbaslik);
		echo '<p class="form-row custom-checkboxes"><label class="woocommerce-form__label checkbox custom-one"><input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="wctr_checkbox"> <span>' . wp_kses_post($checkbox1_text) . '<span class="required">*</span></span></label></p>';
	}
	add_action('woocommerce_checkout_after_terms_and_conditions', 'woocontracts_checkout_additional_checkboxes');

	function woocontracts_checkout_field_process()
	{
		if (isset($_POST['billing_tc'])) {
			$tcno = sanitize_text_field(wp_unslash($_POST['billing_tc']));
		} else {
			$tcno = '';
		}
		if (!woocontracts_isTcKimlik($tcno) && !empty($tcno)) {
			wc_add_notice(wp_kses_post(__('Lütfen Geçerli Bir TC Kimlik No Girin.', 'sozlesmeler')), 'error');
		}
		$woocontractsbaslik = (!empty(get_option("woocontracts_baslik")) ? get_option("woocontracts_baslik") : "Sözleşmeler");
		if (!isset($_POST['wctr_checkbox'])) {
			/* translators: %1$s: Label of link to contracts section */
			wc_add_notice(wp_kses_post(sprintf(__('<a href="#sozlesmeler">%1$s</a> bölümünü kabul etmeniz gerekmektedir', 'sozlesmeler'), $woocontractsbaslik)), 'error');
		}
	}
	add_action('woocommerce_checkout_process', 'woocontracts_checkout_field_process');

	function woocontracts_maile_ekle($order, $sent_to_admin, $plain_text, $email)
	{
		ob_start();
		echo '<div id="urunListesi" class="tg-wrap"><table class="tg"><tr><th class="tg-hgcj">Cinsi/Türü</th><th class="tg-hgcj">Miktarı</th><th class="tg-hgcj">Birim Fiyatı</th><th class="tg-hgcj">Toplam Satış Bedeli</th></tr>';
		foreach ($order->get_items() as $item_id => $item) {
			$item_name = $item->get_name();
			$quantity = $item->get_quantity();
			$totalprice = $item->get_total();
			$price = $totalprice / $quantity;
			echo wp_kses_post('<tr><td class="tg-s6z2">' . $item_name . '</td><td class="tg-s6z2">' . $quantity . '</td><td class="tg-s6z2">' . get_woocommerce_currency_symbol() . number_format($price, 2) . '</td><td class="tg-s6z2">' . get_woocommerce_currency_symbol() . number_format($totalprice, 2) . '</td></tr>');
		}
		echo '</table></div>';
		$urunListeVar = ob_get_clean();
		$woocontracts1a = get_option("woocontracts_1a");
		$woocontracts2a = get_option("woocontracts_2a");
		$woocontracts3a = get_option("woocontracts_3a");
		$woocontractsbaslik = (!empty(get_option("woocontracts_baslik")) ? stripslashes(get_option("woocontracts_baslik")) : "Sözleşmeler");
		$woocontracts1baslik = (!empty(get_option("woocontracts_1_baslik")) ? stripslashes(get_option("woocontracts_1_baslik")) : "Sözleşme 1");
		$woocontracts2baslik = (!empty(get_option("woocontracts_2_baslik")) ? stripslashes(get_option("woocontracts_2_baslik")) : "Sözleşme 2");
		$woocontracts3baslik = (!empty(get_option("woocontracts_3_baslik")) ? stripslashes(get_option("woocontracts_3_baslik")) : "Sözleşme 3");
		$woocontracts1 = (!empty(get_option("woocontracts_1_yaz")) ? stripslashes(get_option("woocontracts_1_yaz")) : "Bu alanları Sözleşmeler kısmından düzenleyebilirsiniz.");
		$woocontracts2 = (!empty(get_option("woocontracts_2_yaz")) ? stripslashes(get_option("woocontracts_2_yaz")) : "Bu alanları Sözleşmeler kısmından düzenleyebilirsiniz.");
		$woocontracts3 = (!empty(get_option("woocontracts_3_yaz")) ? stripslashes(get_option("woocontracts_3_yaz")) : "Bu alanları Sözleşmeler kısmından düzenleyebilirsiniz.");
		$search = array("[fatura-isim]", "[fatura-firma]", "[fatura-adres]", "[tc-kimlik-no]", "[vergi-dairesi]", "[vergi-numarasi]", "[kargo-isim]", "[kargo-firma]", "[kargo-adres]", "[telefon]", "[eposta]", "[tarih]", "[urun-listesi]", "[toplam-tutar]", "[kargo-tutar]", "[vergi-tutar]", "[sepet-tutar]", "[odeme-yontemi]");
		$fisim = $order->get_formatted_billing_full_name();
		$kargisim = $order->get_formatted_shipping_full_name();
		$tckimlikno = $order->get_meta('_billing_tc');
		$vergidairesi = $order->get_meta('_billing_vergi_dairesi');
		$verginumarasi = $order->get_meta('_billing_vergi_no');
		$tarih = $order->get_date_created()->format('d-m-Y');
		$fil = WC()->countries->states[$order->get_billing_country()][$order->get_billing_state()];
		$fadres = $order->get_billing_address_1() . ' ' . $order->get_billing_address_2() . ' ' . $order->get_billing_postcode() . ' ' . $order->get_billing_city() . ' ' . $fil;
		if (!empty($order->get_shipping_country())) {
			$kargil = WC()->countries->states[$order->get_shipping_country()][$order->get_shipping_state()];
			$kargadres = $order->get_shipping_address_1() . ' ' . $order->get_shipping_address_2() . ' ' . $order->get_shipping_postcode() . ' ' . $order->get_shipping_city() . ' ' . $kargil;
		} else {
			$kargil = $fil;
			$kargadres = $fadres;
		}
		$replace = array($fisim, $order->get_billing_company(), $fadres, $tckimlikno, $vergidairesi, $verginumarasi, $kargisim, $order->get_shipping_company(), $kargadres, $order->get_billing_phone(), $order->get_billing_email(), $tarih, $urunListeVar, $order->get_formatted_order_total(), $order->get_shipping_to_display(), $order->get_total_tax(), $order->get_subtotal_to_display(), $order->get_payment_method_title());
		$woocontracts1yaz = wp_kses_post(nl2br(str_replace($search, $replace, $woocontracts1)));
		$woocontracts2yaz = wp_kses_post(nl2br(str_replace($search, $replace, $woocontracts2)));
		$woocontracts3yaz = wp_kses_post(nl2br(str_replace($search, $replace, $woocontracts3)));
		echo '<div id="sozlesmeler"><h2>' . wp_kses_post($woocontractsbaslik) . '</h2>';
		if (get_option("woocontracts_1a") == 1) {
			echo '<div class="woocontractsdis">
				<h4>' . wp_kses_post($woocontracts1baslik) . '</h4>
				<div id="woocontracts1" class="woocontractsic">' . wp_kses_post($woocontracts1yaz) . '</div>
			</div>';
		}
		if (get_option("woocontracts_2a") == 1) {
			echo '<div class="woocontractsdis">
				<h4>' . wp_kses_post($woocontracts2baslik) . '</h4>
				<div id="woocontracts2" class="woocontractsic">' . wp_kses_post($woocontracts2yaz) . '</div>
			</div>';
		}
		if (get_option("woocontracts_3a") == 1) {
			echo '<div class="woocontractsdis">
				<h4>' . wp_kses_post($woocontracts3baslik) . '</h4>
				<div id="woocontracts3" class="woocontractsic">' . wp_kses_post($woocontracts3yaz) . '</div>
			</div>';
		}
		echo '</div><br>';
		echo '<style>
			.woocontractsic{width: 100% !important;padding: 6px 40px 10px 8px;height: 110px;background-color:#F4F4F4;overflow:auto;font-size:small;}
			.woocontractsdis{width: 90% !important;}
			.tg {
				border-collapse: collapse;
				border-spacing: 0;
				border-color: #ccc !important;
				margin: 0px auto;
				width: 90%;
			}
			.tg td {
				font-family: Arial, sans-serif;
				font-size: 14px;
				padding: 10px 5px;
				border-style: solid !important;
				border-width: 1px !important;
				overflow: hidden;
				word-break: normal;
				border-color: #ccc !important;
				color: #333;
				background-color: #fff;
			}
			.tg th {
				font-family: Arial, sans-serif;
				font-size: 14px;
				font-weight: normal;
				padding: 10px 5px;
				border-style: solid !important;
				border-width: 1px !important;
				overflow: hidden;
				word-break: normal;
				border-color: #ccc !important;
				color: #333;
				background-color: #f0f0f0;
			}
			.tg .tg-s6z2 {
				text-align: center;
			}
			.tg .tg-hgcj {
				font-weight: bold;
				text-align: center;
			}
			@media screen and (max-width: 767px) {
				.tg {
					width: auto !important;
				}
				.tg col {
					width: auto !important;
				}
				.tg-wrap {
					overflow-x: auto;
					-webkit-overflow-scrolling: touch;
					margin:0;
				}
			}
		</style>';
	}
	add_action('woocommerce_email_customer_details', 'woocontracts_maile_ekle', 10, 4);

	function woocontracts_admin_menu()
	{
		add_submenu_page('woocommerce', 'WooCommerce Sözleşmeleri', 'Sözleşmeler', 'manage_options', 'woocontracts-ayarlar', 'woocontracts_admin_panel');
	}
	add_action('admin_menu', 'woocontracts_admin_menu');

	function woocontracts_expanded_allowed_tags()
	{
		$my_allowed = wp_kses_allowed_html('post');
		$my_allowed['iframe'] = array(
			'src' => array(),
			'height' => array(),
			'width' => array(),
			'frameborder' => array(),
			'allowfullscreen' => array(),
			'style' => array(),
		);
		$my_allowed['input'] = array(
			'class' => array(),
			'id' => array(),
			'name' => array(),
			'value' => array(),
			'type' => array(),
		);
		$my_allowed['select'] = array(
			'class' => array(),
			'id' => array(),
			'name' => array(),
			'value' => array(),
			'type' => array(),
		);
		$my_allowed['option'] = array(
			'selected' => array(),
		);
		$my_allowed['style'] = array(
			'types' => array(),
		);
		return $my_allowed;
	}

	function woocontracts_admin_panel()
	{
		$tab = isset($_GET['tab']) ? $_GET['tab'] : null;
		if (isset($_POST["action"]) && $_POST["action"] == "guncelle") {
			if (!isset($_POST['woocontracts_update']) || !wp_verify_nonce($_POST['woocontracts_update'], 'woocontracts_update')) {
				print 'Üzgünüz, bu sayfaya erişim yetkiniz yok!';
				exit;
			} else {
				$allowed_tags = woocontracts_expanded_allowed_tags();
				if (isset($_POST['woocontracts_update']) && wp_verify_nonce($_POST['woocontracts_update'], 'woocontracts_update')) {
					if (isset($_POST['woocontractsbaslik'])) {
						$woocontractsbaslik = wp_filter_post_kses($_POST['woocontractsbaslik']);
						update_option('woocontracts_baslik', $woocontractsbaslik);
					}
					if (isset($_POST['woocontracts1baslik'])) {
						$woocontracts1baslik = wp_filter_post_kses($_POST['woocontracts1baslik']);
						update_option('woocontracts_1_baslik', $woocontracts1baslik);
					}
					if (isset($_POST['woocontracts2baslik'])) {
						$woocontracts2baslik = wp_filter_post_kses($_POST['woocontracts2baslik']);
						update_option('woocontracts_2_baslik', $woocontracts2baslik);
					}
					if (isset($_POST['woocontracts3baslik'])) {
						$woocontracts3baslik = wp_filter_post_kses($_POST['woocontracts3baslik']);
						update_option('woocontracts_3_baslik', $woocontracts3baslik);
					}
					if (isset($_POST['woocontracts1yaz'])) {
						$woocontracts1yaz = wp_filter_post_kses($_POST['woocontracts1yaz']);
						update_option('woocontracts_1_yaz', $woocontracts1yaz);
					}
					if (isset($_POST['woocontracts2yaz'])) {
						$woocontracts2yaz = wp_filter_post_kses($_POST['woocontracts2yaz']);
						update_option('woocontracts_2_yaz', $woocontracts2yaz);
					}
					if (isset($_POST['woocontracts3yaz'])) {
						$woocontracts3yaz = wp_filter_post_kses($_POST['woocontracts3yaz']);
						update_option('woocontracts_3_yaz', $woocontracts3yaz);
					}
					$woocontracts1a = ((isset($_POST['woocontracts1a'])) ? '1' : '0');
					update_option('woocontracts_1a', $woocontracts1a);
					$woocontracts2a = ((isset($_POST['woocontracts2a'])) ? '1' : '0');
					update_option('woocontracts_2a', $woocontracts2a);
					$woocontracts3a = ((isset($_POST['woocontracts3a'])) ? '1' : '0');
					update_option('woocontracts_3a', $woocontracts3a);
				}

				echo '<div class="updated"><p><strong>' . esc_html__('Ayarlar kaydedildi', 'sozlesmeler') . '</strong></p></div>';
			}
		} ?>

		<div class="wrap">
			<h1 class="wp-heading-inline"><?php esc_html_e('WooCommerce Sözleşmeleri', 'sozlesmeler'); ?></h1>
			<nav class="nav-tab-wrapper">
				<a href="?page=woocontracts-ayarlar" class="nav-tab <?php if ($tab === null) : ?>nav-tab-active<?php endif; ?>">Genel Ayarlar</a>
				<a href="?page=woocontracts-ayarlar&tab=pdf-ayarlari" class="nav-tab <?php if ($tab === 'pdf-ayarlari') : ?>nav-tab-active<?php endif; ?>">PDF Ayarları</a>
				<a href="?page=woocontracts-ayarlar&tab=sozlesme-icerikleri" class="nav-tab <?php if ($tab === 'sozlesme-icerikleri') : ?>nav-tab-active<?php endif; ?>">Sözleşme İçerikleri</a>
				<a href="https://shopier.com/19132664" target="_blank" class="nav-tab prolink"><?php esc_html_e('PRO sürümü satın al ve gelişmiş özelliklerin kilidini aç', 'sozlesmeler'); ?></a>
			</nav>
			<div class="tab-content">
				<form method="post">
					<?php switch ($tab):
						case 'sozlesme-icerikleri': ?>
							<?php $editorSettings = array('media_buttons' => false, 'textarea_rows' => 10, 'teeny' => true); ?>
							<p><strong><?php esc_html_e('Kullanılabilir Kısa Kodlar', 'sozlesmeler'); ?> :</strong> &ensp;<span style="font-size:smaller;">(<?php esc_html_e('Kopyalamak için kodun üzerine tıklayın', 'sozlesmeler'); ?>)</span><br></p>
							<p style="font-size:10px;">
								<span style="cursor:pointer;" onclick="copyToClipboard(this);">[fatura-isim]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[fatura-firma]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[fatura-adres]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[tc-kimlik-no]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[vergi-dairesi]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[vergi-numarasi]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[kargo-isim]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[kargo-firma]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[kargo-adres]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[telefon]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[eposta]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[tarih]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[urun-listesi]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[sepet-tutar]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[kargo-tutar]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[vergi-tutar]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[toplam-tutar]</span>
								&ensp;<span style="cursor:pointer;" onclick="copyToClipboard(this);">[odeme-yontemi]</span><br>
							</p>
							<table class="form-table">
								<tr>
									<th scope="row"><label for="woocontractsbaslik">Sözleşmeler Ana Başlık</label></th>
									<td><input type="text" placeholder="Sözleşmeler Ana Başlığı" name="woocontractsbaslik" value="<?php echo esc_attr(stripslashes(get_option(" woocontracts_baslik"))); ?>"></td>
								</tr>
								<tr>
									<th scope="row"><label for="woocontracts1a">Sözleşme 1 Aktif</label></th>
									<td colspan="2"><input type="checkbox" id="woocontracts1a" name="woocontracts1a" value="1" <?php checked(1 == get_option("woocontracts_1a")); ?>></td>
								</tr>
								<tr>
									<th scope="row"><label for="woocontracts1baslik">Sözleşme 1 Başlık</label></th>
									<td colspan="2"><input type="text" placeholder="Sözleşme Başlığı" name="woocontracts1baslik" value="<?php echo esc_attr(stripslashes(get_option(" woocontracts_1_baslik"))); ?>"></td>
								</tr>
								<tr>
									<th scope="row"><label for="woocontracts1yaz">Sözleşme 1 İçerik</label></th>
									<td colspan="2"><?php wp_editor(stripslashes(get_option("woocontracts_1_yaz")), 'woocontracts1yaz', $editorSettings) ?></td>

								</tr>
								<tr>
									<th scope="row"><label for="woocontracts2a">Sözleşme 2 Aktif</label></th>
									<td colspan="2"><input type="checkbox" id="woocontracts2a" name="woocontracts2a" value="1" <?php checked(1 == get_option("woocontracts_2a")); ?>></td>
								</tr>
								<tr>
									<th scope="row"><label for="woocontracts2baslik">Sözleşme 2 Başlık</label></th>
									<td colspan="2"><input type="text" placeholder="Sözleşme Başlığı" name="woocontracts2baslik" value="<?php echo esc_attr(stripslashes(get_option(" woocontracts_2_baslik"))); ?>"></td>
								</tr>
								<tr>
									<th scope="row"><label for="woocontracts2yaz">Sözleşme 2 İçerik</label></th>
									<td colspan="2"><?php wp_editor(stripslashes(get_option("woocontracts_2_yaz")), 'woocontracts2yaz', $editorSettings) ?></td>
								</tr>
								<tr>
									<th scope="row"><label for="woocontracts3a">Sözleşme 3 Aktif</label></th>
									<td colspan="2"><input type="checkbox" id="woocontracts3a" name="woocontracts3a" value="1" <?php checked(1 == get_option("woocontracts_3a")); ?>></td>
								</tr>
								<tr>
									<th scope="row"><label for="woocontracts3baslik">Sözleşme 3 Başlık</label></th>
									<td colspan="2"><input type="text" placeholder="Sözleşme Başlığı" name="woocontracts3baslik" value="<?php echo esc_attr(stripslashes(get_option(" woocontracts_3_baslik"))); ?>"></td>
								</tr>
								<tr>
									<th scope="row"><label for="woocontracts3yaz">Sözleşme 3 İçerik</label></th>
									<td colspan="2"><?php wp_editor(stripslashes(get_option("woocontracts_3_yaz")), 'woocontracts3yaz', $editorSettings) ?></td>
								</tr>
								<?php wp_nonce_field('woocontracts_update', 'woocontracts_update'); ?>
								<input type="hidden" name="action" value="guncelle">
							</table>
						<?php break;
						case 'pdf-ayarlari': ?>
							<table class="form-table">
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrlogo">PDF Logosu Aktif</label></th>
									<td><label for="wctrlogo"><input type="checkbox" id="wctrlogo" name="wctrlogo" value="1" disabled></label></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrlogoURL">PDF Logo URL</label></th>
									<td><input type="text" id="wctrlogoURL" name="wctrlogoURL" value="" disabled>
										<p class="description">Sözleşme PDF dosyalarına basılacak logoya ait URL veya yol(path)</p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrlogoX">PDF Logo X</label></th>
									<td><input type="number" id="wctrlogoX" name="wctrlogoX" value="<?php echo stripslashes(get_option("wctr_logoX")); ?>" disabled>
										<p class="description">Logonun PDF dosyasındaki sayfa kenarına soldan uzaklığı (varsayılan:10)</p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrlogoY">PDF Logo Y</label></th>
									<td><input type="number" id="wctrlogoY" name="wctrlogoY" value="<?php echo stripslashes(get_option("wctr_logoY")); ?>" disabled>
										<p class="description">Logonun PDF dosyasındaki sayfa kenarına yukarıdan uzaklığı (varsayılan:3)</p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrlogoW">PDF Logo Genişlik</label></th>
									<td><input type="number" id="wctrlogoW" name="wctrlogoW" value="<?php echo stripslashes(get_option("wctr_logoW")); ?>" disabled>
										<p class="description">Logonun PDF dosyasındaki genişliği (varsayılan:30)</p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrlogoH">PDF Logo Yükseklik</label></th>
									<td><input type="number" id="wctrlogoH" name="wctrlogoH" value="<?php echo stripslashes(get_option("wctr_logoH")); ?>" disabled>
										<p class="description">Logonun PDF dosyasındaki yüksekliği, otomatik hesaplanması için 0 giriniz (varsayılan:0)</p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrlogoL">PDF Logo Link</label></th>
									<td><input type="text" id="wctrlogoL" name="wctrlogoL" value="<?php echo stripslashes(get_option("wctr_logoL")); ?>" disabled>
										<p class="description">Sözleşme PDF dosyalarına basılacak logoya tıklayınca gidilmesini istediğiniz URL (varsayılan:boş)</p>
									</td>
								</tr>
							</table>
						<?php break;
						default: ?>
							<table class="form-table">
								<tr valign="top">
									<th scope="row"><label class="pro" for="woocPopup">Popup Görünümü</label></th>
									<td><label for="woocPopup"><input type="checkbox" id="woocPopup" name="woocPopup" value="1" disabled>&nbsp;Popup görünümünü aktifleştir</label></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="woocPos">Sözleşmelerin Konumu</label></th>
									<td>
										<fieldset>
											<select name="wctr_position" id="woocPos" disabled>
												<option value="0" selected>Şartlar &amp; Koşullar'dan Önce</option>
												<option value="2">Fatura Bilgilerinden Sonra</option>
												<option value="3">Ödeme Formundan Sonra</option>
											</select>
										</fieldset>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="woocCheck">Onay Kutusu</label></th>
									<td><label for="woocCheck"><input type="checkbox" id="woocCheck" name="woocCheck" value="" checked disabled>&nbsp;Onay kutusunu aktifleştir</label></td>
								</tr>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="woocAttachment">E-posta Eki</label></th>
									<td><label for="woocAttachment"><input type="checkbox" id="woocAttachment" name="woocAttachment" value="" disabled>&nbsp; Sözleşmeleri e-postalara dosya eki (.pdf attachment) olarak ekle</label></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrMails_no">Epostalar</label></th>
									<td>
										<label for="wctrMails_no"><input type="checkbox" id="wctrMails_no" name="wctr_emails[]" value="new_order" checked disabled>&nbsp;Yeni Sipariş<br></label>
										<label for="wctrMails_cpo"><input type="checkbox" id="wctrMails_cpo" name="wctr_emails[]" value="customer_processing_order" checked disabled>&nbsp;Müşteri İşlenen Sipariş<br></label>
										<label for="wctrMails_coho"><input type="checkbox" id="wctrMails_coho" name="wctr_emails[]" value="customer_on_hold_order" checked disabled>&nbsp;Müşteri Bekleyen Sipariş<br></label>
										<label for="wctrMails_co"><input type="checkbox" id="wctrMails_co" name="wctr_emails[]" value="cancelled_order" checked disabled>&nbsp;Müşteri İptal Edilen Sipariş<br></label>
										<label for="wctrMails_cro"><input type="checkbox" id="wctrMails_cro" name="wctr_emails[]" value="customer_refunded_order" checked disabled>&nbsp;Müşteri İade Edilen Sipariş<br></label>
										<label for="wctrMails_cpro"><input type="checkbox" id="wctrMails_cpro" name="wctr_emails[]" value="customer_partially_refunded_order" checked disabled>&nbsp;Müşteri Kısmi İade Edilen Sipariş<br></label>
										<label for="wctrMails_ci"><input type="checkbox" id="wctrMails_ci" name="wctr_emails[]" value="customer_invoice" checked disabled>&nbsp;Müşteri Faturası<br></label>
										<label for="wctrMails_fo"><input type="checkbox" id="wctrMails_fo" name="wctr_emails[]" value="failed_order" checked disabled>&nbsp;Müşteri Başarısız Sipariş<br></label>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrUserDownload">Hesabım Sayfası</label></th>
									<td><label for="wctrUserDownload"><input type="checkbox" id="wctrUserDownload" name="wctrUserDownload" value="" checked disabled>&nbsp;Hesabım sayfasına sözleşmeler butonu eklensin</label>
										<p class="description">Müşteriler Hesabım -> Siparişler sayfasından sözleşmeleri indirebilsinler.</p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrFaturaTipi">Fatura Türü</label></th>
									<td><label for="wctrFaturaTipi"><input type="checkbox" id="wctrFaturaTipi" name="wctrFaturaTipi" value="" disabled>&nbsp;Bireysel / Kurumsal fatura seçimini aktifleştir</label>
										<p class="description">Müşteriler ödeme sayfasında fatura türünü seçebilsinler.</p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrTCE">TCKN Ekle</label></th>
									<td><label for="wctrTCE"><input type="checkbox" id="wctrTCE" name="wctrTCE" value="" checked disabled>&nbsp;Ödeme formuna TC Kimlik Numarası alanını ekle</label></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrTCR">TCKN Zorunluluğu</label></th>
									<td><label for="wctrTCR"><input type="checkbox" id="wctrTCR" name="wctrTCR" value="" disabled>&nbsp;TC Kimlik Numarası alanının doldurulması zorunlu olsun</label></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrTCPos">TCKN Konumu</label></th>
									<td><input type="number" id="wctrTCPos" name="wctrTCPos" value="" disabled>
										<p class="description">TCKN alanının formdaki konumu (varsayılan:21)</p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrVergiE">VD / VN Ekle</label></th>
									<td><label for="wctrVergiE"><input type="checkbox" id="wctrVergiE" name="wctrVergiE" value="" checked disabled>&nbsp;Ödeme formuna Vergi Dairesi ve Vergi No alanlarını ekle</label></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrVergiR">VD / VN Zorunluluğu</label></th>
									<td><label for="wctrVergiR"><input type="checkbox" id="wctrVergiR" name="wctrVergiR" value="" disabled>&nbsp;VD / VN alanlarının doldurulması zorunlu olsun</label></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrVergiPos">VD / VN Konumu</label></th>
									<td><input type="number" id="wctrVergiPos" name="wctrVergiPos" value="" disabled>
										<p class="description">VD / VN alanının formdaki konumu (varsayılan:31)</p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrCheckTel">Telefon Kontrolü</label></th>
									<td><label for="wctrCheckTel"><input type="checkbox" id="wctrCheckTel" name="wctrCheckTel" value="" disabled>&nbsp;Telefon numarasının TR GSM numarası olduğu kontrol edilsin</label></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label class="pro" for="wctrbeta">Beta Testi</label></th>
									<td><label for="wctrbeta"><input type="checkbox" id="wctrbeta" name="wctrbeta" value="" disabled>&nbsp;Beta sürüm güncellemelerini al</label>
										<p class="description">!!! Henüz kararlı sürümü yayımlanmamış güncellemeleri almak bazı hatalara neden olabilir !!!</p>
									</td>
								</tr>
							</table>
					<?php break;
					endswitch; ?>
					<p class="submit"><input type="submit" value="Değişiklikleri Kaydet" class="button button-primary"></p>
				</form>
			</div>
	<?php }
} else {
	function woocontracts_woocommerce_warning()
	{
		echo '
        <div class="notice notice-error">
            <p>
                "Woocommerce Sözleşmeleri" eklentisi için sitenizde WooCommerce kurulu ve aktif olmalıdır. Ancak WooCommerce eklentisi tespit edilemedi
            </p>
        </div>
        ';
	}
	add_action('admin_notices', 'woocontracts_woocommerce_warning');
}
add_action('before_woocommerce_init', function () {
	if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
	}
});
	?>