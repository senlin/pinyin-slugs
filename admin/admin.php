<?php
/**
 * A function for displaying messages in the admin.  It will wrap the message in the appropriate <div> with the 
 * custom class entered.  The updated class will be added if no $class is given.
 *
 * @since 0.1
 * @param $class string Class the <div> should have.
 * @param $message string The text that should be displayed.
 */
function PinyinSlug_admin_message( $class = 'updated', $message = '' ) {

	echo '<div class="' . ( !empty( $class ) ? esc_attr( $class ) : 'updated' ) . '"><p><strong>' . $message . '</strong></p></div>';
}

function setPinyinSlugOptions() {
?>

<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>

	<h2><?php _e( 'SO Pinyin Slugs Plugin Settings', 'so-pinyin-slugs' ); ?></h2>
	
	<div class="post-box-container column-1 normal" style="float:left;width:66%;">

		<div id="pinyinslugs-intro" class="postbox" style="display:block;margin:30px 10px 10px 0;">
			
			<h3 class="hndle" style="padding:5px;"><span><?php _e( 'Important Information', 'so-pinyin-slugs' ); ?></span></h3>
			
			<div class="inside">
	
				<p><?php _e( 'SO Pinyin Slugs automatically transforms Chinese character titles into pinyin slugs.', 'so-pinyin-slugs' ); ?></p>
			
				<p><?php _e( 'SO Pinyin Slugs will only work for new slugs, not on existing slugs. You can choose to rewrite the slugs of existing permalinks manually.', 'so-pinyin-slugs' ); ?></p>
			
				<p><?php _e( '<strong>Note:</strong> If you choose to manually rewrite the slugs of existing permalinks, then please keep in mind that the previous permalinks that have been indexed by search engines (like Google, Baidu) do not change automatically. This may impact your site ranking if you just leave it like that.', 'so-pinyin-slugs' ); ?></p>
			
				<p><?php _e( 'In that case you might want to add 301 Permanent Redirects for those specific links to your .htaccess file.', 'so-pinyin-slugs' ); ?></p>
			
				<p><?php _e( 'A <strong>limitation of SO Pinyin Slugs</strong> is that it completely ignores anything other than Chinese characters in your slug. For example: if you have a product name with a number in it, then the plugin ignores this number. You will have to add that manually to the slug if needed. Likewise, if you\'re writing an English title and you combine it with some Chinese characters, the resulting slug will only contain the pinyin of those characters; in other words the English is completely ignored.', 'so-pinyin-slugs' ); ?></p>
			</div> <!-- end .inside -->
	
		</div> <!-- end .post-box -->
		
	
		<div id="pinyinslugs-settings" class="postbox" style="margin:30px 10px 10px 0;">
	
			<h3 class="title hndle" style="padding:5px;"><span><?php _e( 'SO Pinyin Slugs - Slug Length', 'so-pinyin-slugs' ); ?></span></h3>
	
			<div class="inside">
	
				<form method="post" action="options.php">
			
					<?php wp_nonce_field( 'update-options' ); ?>
			
					<p><label for="PinyinSlug_length"><?php _e( 'Slug Length', 'so-pinyin-slugs' ); ?></label></p>
			
					<p><input name="PinyinSlug_length" type="text" id="PinyinSlug_length" value="<?php echo get_option( 'PinyinSlug_length',100 ); ?>" /></p>
							
					<p><?php _e( 'By default the maximum slug length is set to 100 letters; anything over that limit will not be converted. If you want to change this limit, you can do that here.', 'so-pinyin-slugs' ); ?></p>
			
					<input type="hidden" name="action" value="update" />
			
					<input type="hidden" name="page_options" value="PinyinSlug_length" />
			
					<p>
						
						<input type="submit" class="button-primary" value="<?php _e( ' Save Changes', 'so-pinyin-slugs' ); ?>" />
					
					</p>
					
				</form>
			
			</div><!--  end .inside -->
		
		</div> <!-- end .postbox -->
	
	</div> <!-- end .column-1 -->
	
	<div class="post-box-container column-2 side" style="float:right;width:32%;">

		<div id="pinyinplugins-rate" class="postbox" style="margin:30px 10px 10px 0;">
	
			<h3 class="hndle" style="padding:5px;"><span><?php _e( 'Rate the SO Pinyin Slugs plugin', 'so-pinyin-slugs' ); ?></span></h3>
	
			<div class="inside">
					
				<?php 
					
					printf( '<p>' . __( 'If you have found this plugin useful, please give it a favourable rating in the %1$s and/or consider contributing to the plugin over at %2$s', 'so-pinyin-slugs' ), 
							'<a href="' . esc_url( 'http://wordpress.org/plugins/so-pinyin-slugs/' ) . '" title="' . esc_attr__( 'Rate this plugin!', 'so-pinyin-slugs' ) . '">' . __( 'WordPress Plugin Repository', 'so-pinyin-slugs' ) . '</a>',
							'<a href="' . esc_url( 'https://github.com/senlin/so-pinyin-slugs' ) . '" title="' . esc_attr__( 'Contribute to SO Pinyin Slugs over at Github', 'so-pinyin-slugs' ) . '">' . __( 'Github', 'so-pinyin-slugs' ) . '</a>.'
						. '</p>' );
						
				?>

			</div><!--  end .inside -->
		
		</div> <!-- end .postbox -->

		<div id="pinyinplugins-support" class="postbox" style="margin:30px 10px 10px 0;">
	
			<h3 class="hndle" style="padding:5px;"><span><?php _e( 'Support', 'so-pinyin-slugs' ); ?></span></h3>
	
			<div class="inside">
					
				<?php 
	
					echo '<p>'; printf( __( 'I will only support this plugin through %s. Therefore, if you have any questions, need help and/or want to make a feature request, please open an issue over at Github. You can also browse through open and closed issues to find what you are looking for and perhaps even help others.<br /><br /><strong>PLEASE DO NOT POST YOUR ISSUES VIA THE WORDPRESS FORUMS</strong><br /><br />Thank you for your understanding and cooperation.', 'so-pinyin-slugs' ),
						'<strong><a href="' . esc_url( 'https://github.com/so-wp/so-pinyin-slugs/issues' ) . '" title="' . esc_attr__( 'Support via Github', 'so-pinyin-slugs' ) . '">' . __( 'Github', 'so-pinyin-slugs' ) . '</a></strong>'
					); echo '</p>';
					
					?>
	
			</div><!--  end .inside -->
		
		</div> <!-- end .postbox -->
	
		<div id="about-pinyinslugs" class="postbox" style="margin:30px 10px 10px 0;">
	
			<h3 class="hndle" style="padding:5px;"><span><?php _e( 'About the Author', 'so-pinyin-slugs' ); ?></span></h3>
	
			<div class="inside">
				<img src="http://www.gravatar.com/avatar/<?php echo md5( 'info@senlinonline.com' ); ?>" style="float: left; margin-right: 10px; padding: 3px; border: 1px solid #DFDFDF;"/>
				<p style="height: 60px; padding-top: 20px">
					<?php printf( __( 'Hi, my name is Piet Bos, I hope you like this plugin! Please check out any of my other plugins on <a href="%s" title="SO WP Plugins">SO WP Plugins</a>. You can find out more information about me via the following links:', 'so-pinyin-slugs' ),
					esc_url( 'http://so-wp.github.io/' )
					); ?>
				</p>
				
				<ul style="clear: both; margin-top: 20px;">
					<li><a href="http://senlinonline.com/" target="_blank" title="Senlin Online"><?php _e('Senlin Online', 'pinyinslugs'); ?></a></li>
					<li><a href="http://wpti.ps/" target="_blank" title="WP TIPS"><?php _e('WP Tips', 'dbfw'); ?></a></li>
					<li><a href="https://plus.google.com/108543145122756748887" target="_blank" title="Piet on Google+"><?php _e( 'Google+', 'so-pinyin-slugs' ); ?></a></li>
					<li><a href="http://cn.linkedin.com/in/pietbos" target="_blank" title="LinkedIn profile"><?php _e( 'LinkedIn', 'so-pinyin-slugs' ); ?></a></li>
					<li><a href="http://twitter.com/SenlinOnline" target="_blank" title="Twitter"><?php _e( 'Twitter: @piethfbos', 'so-pinyin-slugs' ); ?></a></li>
					<li><a href="http://github.com/senlin" title="on Github"><?php _e( 'Github', 'so-pinyin-slugs' ); ?></a></li>
					<li><a href="http://profiles.wordpress.org/senlin/" title="on WordPress.org"><?php _e( 'WordPress.org Profile', 'so-pinyin-slugs' ); ?></a></li>
				</ul>
			
			</div> <!-- end .inside -->
		
		</div> <!-- end .post-box -->
	
	</div> <!-- end .column-2 -->

</div>
<?php
}