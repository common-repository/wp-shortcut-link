<?php
if (!defined('ABSPATH')) {
    exit;
}
include_once ('function.php');
class CatShortcode_Admin extends CatShortcode_FUN {
    public function __construct() {
      
        add_action('admin_menu', array(&$this, 'wpslab_Admin_menue'));
        add_action('admin_enqueue_scripts', array(&$this, 'wpslab_admin_scripts'));
        add_action('wp_ajax_save_wpslab_data', array(&$this, 'save_wpslab_data'));
        add_action('wp_loaded', array(&$this, 'wpslab_redirect_function'));
        add_action('wp_ajax_save_wpslab_get_list_cat', array(&$this, 'save_wpslab_get_list_cat'));
    }
    function wpslab_redirect_function() {


    
        if (isset($_GET["page"])) {
         
        $page = sanitize_text_field($_GET["page"]);
        if ($page == 'wpslab_add_new') {
            if (!isset($_GET["id_wpslab"])) {
                global $wpdb;
                $table = $wpdb->prefix . "wpslab";
                $data["title"] = "";
                $data["jsdata"] = "";
                $wpdb->insert($table, $data);
                $lastid = $wpdb->insert_id;
                $actual_link = (isset($_SERVER['HTTPS'])) ? "https" : "http";
                $actual_link.= "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "&id_wpslab=" . $lastid;
                wp_redirect($actual_link);
                exit;
            }
        }
      }
    }
    function save_wpslab_get_list_cat() {
        global $wpdb;
        $table = $wpdb->prefix . "wpslab";
        $wpslab = $wpdb->get_results("SELECT * FROM $table ");
        $res["status"] = 200;
        $res["wpslab"] = $wpslab;
        echo json_encode($res);
        exit();
    }
    function save_wpslab_data() {
        $data["title"] = sanitize_text_field($_POST["title"]);
        $data["jsdata"] = html_entity_decode(stripslashes($_POST["jsdata"]));
        $id_wpslab = intval($_POST["id_wpslab"]);
        /*
        if ( ! wp_verify_nonce( $_POST['nonce'], 'wpttp' ) )
        {
         $res["status"]=201 ; 
         echo json_encode($res);
         exit(); 
        }
        */
        global $wpdb;
        $table = $wpdb->prefix . "wpslab";
        $is_ex = $wpdb->get_results("SELECT * FROM $table WHERE id_wpslab=" . $id_wpslab);
        if (count($is_ex) == 1) {
            $wpdb->update($table, $data, array("id_wpslab" => $id_wpslab));
        }
        if (count($is_ex) == 0) {
            $wpdb->insert($table, $data);
        }
        $res["status"] = 200;
        echo json_encode($res);
        exit();
    }
    function wpslab_admin_scripts() {
        if (is_admin()) {
            wp_enqueue_media();
        }
        wp_register_style('wpslab-css', plugins_url('/css/style.css', __FILE__));
        wp_enqueue_style('wpslab-css');
        wp_enqueue_script('wpslab-js', plugins_url('/js/wpslab.js', __FILE__), array('jquery'));
        wp_localize_script('wpslab-js', 'the_in_url', array('in_url' => admin_url('admin-ajax.php')));
    }
     function wpslab_Admin_menue() {
        add_menu_page('wpslab', 'shortcut link', 'administrator', 'wpslab_all_cat', array(&$this, 'wpslab_all_cat'), "dashicons-format-aside");
        add_submenu_page('wpslab_all_cat', "addwpslab", "Add New", 'administrator', 'wpslab_add_new', array(&$this, 'wpslab_add_new'));
    }
    function wpslab_all_cat() {
        global $wpdb;
        $table = $wpdb->prefix . "wpslab";
        if (isset($_GET["delete"])) {
            $wpslab = $wpdb->get_results("SELECT * FROM $table WHERE id_wpslab=" . intval($_GET['delete']));
            if (count($wpslab) == 1) {
                $wpdb->delete($table, array("id_wpslab" => intval($_GET['delete'])));
               
            }
        }
        $wpslab = $wpdb->get_results("SELECT * FROM $table ");
        $actual_link = (isset($_SERVER['HTTPS'])) ? "https" : "http";
        $actual_link.= "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>

   
    <table class=" wp-list-table widefat fixed posts">
      
      <thead>
        <tr>
          <th  ><?php _e("Row", "wpslab") ?></th>
          <th  class=""><?php _e("Title", "wpslab") ?>  </th>
          <th  class="manage-column column-tags"><?php _e("Shortcode", "wpslab") ?>  </th>
          <th class="manage-column column-tags"> <?php _e("Action", "wpslab") ?></th>
        </tr>
      </thead>

        <tfoot>
        <tr>
          <th  ><?php _e("Row", "wpslab") ?></th>
         <th  class=""><?php _e("Title", "wpslab") ?>  </th>
          <th  class="manage-column column-tags"><?php _e("Shortcode", "wpslab") ?>  </th>
          <th class="manage-column column-tags"> <?php _e("Action", "wpslab") ?></th>
        </tr>
        </tr>
      </tfoot>


      <tbody>
         <?php
        $i = 1;
        foreach ($wpslab as $value) { ?>

          <tr>
            <td  class="author column-author"><?php echo $i; ?></td>
            <td  class=""><?php echo $value->title; ?>  </td>
            <td  class="author column-author">[wpslab id=<?php echo $value->id_wpslab; ?>] </td>

            <td>
              <a href="<?php echo $actual_link ?>&delete=<?php echo $value->id_wpslab ?>">
               <?php _e("Delete", "wpslab") ?>
               <a href="<?php echo str_replace("wpslab_all_cat", "wpslab_add_new", $actual_link) ?>&id_wpslab=<?php echo $value->id_wpslab ?>">
               <?php _e("Edit", "wpslab") ?>
             </a>

            </td>
        </tr>


          <?php $i++;
        } ?>
      </tbody>

    

    </table>


    <?php
    }
    function wpslab_add_new() {
        global $wpdb;
        $table = $wpdb->prefix . "wpslab";
        $pluginurl = plugin_dir_url(__FILE__);
        $id_wpslab = intval($_GET["id_wpslab"]);
        $wpslab = $wpdb->get_results("SELECT * FROM $table WHERE id_wpslab=" . $id_wpslab);
        $wpslab = $wpslab[0];
        //$embedurl = home_url() . '?cesembed=' . $id_wpslab . "&dd=1";
        echo (' 
    
    <div class="cesrow m-top">
      <h3> '.__("Title", "wpslab").' </h3>
      <input type="text" value="' . $wpslab->title . '"  class="ceslg-11 cesmd-11 cesxs-11"  spellcheck="true" autocomplete="off" id="titlecat">


      <input type="hidden"  class="ceslg-11cesmd-11 cesxs-11" value="' . $id_wpslab . '"  id="id_wpslab">
      <textarea class="ceshide" id="jsdata">' . $wpslab->jsdata . '</textarea>
      <button id="savecat">'.__("Save", "wpslab").' <span class="spinner"></span> </button>

    </div>

     

     


   <div class="ces ceslg-12 cesmd-12 cessm-12 cesxs-12">
    <div class="cesheader">

       <p>
         <h3>'.__("Themeplate", "wpslab").'</h3>
       </p>
      
      <img class="imggrid" numgrid="1" grid="12" src="' . $pluginurl . '/img/col12.png" >
      <img class="imggrid" numgrid="2" grid="6-6" src="' . $pluginurl . '/img/col66.png" >
      <img class="imggrid" numgrid="4" grid="3-3-3-3" src="' . $pluginurl . '/img/col3333.png" >
      <img class="imggrid" numgrid="3" grid="4-4-4" src="' . $pluginurl . '/img/col444.png" >
      <img class="imggrid" numgrid="3" grid="3-6-3" src="' . $pluginurl . '/img/col363.png" >
      <img class="imggrid" numgrid="3" grid="6-3-3" src="' . $pluginurl . '/img/col633.png" >
      <img class="imggrid" numgrid="3" grid="3-3-6" src="' . $pluginurl . '/img/col336.png" >
      <img class="imggrid" numgrid="2" grid="8-4" src="' . $pluginurl . '/img/col84.png" >
      <img class="imggrid" numgrid="2" grid="4-8" src="' . $pluginurl . '/img/col48.png" >

      <img class="imggrid" numgrid="3" grid="3-4-5" src="' . $pluginurl . '/img/col235.png" >
      <img class="imggrid" numgrid="3" grid="4-3-5" src="' . $pluginurl . '/img/col325.png" >
      <img class="imggrid" numgrid="3" grid="4-5-3" src="' . $pluginurl . '/img/col352.png" >
      <img class="imggrid" numgrid="3" grid="5-3-4" src="' . $pluginurl . '/img/col523.png" >
      <img class="imggrid" numgrid="3" grid="2-5-5" src="' . $pluginurl . '/img/col255.png" >
      <img class="imggrid" numgrid="3" grid="5-5-2" src="' . $pluginurl . '/img/col552.png" >
      <img class="imggrid" numgrid="3" grid="5-2-5" src="' . $pluginurl . '/img/col525.png" >


    
    <hr>
    </div>
    
    

  
    
  </div>

  <div class="pdesine m-top">
   <div class="desinebox" id="desinebox">
   
  <div  id="desine"></div>
  

</div>


 <div class="desinebox m-top" id="boxembed" >
   
 
  <div>
        <img height="20"  src="' . $pluginurl . '/img/nav.png" >
   </div>
  <h3 class="embedtitle">'.__("Demo", "wpslab").' </h3>
  <div class="cesresponsive">

    
        <p>
           <h3>'.__("Responsive", "wpslab").'</h3>
        </p>
        <img data-w="1200" device="lg" class="imgresponsive cesactive"  src="' . $pluginurl . '/img/desktop-monitor.png" >
        <img data-w="992" device="md" class="imgresponsive "  src="' . $pluginurl . '/img/laptop.png" >
        <img data-w="768" device="sm" class="imgresponsive "  src="' . $pluginurl . '/img/computer-tablet.png" >
        <img data-w="300" device="xs" class="imgresponsive "  src="' . $pluginurl . '/img/smartphone-call.png" >
    </div>
    <div id="embedparent">
  <iframe id="embed" s="'.$pluginurl.'" width="1200" height="600" src=""></iframe>
</div>
</div>

</div/>

  <div  class="modalces">
    <div class="modalces-content "> 
      <span class="closeces">×</span>


        
        

      <div id="itemcontent" >

      <div class="cesrow" >

      <div class="cesmd-4 cesxs-12" >
            <label class="cesrow" >hight</label>
            <input class="cesrow" type="number" id="changehight">
         </div>

      <div class="cesmd-4 cesxs-12" >
            <label class="cesrow" >font size</label>
             <input class="cesrow" type="number" id="chanfontsize">
          </div> 
          
   

  <div class="cesmd-4 cesxs-12" >
            <label class="cesrow" >Grid desktop</label>
            <select id="collg" class="cesrow">
            <option value="1">8%</option>
            <option value="2">16%</option>
            <option value="3">25%</option>
            <option value="4">33%</option>
            <option value="5">40%</option>
            <option value="6">50%</option>
            <option value="7">60%</option>
            <option value="8">65%</option>
            <option value="9">75%</option>
            <option value="10">80%</option>
            <option value="11">90%</option>
            <option value="12">100%</option>
          </select>
  </div> 
  <div class="cesmd-4 cesxs-12" >

  <label class="cesrow" >Grid laptop</label>
            <select id="colmd" class="cesrow">
            <option value="1">8%</option>
            <option value="2">16%</option>
            <option value="3">25%</option>
            <option value="4">33%</option>
            <option value="5">40%</option>
            <option value="6">50%</option>
            <option value="7">60%</option>
            <option value="8">65%</option>
            <option value="9">75%</option>
            <option value="10">80%</option>
            <option value="11">90%</option>
            <option value="12">100%</option>
          </select>

</div> 
  <div class="cesmd-4 cesxs-12" >
 <label class="cesrow" >Grid smartphone</label>
            <select id="colsm" class="cesrow">
            <option value="1">8%</option>
            <option value="2">16%</option>
            <option value="3">25%</option>
            <option value="4">33%</option>
            <option value="5">40%</option>
            <option value="6">50%</option>
            <option value="7">60%</option>
            <option value="8">65%</option>
            <option value="9">75%</option>
            <option value="10">80%</option>
            <option value="11">90%</option>
            <option value="12">100%</option>
          </select>
</div> 
<div class="cesmd-4 cesxs-12" >
 <label class="cesrow" >Grid small device</label>
            <select id="colxs" class="cesrow gridresponsive" >
            <option value="1">8%</option>
            <option value="2">16%</option>
            <option value="3">25%</option>
            <option value="4">33%</option>
            <option value="5">40%</option>
            <option value="6">50%</option>
            <option value="7">60%</option>
            <option value="8">65%</option>
            <option value="9">75%</option>
            <option value="10">80%</option>
            <option value="11">90%</option>
            <option value="12">100%</option>
          </select>
</div>

     </div>


       
       
         
          <div class="cesrow " >
            <label  >Link</label>
             <input type="text" class="cesrow"  placeholder="Link" id="ceslink">
           </div>
            

 
  

        <div class="cestextarea">
          <input type="text" name="" id="cestextarea" placeholder="Link Title">
        </div>


        <div class="cesli cescolor"><div id="cescolorbox"></div>

          <ul class="cesdropdown cescolorels">

            ' . $this->get_list_color() . '
          </ul>


          </div>

        <div class="cesli cesemoji"><img src="' . $pluginurl . '/img/emoji.png" >

          <ul class="cesdropdown cesemojilist">
           ' . $this->list_emoji() . '
          </ul>


        </div>
        <div class="cesleft">
          <button class="cesbtn set_custom_images" id="setphoto">
            <img src="' . $pluginurl . '/img/photo.png">
             photo
          </button>
           <input  type="color" id="changcolor">
 </div>

        <div class="cesfooter">
      

          


           <div class="cesulcat">' . $this->get_all_cat() . '</div>
    </div>



      </div>
    </div>
  </div>



     ');
    }
}
new CatShortcode_Admin();
