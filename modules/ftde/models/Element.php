<?php

namespace app\modules\ftde\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use app\modules\main\models\Diagram;

/**
 * This is the model class for table "start_to_end".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property int $type
 * @property string $description
 * @property int $indent_x
 * @property int $indent_y
 * @property int $diagram

 *
 * @property Diagram $diagramFk

 */

class Element extends \yii\db\ActiveRecord
{
    const AND_TYPE = 0;         // И
    const OR_TYPE = 1;           // Или
    const COMMON_FAULT = 2;           // Отказ
    const INITIAL_FAULT = 3;
    const BASIC_EVENT = 4;      //Базисное событие
    const UNDEVELOPED_EVENT = 5;
    const PROHIBITION_TYPE = 6;
   


    /**
     * @return string table name
     */
    public static function tableName()
    {
        return '{{%fault_tree_element}}';
    }

    /**
     * @return array the validation rules
     */
    public function rules()
    {
        return [
            [['type', 'diagram'], 'required'],
            [['type', 'diagram', 'indent_x', 'indent_y'], 'integer'],
            [['indent_x', 'indent_y'], 'default', 'value' => 0],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            // // 'type','diagram' вместе должны быть уникальны, т.е. допускается только по одному началу и завершению на диаграмму
            // ['type', 'unique', 'targetAttribute' => ['type', 'diagram']],

            [['diagram'], 'exist', 'skipOnError' => true, 'targetClass' => Diagram::className(),
                'targetAttribute' => ['diagram' => 'id']],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'START_TO_END_MODEL_ID'),
            'created_at' => Yii::t('app', 'START_TO_END_MODEL_CREATED_AT'),
            'updated_at' => Yii::t('app', 'START_TO_END_MODEL_UPDATED_AT'),
            'name' => Yii::t('app', 'STATE_MODEL_NAME'),
            'description' => Yii::t('app', 'STATE_MODEL_DESCRIPTION'),
            'type' => Yii::t('app', 'START_TO_END_MODEL_TYPE'),
            'indent_x' => Yii::t('app', 'START_TO_END_MODEL_INDENT_X'),
            'indent_y' => Yii::t('app', 'START_TO_END_MODEL_INDENT_Y'),
            'diagram' => Yii::t('app', 'START_TO_END_MODEL_DIAGRAM'),
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
    public function getDiagramFk()
    {
        return $this->hasOne(Diagram::className(), ['id' => 'diagram']);
    }




    /**
     * --------------------------------------Получение списка типов ---------------------.
     * @return array - массив всех возможных типов состояний
     */
    public static function getTypesArray()
    {
        return [
            self::AND_TYPE => Yii::t('app', 'START_TO_END_MODEL_START_TYPE'),
            self::OR_TYPE => Yii::t('app', 'START_TO_END_MODEL_END_TYPE'),
            self::COMMON_FAULT => Yii::t('app', 'START_TO_END_MODEL_END_TYPE'),
            self::INITIAL_FAULT => Yii::t('app', 'START_TO_END_MODEL_END_TYPE'),
            self::BASIC_EVENT => Yii::t('app', 'START_TO_END_MODEL_END_TYPE'),
        ];
    }

    /**
     * Получение названия типа состояния.
     * @return mixed
     */
    public function getTypeName()
    {
        return ArrayHelper::getValue(self::getTypesArray(), $this->type);
    }

    /**
     * Получение списка типов состояний на английском.
     *
     * @return array - массив всех возможных типов диаграмм на английском
     */
    public static function getTypesArrayEn()
    {
        return [
            self::AND_TYPE => 'And',
            self::OR_TYPE => 'Or',
            self::COMMON_FAULT => 'Fault',
            self::INITIAL_FAULT => 'Initial fault',
            self::BASIC_EVENT => 'Basic event',
        ];
    }

    /**
     * Получение названия типа состояний на английском.
     *
     * @return mixed
     */
    public function getTypeNameEn()
    {
        return ArrayHelper::getValue(self::getTypesArrayEn(), $this->type);
    }
}