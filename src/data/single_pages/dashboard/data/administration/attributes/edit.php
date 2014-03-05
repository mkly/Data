<?php defined('C5_EXECUTE') or die('Access Denied.') ?>

<?= $dashboard->getDashboardPaneHeaderWrapper(t('Edit %s Attribute', $dataType->dtName), false, false, false, $navigation) ?>
<form method="post">
<div class="ccm-pane-body">
	<?= $form->hidden('akID', $key->getAttributeKeyID()) ?>
	<fieldset>
		<legend><?= t('%s: Basic Details', $type->getAttributeTypeName()) ?></legend>

		<div class="clearfix">
			<?= $form->label('akHandle', t('Handle')) ?>
			<div class="input">
				<?= $form->text('akHandle', preg_replace('/^data_' . $dataType->dtHandle . '_/', '', $akHandle)) ?>
			</div>
		</div>

		<div class="clearfix">
			<?= $form->label('akName', t('Name')) ?>
			<div class="input">
				<?= $form->text('akName', $akName) ?>
			</div>
		</div>

		<div class="clearfix">
			<?= $form->label('asID', t('Set')) ?>
			<div class="input">
				<?= $form->select('asID', $sets, $asID) ?>
			</div>
		</div>

		<div class="clearfix">
			<label><?= t('Searchable') ?></label>
			<div class="input">
				<ul class="inputs-list">
					<li><label><?= $form->checkbox('akIsSearchableIndexed', 1, $akIsSearchableIndexed) ?> <span><?= t('Content included in data keyword search.') ?></span></label></li>
					<li><label><?= $form->checkbox('akIsSearchable', 1, $akIsSearchable) ?> <span><?= t('Field available in Dashboard data search.') ?></span></label></li>
				</ul>
			</div>
		</div>
	</fieldset>

	<?php $type->render('type_form', $key) ?>
	<?= $form->hidden('atID', $type->getAttributeTypeID()) ?>
	<?= $form->hidden('akCategoryID', $category->getAttributeKeyCategoryID()) ?>
	<?= $token->output('create_data_attribute') ?>
</div>
<div class="ccm-pane-footer">
	<div class="ccm-buttons">
		<?= $interface->submit(t('Update')) ?>
		<?= $interface->button(t('Delete'), $this->action('delete', $dataType->dtID, $key->getAttributeKeyID())) ?>
		<?= $interface->button(t('Cancel'), $this->action('view', $dataType->dtID)) ?>
	</div>
</div>
</form>
<?= $dashboard->getDashboardPaneFooterWrapper(false) ?>
