<?php

/**
 * @property integer $id
 * @property string  $name 
 * @property string  $value
 */
class Setting extends CActiveRecord
{
    
    const FORCE_SSL = 'force_ssl';
    const RECENT_ENTRIES_WIDGET_ENABLED = 'recent_entries_widget_enabled';
    const RECENT_ENTRIES_WIDGET_COUNT   = 'recent_entries_widget_count';
    const RECENT_ENTRIES_WIDGET_POSITION = 'recent_entries_widget_position';
    const MOST_VIEWED_ENTRIES_WIDGET_ENABLED = 'most_viewed_entries_widget_enabled';
    const MOST_VIEWED_ENTRIES_WIDGET_COUNT   = 'most_viewed_entries_widget_count';
    const MOST_VIEWED_ENTRIES_WIDGET_POSITION   = 'most_viewed_entries_widget_position';
    const TAG_CLOUD_WIDGET_POSITION   = 'tag_cloud_widget_position';

    /**
     * @param string $className
     * @return CActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id'    => 'ID',
            'name'  => 'Name',
            'value' => 'Value',
        );
    }

    /**
     * Scope
     *
     * @param string $name
     * @return Tag
     */
    public function name($name)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'name=:name',
            'params'    => array(':name' => $name),
        ));

        return $this;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('name', 'required'),
            array('value', 'required'),
        );
    }

    /**
     * @return array
     */
    public function scopes()
    {
        $alias = $this->getTableAlias();

        return array(
            'sidebar' => array(
                'condition' => $alias . '.name=:tag_widget OR ' . $alias . '.name=:recent_widget OR ' . $alias . '.name=:most_viewed',
                'params'    => array(
                    ':tag_widget'    => Setting::TAG_CLOUD_WIDGET_POSITION,
                    ':recent_widget' => Setting::RECENT_ENTRIES_WIDGET_POSITION,
                    ':most_viewed'   => Setting::MOST_VIEWED_ENTRIES_WIDGET_POSITION,
                ),
                'order' => $alias . '.value ASC',
            ),
        );
    }

    /**
     * @return string
     */
    public function tableName()
    {
        return 'setting';
    }

}