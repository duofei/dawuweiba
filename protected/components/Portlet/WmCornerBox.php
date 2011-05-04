<?php

class WmCornerBox extends CWidget
{
	public $tagName = 'div';

	public $htmlOptions = array();

	public $title;

	public $decorationCssClass = '';

	public $contentCssClass = '';
	
	public $data;
	
	public function init()
	{
	    $this->htmlOptions['class'] .= ' corner';
		$this->htmlOptions['id'] = $this->getId();
		echo CHtml::openTag($this->tagName, $this->htmlOptions);
		echo '<b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>';
		echo "<div class=\"content {$this->contentCssClass}\">";
		$this->renderDecoration();
		
	}

	public function run()
	{
		$this->renderContent();
		echo '</div>';
		echo '<b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>';
		echo CHtml::closeTag($this->tagName);
	}

	protected function renderDecoration()
	{
		if($this->title!==null) {
			echo '<h3 class="f14px fb cred ma-b5px indent10px lh20px bline"><span class="' . $this->decorationCssClass . '">' . $this->title . '</span></h3>';
		}
	}

	protected function renderContent()
	{
	}
}