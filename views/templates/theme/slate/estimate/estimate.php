<?php

/**
 * DO NOT EDIT THIS FILE! Instead customize it via a theme override.
 *
 * Any edit will not be saved when this plugin is upgraded. Not upgrading will prevent you from receiving new features,
 * limit our ability to support your site and potentially expose your site to security risk that an upgrade has fixed.
 *
 * Theme overrides are easy too, so there's no excuse...
 *
 * https://sproutapps.co/support/knowledgebase/sprout-invoices/customizing-templates/
 *
 * You find something that you're not able to customize? We want your experience to be awesome so let support know and we'll be able to help you.
 *
 */
do_action( 'pre_si_estimate_view' ); ?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<?php si_head(); ?>
		<meta name="robots" content="noindex, nofollow" />
	</head>

	<body id="estimate" <?php body_class( 'web_view' ); ?>>

		<div id="outer_doc_wrap">
						
			<?php do_action( 'si_estimate_outer_doc_wrap' ) ?>

			<div id="doc_header_wrap" class="sticky_header">

				<?php do_action( 'si_doc_header_start' ) ?>
				
				<header id="header_title">
					
					<span class="header_id"><?php the_title() ?></span>
					
					<div id="doc_actions">
						<?php do_action( 'si_doc_actions_pre' ) ?>
						<?php if ( ! si_is_estimate_approved() ) : ?>
							<a href="#accept" class="button primary_button status_change" data-status-change="accept" data-id="<?php the_ID() ?>" data-nonce="<?php esc_attr_e( wp_create_nonce( SI_Controller::NONCE ) ) ?>"><?php esc_html_e( 'Accept Estimate', 'sprout-invoices' ) ?></a>
						<?php else : ?>
							<a href="javascript:void(0)" class="button primary_button disabled"><?php esc_html_e( 'Accepted', 'sprout-invoices' ) ?></a>
						<?php endif ?>
						<?php if ( ! si_is_estimate_declined() ) : ?>
							<a href="#decline" class="button status_change" data-status-change="decline" data-id="<?php the_ID() ?>" data-nonce="<?php esc_attr_e( wp_create_nonce( SI_Controller::NONCE ) ) ?>"><?php esc_html_e( 'Decline Estimate', 'sprout-invoices' ) ?></a>
						<?php else : ?>
							<a href="javascript:void(0)" class="button disabled"><?php esc_html_e( 'Declined', 'sprout-invoices' ) ?></a>
						<?php endif ?>
						<?php do_action( 'si_doc_actions' ) ?>
					</div><!-- #doc_actions -->

				</header><!-- #header_title -->

				<?php do_action( 'si_doc_header_end' ) ?>

			</div><!-- #doc_header_wrap -->

			<div id="document_wrap">

				<div id="doc">

					<section id="header_wrap" class="clearfix">

						<div id="vcards">

							<?php do_action( 'si_document_vcards_pre' ) ?>
						
							<?php if ( si_get_estimate_client_id() ) : ?>
								<div id="sent_to_estimate">
									<b><?php echo get_the_title( si_get_estimate_client_id() ) ?></b>
									<?php do_action( 'si_document_client_addy' ) ?>
									<?php si_client_address( si_get_estimate_client_id() ) ?>
								</div><!-- #sent_to_estimate -->
							<?php endif ?>

							<div id="sent_from_estimate">

								<div id="inner_logo">
									<?php if ( get_theme_mod( 'si_logo' ) ) : ?>
										<img src="<?php echo esc_url( get_theme_mod( 'si_logo', si_doc_header_logo_url() ) ); ?>" alt="document logo" >
									<?php else : ?>
										<img src="<?php echo esc_url( si_doc_header_logo_url() ) ?>" alt="document logo" >
									<?php endif; ?>
								</div>

								<b><?php si_company_name() ?></b> 
								<?php si_doc_address() ?>
							</div><!-- #sent_from_estimate -->
							
							<?php do_action( 'si_document_vcards' ) ?>

						</div><!-- #vcards -->
						
						<div class="doc_details clearfix">
						
							<?php do_action( 'si_document_details_pre' ) ?>

							<dl class="date">
								<dt><span class="dt_heading"><span class="dashicons dashicons-calendar-alt"></span><?php esc_html_e( 'Date', 'sprout-estimates' ) ?></span></dt>
								<dd><?php si_estimate_issue_date() ?></dd>
							</dl>

							<?php if ( si_get_estimate_id() ) : ?>
								<dl class="estimate_number">
									<dt><span class="dt_heading"><span class="dashicons dashicons-tag"></span><?php esc_html_e( 'Estimate Number', 'sprout-estimates' ) ?></span></dt>
									<dd><?php si_estimate_id() ?></dd>
								</dl>
							<?php endif ?>

							<?php if ( si_get_estimate_expiration_date() ) : ?>
								<dl class="date">
									<dt><span class="dt_heading"><span class="dashicons dashicons-flag"></span><?php esc_html_e( 'Estimate Due', 'sprout-estimates' ) ?></span></dt>
									<dd><?php si_estimate_expiration_date() ?></dd>
								</dl>
							<?php endif ?>

							<?php do_action( 'si_document_details_totals' ) ?>

							<dl class="doc_total doc_balance">
								<dt><span class="dt_heading"><span class="dashicons dashicons-money"></span><?php esc_html_e( 'Estimate Total', 'sprout-estimates' ) ?></span></dt>
								<dd><?php sa_formatted_money( si_get_estimate_total() ) ?></dd>
							</dl>

							<?php do_action( 'si_document_details' ) ?>
						</div><!-- #doc_details -->

					</section>

					<section id="doc_line_items_wrap" class="clearfix">
					
						<div id="doc_line_items" class="clearfix">
							
							<?php do_action( 'si_doc_line_items', get_the_id() ) ?>

						</div><!-- #doc_line_items -->

					</section>

					<section id="doc_notes">
						<?php if ( strlen( si_get_estimate_notes() ) > 1 ) : ?>
						<?php do_action( 'si_document_notes' ) ?>
						<div id="doc_notes">
							<h2><?php esc_html_e( 'Notes', 'sprout-estimates' ) ?></h2>
							<?php si_estimate_notes() ?>
						</div><!-- #doc_notes -->
						
						<?php endif ?>

						<?php if ( strlen( si_get_estimate_terms() ) > 1 ) : ?>
						<?php do_action( 'si_document_terms' ) ?>
						<div id="doc_terms">
							<h2><?php esc_html_e( 'Terms', 'sprout-estimates' ) ?></h2>
							<?php si_estimate_terms() ?>
						</div><!-- #doc_terms -->
						
						<?php endif ?>

					</section>

					<?php do_action( 'si_doc_wrap_end' ) ?>

				</div><!-- #doc -->

				<div id="footer_wrap">
					<?php do_action( 'si_document_footer' ) ?>
					<aside>
						<ul class="doc_footer_items">
							<li class="doc_footer_item">
								<?php echo make_clickable( home_url() ) ?>
							</li>
							<?php if ( si_get_company_email() ) : ?>
								<li class="doc_footer_item">
									<?php echo make_clickable( si_get_company_email() ) ?>
								</li>
							<?php endif ?>
						</ul>
					</aside>
				</div><!-- #footer_wrap -->
			
			</div><!-- #document_wrap -->

		</div><!-- #outer_doc_wrap -->
		
		<div id="doc_history">
			<?php do_action( 'si_document_history' ) ?>
			<?php foreach ( si_doc_history_records() as $item_id => $data ) : ?>
				<dt>
					<span class="history_status <?php echo esc_attr( $data['status_type'] ); ?>"><?php echo esc_attr( $data['type'] ); ?></span><br/>
					<span class="history_date"><?php echo esc_html( date_i18n( get_option( 'date_format' ).' @ '.get_option( 'time_format' ), strtotime( $data['post_date'] ) ) ) ?></span>
				</dt>

				<dd>
					<?php if ( SI_Notifications::RECORD === $data['status_type'] ) : ?>
						<p>
							<?php echo esc_html( $update_title ) ?>
							<br/><a href="#TB_inline?width=600&height=380&inlineId=notification_message_<?php echo (int) $item_id ?>" id="show_notification_tb_link_<?php echo (int) $item_id ?>" class="thickbox si_tooltip notification_message" title="<?php esc_html_e( 'View Message', 'sprout-estimates' ) ?>"><?php esc_html_e( 'View Message', 'sprout-estimates' ) ?></a>
						</p>
						<div id="notification_message_<?php echo (int) $item_id ?>" class="cloak">
							<?php echo wpautop( $data['content'] ) ?>
						</div>
					<?php elseif ( SI_Estimates::VIEWED_STATUS_UPDATE === $data['status_type'] ) : ?>
						<p>
							<?php echo $data['update_title']; ?>
						</p>
					<?php else : ?>
						<?php echo wpautop( $data['content'] ) ?>
					<?php endif ?>
					
				</dd>
			<?php endforeach ?>
		</div><!-- #doc_history -->

		<div id="footer_credit">
			<?php do_action( 'si_document_footer_credit' ) ?>
			<!--<p><?php esc_html_e( 'Powered by Sprout Estimates', 'sprout-estimates' ) ?></p>-->
		</div><!-- #footer_messaging -->

	</body>
	<?php do_action( 'si_document_footer' ) ?>
	<?php si_footer() ?>
	<!-- Template Version 9.2.2 -->
</html>
<?php do_action( 'estimate_viewed' ) ?>