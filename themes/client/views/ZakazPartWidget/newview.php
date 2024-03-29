<?php
/**
 * Created by PhpStorm.
 * User: coolfire
 * Date: 05.05.15
 * Time: 15:55
 */
if (count($data['files']) > 0 || !User::model()->isCustomer()) {
?>
<div class="col-xs-12">
	<div class="row zero-edge">
		<div class="panel-group" id="accordion">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="partStatus"></div>
					<div class="title-name">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion"
							   href="#collapseOne<?php echo $data['id']; ?>" id="part_title_<?php echo $data['id']; ?>">
								<?php echo $data['title']; ?>
							</a>
						</h4>
					</div>
					<?php if (User::model()->isAuthor()) { ?>
					<div class="title-time"><?=ProjectModule::t('Date')?>:<br />
						<?php echo $data['dbdate']; ?>
					</div>
					<?php } ?>
				</div>
				<div id="collapseOne<?php echo $data['id']; ?>" class="panel-collapse collapse">
					<div class="panel-body">
						<?php
						if (User::model()->isAuthor()) {
							?>
							<textarea class="col-xs-12" disabled><?php echo $data['comment']; ?></textarea>
						<?php } ?>

						<div class="part_files">
							<?php foreach ($data['files'] as $k => $v){
								echo '<div class="row">';
								if ($v['id']!=0) echo '<a href="' . $v['file_name'] . '" id="parts_file" data-part="' . $data['id'] . '">';
								if (User::model()->isAuthor() || ($v['id']!=0)) echo $v['orig_name'];
								if ($v['id']!=0) echo '</a>';
								echo '</div>';
							} ?>
						</div>

						<?php if (User::model()->isExecutor($data['proj_id'])) $this->widget('ext.EAjaxUpload.EAjaxUpload',
							array(
								'id' => 'EAjaxUpload' . $data['id'],
								'config' => array(
									'action' => Yii::app()->createUrl('/project/zakazParts/upload', array('proj_id' => $data['proj_id'], 'id' => $data['id'])),
									'template' => '<div class="qq-uploader"><div class="qq-upload-drop-area"><span>Перетащите файлы сюда</span></div><div class="qq-upload-button">Загрузить материал</div><ul class="qq-upload-list"></ul></div>',
									'disAllowedExtensions' => array('exe'),
									'sizeLimit' => 10 * 1024 * 1024,// maximum file size in bytes
									'minSizeLimit' => 10,// minimum file size in bytes
									'onComplete' => "js:function(id, fileName, responseJSON){}"
								)
							)
						);
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
