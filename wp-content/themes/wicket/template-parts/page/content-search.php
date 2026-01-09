<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wicket
 */

?>






<div class="search-card complain-box">
	<div class="focus-part-complain">

		<div class="name-dig">

			<div class="img"><?php the_post_thumbnail(); ?></div>

			<div class="title-cat">
				<div class="title"><?php the_title(); ?></div>
				<div class="cat-dig <?php echo esc_attr(implode(' ', get_post_class())); ?>">
					<?php 
					$postcat = get_the_category(get_the_ID());
					if(isset($postcat[0])){
                    //var_dump($postcat);

					$postcat_name = $postcat[0]->name;
					// var_dump(esc_html( $postcat[0]->name )); 
					//echo get_the_category_list(', '); ?>
					<?php //echo "বাংলাদেশ"; ?>


					<?php
					if ($postcat_name == "Customer Service") {
						echo "কাস্টমার সার্ভিস";
					} elseif ($postcat_name == "Sub Admin") {
						echo "সাব-এডমিন";
					} elseif ($postcat_name == "Admin") {
						echo "এডমিন";
					} elseif ($postcat_name == "Super Agent") {
						echo "সুপার এজেন্ট";
					} elseif ($postcat_name == "Master Agent") {
						echo "মাস্টার এজেন্ট";
					}else {
						echo "";
					}
				    }
					?>


				</div>
			</div>

		</div>

		<div class="user-id">
			<p class="id-ti">আইডি</p>
			<div class="id-value"><?php echo get_post_meta(get_the_ID(), 'user_id', true); ?></div>
		</div>


	</div>
	<div class="social-connection-group complain-ss">

	    <?php if(get_post_meta(get_the_ID(), 'whatsapp_primary', true) != ''){ ?>
		<div class="group-1">
			<span class="value-1">WhatsApp <span>(Primary)</span></span>
			<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_primary_link', true); ?> " target="_blank"><?php echo get_post_meta(get_the_ID(), 'whatsapp_primary', true); ?></a></span>
			<span class="copy-btn">Copy Number</span>
			<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_primary_link', true); ?> " target="_blank">Message</a></span>
		</div>
		<?php } ?>

		<?php if(get_post_meta(get_the_ID(), 'whatsapp_secondary', true) != ''){ ?>
		<div class="group-2">
			<span class="value-1">WhatsApp <span>(Secondary)</span></span>
			<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary_link', true); ?> " target="_blank"><?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary', true); ?></a></span>
			<span class="copy-btn">Copy Number</span>
			<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary_link', true); ?>" target="_blank">Message</a></span>
		</div>
		<?php } ?>

        <?php if(get_post_meta(get_the_ID(), 'messenger', true) != ''){ ?>  
		<div class="group-3">
			<span class="value-1">Messenger</span>
			<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'messenger', true); ?>" target="_blank"><?php echo get_post_meta(get_the_ID(), 'messenger', true); ?></a></span>
			<span class="copy-btn">Copy</span>
			<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'messenger', true); ?>" target="_blank">Message</a></span>
		</div>
		<?php } ?>

	</div>
</div>








