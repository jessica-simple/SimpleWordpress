<?php
if( ! defined( 'ABSPATH' ) ){
	exit; // Exit if accessed directly
}
?>

<div id="mfn-custom" class="wrap about-wrap">

	<?php include_once( plugin_dir_path( __DIR__ ) . '/templates/parts/header.php' ); ?>

	<div class="dashboard-tab">

		<div class="col">
			<?php if( mfn_is_registered() ): ?>

				<h3 class="primary"><?php __( 'Theme is registered', 'becstm' ); ?></h3>

				<form class="form-register form-deregister" method="post">

					<?php settings_fields( 'betheme_registration' ); ?>

					<p>
						<code><?php echo mfn_get_purchase_code_hidden(); ?></code>
					</p>

					<p class="confirm deregister">
						<a class="mfn-button mfn-button-primary mfn-button-fw"><?php echo __( 'Deregister Theme', 'becstm' ); ?></a>
					</p>

					<?php if( WHITE_LABEL ): ?>

						<p class="question"><?php echo __( 'This feature is disabled in White Label mode.', 'mfn-opts' );?></p>

					<?php else: ?>

						<p class="question">
							<?php $this->deregister(); ?>
							<input type="input" hidden name="action_name" value="deregister" />
							<span><?php echo __( 'Are you sure you want to deregister the theme?', 'becstm' ); ?></span>
							<a class="mfn-button cancel" target="_blank" href="#"><?php echo __( 'Cancel', 'becstm' ); ?></a>
							<input type="submit" class="mfn-button mfn-button-primary" name="deregister" value="<?php echo __( 'Deregister', 'becstm' ); ?>" />
						</p>

					<?php endif; ?>

				</form>

				<p class="check-licenses"><a target="_blank" href="http://api.muffingroup.com/licenses/"> <?php echo __('Check your licenses', 'becstm'); ?> </a></p>

			<?php endif; ?>

			<h3 class="primary"> <?php __('Import/Export settings', 'becstm'); ?> </h3>
			<form class="form-import form-export" method="post">
				<textarea class="becustom-import-export" name="importexport"></textarea>

				<input type="input" hidden name="action_name" value="import" />
				<input type="input" hidden id="export-content" name="export-content" value="<?php echo $this->export_options() ?>" />
				<input type="input" hidden id="import-content" name="import-content" value="" />
				<?php $this->import_options(); ?>

				<br />
				<input type="submit" class="mfn-button mfn-button-primary" name="import" disabled value="<?php echo __( 'Import', 'becstm' ); ?>" />
				<input type="submit" class="mfn-button mfn-button-primary" name="export" disabled value="<?php echo __( 'Export', 'becstm' ); ?>" />
			</form>

		</div>
	</div>

</div>
