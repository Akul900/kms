<?php

namespace app\modules\ftde\models;

use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "state_connection".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property int $element_from
 * @property int $element_to
 *
 * @property Element $diagramFk
 * @property Element $stateFk
 */

class StateConnection extends \yii\db\ActiveRecord
{
    /**
     * @return string table name
     */
    public static function tableName()
    {
        return '{{%fault_tree_connection}}';
    }

    /**
     * @return array the validation rules
     */
    public function rules()
    {
        return [
            [['element_to', 'element_from'], 'required'],
            [['element_to', 'element_from'], 'integer'],

            // 'state','start_to_end' вместе должны быть уникальны, т.е. допускается только по одной связи между state и start_to_end
            ['element_to', 'unique', 'targetAttribute' => ['element_to', 'element_from']],

            [['element_from'], 'exist', 'skipOnError' => true, 'targetClass' => Element::className(),
                'targetAttribute' => ['element_from' => 'id']],

            [['element_to'], 'exist', 'skipOnError' => true, 'targetClass' => Element::className(),
                'targetAttribute' => ['element_to' => 'id']],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'STATE_CONNECTION_MODEL_ID'),
            'created_at' => Yii::t('app', 'STATE_CONNECTION_MODEL_CREATED_AT'),
            'updated_at' => Yii::t('app', 'STATE_CONNECTION_MODEL_UPDATED_AT'),
            'element_from' => Yii::t('app', 'STATE_CONNECTION_MODEL_START_TO_END'),
            'element_to' => Yii::t('app', 'STATE_CONNECTION_MODEL_STATE'),
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementFromFk()
    {
        return $this->hasOne(Element::className(), ['id' => 'element_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementToFk()
    {
        return $this->hasOne(Element::className(), ['id' => 'element_to']);
    }
}