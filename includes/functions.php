<?php


class WEBIOTSTestimonails{



    function __construct(){

        // File upload allowed

        $whitelist_files[]      = array("mimetype"=>"image/jpeg","ext"=>"jpg") ;
        $whitelist_files[]      = array("mimetype"=>"image/jpg","ext"=>"jpg") ;
        $whitelist_files[]      = array("mimetype"=>"image/png","ext"=>"png") ;
        $whitelist_files[]      = array("mimetype"=>"text/plain","ext"=>"txt") ;
        $this->whitelist_files = $whitelist_files;
        add_action("plugins_loaded", array($this, "webiotsapptextdomain")); //load language/textdomain
        /** register post type **/
        add_action("init", array($this, "webiotsposttypeapptestimonials"));



        /** register metabox for admin **/
        if(is_admin()){
            add_action("admin_head",array($this,"webiotsadminheadapptestimonails"),1);
            add_action("add_meta_boxes",array($this,"webiotsmetaboxapptestimonials"));
            add_action("save_post",array($this,"webiotsmetaboxapptestimonialssave"));
        }
    }





    // Register textdomain
    function webiotsapptextdomain(){
        load_plugin_textdomain("app-testimonails", false, TESTIMONAILS_DIR . "/languages");
    }
    /** register post for table test **/


    /** register post for table testimonials **/
    public function webiotsposttypeapptestimonials()
    {
        $labels = array(
            "name" => _x("Testimonials", "post type general name", "app-testimonails"),
            "singular_name" => _x("Testimonial", "post type singular name", "app-testimonails"),
            "menu_name" => _x("Testimonials", "admin menu", "app-testimonails"),
            "name_admin_bar" => _x("Testimonials", "add new on admin bar", "app-testimonails"),
            "add_new" => _x("Add new Testimonials", "item", "app-testimonails"),
            "add_new_item" => __("Add new Testimonials", "app-testimonails"),
            "new_item" => __("new item", "app-testimonails"),
            "edit_item" => __("Edit Testimonials", "app-testimonails"),
            "view_item" => __("View Testimonials", "app-testimonails"),
            "all_items" => __("All Testimonials", "app-testimonails"),
            "search_items" => __("Search Testimonials", "app-testimonails"),
            "parent_item_colon" => __("parent Testimonials:", "app-testimonails"),
            "not_found" => __("not found", "app-testimonails"),
            "not_found_in_trash" => __("not found in trash", "app-testimonails"));
        $args = array(
            "labels" => $labels,
            "public" => true,
            "menu_icon" => "dashicons-format-quote",
            "publicly_queryable" => false,
            "show_ui" => true,
            "show_in_menu" => true,
            "query_var" => true,
            "capability_type" => "page",
            "has_archive" => true,
            "hierarchical" => true,
            "menu_position" => null,
            "taxonomies" => array(),
            "supports" => array("title","thumbnail"));
        register_post_type("app_testimonials", $args);
    }

    /** register metabox for testimonials **/
    public function webiotsmetaboxapptestimonials($hook)
    {
        $allowed_hook = array("app_testimonials");
        if(in_array($hook, $allowed_hook))
        {
            add_meta_box("webiotsmetaboxapptestimonials",
                __("Testimonials","app-testimonails"),
                array($this,"webiotsmetaboxapptestimonialscallback"),
                $hook,
                "normal",
                "high");

        }


    }
    /** callback metabox for testimonials **/
    public function webiotsmetaboxapptestimonialscallback($post)
    {
        $this->webiotstestimonailsenqueue();
        wp_enqueue_style("thickbox");
        wp_nonce_field("webiotsmetaboxapptestimonialssave","webiotsmetaboxapptestimonials_nonce");
        printf("<table class=\"form-table\">");
        $value_testimonials_name = get_post_meta($post->ID, "_testimonials_name", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_name\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"testimonials_name\" name=\"testimonials_name\" value=\"%s\" /></td></tr>",__("Name", "app-testimonails"), esc_attr($value_testimonials_name));
        $settings = array("media_buttons"=>true);
        $value_testimonials_description = get_post_meta($post->ID, "_testimonials_description", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_description\">%s</label></th><td>",__("Description","app-testimonails"));
        wp_editor(html_entity_decode($value_testimonials_description),"testimonials_description",$settings);
        printf("</td></tr>");
        $value_testimonials_author_name = get_post_meta($post->ID, "_testimonials_author_name", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_author_name\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"testimonials_author_name\" name=\"testimonials_author_name\" value=\"%s\" /></td></tr>",__("Author Name", "app-testimonails"), esc_attr($value_testimonials_author_name));
        $value_testimonials_designation = get_post_meta($post->ID, "_testimonials_designation", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_designation\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"testimonials_designation\" name=\"testimonials_designation\" value=\"%s\" /></td></tr>",__("Designation", "app-testimonails"), esc_attr($value_testimonials_designation));
        $value_testimonials_profile_url = get_post_meta($post->ID, "_testimonials_profile_url", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_profile_url\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"testimonials_profile_url\" name=\"testimonials_profile_url\" value=\"%s\" /></td></tr>",__("Profile Url", "app-testimonails"), esc_attr($value_testimonials_profile_url));
        $value_testimonials_youtube = get_post_meta($post->ID, "_testimonials_youtube", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_youtube\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"testimonials_youtube\" name=\"testimonials_youtube\" value=\"%s\" placeholder=\"4HkG8z3sa-0\" /><p class=\"description\">Use Youtube ID example: 4HkG8z3sa-0 get from link: https://www.youtube.com/watch?v=<kbd>4HkG8z3sa-0</kbd></p></td></tr>",__("Youtube", "app-testimonails"), esc_attr($value_testimonials_youtube));
        $value_testimonials_fb_url = get_post_meta($post->ID, "_testimonials_fb_url", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_fb_url\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"testimonials_fb_url\" name=\"testimonials_fb_url\" value=\"%s\" /></td></tr>",__("Facebook", "app-testimonails"), esc_attr($value_testimonials_fb_url));
        $value_testimonials_linkedin_url = get_post_meta($post->ID, "_testimonials_linkedin_url", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_linkedin_url\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"testimonials_linkedin_url\" name=\"testimonials_linkedin_url\" value=\"%s\" /></td></tr>",__("Linkedin", "app-testimonails"), esc_attr($value_testimonials_linkedin_url));
        $value_testimonials_twitter = get_post_meta($post->ID, "_testimonials_twitter", true);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_twitter\">%s</label></th><td ><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"testimonials_twitter\" name=\"testimonials_twitter\" value=\"%s\" /></td></tr>",__("Twitter", "app-testimonails"), esc_attr($value_testimonials_twitter));
        // $value_testimonials_rate = get_post_meta($post->ID, "_testimonials_rate", true);
        // printf("<tr><th scope=\"row\"><label for=\"testimonials_rate\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"text\" id=\"testimonials_rate\" name=\"testimonials_rate\" value=\"%s\" /></td></tr>",__("Rating", "app-testimonails"), esc_attr($value_testimonials_rate));

        $value_testimonials_rate = get_post_meta($post->ID, "_testimonials_rate", true);
        // var_dump($value_testimonials_rate);
        printf("<tr><th scope=\"row\"><label for=\"testimonials_rate\">%s</label></th>",__("Rating", "app-testimonails"), esc_attr($value_testimonials_rate));
        printf(" <td style=\"float: left;\">");
        if($value_testimonials_rate == "5") {
            printf("<input class=\"star star-5\" placeholder=\"\" type=\"radio\" id=\"star5\" checked name=\"testimonials_rate\"  value=\"5\" />
 <label class=\"star star-5\" for=\"star5\"></label>");
        }
        else{
            printf("<input class=\"star star-5\" placeholder=\"\" type=\"radio\" id=\"star5\"  name=\"testimonials_rate\"  value=\"5\" />
 <label class=\"star star-5\" for=\"star5\"></label>");
        }

        if($value_testimonials_rate == "4") {
            printf("<input class=\"star star-4\" checked placeholder=\"\" type=\"radio\" id=\"star4\" name=\"testimonials_rate\" value=\"4\" />
 <label class=\"star star-4\" for=\"star4\"></label>");
        }
        else{
            printf("<input class=\"star star-4\" placeholder=\"\" type=\"radio\" id=\"star4\" name=\"testimonials_rate\" value=\"4\" />
 <label class=\"star star-4\" for=\"star4\"></label>");
        }


        if($value_testimonials_rate == "3") {
            printf("<input class=\"star star-3\" checked placeholder=\"\" type=\"radio\" id=\"star3\" name=\"testimonials_rate\" value=\"3\" />
 <label class=\"star star-3\" for=\"star3\"></label>");
        }
        else {
            printf("<input class=\"star star-3\" placeholder=\"\" type=\"radio\" id=\"star3\" name=\"testimonials_rate\" value=\"3\" />
 <label class=\"star star-3\" for=\"star3\"></label>");
        }

        if($value_testimonials_rate == "2") {
            printf("<input class=\"star star-2\" checked placeholder=\"\" type=\"radio\" id=\"star2\" name=\"testimonials_rate\" value=\"2\" />
 <label class=\"star star-2\" for=\"star2\"></label>");
        }
        else {
            printf("<input class=\"star star-2\" placeholder=\"\" type=\"radio\" id=\"star2\" name=\"testimonials_rate\" value=\"2\" />
 <label class=\"star star-2\" for=\"star2\"></label>");
        }

        if($value_testimonials_rate == "1") {
            printf("<input class=\"star star-1\" checked placeholder=\"\" type=\"radio\"  id=\"star1\" name=\"testimonials_rate\" value=\"1\" />
 <label class=\"star star-1\" for=\"star1\"></label>");
        }
        else {
            printf("<input class=\"star star-1\" placeholder=\"\" type=\"radio\"  id=\"star1\" name=\"testimonials_rate\" value=\"1\" />
 <label class=\"star star-1\" for=\"star1\"></label>");
        }

        printf("</td>
        </tr>",__("Rating", "app-testimonails"), esc_attr($value_testimonials_rate));


        printf("</table>");

    }
    /*
     * Styles For the Backend
     */

    public function webiotstestimonailsenqueue()
    {
        wp_enqueue_media();
        wp_register_style("ionicon", "//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css",array(),"1.2.4" );
        wp_enqueue_style("ionicon");
        wp_enqueue_script("app_testimonails", plugins_url("/",__FILE__) . "/js/admin.js", array("jquery","thickbox"),"1",true );
        wp_register_style( 'stylecss',plugins_url( 'assets/css/style.css', dirname(__FILE__) ));
        wp_enqueue_style("stylecss");
        wp_register_style("font-awesome", "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css",array(),"1.2.4" );
        wp_enqueue_style("font-awesome");


    }



    /*
     * Registering Scripts and styles
     */

    public function webiotsmetaboxapptestimonialssave($post_id)
    {

        // Check if our nonce is set.
        if (!isset($_POST["webiotsmetaboxapptestimonials_nonce"]))
            return $post_id;
        $nonce = $_POST["webiotsmetaboxapptestimonials_nonce"];
        // Verify that the nonce is valid.
        if(!wp_verify_nonce($nonce, "webiotsmetaboxapptestimonialssave"))
            return $post_id;
        // If this is an autosave, our form has not been submitted,
        // so we don't want to do anything.
        if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
            return $post_id;
        // Check the user's permissions.
        if ("page" == $_POST["post_type"])
        {
            if (!current_user_can("edit_page", $post_id))
                return $post_id;
        } else
        {
            if (!current_user_can("edit_post", $post_id))
                return $post_id;
        }
        // Sanitize the user input.
        $post_testimonials_name = sanitize_text_field($_POST["testimonials_name"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_name", $post_testimonials_name);
        // Sanitize the user input.
        $post_testimonials_description = esc_html($_POST["testimonials_description"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_description", $post_testimonials_description);
        // Sanitize the user input.
        $post_testimonials_author_name = sanitize_text_field($_POST["testimonials_author_name"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_author_name", $post_testimonials_author_name);
        // Sanitize the user input.
        $post_testimonials_designation = sanitize_text_field($_POST["testimonials_designation"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_designation", $post_testimonials_designation);
        // Sanitize the user input.
        $post_testimonials_profile_url = sanitize_text_field($_POST["testimonials_profile_url"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_profile_url", $post_testimonials_profile_url);
        // Sanitize the user input.
        $post_testimonials_youtube = sanitize_text_field($_POST["testimonials_youtube"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_youtube", $post_testimonials_youtube);
        // Sanitize the user input.
        $post_testimonials_fb_url = sanitize_text_field($_POST["testimonials_fb_url"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_fb_url", $post_testimonials_fb_url);
        // Sanitize the user input.
        $post_testimonials_linkedin_url = sanitize_text_field($_POST["testimonials_linkedin_url"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_linkedin_url", $post_testimonials_linkedin_url);
        // Sanitize the user input.
        $post_testimonials_twitter = sanitize_text_field($_POST["testimonials_twitter"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_twitter", $post_testimonials_twitter);

        $post_testimonials_rate = sanitize_text_field($_POST["testimonials_rate"] );
        // Update the meta field.
        update_post_meta($post_id, "_testimonials_rate", $post_testimonials_rate);
        return true;
    }

    function webiotsadminheadapptestimonails($hooks){
        echo "<style type=\"text/css\">";
        echo ".app_testimonails_ionicons .ion{cursor:pointer;text-align:center;border:1px solid #eee;font-size:32px;width:32px;height:32px;padding:6px;}";
        echo "</style>";
    }


}

/*
 *  Load the testimonials class and plugins
 */
new WEBIOTSTestimonails();




/*
 *  Get All the testimonials
 */
function shortcodewebiotstestimonials( $atts ) {


    if(isset( $atts['style'])){
        $slide = $atts['style'];//Getting Slide Templates
    }
    if(isset( $atts['category'])) {
        $category = $atts['category'];
    }else{
        $category ="";
    }

    global $wp_query,$post;


    $args = array(
        'posts_per_page'    => -1,
        'post_type'         => 'app_testimonials',
    );
    if(strlen($category)>0){
        $args['tax_query'] =  array(
            'relation'     => 'AND',
            array(
                'taxonomy' => 'testimonial-category',
                'field' => 'slug',
                'terms' => $category,
            )
        );
    }

    $loop = new WP_Query( $args );

    if( ! $loop->have_posts() ) {
        return false;
    }
    ob_start();
    if($slide=="grid1"){
        include_once(WEBIOTS_TESTIMONAILS_PATH.'/templates/slider/slide1.php');
    }else if($slide=="grid2"){
        include_once(WEBIOTS_TESTIMONAILS_PATH.'/templates/slider/slide2.php');
    }else if($slide=="list1"){
        include_once(WEBIOTS_TESTIMONAILS_PATH.'/templates/slider/slide3.php');
    }else if($slide=="list2"){
        include_once(WEBIOTS_TESTIMONAILS_PATH.'/templates/slider/slide4.php');
    }else if($slide=="video"){
        include_once(WEBIOTS_TESTIMONAILS_PATH.'/templates/slider/video.php');
    }else{
        include_once(WEBIOTS_TESTIMONAILS_PATH.'/templates/slider/slide1.php');
    }
    $output = ob_get_clean();

    return $output;



    wp_reset_postdata();
}





function shortcodewebiotstestimonialsform() {
    $site_key = get_option('site_key');
    if(strlen($site_key) > 10) {
        ?> <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php }

    ?>


    <form class="form-horizontal" id="new_post" name="new_post" method="post" enctype="multipart/form-data" >
        <fieldset>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="testimonials_name">Full Name</label>
                <div class="col-md-6">
                    <input id="testimonials_name" name="testimonials_name" type="text" placeholder="" class="form-control input-md" required="">

                </div>
            </div>

            <!-- File Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="thumbnail">Upload Image</label>
                <div class="col-md-6">
                    <input id="thumbnail" name="thumbnail" class="input-file" type="file">
                </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="testimonials_description">Description</label>
                <div class="col-md-6">
                    <textarea class="form-control" id="testimonials_description" name="testimonials_description"></textarea>
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="testimonials_author_name">Author Name</label>
                <div class="col-md-6">
                    <input id="testimonials_author_name" name="testimonials_author_name" type="text" placeholder="" class="form-control input-md" required="">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="testimonials_designation">Designation</label>
                <div class="col-md-6">
                    <input id="testimonials_designation" name="testimonials_designation" type="text" placeholder="" class="form-control input-md">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="testimonials_profile_url">Profile Url</label>
                <div class="col-md-6">
                    <input id="testimonials_profile_url" name="testimonials_profile_url" type="text" placeholder="" class="form-control input-md">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="testimonials_youtube">Youtube</label>
                <div class="col-md-6">
                    <input id="testimonials_youtube" name="testimonials_youtube" type="text" placeholder="4HkG8z3sa-0" class="form-control input-md">
                    <span class="help-block">Use Youtube ID example: 4HkG8z3sa-0 get from link: https://www.youtube.com/watch?v=4HkG8z3sa-0</span>
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="testimonials_fb_url">Facebook</label>
                <div class="col-md-6">
                    <input id="testimonials_fb_url" name="testimonials_fb_url" type="text" placeholder="" class="form-control input-md">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="testimonials_linkedin_url">Linkedin</label>
                <div class="col-md-6">
                    <input id="testimonials_linkedin_url" name="testimonials_linkedin_url" type="text" placeholder="" class="form-control input-md">

                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="testimonials_linkedin_url">Twitter</label>
                <div class="col-md-6">
                    <input id="testimonials_twitter" name="testimonials_twitter" type="text" placeholder="" class="form-control input-md">

                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="testimonials_linkedin_url">Rate</label>

                <div style="float: left;">
                    <input class="star star-5" id="star-5" type="radio" value="5" name="testimonials_rate"/>
                    <label class="star star-5" for="star-5"></label>
                    <input class="star star-4" id="star-4" type="radio" value="4" name="testimonials_rate"/>
                    <label class="star star-4" for="star-4"></label>
                    <input class="star star-3" id="star-3" type="radio" value="3" name="testimonials_rate"/>
                    <label class="star star-3" for="star-3"></label>
                    <input class="star star-2" id="star-2" type="radio" value="2" name="testimonials_rate"/>
                    <label class="star star-2" for="star-2"></label>
                    <input class="star star-1" id="star-1" type="radio" value="1" name="testimonials_rate"/>
                    <label class="star star-1" for="star-1">
                </div>
            </div>

            <?php    if(strlen($site_key) > 10) {  ?>
                <div class="form-group">
                    <label class="col-md-4 control-label" for=""></label>
                    <div class="col-md-6">
                        <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
                    </div>
                </div>
            <?php } ?>
            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="submit_testimonials"></label>
                <div class="col-md-4">
                    <button id="submit_testimonials" name="submit_testimonials" class="btn btn-success">Submit</button>
                </div>
            </div>
        </fieldset>
    </form>


    <?php
}

//load scripts
function webiotstestmonialsscriptsstyles() {
//Register Styles



    wp_register_style( 'googlefonts', 'https://fonts.googleapis.com/css?family=Roboto' );
    wp_register_style( 'font-awesome', 'http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_register_style( 'slick',plugins_url( 'assets/css/slick.css', dirname(__FILE__) ));
    wp_register_style( 'slick-theme',plugins_url( 'assets/css/slick-theme.css', dirname(__FILE__) ));
    wp_register_style( 'testimonials',plugins_url( 'assets/css/testimonials.css', dirname(__FILE__) ));



    ;
    wp_enqueue_style( 'googlefonts' );
    wp_enqueue_style( 'font-awesome' );
    wp_enqueue_style( 'slick' );
    wp_enqueue_style( 'slick-theme' );
    wp_enqueue_style( 'testimonials' );

//Register Scripts

    wp_register_script( 'slick', plugins_url( 'assets/js/slick.js', dirname(__FILE__) ),array(),'1.0',true);
    wp_register_script( 'slickfunction', plugins_url( 'assets/js/function.js', dirname(__FILE__) ),array(),'1.0',true);
    wp_enqueue_script('jquery');
    wp_enqueue_script( 'slick' );
    wp_enqueue_script( 'slickfunction' );


}








//load scripts
function testmonialsscriptsstyles1() {
//Register Styles


    wp_register_style( 'bootstrapcss', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' );
    wp_register_style( 'googlefonts', 'https://fonts.googleapis.com/css?family=Roboto' );
    wp_register_style( 'font-awesome', 'http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_register_style( 'stylecss',plugins_url( 'assets/css/style.css', dirname(__FILE__) ));


    wp_enqueue_style( 'bootstrapcss' );
    wp_enqueue_style( 'googlefonts' );
    wp_enqueue_style( 'font-awesome' );

//Register Scripts

    wp_register_script( 'jqueryjs', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js',array(),'1.11.3',true);
    wp_register_script( 'bootstrapjs', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js',array(),'3.3.6',true);
    wp_register_script( 'functionjs', plugins_url( 'assets/js/function.js', dirname(__FILE__) ),array(),'1.0',true);
    wp_enqueue_script('jqueryjs');
    wp_enqueue_script( 'bootstrapjs' );
    wp_enqueue_script( 'functionjs' );


}
/*
* Funtion to Insert Testimonials From Frontend
*/
include_once(ABSPATH . 'wp-includes/pluggable.php');

if (isset( $_POST['submit_testimonials'] ) )
{

    $testimonial_args = array(

        'post_title'    => $_POST['testimonials_name'],

        'post_content'  => $_POST['testimonials_description'],

        'post_status'   => 'pending',

        'post_type' => 'app_testimonials'

    );

    // insert the post into the database

    $postid = wp_insert_post( $testimonial_args, $wp_error);


    // var_dump($postid);
    // Sanitize the user input.
    $post_testimonials_name = sanitize_text_field($_POST["testimonials_name"] );
    // Update the meta field.
    update_post_meta($postid, "_testimonials_name", $post_testimonials_name);
    // Sanitize the user input.
    $post_testimonials_description = esc_html($_POST["testimonials_description"] );
    // Update the meta field.
    update_post_meta($postid, "_testimonials_description", $post_testimonials_description);
    // Sanitize the user input.
    $post_testimonials_author_name = sanitize_text_field($_POST["testimonials_author_name"] );
    // Update the meta field.
    update_post_meta($postid, "_testimonials_author_name", $post_testimonials_author_name);
    // Sanitize the user input.
    $post_testimonials_designation = sanitize_text_field($_POST["testimonials_designation"] );
    // Update the meta field.
    update_post_meta($postid, "_testimonials_designation", $post_testimonials_designation);
    // Sanitize the user input.
    $post_testimonials_profile_url = sanitize_text_field($_POST["testimonials_profile_url"] );
    // Update the meta field.
    update_post_meta($postid, "_testimonials_profile_url", $post_testimonials_profile_url);
    // Sanitize the user input.
    $post_testimonials_youtube = sanitize_text_field($_POST["testimonials_youtube"] );
    // Update the meta field.
    update_post_meta($postid, "_testimonials_youtube", $post_testimonials_youtube);
    // Sanitize the user input.
    $post_testimonials_fb_url = sanitize_text_field($_POST["testimonials_fb_url"] );
    // Update the meta field.
    update_post_meta($postid, "_testimonials_fb_url", $post_testimonials_fb_url);
    // Sanitize the user input.
    $post_testimonials_linkedin_url = sanitize_text_field($_POST["testimonials_linkedin_url"] );
    // Update the meta field.
    update_post_meta($postid, "_testimonials_linkedin_url", $post_testimonials_linkedin_url);
    // Sanitize the user input.
    $post_testimonials_twitter = sanitize_text_field($_POST["testimonials_twitter"] );
    // Update the meta field.
    update_post_meta($postid, "_testimonials_twitter", $post_testimonials_twitter);

    $post_testimonials_rate = sanitize_text_field($_POST["testimonials_rate"] );
    // Update the meta field.
    update_post_meta($postid, "_testimonials_rate", $post_testimonials_rate);


    if (!function_exists('wp_generate_attachment_metadata')){
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
    }
    if ($_FILES) {
        foreach ($_FILES as $file => $array) {
            if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
                return "upload error : " . $_FILES[$file]['error'];
            }
            $attach_id = media_handle_upload( $file, $postid );
        }
    }
    if ($attach_id > 0){
        //and if you want to set that image as Post  then use:
        update_post_meta($postid,'_thumbnail_id',$attach_id);
    }



}

/*
 * Load Testimonial by category
 *
 */

function webiotstestimonialcategory() {

    register_taxonomy(
        'testimonial-category',
        'app_testimonials',
        array(
            'label' => __( 'Category' ),
            'rewrite' => array( 'slug' => 'testimonial-category' ),
            'hierarchical' => true,
        )
    );
}
add_action( 'init', 'webiotstestimonialcategory' );



/*
 *
 */

/**
 * Display a custom taxonomy dropdown in admin
 */
add_action('restrict_manage_posts', 'tsmfilterposttypebytaxonomy');
function tsmfilterposttypebytaxonomy() {
    global $typenow;
    $post_type = 'app_testimonials'; // change to your post type
    $taxonomy  = 'testimonial-category'; // change to your taxonomy
    if ($typenow == $post_type) {
        $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => __("Show All {$info_taxonomy->label}"),
            'taxonomy'        => $taxonomy,
            'name'            => $taxonomy,
            'orderby'         => 'name',
            'selected'        => $selected,
            'show_count'      => true,
            'hide_empty'      => true,
        ));
    };
}
/**
 * Filter posts by taxonomy in admin
 */
add_filter('parse_query', 'tsmconvertidtoterminquery');
function tsmconvertidtoterminquery($query) {
    global $pagenow;
    $post_type = 'app_testimonials'; // change to your post type
    $taxonomy  = 'testimonial-category'; // change to your taxonomy
    $q_vars    = &$query->query_vars;
    if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
        $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
        $q_vars[$taxonomy] = $term->slug;
    }
}

function addonvcwebiotstestimonials() {
    vc_map( array(
        "name" => __("Webiots Testimonials"),
        "base" => "webiots-tm",
        "category" => __('Testimonials'),
        "icon"=> "vc_testimonial_icon",
        "params" => array(
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => __("Layouts"),
                "param_name" => "style",
                "value" => __(array("grid1","grid2","list1","list2","video"),"app_testimonials"),
                "description" => __("Select the layout suitable according to your need")
            )
        )
    ));
}