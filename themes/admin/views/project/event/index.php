<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->baseUrl.'/css/manager.css');?>
<?
/**
 * @author Emericanec
 */
?>
<div class="events">
    
    <h2><?=ProjectModule::t('Events')?></h2>
   <div class="row white-block">
       <div class="col-xs-12">
    <table class="table table-striped" style="text-align: justify; width: 100%;">
        <thead>
            <th>
                <?=ProjectModule::t('id')?>
            </th>
            <th>
                <?=ProjectModule::t('Description')?>
            </th>
            <th>
                <?=ProjectModule::t('Link')?>
            </th>
            <th>
                <?=ProjectModule::t('Date and time')?>
            </th>
        </thead>
        <?php foreach ($events as $event) {?>
         <tr>
            <td><?php echo $event->id?></td>
            <td><?php echo $event->description?></td>
            <td>
                <?php switch ($event->type) {
                    case EventHelper::TYPE_EDIT_ORDER:
                    case EventHelper::TYPE_UPDATE_PROFILE:
                        echo CHtml::link(
                                Yii::t('site','Show'),
                                Yii::app()->createUrl('moderate/index', ['id' => $event->id])
                            );
                    break;
                    case EventHelper::TYPE_NOTIFICATION:
                        echo '<td> Ссылка отсутствует</td>';
                    break;
                    case EventHelper::TYPE_MESSAGE:
                        echo CHtml::button(Yii::t('site','Delete'),array('onclick'=>'$.post("'.Yii::app()->createUrl('project/event/index',array('id'=>$event->id)).'",function(xhr,data,msg){alert(xhr.msg);},"json");'));
                    default:
                        echo CHtml::link(Yii::t('site', 'Show'), ['/project/zakaz/preview', 'id' => $event->id]);
                    break;
                }?>
            </td>
            <td><?php echo date("Y-m-d H:i", $event->timestamp); ?></td>
         </tr>
        <?php } ?>
    </table>
    </div>
   </div>
</div>