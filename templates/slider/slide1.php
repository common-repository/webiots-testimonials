<section  itemscope itemtype="http://schema.org/Product">
    <div itemprop="name" content="product-reviews" class="regular wi-slider">
    <?php
    $reviewCount = 0;
    $aggretated_rating = 0;

    while( $loop->have_posts() ) {

        $loop->the_post();
        ?>

        <div class="wi-slide" itemprop="review" itemscope itemtype="http://schema.org/Review">
            <div class="wi-testimonial"  itemprop="reviewBody">
                <div class="wi-user-img">
                    <?php
                    if (has_post_thumbnail( $post->ID ) ) {
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
                    ?>
                    <img src="<?php echo $image[0]; ?>" class="center-block img-circle wi-img-fluid" itemprop="image" content="<?php echo $image[0]; ?>">

                    <?php
                    }
                    ?>
                </div>

                <!--Content-->
                <div itemprop="author" itemscope itemtype="http://schema.org/Person"><h3 class="wi-mb-3"    itemprop="name"><?php echo get_post_meta($post->ID,"_testimonials_author_name", true); ?></h3></div>
                <h6 class="wi-mb-3 wi-font-bold wi-grey-text"><?php echo get_post_meta($post->ID, "_testimonials_designation", true); ?></h6>
                <div class="wi-social-icons">
                    <ul class="list-inline">
                        <?php
                        $fburl = get_post_meta($post->ID, "_testimonials_fb_url", true);
                        if(strlen($fburl)>0){
                            ?>
                            <li><a href="<?php echo $fburl; ?>" target="_blank"> <i class="fa fa-facebook" aria-hidden="true" ></i></a></li>
                        <?php
                        }
                        $linkedurl = get_post_meta($post->ID, "_testimonials_linkedin_url", true);
                        if(strlen($linkedurl)>0){
                            ?>
                            <li><a href="<?php echo $linkedurl; ?>" target="_blank"> <i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            <?php
                        }
                        $twitterurl = get_post_meta($post->ID, "_testimonials_twitter", true);
                        if(strlen($twitterurl)>0){
                            ?>
                            <li><a href="<?php echo $twitterurl; ?>" target="_blank"> <i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <p><i class="fa fa-quote-left"></i>  <?php echo get_post_meta($post->ID,"_testimonials_description", true); ?></p>

                <!--Review-->
                <div class="wi-orange-text">
                    <?php $value_testimonials_rate = get_post_meta($post->ID, "_testimonials_rate", true);

                    if($value_testimonials_rate>0){
                        for($i=0;$i<$value_testimonials_rate;$i++){
                            echo "<i class=\"fa fa-star\"></i>";
                        } }else{
                        $value_testimonials_rate =1;
                        echo "<i class=\"fa fa-star\"></i>";
                    }
                    $aggretated_rating = intval($value_testimonials_rate)+$aggretated_rating;
                    ?>
                </div>
                <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                    <meta itemprop="worstRating" content = "1"/>
                    <meta itemprop="ratingValue" content = "<?php echo $value_testimonials_rate; ?>"/>
                    <meta itemprop="bestRating" content="5"/>
                </div>
            </div>
        </div>
        <?php
        $reviewCount++;

    }
    ?>
    <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <?php
        $ratingValue = (int)($aggretated_rating/$reviewCount);
        ?>
        <meta itemprop="ratingValue" content="<?php echo $ratingValue; ?>"/>
        <meta itemprop="reviewCount" content="<?php echo $reviewCount; ?>"/>
    </div>
</div>

</section>
