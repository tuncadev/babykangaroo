<?php
if ( ! class_exists( 'Martfury_Social_Links_Widget' ) ) {
	class Martfury_Social_Links_Widget extends WP_Widget {
		protected $default;
		protected $socials;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->socials = array(
				'facebook'   => esc_html__( 'Facebook', 'martfury' ),
				'twitter'    => esc_html__( 'Twitter', 'martfury' ),
				'googleplus' => esc_html__( 'Google Plus', 'martfury' ),
				'youtube'    => esc_html__( 'Youtube', 'martfury' ),
				'tumblr'     => esc_html__( 'Tumblr', 'martfury' ),
				'linkedin'   => esc_html__( 'Linkedin', 'martfury' ),
				'pinterest'  => esc_html__( 'Pinterest', 'martfury' ),
				'flickr'     => esc_html__( 'Flickr', 'martfury' ),
				'instagram'  => esc_html__( 'Instagram', 'martfury' ),
				'dribbble'   => esc_html__( 'Dribbble', 'martfury' ),
				'skype'      => esc_html__( 'Skype', 'martfury' ),
				'rss'        => esc_html__( 'RSS', 'martfury' ),
				'telegram'   => esc_html__( 'Telegram', 'martfury' ),
				'whatsapp'   => esc_html__( 'Whatsapp', 'martfury' ),
				'viber'   => esc_html__( 'Viber', 'martfury' ),
				'vk'   => esc_html__( 'Vkontakte', 'martfury' ),
				'ok'   => esc_html__( 'Odnoklassniki ', 'martfury' ),
				'tiktok'   => esc_html__( 'Tiktok ', 'martfury' ),

			);
			$this->default = array(
				'title' => '',
			);
			foreach ( $this->socials as $k => $v ) {
				$this->default["{$k}_title"] = $v;
				$this->default["{$k}_url"]   = '';
			}

			parent::__construct(
				'social-links-widget',
				esc_html__( 'Martfury - Social Links', 'martfury' ),
				array(
					'classname'   => 'social-links-widget social-links',
					'description' => esc_html__( 'Display links to social media networks.', 'martfury' ),
				),
				array( 'width' => 600, 'height' => 350 )
			);
		}

		/**
		 * Outputs the HTML for this widget.
		 *
		 * @param array $args An array of standard parameters for widgets in this theme
		 * @param array $instance An array of settings for this widget instance
		 *
		 * @return void Echoes it's output
		 */
		function widget( $args, $instance ) {
			$instance = wp_parse_args( $instance, $this->default );

			extract( $args );
			echo $before_widget;

			if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
				echo $before_title . $title . $after_title;
			}

			echo '<div class="social-links-list">';

			foreach ( $this->socials as $social => $label ) {
				if ( ! empty( $instance[ $social . '_url' ] ) ) {
					$icon_social = 'social_' . $social;
					if ( $social == 'telegram' ) {
						$icon_social = 'fa fa-' . $social;
					} elseif ( $social == 'whatsapp' ) {
						$icon_social = 'ion-social-' . $social;
					} elseif ( $social == 'vk' ) {
						$icon_social = 'fa fa-' . $social;
					}

					if ( $social == 'viber' ) {
						printf(
							'<a href="%s" class="share-%s tooltip-enable share-social" rel="nofollow" title="%s" data-toggle="tooltip" data-placement="top" target="_blank">%s</a>',
							 $instance[ $social . '_url' ],
							esc_attr( $social ),
							esc_attr( $instance[ $social . '_title' ] ),
                            '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><path d="M250.1,0C211.3,0,115.5,4.1,64.5,53c-36.7,34.7-51,87.7-53,151c-4.1,100,18.3,163.2,55.1,195.8c8.2,6.1,28.6,22.5,67.3,34.7v51c0,0,0,20.4,12.2,24.5c2,0,4.1,2,6.1,2c12.2,0,22.4-14.3,36.7-28.6c12.2-14.3,22.5-24.5,28.6-34.7H246h16.3c38.8,0,134.6-4.1,185.6-53c36.7-36.7,51-89.8,51-157.1c2-10.2,2-22.4,2-34.7c-2-75.5-24.5-126.5-55.1-155C433.7,38.8,380.7,0,266.4,0H250.1z M246,38.8h14.3h2.1h2c108.1,0,148.9,34.7,153,38.8c24.5,20.4,38.8,65.3,40.8,124.4v8.2c2,12.2,2,22.4,2,28.5c-2,61.2-14.3,102-38.8,128.5C378.6,405.9,288.9,408,264.4,408h-14.3h-2h-2h-20.4L193,444.7l-20.4,22.4
                            l-4.1,6.1c-4.1,4.1-10.2,12.3-14.3,14.3v-4.1v-87.7c-40.8-10.2-57.1-24.5-61.2-28.6C64.5,342.7,48.2,283.5,52.3,204v-20.4
                            c4.1-49,16.3-81.6,36.7-104C131.8,40.8,221.6,38.8,246,38.8z M244,83.6c-10.2,0-10.2,14.3,0,14.3c75.5,0,140.8,51,140.8,146.9
                            c0,10.2,14.3,10.2,14.3,0C399,140.7,329.7,81.6,244,83.6z M163.2,102.5c-4.3-0.5-8.9,0.5-13,3.5c-20.4,10.2-40.8,30.6-34.7,53.1
                            c0,0,4.1,18.3,26.5,57.1c12.2,18.4,22.4,34.7,32.6,46.9c10.2,14.3,26.5,30.6,42.8,42.8c32.6,26.5,83.7,53,106.1,59.2
                            c20.4,6.1,42.8-14.3,53-34.7c4.1-8.2,2-18.3-6.1-24.5c-12.2-12.2-32.6-26.5-46.9-34.7c-10.2-6.1-22.5-2.1-26.5,4.1l-10.2,12.2
                            c-4.1,6.1-14.3,6.1-14.3,6.1c-67.3-18.4-85.7-87.7-85.7-87.7s0-8.1,6.1-14.3l12.2-10.2c6.1-4.1,10.2-16.3,4.1-26.5
                            c-4.1-6.1-10.2-18.3-16.3-24.5c-6.1-8.2-18.4-22.4-18.4-22.4C171.6,105.1,167.5,103,163.2,102.5z M258.3,124.4
                            c-10.2-2-12.2,14.3-2,14.3c57.1,4.1,89.8,42.8,87.7,91.8c-2,10.2,14.3,10.2,14.3,0C360.3,173.4,323.6,126.5,258.3,124.4z
                             M264.4,163.2c-10.2-2-10.2,14.3,0,14.3c24.5,0,36.7,14.3,36.7,38.8c2,10.2,16.3,10.2,16.3,0C315.4,183.6,297,163.2,264.4,163.2z"/></g></svg>'
						);
					} elseif ( $social == 'tiktok' ) {
						printf(
							'<a href="%s" class="share-%s tooltip-enable share-social" rel="nofollow" title="%s" data-toggle="tooltip" data-placement="top" target="_blank">%s</a>',
							 $instance[ $social . '_url' ],
							esc_attr( $social ),
							esc_attr( $instance[ $social . '_title' ] ),
                            '<svg viewBox="0 0 32 32">
							<path fill="currentColor" d="M16.707 0.027c1.747-0.027 3.48-0.013 5.213-0.027 0.107 2.040 0.84 4.12 2.333 5.56 1.493 1.48 3.6 2.16 5.653 2.387v5.373c-1.92-0.067-3.853-0.467-5.6-1.293-0.76-0.347-1.467-0.787-2.16-1.24-0.013 3.893 0.013 7.787-0.027 11.667-0.107 1.867-0.72 3.72-1.8 5.253-1.747 2.56-4.773 4.227-7.88 4.28-1.907 0.107-3.813-0.413-5.44-1.373-2.693-1.587-4.587-4.493-4.867-7.613-0.027-0.667-0.040-1.333-0.013-1.987 0.24-2.533 1.493-4.96 3.44-6.613 2.213-1.92 5.307-2.84 8.2-2.293 0.027 1.973-0.053 3.947-0.053 5.92-1.32-0.427-2.867-0.307-4.027 0.493-0.84 0.547-1.48 1.387-1.813 2.333-0.28 0.68-0.2 1.427-0.187 2.147 0.32 2.187 2.427 4.027 4.667 3.827 1.493-0.013 2.92-0.88 3.693-2.147 0.253-0.44 0.533-0.893 0.547-1.413 0.133-2.387 0.080-4.76 0.093-7.147 0.013-5.373-0.013-10.733 0.027-16.093z"></path>
							</svg>'
						);
					}  elseif ( $social == 'ok' ) {
						printf(
							'<a href="%s" class="share-%s tooltip-enable share-social" rel="nofollow" title="%s" data-toggle="tooltip" data-placement="top" target="_blank">%s</a>',
							 $instance[ $social . '_url' ],
							esc_attr( $social ),
							esc_attr( $instance[ $social . '_title' ] ),
                            '<svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="odnoklassniki" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M275.1 334c-27.4 17.4-65.1 24.3-90 26.9l20.9 20.6 76.3 76.3c27.9 28.6-17.5 73.3-45.7 45.7-19.1-19.4-47.1-47.4-76.3-76.6L84 503.4c-28.2 27.5-73.6-17.6-45.4-45.7 19.4-19.4 47.1-47.4 76.3-76.3l20.6-20.6c-24.6-2.6-62.9-9.1-90.6-26.9-32.6-21-46.9-33.3-34.3-59 7.4-14.6 27.7-26.9 54.6-5.7 0 0 36.3 28.9 94.9 28.9s94.9-28.9 94.9-28.9c26.9-21.1 47.1-8.9 54.6 5.7 12.4 25.7-1.9 38-34.5 59.1zM30.3 129.7C30.3 58 88.6 0 160 0s129.7 58 129.7 129.7c0 71.4-58.3 129.4-129.7 129.4s-129.7-58-129.7-129.4zm66 0c0 35.1 28.6 63.7 63.7 63.7s63.7-28.6 63.7-63.7c0-35.4-28.6-64-63.7-64s-63.7 28.6-63.7 64z" class=""></path></svg>'
						);
					} else {
						printf(
							'<a href="%s" class="share-%s tooltip-enable share-social" rel="nofollow" title="%s" data-toggle="tooltip" data-placement="top" target="_blank"><i class="social %s"></i></a>',
							esc_url( $instance[ $social . '_url' ] ),
							esc_attr( $social ),
							esc_attr( $instance[ $social . '_title' ] ),
							esc_attr( $icon_social )
						);
                    }

				}
			}

			echo '</div>';

			echo $after_widget;
		}

		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @param array $instance
		 *
		 * @return array
		 */
		function form( $instance ) {
			$instance = wp_parse_args( $instance, $this->default );
			?>

            <p>
                <label
                        for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'martfury' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo esc_attr( $instance['title'] ); ?>"/>
            </p>
			<?php
			foreach ( $this->socials as $social => $label ) {
				printf(
					'<div class="mr-recent-box">
					<label>%s</label>
					<p><input type="text" class="widefat" name="%s" placeholder="%s" value="%s"></p>
				</div>',
					$label,
					$this->get_field_name( $social . '_url' ),
					esc_html__( 'URL', 'martfury' ),
					$instance[ $social . '_url' ]
				);
			}
		}
	}
}