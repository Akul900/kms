<?php

namespace app\components;

use DOMDocument;
use app\modules\main\models\Diagram;
use app\modules\ftde\models\Element;
use app\modules\ftde\models\StateConnection;
use app\modules\ftde\models\StateProperty;
use app\modules\ftde\models\StartToEnd;
use app\modules\ftde\models\Transition;
use app\modules\ftde\models\TransitionProperty;


class FaultTreeXMLGenerator
{

    public static function drawingStateProperty($xml, $id_state, $xml_element)
    {
        //подбор всех StateProperty
        $state_property_elements = StateProperty::find()->where(['fault' => $id_state])->all();
        if ($state_property_elements != null){
            foreach ($state_property_elements as $sp_elem){
                //отрисовка "StateProperty"
                $state_property_element = $xml->createElement('FaultParameter');
                $state_property_element->setAttribute('id', $sp_elem->id);
                $state_property_element->setAttribute('name', $sp_elem->name);
                $state_property_element->setAttribute('operator', $sp_elem->getOperatorName());
                $state_property_element->setAttribute('value', $sp_elem->value);
                $state_property_element->setAttribute('description', $sp_elem->description);
                $xml_element->appendChild($state_property_element);
            }
        }
    }

    // public static function drawingTransitionProperty($xml, $id_transition, $xml_element)
    // {
    //     //подбор всех TransitionProperty
    //     $transition_property_elements = TransitionProperty::find()->where(['transition' => $id_transition])->all();
    //     if ($transition_property_elements != null){
    //         foreach ($transition_property_elements as $tp_elem){
    //             //отрисовка "TransitionProperty"
    //             $transition_property_element = $xml->createElement('TransitionProperty');
    //             $transition_property_element->setAttribute('id', $tp_elem->id);
    //             $transition_property_element->setAttribute('name', $tp_elem->name);
    //             $transition_property_element->setAttribute('operator', $tp_elem->getOperatorName());
    //             $transition_property_element->setAttribute('value', $tp_elem->value);
    //             $transition_property_element->setAttribute('description', $tp_elem->description);
    //             $xml_element->appendChild($transition_property_element);
    //         }
    //     }
    // }


    public function generateSTDXMLCode($id)
    {
        $diagram = Diagram::find()->where(['id' => $id])->one();
        $arr = explode(' ',trim($diagram->name));
        // Определение наименования файла
        $file = $diagram->id.'_'.$arr[0].'.xml';
        if (!file_exists($file))
            fopen($file, 'w');
        // Создание документа DOM с кодировкой UTF8
        $xml = new DomDocument('1.0', 'UTF-8');
        // Создание корневого узла Diagram
        $diagram_element = $xml->createElement('Diagram');
        $diagram_element->setAttribute('id', $diagram->id);
        $diagram_element->setAttribute('type', $diagram->getTypeNameEn());
        $diagram_element->setAttribute('name', $diagram->name);
        $diagram_element->setAttribute('description', $diagram->description);
        

        // Добавление корневого узла Diagram в XML-документ
        $xml->appendChild($diagram_element);

        //подбор всех Element
        $state_elements = Element::find()->where(['diagram' => $id])->orderBy(['id' => SORT_ASC])->all();
        if ($state_elements != null) {
            foreach ($state_elements as $s_elem) {
                //Создание "State"
                $state_element = $xml->createElement('Element');
                $state_element->setAttribute('id', $s_elem->id);
                $state_element->setAttribute('name', $s_elem->name);
                $state_element->setAttribute('type', $s_elem->getTypeNameEn());
                $state_element->setAttribute('description', $s_elem->description);
                $state_element->setAttribute('indent_x', $s_elem->indent_x);
                $state_element->setAttribute('indent_y', $s_elem->indent_y);
                $diagram_element->appendChild($state_element);

                //отрисовка "StateProperty"
                self::drawingStateProperty($xml, $s_elem->id, $state_element);
            }
        }


        // $start_to_end_elements = StartToEnd::find()->where(['diagram' => $id])->orderBy(['id' => SORT_ASC])->all();
        // //подбор всех State
        // foreach ($state_elements as $s_elem) {
        //     //Создание "State"
        //     $start_to_end_elements = $xml->createElement('StartToEnd');
        //     $start_to_end_elements->setAttribute('id', $s_elem->id);
        //     $start_to_end_elements->setAttribute('type', $s_elem->getTypeNameEn());
        //     $diagram_element->appendChild($start_to_end_elements);
        //     if ($state_elements != null) {
        //     }
        // }

        //подбор всех Transition
        $connection_all = StateConnection::find()->all();
        $connection_elements = array();//массив связей
        foreach ($connection_all as $t){
            foreach ($state_elements as $s){
                if ($t->element_from == $s->id){
                    array_push($connection_elements, $t);
                }
            }
        }

        if ($connection_elements != null) {
            foreach ($connection_elements as $t_elem) {
                //Создание "Transition"
                $transition_element = $xml->createElement('Connection');
                $transition_element->setAttribute('id', $t_elem->id);
                $transition_element->setAttribute('element-from', $t_elem->element_from);
                $transition_element->setAttribute('element-to', $t_elem->element_to);
                $diagram_element->appendChild($transition_element);

                // //отрисовка "TransitionProperty"
                // self::drawingTransitionProperty($xml, $t_elem->id, $transition_element);
            }
        }

        // Сохранение RDF-файла
        $xml->formatOutput = true;
        header("Content-type: application/octet-stream");
        header('Content-Disposition: filename="'.$file.'"');
        echo $xml->saveXML();
        exit;
    }
}