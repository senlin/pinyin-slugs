<?php
/**
 * Render the Plugin options form
 * @since 2014.07.29
 * @modified 2.1.3
 */
function sops_render_form() { ?>

	<div class="wrap">

		<!-- Display Plugin Header, and Description -->
		<h1><?php _e( 'Pinyin Slugs Settings', 'so-pinyin-slugs' ); ?></h1>

		<div class="pinyinslugs-intro">

			<h3 class="hndle"><span><?php _e( 'Important Information', 'so-pinyin-slugs' ); ?></span></h3>

			<div class="inside">

				<p><?php _e( 'Pinyin Slugs automatically transforms Chinese character titles into pinyin slugs.', 'so-pinyin-slugs' ); ?></p>

				<p><?php _e( 'Pinyin Slugs will only work for new slugs, not on existing slugs. You can choose to rewrite the slugs of existing permalinks manually.', 'so-pinyin-slugs' ); ?></p>

				<p><?php _e( '<strong>Note:</strong> If you choose to manually rewrite the slugs of existing permalinks, then please keep in mind that the previous permalinks that have been indexed by search engines (like Google, Baidu) do not change automatically. This may impact your site ranking if you just leave it like that.', 'so-pinyin-slugs' ); ?></p>

				<p><?php _e( 'In that case you might want to add 301 Permanent Redirects for those specific links to your .htaccess file.', 'so-pinyin-slugs' ); ?></p>

				<p><?php _e( 'A <strong>limitation of Pinyin Slugs</strong> is that it completely ignores anything other than Chinese characters in your slug. For example: if you have a product name with a number in it, then the plugin ignores this number. You will have to add that manually to the slug if needed. Likewise, if you\'re writing an English title and you combine it with some Chinese characters, the resulting slug will only contain the pinyin of those characters; in other words the English is completely ignored.', 'so-pinyin-slugs' ); ?></p>
			</div> <!-- end .inside -->

		</div> <!-- end .pinyinslugs-intro -->

		<div id="sops-settings">

			<!-- Beginning of the Plugin Options Form -->
			<form method="post" action="options.php">

				<?php settings_fields( 'sops_plugin_options' ); ?>

				<?php $options = get_option( 'sops_options' ); ?>

				<table class="form-table"><tbody>

					<tr valign="top">
						<th scope="row">
							<label for="sops-sluglength"><?php _e( 'Slug Length', 'so-pinyin-slugs' ); ?></label>
						</th>

						<td>
							<input name="sops_options[slug_length]" type="number" id="slug_length" value="<?php echo $options['slug_length']; ?>" />
							<p class="description"><?php _e( 'By default the maximum slug length is set to 100 letters; anything over that limit will not be converted. If you want to change this limit, you can do that here.', 'so-pinyin-slugs' ); ?></p>
							<input type="hidden" name="action" value="update" />
							<input type="hidden" name="page_options" value="<?php echo $options['slug_length']; ?>" />
						</td>
					</tr>

				</tbody></table> <!-- end .tbody end table -->

				<p class="submit">

					<input type="submit" class="button-primary" value="<?php _e( 'Save Settings', 'so-pinyin-slugs' ) ?>" />

				</p>

			</form>

		</div><!-- #sops-settings -->

		<p class="rate-this-plugin">
			<?php
			/* Translators: 1 is link to WP Repo */
			printf( __( 'If you have found this plugin at all useful, please give it a favourable rating in the <a href="%s" title="Rate this plugin!">WordPress Plugin Repository</a>.', 'so-pinyin-slugs' ),
				esc_url( 'https://wordpress.org/support/view/plugin-reviews/so-pinyin-slugs' )
			);
			?>
		</p>

		<p class="support">
			<?php
			/* Translators: 1 is link to Github Repo */
			printf( __( 'If you have an issue with this plugin or want to leave a feature request, please note that I give <a href="%s" title="Support or Feature Requests via Github">support via Github</a> only.', 'so-pinyin-slugs' ),
				esc_url( 'https://github.com/senlin/pinyin-slugs/issues' )
			);
			?>
		</p>

		<div class="author postbox">

			<h3 class="hndle">
				<span><?php _e( 'About the Author', 'so-pinyin-slugs' ); ?></span>
			</h3>

			<div class="inside">
				<div class="top">
					<img class="author-image" src="<?php echo esc_url( plugins_url( 'so-pinyin-slugs/images/pietbos-80x80.jpg' ) ); ?>" alt="plugin author Pieter Bos" width="80" height="80" />
					<p>
						<?php printf( __( 'Hi, my name is Pieter Bos, I hope you like this plugin! Please check out any of my other plugins on <a href="%s" title="SO WP Plugins">SO WP Plugins</a>. You can find out more information about me via the following links:', 'so-pinyin-slugs' ),
							esc_url( 'https://so-wp.com' )
						); ?>
					</p>
				</div> <!-- end .top -->

				<ul>
						<li><a href="https://bohanintl.com" target="_blank" title="BHI Consulting for Websites"><?php _e( 'BHI Consulting for Websites', 'so-pinyin-slugs' ); ?></a></li>
						<li><a href="https://www.linkedin.com/in/pieterbos83/" target="_blank" title="LinkedIn profile"><?php _e( 'LinkedIn', 'so-pinyin-slugs' ); ?></a></li>
						<li><a href="https://so-wp.com" target="_blank" title="SO WP"><?php _e( 'SO WP', 'so-pinyin-slugs' ); ?></a></li>
						<li><a href="https://github.com/senlin" title="on Github"><?php _e( 'Github', 'so-pinyin-slugs' ); ?></a></li>
						<li><a href="https://bohanintl.com/wptips" target="_blank" title="WP TIPS"><?php _e( 'WP Tips', 'so-pinyin-slugs' ); ?></a></li>
						<li><a href="https://profiles.wordpress.org/senlin/" title="on WordPress.org"><?php _e( 'WordPress.org Profile', 'so-pinyin-slugs' ); ?></a></li>
				</ul>

			</div> <!-- end .inside -->

		</div> <!-- end .postbox -->

	</div> <!-- end .wrap -->

<?php }
