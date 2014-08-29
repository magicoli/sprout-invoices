<?php

/**
 * Paypal offsite payment processor.
 *
 * These actions are fired for each checkout page.
 * 
 * Payment page - 'si_checkout_action_'.SI_Checkouts::PAYMENT_PAGE
 * Review page - 'si_checkout_action_'.SI_Checkouts::REVIEW_PAGE
 * Confirmation page - 'si_checkout_action_'.SI_Checkouts::CONFIRMATION_PAGE
 *
 * Necessary methods:
 * get_instance -- duh
 * get_slug -- slug for the payment process
 * get_options -- used on the invoice payment dropdown
 * process_payment -- called when the checkout is complete before the confirmation page is shown. If a
 * payment fails than the user will be redirected back to the invoice.
 *
 * @package SI
 * @subpackage Payment Processing_Processor
 */
class SI_Checks extends SI_Offsite_Processors {
	const PAYMENT_METHOD = 'Check';
	const PAYMENT_SLUG = 'checks';
	protected static $instance;

	public static function get_instance() {
		if ( !( isset( self::$instance ) && is_a( self::$instance, __CLASS__ ) ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function get_payment_method() {
		return self::PAYMENT_METHOD;
	}

	public function get_slug() {
		return self::PAYMENT_SLUG;
	}

	public static function register() {
		self::add_payment_processor( __CLASS__, self::__( 'Check/PO Payment' ) );
	}

	public static function public_name() {
		return self::__( 'Check/PO' );
	}

	public static function checkout_options() {
		$option = array(
			'icons' => array( SI_URL . '/resources/front-end/img/check.png', SI_URL . '/resources/front-end/img/po.png' ),
			'label' => self::__('Check'),
			'cc' => array()
			);
		return $option;
	}

	protected function __construct() {
		parent::__construct();

		
		// Remove pages
		add_filter( 'si_checkout_pages', array( $this, 'remove_checkout_pages' ) );
	}



	/**
	 * Loaded via SI_Payment_Processors::show_payments_pane
	 * @param  SI_Checkouts $checkout 
	 * @return                  
	 */
	public function payments_pane( SI_Checkouts $checkout ) {
		self::load_view( 'templates/checkout/checks/form', array(
				'checkout' => $checkout,
				'type' => self::PAYMENT_SLUG,
				'check_fields' => $this->check_info_fields( $checkout )
			), TRUE );
	}



	/**
	 * Loaded via SI_Payment_Processors::show_payments_pane
	 * @param  SI_Checkouts $checkout 
	 * @return                  
	 */
	public function invoice_pane() {
		self::load_view( 'templates/checkout/checks/form', array(
				'checkout' => NULL,
				'type' => self::PAYMENT_SLUG,
				'check_fields' => self::check_info_fields( $checkout )
			), TRUE );
	}

	/**
	 * An array of fields for check payments
	 *
	 * @static
	 * @return array
	 */
	public static function check_info_fields( $checkout = '' ) {
		$fields = array(
			'amount' => array(
				'type' => 'text',
				'weight' => 1,
				'label' => self::__( 'Amount' ),
				'attributes' => array(
					//'autocomplete' => 'off',
				),
				'required' => TRUE
			),
			'check_number' => array(
				'type' => 'text',
				'weight' => 5,
				'label' => self::__( 'Check/PO Number' ),
				'attributes' => array(
					//'autocomplete' => 'off',
				),
				'required' => TRUE
			),
			'mailed' => array(
				'type' => 'date',
				'weight' => 10,
				'label' => self::__( 'Date Mailed' ),
				'attributes' => array(
					'autocomplete' => 'off',
				),
				'required' => TRUE
			),
			'notes' => array(
				'type' => 'textarea',
				'weight' => 15,
				'label' => self::__( 'Notes' ),
				'attributes' => array(
					//'autocomplete' => 'off',
				),
				'required' => FALSE
			)
		);
		$fields = apply_filters( 'sa_checks_fields', $fields, $checkout );
		uasort( $fields, array( __CLASS__, 'sort_by_weight' ) );
		return $fields;
	}

	/**
	 * The review page is unnecessary
	 *
	 * @param array   $pages
	 * @return array
	 */
	public function remove_checkout_pages( $pages ) {
		unset( $pages[SI_Checkouts::REVIEW_PAGE] );
		return $pages;
	}

	/**
	 * Process a payment
	 *
	 * @param SI_Checkouts $checkout
	 * @param SI_Invoice $invoice
	 * @return SI_Payment|bool FALSE if the payment failed, otherwise a Payment object
	 */
	public function process_payment( SI_Checkouts $checkout, SI_Invoice $invoice ) {
		$amount = ( isset( $_POST['sa_checks_amount'] ) ) ? $_POST['sa_checks_amount'] : FALSE ;
		$number = ( isset( $_POST['sa_checks_check_number'] ) ) ? $_POST['sa_checks_check_number'] : FALSE ;
		$date = ( isset( $_POST['sa_checks_mailed'] ) ) ? $_POST['sa_checks_mailed'] : FALSE ;
		$notes = ( isset( $_POST['sa_checks_notes'] ) ) ? $_POST['sa_checks_notes'] : '' ;

		if ( !$amount ) {
			return FALSE;
		}

		// create new payment
		$payment_id = SI_Payment::new_payment( array(
				'payment_method' => self::get_payment_method(),
				'invoice' => $invoice->get_id(),
				'amount' => $amount,
				'transaction_id' => $number,
				'data' => array(
					'amount' => $amount,
					'check_number' => $number,
					'date' => $date,
					'notes' => $notes
				),
			), SI_Payment::STATUS_PENDING );
		if ( !$payment_id ) {
			return FALSE;
		}

		$payment = SI_Payment::get_instance( $payment_id );
		do_action( 'payment_pending', $payment );
		return $payment;
	}

	/**
	 * Grabs error messages from a PayPal response and displays them to the user
	 *
	 * @param array   $response
	 * @param bool    $display
	 * @return void
	 */
	private function set_error_messages( $message, $display = TRUE ) {
		if ( $display ) {
			self::set_message( $message, self::MESSAGE_STATUS_ERROR );
		} else {
			do_action( 'si_error', __CLASS__ . '::' . __FUNCTION__ . ' - error message from paypal', $message );
		}
	}
}
SI_Checks::register();