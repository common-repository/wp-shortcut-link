<?php
add_action('wp_enqueue_scripts', 'vbtab_js_and_css2');
add_action('wp_footer', 'wpshout_action_example');
add_action('wp_ajax_save_wpslab_dataclient', 'save_wpslab_dataclient');
add_action('wp_ajax_wpslab_get_list_cat_client', 'wpslab_get_list_cat_client');
add_action('wp_ajax_nopriv_wpslab_get_list_cat_client', 'wpslab_get_list_cat_client');
add_action('wp_ajax_remove_wpslab_dataclient', 'remove_wpslab_dataclient');
function remove_wpslab_dataclient() {
    $urlpage =sanitize_url( $_POST["url"]);
    $id_wpslab = intval($_POST["id_wpslab"]);
    if (!wp_verify_nonce($_POST['nonce'], 'wpslabcli')) {
        $res["status"] = 201;
        echo json_encode($res);
        exit();
    }
    global $wpdb;
    $table = $wpdb->prefix . "wpslab_client";
    $wpdb->delete($table, array("id_wpslab" => $id_wpslab, "urlpage" => $urlpage));
    $res["status"] = 200;
    echo json_encode($res);
    exit();
}
function wpslab_get_list_cat_client() {
    global $wpdb;
    $table = $wpdb->prefix . "wpslab";
    $table2 = $wpdb->prefix . "wpslab_client";
    $urlpage = sanitize_url($_POST["url"]);
    $wpslab = $wpdb->get_results("SELECT * FROM $table ");
    $wpslab_client = $wpdb->get_results("SELECT * FROM $table2 LEFT JOIN $table  ON {$table2}.id_wpslab = {$table}.id_wpslab WHERE {$table2}.urlpage='" . $urlpage . "' ");
    $res["status"] = 200;
    $res["wpslab"] = $wpslab;
    $res["wpslab_client"] = $wpslab_client;
    echo json_encode($res);
    exit();
}
function save_wpslab_dataclient() {
    $data["urlpage"] = sanitize_url($_POST["url"]);
    $data["classel"] = sanitize_text_field($_POST["lastClass"]);
    $data["id_wpslab"] = intval($_POST["id_wpslab"]);
    if (!wp_verify_nonce($_POST['nonce'], 'wpslabcli')) {
        $res["status"] = 201;
        echo json_encode($res);
        exit();
    }
    global $wpdb;
    $table = $wpdb->prefix . "wpslab_client";
    $is_ex = $wpdb->get_results("SELECT * FROM $table WHERE urlpage='" . $data["urlpage"] . "' AND classel='" . $data["classel"] . "'");
    
    if (count($is_ex) == 1) {
        $wpdb->update($table, ["id_wpslab"=>$data["id_wpslab"]], array("urlpage" => $data["urlpage"], "classel" => $data["classel"]));
    }
    if (count($is_ex) == 0) {
        $wpdb->insert($table, $data);
    }
    

    $res["status"] = 200;
    echo json_encode($res);
    exit();
}
function vbtab_js_and_css2() {
    //wp_register_style('vbtab_css', plugins_url('/css/style.css', __FILE__) );
    // wp_enqueue_style( 'vbtab_css' );
    wp_enqueue_script("vbtab_js", plugin_dir_url(__FILE__) . 'js/client.js', array('jquery'));
    wp_localize_script('vbtab_js', 'the_in_url', array('in_url' => admin_url('admin-ajax.php')));
}
function wpshout_action_example() {
    global $wp;
    $is_edit = 0;
    if (current_user_can('administrator')) {
        $is_edit = 1;
    }
    $nonce = wp_create_nonce('wpslabcli');
    $html = ' <div class="wpslabdata" 
         data-url="' . home_url($wp->request) . '"  
         edit="' . $is_edit . '"
         nonce="' . $nonce . '"

         >';
    $html.= '</div>';
    _e($html);
}
