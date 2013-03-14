<?php

Yii::import('zii.widgets.grid.CGridView');

class GridView extends CGridView
{

    /**
     * @return void
     */
    public function init()
    {
        $this->pager = array(
            'cssFile'              => false,
            'header'               => false,
            'htmlOptions'          => array('class' => 'pagination'),
            'prevPageLabel'        => '&laquo;',
            'previousPageCssClass' => 'arrow',
            'nextPageLabel'        => '&raquo;',
            'nextPageCssClass'     => 'arrow',
            'firstPageLabel'       => false,
            'firstPageCssClass'    => 'hide',
            'lastPageLabel'        => false,
            'lastPageCssClass'     => 'hide',
            'selectedPageCssClass' => 'current',
        );

        parent::init();
    }

}
