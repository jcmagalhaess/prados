<?php
/**
 * Event Tickets Emails: Order Ticket Totals - Ticket Price
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/emails/template-parts/body/order/ticket-totals/ticket-price.php
 *
 * See more documentation about our views templating system.
 *
 * @link    https://evnt.is/tickets-emails-tpl Help article for Tickets Emails template files.
 *
 * @version 5.5.11
 * @since   5.5.11
 *
 * @var Tribe__Template                    $this               Current template object.
 * @var \TEC\Tickets\Emails\Email_Abstract $email              The email object.
 * @var string                             $heading            The email heading.
 * @var string                             $title              The email title.
 * @var bool                               $preview            Whether the email is in preview mode or not.
 * @var string                             $additional_content The email additional content.
 * @var bool                               $is_tec_active      Whether `The Events Calendar` is active or not.
 * @var \WP_Post                           $order              The order object.
 * @var array<string, mixed>               $cart_item          The cart item.
 */

use TEC\Tickets\Commerce\Utils\Value;

if ( empty( $cart_item['ticket_id'] ) ) {
	return;
}

if ( empty( $cart_item['sub_total'] ) ) {
	return;
}

$ticket_price = Value::create( $cart_item['sub_total'] );
?>
<td class="tec-tickets__email-table-content-order-ticket-totals-cell tec-tickets__email-table-content-order-align-right" align="right">
	<?php echo esc_html( $ticket_price->get_currency() ); ?>
</td>
