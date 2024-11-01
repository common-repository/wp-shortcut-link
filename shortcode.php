<?php

if (!defined('ABSPATH')) {
    exit;
}
add_action('wp_enqueue_scripts', 'wpslab_wp_scripts');
add_shortcode('wpslab', 'wpslab_shortcode');
function wpslab_wp_scripts()
{
    wp_register_style('wpslab_css', plugins_url('/css/client.css', __FILE__));
    wp_enqueue_style('wpslab_css');
}
function wpslab_shortcode($atts, $content = null)
{
    $html     = "";
    $id_wpslab = intval($atts["id"]);
    global $wpdb;
    $table = $wpdb->prefix."wpslab";
    $wpslab = $wpdb->get_results("SELECT * FROM $table WHERE id_wpslab=". $id_wpslab);
    
    
    if (count($wpslab) == 1) {
        
        $wpslab = $wpslab[0];
        if (!empty($wpslab->jsdata)) {
            
            
            foreach (json_decode($wpslab->jsdata) as $key => $value) {
                
                $html.= '<div class="cesrow "   >';
                
                $childs    = $value->childs;
                $heightels = $value->height;
                
                
                
                foreach ($childs as $val) {
                    
                    
                    $classel = "ceslg-" . $val->collg . " cesmd-" . $val->colmd . " cessm-" . $val->colsm . " cesxs-" . $val->colxs;
                    
                    
                    $style = "font-size:" . $val->fontsize . "px;color:" . $val->color . ";height:" . $heightels . "px;line-height:" . $heightels . "px;";
                    if ($val->bgtype == "color") {
                        $style.= "background:" . $val->bg . ";";
                    }
                    if ($val->bgtype == "img") {
                        $style.= "background-image:url('" . $val->bg . "');background-size: contain;background-repeat: no-repeat;";
                    }
                    
                    $html.= '<a href="' . $val->link . '" id="' . $val->id . '" class="' . $classel . '"><div class="cesitem" style="' . $style . '"><span class="textcontent">' . $val->textcontent . '</span></div></a>';
                    
                }
                
                
                
            }
        }
    }
    
    
    return $html;
}