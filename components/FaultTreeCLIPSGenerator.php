<?php

namespace app\components;

use Transliterator;
use app\modules\ftde\models\Element;
use app\modules\main\models\Diagram;
use app\modules\ftde\models\StateConnection;

use function PHPSTORM_META\elementType;

class FaultTreeCLIPSGenerator
{

    public function generateCLIPSCode($id)
    {
        $diagram = Diagram::find()->where(['id' => $id])->one();
        $arr = explode(' ',trim($diagram->name));
       // Получение всех элементов
        $elements = Element::find()->where(['diagram' => $id])->orderBy(['id' => SORT_ASC])->all();

        // Получение всех соединений
        $connections = StateConnection::find()->all();
        $transliterator = Transliterator::create('Any-Latin; Latin-ASCII');
        $element_to_mas = array();
        $elemet_from_mas = null;
        $next_elements = array();


        // Функция генерации правил CLIPS

        $clips = "";
   

        // Шаблоны для построения событий
        $clips .= "(deftemplate Event\n";
        $clips .= "  (slot Name (default \"Отказ сети\"))\n";
        $clips .= "  (slot ID (default \"E1\"))\n";
        $clips .= "  (slot Type (default \"Отказ\"))\n";
        $clips .= ")\n\n";

        $connection_elements = array();//массив связей
        foreach ($connections as $t){
            foreach ($elements as $s){
                if ($t->element_from == $s->id){
                    array_push($connection_elements, $t);
                }
            }
        }
        
        $elementStartTo = findStart($connection_elements, $elements);
        if($elementStartTo == null){
            return;
        }
        $elemet_from_mas = findType($elements, $elementStartTo);
        $element_to_mas = findToElements($connections, $elements, $elemet_from_mas);

        if($elemet_from_mas[0][1] == Element::OR_TYPE){
            $clips .= orClips($elementStartTo, $transliterator, $element_to_mas);
        }elseif($elemet_from_mas[0][1] == Element::AND_TYPE){
            $clips .= andClips($elementStartTo, $transliterator, $element_to_mas);
        }elseif($elemet_from_mas[0][1] == Element::AND_WITH_PRIORITY){
            $clips .= andClips($elementStartTo, $transliterator, $element_to_mas);
        }elseif($elemet_from_mas[0][1] == Element::PROHIBITION_TYPE){
            $clips .= andClips($elementStartTo, $transliterator, $element_to_mas);
        }elseif($elemet_from_mas[0][1] == Element::MAJORITY_VALVE){
            $clips .= orClips($elementStartTo, $transliterator, $element_to_mas);
        }elseif($elemet_from_mas[0][1] == Element::COMMON_FAULT || $elemet_from_mas[0][1] == Element::BASIC_EVENT
        || $elemet_from_mas[0][1] == Element::UNDEVELOPED_EVENT || $elemet_from_mas[0][1] == Element::CONDITIONAL_EVENT || $elemet_from_mas[0][1] == Element::HIDDEN_EVENT){
            $clips .= faultClips($elementStartTo, $transliterator, $element_to_mas);
        }

        
        $clips .= clipsCreate($connections, $elements, $element_to_mas, $transliterator, "");

        $file = $diagram->id.'_'.$arr[0].'.clp';

        file_put_contents($file, $clips);
        header("Content-type: application/octet-stream");
        header('Content-Disposition: filename="'.$file.'"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($file);
      
        // Удаление файла после скачивания, если необходимо
        unlink($file);
        exit;
    }
}


function findType($elements, $elementStartTo){
    $elemet_from_mas = array();
    foreach ($elements as $e){
        if ($elementStartTo[0][3] == $e->id){
          //  $elemet_from = $elementStartTo[0][2];
            array_push($elemet_from_mas , [$elementStartTo[0][3], $e->type]);
        }
    }
    return $elemet_from_mas;
}


function orClips($elementStartTo, $transliterator, $element_to_mas){
    $clips="";
    $sourceRu = "";
    $sourceEng = "";
    $targetRu = $elementStartTo[0][1];
    $targetEng = str_replace(' ', '-', $transliterator->transliterate($elementStartTo[0][1]));
    foreach ($element_to_mas as $mas){
        $sourceRu  .= "{$mas->name} или ";
        $sourceEng .= "{$transliterator->transliterate($mas->name)}+";
    }
    $sourceRu = substr($sourceRu, 0, -8);
    $sourceEng = substr(str_replace(' ', '-', $sourceEng), 0, -1);
    $clips .= "(defrule {$sourceEng}>{$targetEng} \"Описание правила: {$sourceRu} > {$targetRu}\"\n";
    $clips .= "(declare (salience 1))\n";
    $clips .= "  (or\n";
    foreach ($element_to_mas as $mas){
        $clips .= "  (Event ; Event\n    (Name \"" . mb_strtoupper($mas->name) . "\")\n    (Type \"{$mas->getTypeNameEn()}\")\n)\n";
    }
    $clips .= "  )\n";
   
   

    $clips .= "=>\n";
    $clips .= "(assert\n  (Event ; Event\n    (Name \"" . mb_strtoupper($targetRu) . "\")\n  (Type \"{$elementStartTo[0][2]}\")\n))\n";
    $clips .= ")\n\n"; 
    return $clips;
}


function andClips($elementStartTo, $transliterator, $element_to_mas){
    $clips="";
    $sourceRu = "";
    $sourceEng = "";
    $targetRu = $elementStartTo[0][1];
    $targetType = $elementStartTo[0][2];
    $targetEng = str_replace(' ', '-', $transliterator->transliterate($elementStartTo[0][1]));
    foreach ($element_to_mas as $mas){
        $sourceRu  .= "{$mas->name} и ";
        $sourceEng .= "{$transliterator->transliterate($mas->name)}+";
    }
    $sourceRu = substr($sourceRu, 0, -4);
    $sourceEng = substr(str_replace(' ', '-', $sourceEng), 0, -1);
    $clips .= "(defrule {$sourceEng}>{$targetEng} \"Описание правила: {$sourceRu} > {$targetRu}\"\n";
    $clips .= "(declare (salience 1))\n";
    foreach ($element_to_mas as $mas){
        $clips .= "  (Event ; Event\n    (Name \"" . mb_strtoupper($mas->name) . "\")\n    (Type \"{$mas->getTypeNameEn()}\")\n)\n";
    }
  
   
   

    $clips .= "=>\n";
    $clips .= "(assert\n  (Event ; Event\n    (Name \"" . mb_strtoupper($targetRu) . "\")\n     (Type \"{$targetType}\")\n))\n";
    $clips .= ")\n\n"; 
    return $clips;
}


function faultClips($elementStartTo, $transliterator, $element_to_mas){
    $clips="";
    $sourceRu = "";
    $sourceEng = "";
   // $sourceType = "";
    $targetRu = $elementStartTo[0][1];
    $targetType = $elementStartTo[0][2];
    
    $targetEng = str_replace(' ', '-', $transliterator->transliterate($elementStartTo[0][1]));

    foreach ($element_to_mas as $mas){
        $sourceRu  .= "{$mas->name} и ";
        $sourceEng .= "{$transliterator->transliterate($mas->name)}+";
    }
        // $sourceRu  .= "{$next_element->name} и ";
        // $sourceEng .= "{$transliterator->transliterate($next_element->name)}+";
    
    $sourceRu = substr($sourceRu, 0, -4);
    $sourceEng = substr(str_replace(' ', '-', $sourceEng), 0, -1);
    $clips .= "(defrule {$sourceEng}>{$targetEng} \"Описание правила: {$sourceRu} > {$targetRu}\"\n";
    $clips .= "(declare (salience 1))\n";
   
    foreach ($element_to_mas as $mas){
        $clips .= "  (Event ; Event\n    (Name \"" . mb_strtoupper($mas->name) . "\")\n    (Type \"{$mas->getTypeNameEn()}\")\n)\n";
    }
      //  $clips .= "  (Event ; Event\n    (Name \"" . mb_strtoupper($next_element->name) . "\")\n    (Type \"{$next_element->getTypeNameEn()}\")\n)\n";

    $clips .= "=>\n";
    $clips .= "(assert\n  (Event ; Event\n    (Name \"" . mb_strtoupper($targetRu) . "\")\n     (Type \"{$targetType}\")\n))\n";
    $clips .= ")\n\n"; 
    return $clips;
}


function findStart($connections, $elements){
    $elementStartTo = array();//массив связей
    foreach ($connections as $c){
        foreach ($elements as $e){
            if ($c->element_from == $e->id && $e->type == Element::INITIAL_FAULT){
                array_push($elementStartTo, [$e->id, $e->name, $e->getTypeNameEn(), $c->element_to ]);

            }
        }
    }
    
    return $elementStartTo;
}

function findStart2($connections, $elements, $next_elements){
    $elementStartName = array();//массив связей
    foreach ($connections as $c){
        foreach ($elements as $e){
                if ($c->element_from == $e->id && $next_elements->id == $c->element_to){
                array_push($elementStartName, [$e->id, $e->name, $e->getTypeNameEn()]);
            }
        }
    }
    return $elementStartName;
}


function findToElements($connections, $elements, $elemet_from){
    $element_to_mas = array();//массив связей
    
    foreach ($connections as $c){
        if($c->element_from == $elemet_from[0][0] && $elemet_from[0][1] != Element::COMMON_FAULT && $elemet_from[0][1] != Element::UNDEVELOPED_EVENT
        && $elemet_from[0][1] != Element::CONDITIONAL_EVENT  && $elemet_from[0][1] != Element::HIDDEN_EVENT){
            foreach ($elements as $e){
                if ($c->element_to == $e->id){

                    array_push($element_to_mas, $e);
                }
            }
        }elseif($c->element_from == $elemet_from[0][0]){
            foreach ($elements as $e){
                if ($c->element_from == $e->id){
                    array_push($element_to_mas, $e);
                }
            }
        }
    }

    return $element_to_mas;
}


function findToElements2($connections, $elements, $elemet_from){
    $element_to_mas = array();//массив связей
    foreach ($connections as $c){
        foreach ($elemet_from as $em){
            if($c->element_from == $em->id && $em->type != Element::COMMON_FAULT && $em->type != Element::UNDEVELOPED_EVENT
                    && $em->type != Element::CONDITIONAL_EVENT && $em->type != Element::HIDDEN_EVENT){
                        foreach ($elements as $e){
                            if ($c->element_to == $e->id){
                        
                                array_push($element_to_mas, $e);
                            }
                        }
                    }elseif($c->element_from == $em->id ){
                        foreach ($elements as $e){
                            if ($c->element_from == $e->id){
                     
                                array_push($element_to_mas, $e);
                            }
                        }
                    }
        }
        
    }



    return $element_to_mas;
}


function findToElements3($connections, $elements, $elemet_from){
    $element_to_mas = array();//массив связей

    
    foreach ($connections as $c){

            if($c->element_from == $elemet_from->id && $elemet_from->type != Element::COMMON_FAULT && $elemet_from->type != Element::UNDEVELOPED_EVENT
                    && $elemet_from->type != Element::CONDITIONAL_EVENT && $elemet_from->type != Element::HIDDEN_EVENT  && $elemet_from->type != Element::BASIC_EVENT ){
                        foreach ($elements as $e){
                            if ($c->element_to == $e->id){
                    
                                array_push($element_to_mas, $e);
                          
                            }
                        }
                    }elseif(($c->element_to == $elemet_from->id && $elemet_from->type == Element::COMMON_FAULT) || ($c->element_to == $elemet_from->id && $elemet_from->type == Element::BASIC_EVENT)
                    || ($c->element_to == $elemet_from->id && $elemet_from->type == Element::UNDEVELOPED_EVENT) || ($c->element_to == $elemet_from->id && $elemet_from->type == Element::CONDITIONAL_EVENT)
                    || ($c->element_to == $elemet_from->id && $elemet_from->type == Element::HIDDEN_EVENT)){

                        foreach ($elements as $e){
                            if ($c->element_to == $e->id){

                                array_push($element_to_mas, $e);
                            }
                        }
                    }
        
        
    }

    return $element_to_mas;
}




function findNextElements($connections, $elements, $element_to_mas){
    $next_elements = array();//массив связей
    foreach ($connections as $c){
        foreach ($elements as $e){
            foreach($element_to_mas as $em){

                if ($c->element_from == $em->id && $e->id == $c->element_to){
                 //   array_push($next_elements, [$e->id, $e->name, $e->type, $c->element_to]);
                 array_push($next_elements, $e);
                }
            }
        }
           
    }

    return $next_elements;
}



function clipsCreate($connections, $elements, $element_to_mas, $transliterator, $clips){
  
    $next_elements = findNextElements($connections, $elements, $element_to_mas);
        

    
    
    foreach($next_elements as $ne){

        $elementStartName = findStart2($connections, $elements, $ne);

        if($ne->type == Element::OR_TYPE){
            $clips .= orClips($elementStartName, $transliterator, $element_to_mas);
        }elseif($ne->type  == Element::AND_TYPE){
            $clips .= andClips($elementStartName, $transliterator, $element_to_mas);
        }
        elseif($ne->type  == Element::PROHIBITION_TYPE){
            $clips .= andClips($elementStartName, $transliterator, $element_to_mas);
        }
        elseif($ne->type  == Element::AND_WITH_PRIORITY){
            $clips .= andClips($elementStartName, $transliterator, $element_to_mas);
        }
        elseif($ne->type  == Element::MAJORITY_VALVE){
            $clips .= orClips($elementStartName, $transliterator, $element_to_mas);
        }elseif($ne->type == Element::COMMON_FAULT || Element::BASIC_EVENT || Element::UNDEVELOPED_EVENT || Element::CONDITIONAL_EVENT || Element::HIDDEN_EVENT){
            $clips .= faultClips($elementStartName, $transliterator, $element_to_mas);
        }
    }
    $element_to_mas = findToElements2($connections, $elements, $next_elements);
    if($next_elements = findNextElements($connections, $elements, $element_to_mas) != null){
        return clipsCreate($connections, $elements, $element_to_mas, $transliterator, $clips);
    }else{
        return $clips;
    }
       $element_to_mas = findToElements3($connections, $elements, $ne);

    
}



    

 





?>
