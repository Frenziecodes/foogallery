<?php
	/**
	 * @package     Freemius
	 * @copyright   Copyright (c) 2016, Freemius, Inc.
	 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
	 * @since       1.2.0
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

    /**
     * @var array $VARS
     * @var Freemius $fs
     */
    $fs = freemius( $VARS['id'] );

    /**
     * @var FS_Payment[] $payments
     */
    $payments = $VARS['payments'];

	$slug = $fs->get_slug();

?>
<div class="postbox">
	<div id="fs_payments">
		<h3><span class="dashicons dashicons-paperclip"></span> <?php fs_esc_html_echo_inline( 'Payments', 'payments', $slug ) ?></h3>

		<div class="inside">
			<table class="widefat">
				<thead>
				<tr>
					<th><?php fs_esc_html_echo_inline( 'ID', 'id', $slug ) ?></th>
					<th><?php fs_esc_html_echo_inline( 'Date', 'date', $slug ) ?></th>
					<th><?php fs_esc_html_echo_inline( 'Amount', 'amount', $slug ) ?></th>
					<th><?php fs_esc_html_echo_inline( 'Invoice', 'invoice', $slug ) ?></th>
				</tr>
				</thead>
				<tbody>
				<?php $odd = true ?>
				<?php foreach ( $payments as $payment ) : ?>
					<tr<?php echo $odd ? ' class="alternate"' : ''; ?>>
						<td><?php echo esc_html( $payment->id ); ?></td>
						<td><?php echo esc_html( date( 'M j, Y', strtotime( $payment->created ) ) ); ?></td>
						<td><?php echo esc_html( $payment->formatted_gross() ); ?></td>
						<td><?php if ( ! $payment->is_migrated() ) : ?><a href="<?php echo esc_url( $fs->_get_invoice_api_url( $payment->id ) ); ?>"
						class="button button-small"
						target="_blank" rel="noopener"><?php fs_esc_html_echo_inline( 'Invoice', 'invoice', $slug ); ?></a><?php endif; ?></td>
					</tr>
					<?php $odd = ! $odd; endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php
