<?php
$this->breadcrumbs=array(
		'Account Payable'=>array('index'),
		$model->system_ref,
);

$this->menu=array(
		array('label'=>'Home', 'url'=>array('/mAccpayable/')),
		array('label'=>'Approval', 'url'=>array('/mAccpayable/','id'=>1)),
		array('label'=>'Payment', 'url'=>array('/mAccpayable/','id'=>2)),
		array('label'=>'Paid', 'url'=>array('/mAccpayable/','id'=>3)),
		array('label'=>'Show All', 'url'=>array('/mAccpayable/','id'=>0)),
		array('label'=>'Print', 'url'=>array('print', 'id'=>$model->id)),
);

$this->menu1=bPorder::getTopUpdated(1);
$this->menu2=bPorder::getTopCreated(1);
//$this->menu3=bPorder::getTopRelated($model->user_ref);

?>

<div class="page-header">
	<h1>
		AP for:
		<?php echo $model->system_ref; ?>
	</h1>
</div>

<?php 

$this->widget('bootstrap.widgets.BootDetailView', array(
		'data'=>$model,
		'attributes'=>array(
				'input_date',
				'periode_date',
				'system_ref',
				array(
						'label'=>'Purchasing Type',
						'value'=>$model->po_type->name,
				),
				array(
						'label'=>'Entity',
						'value'=>$model->organization->name,
				),
				array(
						'label'=>'Supplier',
						'value'=>$model->supplier->company_name,
				),
				'remark',
				array(
						'label'=>'Payment Status',
						'value'=>$model->paymentCheck(),
				),
				array(
						'label'=>'Journal Status',
						'value'=>$model->journal_state->name,
				),
		),
)); ?>

<br />

<?php $this->widget('bootstrap.widgets.BootGridView', array(
		'id'=>'u-order-detail-grid',
		'dataProvider'=>bPorderDetail::model()->search($model->id),
		'template'=>'{items}{pager}',
		'itemsCssClass'=>'table table-striped table-bordered',
		'columns'=>array(
				array(
						'header'=>'Item.',
						'value'=>'$data->item_id',
				),
				'description',
				'qty',
				'uom',
				array(
						'value'=>'$data->amountf()',
						'name'=>'amount',
						'htmlOptions'=>array(
								'style'=>'text-align: right; padding-right: 5px;'
						),
				),
				array(
						'header'=>'Total',
						'value'=>'$data->totalf()',
						'name'=>'amount',
						'htmlOptions'=>array(
								'style'=>'text-align: right; padding-right: 5px;'
						),
				),
		),
));

?>
<br />
<b> Total: <?php echo $model->sum_pof(); ?>
</b>

<hr />

<h2>Purchased Journal</h2>
<?php 

$this->widget('bootstrap.widgets.BootDetailView', array(
		'data'=>uJournal::model()->searchTagPurchasing($model->system_ref),
		'attributes'=>array(
				'input_date',
				'yearmonth_periode',
				'user_ref',
				'system_ref',
				'remark',
		),
));
echo $this->renderPartial('/uJournal/_viewDetail', array('id'=>uJournal::model()->searchTagPurchasing($model->system_ref)->id));
?>

<?php 
if ($model->journal_state_id ==3) {

	?>
<hr />
<h2>Payment Journal</h2>
<?php 

$this->widget('bootstrap.widgets.BootDetailView', array(
		'data'=>uJournal::model()->searchTagPayment($model->system_ref),
		'attributes'=>array(
				'input_date',
				'yearmonth_periode',
				'user_ref',
				'system_ref',
				'remark',
		),
));
echo $this->renderPartial('/uJournal/_viewDetail', array('id'=>uJournal::model()->searchTagPayment($model->system_ref)->id));
}

?>
