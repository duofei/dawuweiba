<div style="width:500px; background:red;">
<?php
$this->widget('CStarRating', array(
	'name'=>'rating',
    'starCount' => 5,
    'minRating' => 1,
    'maxRating' => 5
));
?>
</div>
