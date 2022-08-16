<?php
/**
 * Simple Show Card
 *
 * @package    Simple Show Card
 * @subpackage SimpleShowCard Main Functions
 */

$simpleshowcard = new SimpleShowCard();

/** ==================================================
 * Main Functions
 */
class SimpleShowCard {

	/** ==================================================
	 * Construct
	 *
	 * @since   1.00
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'simpleshowcard_init' ) );
		add_action( 'wp_footer', array( $this, 'load_style' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_style' ) );

	}

	/** ==================================================
	 * Attribute block
	 *
	 * @since 1.00
	 */
	public function simpleshowcard_init() {

		$simpleshowcard_settings = get_option(
			'simpleshowcard_settings',
			array(
				'card_id' => null,
				'card_lang' => 'en',
				'card_imgsize' => 200,
                'text_color' => '#333333',
			)
        );

        /* 
        Options:
         - card_id : card id
         - card_lang : language
         - card_imgsize : credit card image width
         - text_color: institution name color
        */
        
		/* 'target_blank' from ver 1.08 */
		if ( ! array_key_exists( 'target_blank', $simpleshowcard_settings ) ) {
			$simpleshowcard_settings['target_blank'] = false;
		}

		register_block_type(
			'simple-show-card/simpleshowcard-block',
			array(
				'editor_script'   => 'simpleshowcard-block',
				'render_callback' => array( $this, 'simpleshowcard_func' ),
				'attributes'      => array(
					'card_id'         => array(
						'type'    => 'string',
						'default' => $simpleshowcard_settings['card_id'],
					),
					'card_lang' => array(
						'type'    => 'string',
						'default' => $simpleshowcard_settings['card_lang'],
					),
					'card_imgsize' => array(
						'type'    => 'number',
						'default' => $simpleshowcard_settings['card_imgsize'],
					),
					'text_color'   => array(
						'type'    => 'string',
						'default' => $simpleshowcard_settings['text_color'],
					),
				),
			)
		);

        add_shortcode( 'simpleshowcard', array( $this, 'simpleshowcard_func' ) );
        
    }
    
    /** ==================================================
	 * Short code
	 *
	 * @param array  $atts  attributes.
	 * @param string $content  contents.
	 * @return string $content  contents.
	 * @since 1.00
	 */
	public function simpleshowcard_func( $atts, $content = null ) {

		$attributes = shortcode_atts(
			array(
				'card_id'       => '',
				'card_lang'     => 'en',
				'card_imgsize'  => 200,
			),
			$atts
        );

        $settings_tbl = get_option(
			'simpleshowcard_settings',
			array(
				'card_id'       => null,
				'card_lang'     => 'en',
				'card_imgsize'  => 200,
			)
		);

        return do_shortcode( $this->simpleshowcard( $attributes ) );
        
    }

    /** ==================================================
	 * Show Credit Card
	 *
	 * @param array $settings  settings.
	 * @return string $content  contents.
	 * @since 1.00
	 */
	private function simpleshowcard( $settings ) {

        $contents = null;

        if ( $settings['card_id'] ) {
            // get json by credit card id
            $json       = file_get_contents('https://hardbacon-test.s3.amazonaws.com/test/' . $settings['card_id'] . '.json');
            $cardInfo   = json_decode($json);

            $cardImage  = "https://hardbacon-prod.s3.us-east-1.amazonaws.com/comparators/" . $settings['card_id'] . "_card";
            $instName   = $settings['card_lang'] == 'en' ? $cardInfo->institution->name->english : $cardInfo->institution->name->french;
            $cardName   = $settings['card_lang'] == 'en' ? $cardInfo->name->english : $cardInfo->name->french;
            $insuHTML   = $settings['card_lang'] == 'en' ? $cardInfo->insurance->english : $cardInfo->insurance->french;

            $contents .= '<div class="secard-block">';
            $contents .= '<div class="sscard-flex">';
            $contents .= '<img src="' . $cardImage . '" width="' . $settings['card_imgsize'] . 'px" />';
            $contents .= '<div><h5>' . $instName . '</h5><h3>' . $cardName . '</h3></div>';
            $contents .= '</div>';
            $contents .= '<div class="sscard-content">' . $insuHTML . '</div>';
            $contents .= '</div>';
        } else {
            $contents .= '<div style="text-align: center;">';
            $contents .= '<h5>Can not find credit card</h5>';
            $contents .= '</div>';
        }

        return $contents;

    }

	/** ==================================================
	 * Load Style
	 *
	 * @since 1.0
	 */
	public function load_style() {
		wp_enqueue_style( 'simple-show-card', plugin_dir_url( __DIR__ ) . 'assets/css/simple-show-card.css', array(), '1.00' );
	}

}
?>