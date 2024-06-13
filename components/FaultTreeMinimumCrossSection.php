<?php

namespace app\components;

use Transliterator;
use app\modules\ftde\models\Element;
use app\modules\ftde\models\StateConnection;
//include 'vendor/autoload.php';

class FaultTreeMinimumCrossSection
{ 
    

    public function generateMinimumCrossSection($id)
    {
       // Получение всех элементов
        $elements = Element::find()->where(['diagram' => $id])->orderBy(['id' => SORT_ASC])->all();

        // Получение всех соединений
        $connections = StateConnection::find()->all();
        $transliterator = Transliterator::create('Any-Latin; Latin-ASCII');
        $element_to_mas = array();
        $elemet_from_mas = null;
        $mcs = [];

 

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
        $elemet_from_mas = findType($connection_elements, $elements, $elementStartTo);
        $element_to_mas = findToElements($connections, $elements, $elemet_from_mas);
        $mcs[] = array_merge($mcs, $elementStartTo);
        // var_dump($elemet_from_mas);
        //     foreach($element_to_mas  as $ne){
        //         print_r($ne);
        //       //  echo "<script>console.log('PHP aaaa: " . $ne->name . "');</script>";
        // }
    
        if($elemet_from_mas[0][1] == Element::OR_TYPE){
            $mcs = orClips($mcs, $transliterator, $element_to_mas, $elementStartTo);
        }elseif($elemet_from_mas[0][1] == Element::AND_TYPE){
            $mcs = andClips($mcs, $transliterator, $element_to_mas, $elementStartTo);
        }elseif($elemet_from_mas[0][1] == Element::AND_WITH_PRIORITY){
            $mcs = andClips($elementStartTo, $transliterator, $element_to_mas, $elementStartTo);
        }elseif($elemet_from_mas[0][1] == Element::PROHIBITION_TYPE){
            $mcs = andClips($elementStartTo, $transliterator, $element_to_mas, $elementStartTo);
        }elseif($elemet_from_mas[0][1] == Element::MAJORITY_VALVE){
            $mcs = orClips($elementStartTo, $transliterator, $element_to_mas, $elementStartTo);
        }elseif($elemet_from_mas[0][1] == Element::COMMON_FAULT || $elemet_from_mas[0][1] == Element::BASIC_EVENT
        || $elemet_from_mas[0][1] == Element::UNDEVELOPED_EVENT || $elemet_from_mas[0][1] == Element::CONDITIONAL_EVENT || $elemet_from_mas[0][1] == Element::HIDDEN_EVENT){
            $mcs = faultClips($mcs, $transliterator, $element_to_mas, $elementStartTo);
    
        }

        
        $mcs = clipsCreate($connections, $elements, $element_to_mas, $transliterator, $mcs);

      //  echo "<script>console.log('PHP mas: \"" . json_encode($mcs) . "\"');</script>";

        // foreach ($mcs as $e){
        //          echo "<script>console.log('PHP 99999: \"" . $e->id. "\"');</script>";
        
        // }
        
      
        //d($mcs);
      

       // Kint::dump($array);


       // print_r($mcs);
      //  var_dump($mcs);
     
       // file_put_contents('mcs.txt', $mcs);
       // header("Content-type: application/octet-stream");
       // header('Content-Disposition: filename="mcs.txt"');
       // exit;
       return $mcs;
    }
}





function findType($connections, $elements, $elementStartTo){
    $elemet_from_mas = array();
    $to = 0;
    foreach ($connections as $c){
        foreach ($elements as $e){
            foreach ($elementStartTo as $et){
                if ($et->id == $c->element_from ){
                    $to = $c->element_to;
                }
            }
        }
    }

    foreach ($elements as $e){
            if ($e->id == $to ){
                array_push($elemet_from_mas , [$to, $e->type]);
            }
    }
   // echo "<script>console.log('PHP type: \"" . $elemet_from_mas[0][1]. "\"');</script>";
    return $elemet_from_mas;
}


function orClips($mcs, $transliterator, $element_to_mas, $elementStartTo){
    $i=0;
    $index_mas=0;
    $mcs_old = array();
    foreach ($element_to_mas as $e){
        if($i < count($element_to_mas))
        {   
            if ($i==0){
                foreach ($mcs as $index => $m){
                    foreach ($m as $ms ){
                        if ($ms->id == $elementStartTo[0]->id){
                            $mcs_old = $m;
                            $index_mas = $index;
                            
                        }
                    }
                }
                array_push($mcs[$index_mas], $e);  
            }
            if ($i>=1){

                $mcs[]  = $mcs_old;
                array_push($mcs[array_key_last($mcs)], $e);

            }
           
        }
        $i++;
    }

    return $mcs;
}


function andClips($mcs, $transliterator, $element_to_mas, $elementStartTo){
    $index_mas= 0;

    foreach ($mcs as $index => $m){
        foreach ($m as $ms ){
            if ($ms->id == $elementStartTo[0]->id){
                $index_mas = $index; 
                
            }
        }
    }

    foreach ($element_to_mas as $e){
         array_push($mcs[$index_mas], $e);  
    }

    return $mcs;
}


function faultClips($mcs, $transliterator, $element_to_mas, $elementStartTo){

  //  d($elementStartTo);
   // d($mcs);
    $index_mas= 0;

    foreach ($mcs as $index => $m){
        foreach ($m as $ms ){
            if ($ms->id == $elementStartTo[0]->id){
                $index_mas = $index; 
                
            }
        }
    }
    //d($element_to_mas);
    foreach ($element_to_mas as $e){
         array_push($mcs[$index_mas], $e);  
    }

    return $mcs;
}



function findStart($connections, $elements){
    $elementStartTo = array();//массив связей
    foreach ($connections as $c){
        foreach ($elements as $e){
            if ($c->element_from == $e->id && $e->type == Element::INITIAL_FAULT){
                array_push($elementStartTo, $e);

            }
        }
    }
    // foreach ($elementStartTo as $e){

    //     echo "<script>console.log('PHP zzzzz: \"" . $e->name . "\"');</script>";
    // }
    
    return $elementStartTo;
}

function findStart2($connections, $elements, $next_elements){
    $elementStartName = array();//массив связей
    foreach ($connections as $c){
        foreach ($elements as $e){
                if ($c->element_from == $e->id && $next_elements->id == $c->element_to){
                array_push($elementStartName, $e);
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
    // foreach ($element_to_mas as $e){

    //     echo "<script>console.log('PHP 1111: \"" . $e->name . "\"');</script>";
    // }
return $element_to_mas;

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
                             //   echo "<script>console.log('PHP 8678678яя: \"" . $e->getTypeNameEn() . "\"');</script>";
                                array_push($element_to_mas, $e);
                            }
                        }
                    }elseif($c->element_from == $em->id ){
                        foreach ($elements as $e){
                            if ($c->element_from == $e->id){
                            //    echo "<script>console.log('PHP 8678678: \"" . $e->getTypeNameEn() . "\"');</script>";
                                array_push($element_to_mas, $e);
                            }
                        }
                    }
        }
        
    }


    // foreach ($element_to_mas as $e){

    //         echo "<script>console.log('PHP 4444: \"" . $e->name . "\"');</script>";
    //     }
    return $element_to_mas;
}


function findToElements3($connections, $elements, $elemet_from){
    $element_to_mas = array();//массив связей
    foreach ($connections as $c){

            if($c->element_from == $elemet_from->id && $elemet_from->type != Element::COMMON_FAULT && $elemet_from->type != Element::UNDEVELOPED_EVENT
                    && $elemet_from->type != Element::CONDITIONAL_EVENT && $elemet_from->type != Element::HIDDEN_EVENT){
                        foreach ($elements as $e){
                            if ($c->element_to == $e->id){
                             //   echo "<script>console.log('PHP 8678678яя: \"" . $e->getTypeNameEn() . "\"');</script>";
                                array_push($element_to_mas, $e);
                            }
                        }
                    }elseif(($c->element_to == $elemet_from->id && $elemet_from->type == Element::COMMON_FAULT) || ($c->element_to == $elemet_from->id && $elemet_from->type == Element::BASIC_EVENT)
                    || ($c->element_to == $elemet_from->id && $elemet_from->type == Element::UNDEVELOPED_EVENT) || ($c->element_to == $elemet_from->id && $elemet_from->type == Element::CONDITIONAL_EVENT)
                    || ($c->element_to == $elemet_from->id && $elemet_from->type == Element::HIDDEN_EVENT)){
                        foreach ($elements as $e){
                            if ($c->element_to == $e->id){
                            //    echo "<script>console.log('PHP 8678678: \"" . $e->getTypeNameEn() . "\"');</script>";
                                array_push($element_to_mas, $e);
                            }
                        }
                    }
        
        
    }


    // foreach ($element_to_mas as $e){

    //         echo "<script>console.log('PHP 3333: \"" . $e->name . "\"');</script>";
    //     }
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
        
    // foreach($next_elements  as $ne){

    //     echo "<script>console.log('PHP aaaa: \"" . $ne->getTypeNameEn() . "\"');</script>";
    
    // }
    
    
    foreach($next_elements as $ne){
        
        $elementStartName = findStart2($connections, $elements, $ne);
        $element_to_mas = findToElements3($connections, $elements, $ne);
        // foreach($element_to_mas  as $ne){

        //echo "<script>console.log('PHP ssssssss: \"" . $ne->getTypeNameEn() . "\"');</script>";
        
        // }
        // echo "<script>console.log('PHP : zzz" . $ne->getTypeNameEn() . "');</script>";
       // echo "<script>console.log('PHP value: " . $elementStartName[0][1] . "');</script>";
        if($ne->type == Element::OR_TYPE){
            $clips = orClips($clips, $transliterator, $element_to_mas, $elementStartName);
        }elseif($ne->type  == Element::AND_TYPE){
            $clips = andClips($clips, $transliterator, $element_to_mas, $elementStartName);
        }
        elseif($ne->type  == Element::PROHIBITION_TYPE){
            $clips = andClips($clips, $transliterator, $element_to_mas, $elementStartName);
        }
        elseif($ne->type  == Element::AND_WITH_PRIORITY){
            $clips = andClips($clips, $transliterator, $element_to_mas, $elementStartName);
        }
        elseif($ne->type  == Element::MAJORITY_VALVE){
            $clips = orClips($clips, $transliterator, $element_to_mas, $elementStartName);
        }elseif($ne->type == Element::COMMON_FAULT || Element::BASIC_EVENT || Element::UNDEVELOPED_EVENT || Element::CONDITIONAL_EVENT || Element::HIDDEN_EVENT){
           
            $clips = faultClips($clips, $transliterator, $element_to_mas, $elementStartName);
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

