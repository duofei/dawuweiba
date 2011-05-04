<?php
if (!isset($selected) || $selected<0){
	$selected = 0;
}
$this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs'=>$tabs,
	'options'=>array(
        'selected'=>$selected,
    ),
));
?>