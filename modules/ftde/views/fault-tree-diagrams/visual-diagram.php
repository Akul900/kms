<?php

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Diagram */
/* @var $transition_model app\modules\stde\models\Transition */
/* @var $states_all app\modules\stde\controllers\StateTransitionDiagramsController */

use yii\helpers\Html;
use app\modules\main\models\Lang;

$this->title = Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAM') . ' - ' . $model->name;

$this->params['menu_add'] = [
    ['label' => Yii::t('app', 'NAV_ADD_FAULT'), 'url' => '#',
        'linkOptions' => ['data-bs-toggle'=>'modal', 'data-bs-target'=>'#addFaultModalForm']],

    ['label' => Yii::t('app', 'NAV_ADD_AND'), 'url' => '#',
        'linkOptions' => ['id'=>'nav_add_start']],

    ['label' => Yii::t('app', 'NAV_ADD_OR'), 'url' => '#',
        'linkOptions' => ['id'=>'nav_add_end']],
    
    ['label' => Yii::t('app', 'NAV_ADD_MAJORITY_VALVE'), 'url' => '#',
        'linkOptions' => ['id'=>'nav_add_majority_valve']],

    ['label' => Yii::t('app', 'NAV_ADD_AND_WITH_PRIORITY'), 'url' => '#',
        'linkOptions' => ['id'=>'nav_add_and_with_priority']],

    ['label' => Yii::t('app', 'NAV_ADD_PROHIBITION'), 'url' => '#',
        'linkOptions' => ['id'=>'nav_add_prohibition']],

    ['label' => Yii::t('app', 'NAV_ADD_NOT'), 'url' => '#',
        'linkOptions' => ['id'=>'nav_add_not']],
    
    ['label' => Yii::t('app', 'NAV_ADD_TRANSFER_VALVE'), 'url' => '#',
        'linkOptions' => ['data-bs-toggle'=>'modal', 'data-bs-target'=>'#addTransferValveModalForm']],

    ['label' => Yii::t('app', 'NAV_BASIC_EVENT'), 'url' => '#',
        'linkOptions' => ['data-bs-toggle'=>'modal', 'data-bs-target'=>'#addBasicEventModalForm']],

    ['label' => Yii::t('app', 'NAV_UNDEVELOPED_EVENT'), 'url' => '#',
        'linkOptions' => ['data-bs-toggle'=>'modal', 'data-bs-target'=>'#addUndevelopedEventModalForm']],
        
    ['label' => Yii::t('app', 'NAV_HIDDEN_EVENT'), 'url' => '#',
        'linkOptions' => ['data-bs-toggle'=>'modal', 'data-bs-target'=>'#addHiddenEventModalForm']],

    ['label' => Yii::t('app', 'NAV_CONDITIONAL_EVENT'), 'url' => '#',
        'linkOptions' => ['data-bs-toggle'=>'modal', 'data-bs-target'=>'#addConditionalEventModalForm']],
];

$this->params['menu_diagram'] = [
    ['label' => '<i class="fa-solid fa-file-import"></i> ' . Yii::t('app', 'NAV_IMPORT'),
        'url' => Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .'/import/'. $model->id],

    ['label' => '<i class="fa-solid fa-file-export"></i> ' . Yii::t('app', 'NAV_EXPORT'),
        'url' => '#', 'linkOptions' => ['data-method' => 'post', 'data-params' => [
        'value' => 'xml',
    ]]],

    // ['label' => '<i class="fa-solid fa-align-center"></i> ' . Yii::t('app', 'NAV_ALIGNMENT'),
    //     'url' => '#', 'linkOptions' => ['id'=>'nav_alignment']],

    // ['label' => '<i class="fa-solid fa-file-export"></i> ' . Yii::t('app', 'NAV_UNLOAD_DECISION_TABLE'),
    //     'url' => '#', 'linkOptions' => ['data-method' => 'post', 'data-params' => [
    //     'value' => 'csv',
    // ]]],
];
?>



<?php

// создаем массив из state для передачи в js
$states_mas = array();
foreach ($states_model_all as $s){
    array_push($states_mas, [$s->id, $s->indent_x, $s->indent_y, $s->name, $s->description, $s->type]);
}

// создаем массив из state_property для передачи в js
$states_property_mas = array();
foreach ($states_property_model_all as $sp){
    array_push($states_property_mas, [$sp->id, $sp->name, $sp->description, $sp->operator, $sp->value, $sp->fault]);
}

// создаем массив из transition для передачи в jsplumb
$transitions_mas = array();
foreach ($transitions_model_all as $t){
    array_push($transitions_mas, [$t->id, $t->name, $t->description, $t->state_from, $t->state_to]);
}

// создаем массив из transition_property для передачи в js
$transitions_property_mas = array();
foreach ($transitions_property_model_all as $tp){
    array_push($transitions_property_mas, [$tp->id, $tp->name, $tp->description, $tp->operator, $tp->value, $tp->transition]);
}

// создаем массив из start_model для передачи в js
$start_mas = array();
foreach ($start_model as $s){
    array_push($start_mas, [$s->id, $s->indent_x, $s->indent_y]);
}

// создаем массив из end_model для передачи в js
$end_mas = array();
foreach ($end_model as $e){
    array_push($end_mas, [$e->id, $e->indent_x, $e->indent_y]);
}

// // создаем массив из states_connection_start для передачи в js
// $states_connection_start_mas = array();
// foreach ($states_connection_start_model_all as $s){
//     array_push($states_connection_start_mas, [$s->id, $s->element_from, $s->element_to]);
// }

// создаем массив из states_connection_end для передачи в js
$states_connection_end_mas = array();
foreach ($states_connection_end_model_all as $s){
    array_push($states_connection_end_mas, [$s->id, $s->element_from, $s->element_to]);
}

// создаем массив из states_connection_fault для передачи в js
$states_connection_fault_mas = array();
foreach ($states_connection_fault_model_all as $s){
    array_push($states_connection_fault_mas, [$s->id, $s->element_from, $s->element_to]);
}

$basic_event_mas = array();
foreach ($basic_event_model as $be){
    array_push($basic_event_mas, [$be->id, $be->indent_x, $be->indent_y, $be->name, $be->description]);
}

// $basic_event_connection_mas = array();
// foreach ($connection_basic_event_model_all as $s){
//     array_push($basic_event_mas, [$s->id, $s->element_from, $s->element_to]);
// }

$prohibition_mas = array();
foreach ($prohibition_model as $e){
    array_push($prohibition_mas, [$e->id, $e->indent_x, $e->indent_y]);
}

$majority_valve_mas = array();
foreach ($majority_valve_model as $e){
    array_push($majority_valve_mas, [$e->id, $e->indent_x, $e->indent_y]);
}

$and_with_priority_mas = array();
foreach ($and_with_priority_model as $e){
    array_push($and_with_priority_mas, [$e->id, $e->indent_x, $e->indent_y]);
}

$undeveloped_event_mas = array();
foreach ($undeveloped_event_model as $u){
    array_push($undeveloped_event_mas, [$u->id, $u->indent_x, $u->indent_y, $u->name, $u->description]);
}

$not_mas = array();
foreach ($not_model as $u){
    array_push($not_mas, [$u->id, $u->indent_x, $u->indent_y]);
}

$transfer_valve_mas = array();
foreach ($transfer_valve_model as $u){
    array_push($transfer_valve_mas, [$u->id, $u->indent_x, $u->indent_y, $u->name, $u->description]);
}

$hidden_event_mas = array();
foreach ($hidden_event_model as $u){
    array_push($hidden_event_mas, [$u->id, $u->indent_x, $u->indent_y, $u->name, $u->description]);
}

$conditional_event_mas = array();
foreach ($conditional_event_model as $u){
    array_push($conditional_event_mas, [$u->id, $u->indent_x, $u->indent_y, $u->name, $u->description]);
}


?>


<?= $this->render('_modal_form_state_editor', [
    'model' => $model,
    'state_model' => $state_model,
]) ?>

<?= $this->render('_modal_form_basic_event', [
    'model' => $model,
    'state_model' => $state_model,
]) ?>

<?= $this->render('_modal_form_undeveloped_event', [
    'model' => $model,
    'state_model' => $state_model,
]) ?>

<?= $this->render('_modal_form_state_property_editor', [
    'model' => $model,
    'state_property_model' => $state_property_model,
]) ?>

<?= $this->render('_modal_form_transition_editor', [
    'model' => $model,
    'transition_model' => $transition_model,
]) ?>

<?= $this->render('_modal_form_transition_property_editor', [
    'model' => $model,
    'transition_property_model' => $transition_property_model,
]) ?>

<?= $this->render('_modal_form_transfer_valve', [
    'model' => $model,
    'state_model' => $state_model,
]) ?>

<?= $this->render('_modal_form_conditional_event', [
    'model' => $model,
    'state_model' => $state_model,
]) ?>

<?= $this->render('_modal_form_hidden_event', [
    'model' => $model,
    'state_model' => $state_model,
]) ?>

<?= $this->render('_modal_form_view_message', [
]) ?>

<?= $this->render('ftde_not', [
    'model' => $model,
    'state_model' => $state_model,
]) ?>

<?= $this->render('ftde_majority', [
        'model' => $model,
        'state_model' => $state_model,
]) ?>

<?= $this->render('ftde_and_with_priority', [
        'model' => $model,
        'state_model' => $state_model,
]) ?>

<?= $this->render('ftde_prohibition', [
        'model' => $model,
        'state_model' => $state_model,
]) ?>

<?= $this->render('ftde_transfer_valve', [
        'model' => $model,
        'state_model' => $state_model,
]) ?>

<?= $this->render('ftde_state_start', [
]) ?>

<?= $this->render('ftde_basic_event', [
]) ?>

<?= $this->render('ftde_and', [
]) ?>

<?= $this->render('ftde_or', [
]) ?>

<?= $this->render('ftde_undeveloped_event', [
]) ?>

<?= $this->render('ftde_hidden_event', [
]) ?>

<?= $this->render('ftde_conditional_event', [
]) ?>

<?= $this->render('_modal_form_delete_connection', [
]) ?>

<?= $this->render('_modal_form_start_to_end_editor', [
]) ?>


<!-- Подключение скрипта для модальных форм -->
<?php
$this->registerJsFile('/js/ftde_modal_forms.js', ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile('/js/ftde_dstribution.js', ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile('/js/ftde_draggable.js', ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile('/js/modal-form.js', ['position' => yii\web\View::POS_HEAD]);
$this->registerCssFile('/css/fault-tree-diagram.css', ['position'=>yii\web\View::POS_HEAD]);
$this->registerCssFile('/css/ftde.css', ['position'=>yii\web\View::POS_HEAD]);
$this->registerJsFile('/js/jsplumb.js', ['position'=>yii\web\View::POS_HEAD]);  // jsPlumb 2.12.9
?>





<script type="text/javascript">
    var guest = <?php echo json_encode(Yii::$app->user->isGuest); ?>;//переменная гость определяет пользователь гость или нет

    var current_connection; //текущее соединение
    var id_state_from = 0; //id состояние из которого выходит связь
    var id_state_to = 0; //id состояния к которому выходит связь
    var state_id_on_click = 0; //id состояния к которому назначается свойство
    var basic_event_id_on_click = 0; //id базового события к которому назначается свойство
    var state_property_id_on_click = 0; //id свойство состояния
    var transition_id_on_click = 0; //id перехода
    var transition_property_id_on_click = 0;//id условия

    var added_transition = false;
    var removed_transition = false;

    var states_mas = <?php echo json_encode($states_mas); ?>;//прием массива состояний из php
    var states_property_mas = <?php echo json_encode($states_property_mas); ?>;//прием массива свойств состояний из php
    var transitions_mas = <?php echo json_encode($transitions_mas); ?>;//прием массива переходов из php
    var transitions_property_mas = <?php echo json_encode($transitions_property_mas); ?>;//прием массива условий из php
    var start_mas = <?php echo json_encode($start_mas); ?>;//прием массива начал из php
    var end_mas = <?php echo json_encode($end_mas); ?>;//прием массива завершений из php

    var states_connection_end_mas = <?php echo json_encode($states_connection_end_mas); ?>;//прием массива соединений завершений из php
    var states_connection_fault_mas = <?php echo json_encode($states_connection_fault_mas); ?>;//прием массива соединений завершений из php

    var basic_event_mas = <?php echo json_encode($basic_event_mas); ?>;//прием массивабазовых событий из php
    var prohibition_mas =  <?php echo json_encode($prohibition_mas); ?>;//прием запретов из php
    var majority_valve_mas =  <?php echo json_encode($majority_valve_mas); ?>;//прием Мажоритарных вентилей из php
    var and_with_priority_mas =  <?php echo json_encode($and_with_priority_mas); ?>;//прием И с приоритетом из php
    var undeveloped_event_mas =  <?php echo json_encode($undeveloped_event_mas); ?>;//прием М с приоритетом из php
    var not_mas =  <?php echo json_encode($not_mas); ?>;//прием Не из php
    var transfer_valve_mas =  <?php echo json_encode($transfer_valve_mas); ?>;//прием Не из php
    var hidden_event_mas =  <?php echo json_encode($hidden_event_mas); ?>;//прием скрытых событий из php
    var conditional_event_mas =  <?php echo json_encode($conditional_event_mas); ?>;//прием условных событий  из php
    
    var message_label = "<?php echo Yii::t('app', 'CONNECTION_DELETE'); ?>";

    var element_from_source = "";
    var element_to_target = "";



    var mas_data_state = {};
    var q = 0;
    var id = "";
    var indent_x = "";
    var indent_y = "";
    var name = "";
    var description = "";
    var type = ""
    $.each(states_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {indent_x = elem;}
            if (j == 2) {indent_y = elem;}
            if (j == 3) {name = elem;}
            if (j == 4) {description = elem;}
            if (j == 5) {type = elem;}
            mas_data_state[q] = {
                "id":id,
                "indent_x":indent_x,
                "indent_y":indent_y,
                "name":name,
                "description":description,
                "type":type
            }
        });
        q = q+1;
    });

    //console.log(mas_data_state);

    var mas_data_basic_event = {};
    var q = 0;
    var id = "";
    var indent_x = "";
    var indent_y = "";
    var name = "";
    var description = "";
    $.each(basic_event_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {indent_x = elem;}
            if (j == 2) {indent_y = elem;}
            if (j == 3) {name = elem;}
            if (j == 4) {description = elem;}
            mas_data_basic_event[q] = {
                "id":id,
                "indent_x":indent_x,
                "indent_y":indent_y,
                "name":name,
                "description":description,
            }
        });
        q = q+1;
    });

    //console.log(mas_data_basic_event);


    var mas_data_transition = {};
    var q = 0;
    var id = "";
    var name = "";
    var description = "";
    var state_from = "";
    var state_to = "";
    $.each(transitions_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {name = elem;}
            if (j == 2) {description = elem;}
            if (j == 3) {state_from = elem;}
            if (j == 4) {state_to = elem;}
            mas_data_transition[q] = {
                "id":id,
                "name":name,
                "description":description,
                "state_from":state_from,
                "state_to":state_to,
            }
        });
        q = q+1;
    });

    //console.log(mas_data_transition);


    var mas_data_state_property = {};
    var q = 0;
    var id = "";
    var name = "";
    var description = "";
    var operator = "";
    var value = "";
    var fault = "";
    $.each(states_property_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {name = elem;}
            if (j == 2) {description = elem;}
            if (j == 3) {operator = elem;}
            if (j == 4) {value = elem;}
            if (j == 5) {fault = elem;}
            mas_data_state_property[q] = {
                "id":id,
                "name":name,
                "description":description,
                "operator":operator,
                "value":value,
                "fault":fault,
            }
        });
        q = q+1;
    });

    //console.log(mas_data_state_property);


    var mas_data_transition_property = {};
    var q = 0;
    var id = "";
    var name = "";
    var description = "";
    var operator = "";
    var value = "";
    var transition = "";
    $.each(transitions_property_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {name = elem;}
            if (j == 2) {description = elem;}
            if (j == 3) {operator = elem;}
            if (j == 4) {value = elem;}
            if (j == 5) {transition = elem;}
            mas_data_transition_property[q] = {
                "id":id,
                "name":name,
                "description":description,
                "operator":operator,
                "value":value,
                "transition":transition,
            }
        });
        q = q+1;
    });

    //console.log(mas_data_transition_property);


    var mas_data_start = {};
    var q = 0;
    var id = "";
    var indent_x = "";
    var indent_y = "";
    $.each(start_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {indent_x = elem;}
            if (j == 2) {indent_y = elem;}
            mas_data_start[q] = {
                "id":id,
                "indent_x":indent_x,
                "indent_y":indent_y,
            }
        });
        q = q+1;
    });
    // console.log(mas_data_start );


    var mas_data_end = {};
    var q = 0;
    var id = "";
    var indent_x = "";
    var indent_y = "";
    $.each(end_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {indent_x = elem;}
            if (j == 2) {indent_y = elem;}
            mas_data_end[q] = {
                "id":id,
                "indent_x":indent_x,
                "indent_y":indent_y,
            }
        });
        q = q+1;
    });
    //console.log(mas_data_end);


    var mas_data_prohibition = {};
    var q = 0;
    var id = "";
    var indent_x = "";
    var indent_y = "";
    $.each(prohibition_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {indent_x = elem;}
            if (j == 2) {indent_y = elem;}
            mas_data_prohibition[q] = {
                "id":id,
                "indent_x":indent_x,
                "indent_y":indent_y,
            }
        });
        q = q+1;
    });

    var mas_data_majority_valve = {};
    var q = 0;
    var id = "";
    var indent_x = "";
    var indent_y = "";
    $.each(majority_valve_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {indent_x = elem;}
            if (j == 2) {indent_y = elem;}
            mas_data_majority_valve[q] = {
                "id":id,
                "indent_x":indent_x,
                "indent_y":indent_y,
            }
        });
        q = q+1;
    });


    var mas_data_and_with_priority = {};
    var q = 0;
    var id = "";
    var indent_x = "";
    var indent_y = "";
    $.each(and_with_priority_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {indent_x = elem;}
            if (j == 2) {indent_y = elem;}
            mas_data_and_with_priority[q] = {
                "id":id,
                "indent_x":indent_x,
                "indent_y":indent_y,
            }
        });
        q = q+1;
    });


    var mas_data_undeveloped_event = {};
    var q = 0;
    var id = "";
    var indent_x = "";
    var indent_y = "";
    var name = "";
    var description = "";
    $.each(undeveloped_event_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {indent_x = elem;}
            if (j == 2) {indent_y = elem;}
            if (j == 3) {name = elem;}
            if (j == 4) {description = elem;}
            mas_data_undeveloped_event[q] = {
                "id":id,
                "indent_x":indent_x,
                "indent_y":indent_y,
                "name":name,
                "description":description,
            }
        });
        q = q+1;
    });

    
    var mas_data_hidden_event = {};
    var q = 0;
    var id = "";
    var indent_x = "";
    var indent_y = "";
    var name = "";
    var description = "";
    $.each(hidden_event_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {indent_x = elem;}
            if (j == 2) {indent_y = elem;}
            if (j == 3) {name = elem;}
            if (j == 4) {description = elem;}
            mas_data_hidden_event[q] = {
                "id":id,
                "indent_x":indent_x,
                "indent_y":indent_y,
                "name":name,
                "description":description,
            }
        });
        q = q+1;
    });


    
    var mas_data_conditional_event = {};
    var q = 0;
    var id = "";
    var indent_x = "";
    var indent_y = "";
    var name = "";
    var description = "";
    $.each(conditional_event_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {indent_x = elem;}
            if (j == 2) {indent_y = elem;}
            if (j == 3) {name = elem;}
            if (j == 4) {description = elem;}
            mas_data_conditional_event[q] = {
                "id":id,
                "indent_x":indent_x,
                "indent_y":indent_y,
                "name":name,
                "description":description,
            }
        });
        q = q+1;
    });

    var mas_data_not = {};
    var q = 0;
    var id = "";
    var indent_x = "";
    var indent_y = "";
    $.each(not_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {indent_x = elem;}
            if (j == 2) {indent_y = elem;}
            mas_data_not[q] = {
                "id":id,
                "indent_x":indent_x,
                "indent_y":indent_y,
            }
        });
        q = q+1;
    });


    var mas_data_transfer_valve = {};
    var q = 0;
    var id = "";
    var indent_x = "";
    var indent_y = "";
    var name = "";
    var description = "";
    $.each(transfer_valve_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {indent_x = elem;}
            if (j == 2) {indent_y = elem;}
            if (j == 3) {name = elem;}
            if (j == 4) {description = elem;}
            mas_data_transfer_valve[q] = {
                "id":id,
                "indent_x":indent_x,
                "indent_y":indent_y,
                "name":name,
                "description":description,
            }
        });
        q = q+1;
    });

    // var mas_data_state_connection_start = {};
    // var q = 0;
    // var id = "";
    // var element_from = "";
    // var element_to = "";
    // $.each(states_connection_start_mas, function (i, mas) {
    //     $.each(mas, function (j, elem) {
    //         if (j == 0) {id = elem;}//записываем id
    //         if (j == 1) {element_from = elem;}
    //         if (j == 2) {element_to = elem;}
    //         mas_data_state_connection_start[q] = {
    //             "id":id,
    //             "element_from":element_from,
    //             "element_to":element_to,
    //         }
    //     });
    //     q = q+1;
    // });


    // var mas_data_basic_event_connection = {};
    // var q = 0;
    // var id = "";
    // var element_from = "";
    // var element_to = "";
    // $.each(basic_event_connection_mas, function (i, mas) {
    //     $.each(mas, function (j, elem) {
    //         if (j == 0) {id = elem;}//записываем id
    //         if (j == 1) {element_from = elem;}
    //         if (j == 2) {element_to = elem;}
    //         mas_data_basic_event_connection[q] = {
    //             "id":id,
    //             "element_from":element_from,
    //             "element_to":element_to,
    //         }
    //     });
    //     q = q+1;
    // });

    // console.log(mas_data_state_connection_start);

    var mas_data_state_connection_fault = {};
    var q = 0;
    var id = "";
    var element_from = "";
    var element_to = "";
    $.each(states_connection_fault_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {element_from = elem;}
            if (j == 2) {element_to = elem;}
            mas_data_state_connection_fault[q] = {
                "id":id,
                "element_from":element_from,
                "element_to":element_to,
            }
        });
        q = q+1;
    });

    console.log(mas_data_state_connection_fault);


    var mas_data_state_connection_end = {};
    var q = 0;
    var id = "";
    var start_to_end = "";
    var state = "";
    $.each(states_connection_end_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {start_to_end = elem;}
            if (j == 2) {state = elem;}
            mas_data_state_connection_end[q] = {
                "id":id,
                "element_from":start_to_end,
                "element_to":state,
            }
        });
        q = q+1;
    });

    //console.log(mas_data_state_connection_end);


    //Обработка модальных окон
    processingOfModalForms()


    //-----начало кода jsPlumb-----
    var instance = "";
    jsPlumb.ready(function () {
        instance = jsPlumb.getInstance({
            Connector:["StateMachine"], //стиль соединения линии
            Endpoint:["Dot", {radius:3}], //стиль точки соединения
            EndpointStyle: { fill: '#337ab7' }, //цвет точки соединения
            PaintStyle : { strokeWidth:3, stroke: "#337ab7", "dashstyle": "0 0", fill: "transparent"},//стиль линии
            HoverPaintStyle : { strokeWidth:3, stroke: "#5bb35b", "dashstyle": "0 0", fill: "transparent"},//стиль линии при наведении
            Overlays:[["PlainArrow", {location:1, width:15, length:15}]], //стрелка
            Container: "visual_diagram_field"
        });

       // Распределение элементов
        deleteDistributionOfElements()
        
        mousemoveState();

        // Делаем элементы двигаемыми
        draggableElements()




        var windows = jsPlumb.getSelector(".div-state");
       
        


        instance.bind("beforeDrop", function (info) {
        var source_id = info.sourceId;
        var target_id = info.targetId;

        var name_source = source_id.split('_')[0];
        var name_target = target_id.split('_')[0];


        var connection_is = false;
        id_source = parseInt(source_id.match(/\d+/));
        id_target = parseInt(target_id.match(/\d+/));
        $.each(mas_data_state_connection_end, function (i, elem) {
            if ((id_target == elem.element_from) && (id_source == elem.element_to)){
                connection_is = true;
            }
        });
        console.log(name_source)
        console.log(name_target)

        // Запреты на соединения
        if (((name_source == 'and') && (name_target == 'or' || name_target == 'prohibition' || name_target == 'and'   || name_target == 'and-with' || name_target == 'majority' || name_target == 'not')) 
        || ((name_source == 'or') && (name_target == 'and' || name_target == 'prohibition' || name_target == 'or'   || name_target == 'and-with' || name_target == 'majority' || name_target == 'not')) 
        //|| ((name_source == 'state-start') && (name_target == 'and' || name_target == 'or' || name_target == 'prohibition' || name_target == 'and-with' || name_target == 'majority' || name_target == 'not'))
        || ((name_source == 'prohibition') && (name_target == 'and' || name_target == 'or' || name_target == 'prohibition' || name_target == 'and-with' || name_target == 'majority' || name_target == 'not'))
        || ((name_source == 'majority') && (name_target == 'and' || name_target == 'or' || name_target == 'prohibition' || name_target == 'and-with' || name_target == 'majority' || name_target == 'not'))
        || ((name_source == 'and-with') && (name_target == 'and' || name_target == 'or' || name_target == 'prohibition' || name_target == 'and-with' || name_target == 'majority' || name_target == 'not'))
        || ((name_source == 'not') && (name_target == 'and' || name_target == 'or' || name_target == 'prohibition' || name_target == 'and-with' || name_target == 'majority' || name_target == 'not'))
        ){
            var message = "<?php echo Yii::t('app', 'AND_OR_CANNOT_BE_LINKED'); ?>";
            document.getElementById("message-text").lastChild.nodeValue = message;
            $("#viewMessageErrorLinkingItemsModalForm").modal("show");
            return false;
        } else {
            if (connection_is == true){
                var message = "<?php echo Yii::t('app', 'CONNECTION_IS_ALREADY_THERE'); ?>";
                document.getElementById("message-text").lastChild.nodeValue = message;
                $("#viewMessageErrorLinkingItemsModalForm").modal("show");
                return false;
            } else {
                return true;
            }
        }
    });
        //построение переходов (связей)
        instance.batch(function () {

            majorityConnectionsStyle()
            hiddenEventConnectionsStyle()
            stateStartConnectionsStyle()
            andWithPriorityConnectionsStyle()
            undevelopedEventConnectionsStyle()
            transferValveConnectionsStyle()
            notConnectionsStyle()
            conditionalEventConnectionsStyle()
            basicEventConnectionsStyle()
            andConnectionsStyle()
            orConnectionsStyle()
            prohibitionConnectionsStyle()

            for (var i = 0; i < windows.length; i++) {

                instance.makeSource(windows[i], {
                    filter: ".fa-share",
                    anchor: "Bottom", //непрерывный анкер
                    maxConnections: 1,
                });

                instance.makeTarget(windows[i], {
                    dropOptions: { hoverClass: "dragHover" },
                    anchor: "Top", //непрерывный анкер
                    allowLoopback: false, // Разрешение создавать кольцевую связь
                    maxConnections: 1,
                    onMaxConnections: function (info, e) {
                        //отображение сообщения об ограничении
                        var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
                        document.getElementById("message-text").lastChild.nodeValue = message;
                        $("#viewMessageErrorLinkingItemsModalForm").modal("show");
            }
                });
            }



             //построение связей из mas_data_state_connection_fault
            $.each(mas_data_state_connection_fault, function (j, elem) {
                var c = instance.connect({
                    source: "state_" + elem.element_from,
                    target: "state_" + elem.element_to,
                    overlays: [
                        ['Label', {
                            label: message_label,
                            location: 0.5, //расположение посередине
                            cssClass: "connections-style",
                        }]
                    ],
                });
            });

            //построение связей из mas_data_state_connection_fault
            $.each(mas_data_state_connection_fault, function (j, elem) {
                var c = instance.connect({
                    source: "state_" + elem.element_from,
                    target: "or_" + elem.element_to,
                    overlays: [
                        ['Label', {
                            label: message_label,
                            location: 0.5, //расположение посередине
                            cssClass: "connections-style",
                        }]
                    ],
                });
                var c = instance.connect({
                    source: "state_" + elem.element_from,
                    target: "and_" + elem.element_to,
                    overlays: [
                        ['Label', {
                            label: message_label,
                            location: 0.5, //расположение посередине
                            cssClass: "connections-style",
                        }]
                    ],
                });


                var c = instance.connect({
                    source: "state_" + elem.element_from,
                    target: "prohibition_" + elem.element_to,
                    overlays: [
                        ['Label', {
                            label: message_label,
                            location: 0.5, //расположение посередине
                            cssClass: "connections-style",
                        }]
                    ],
                });

            });

            //Соединения
            prohibitionConnections()
            andConnections()
            orConnections()
            basicEventConnections()
            undevelopedEventConnections()
            majorityConnections()
            andWithPriorityConnections()
            notConnections()
            transferValveConnections()
            hiddenEventConnections()
            conditionalEventConnections()
            stateStartConnections()
        });


        //обработка клика на связь для просмотра перехода
        instance.bind("click", function (c) {
            // var source_id = c.sourceId;
            // var target_id = c.targetId;
            element_from_source = c.sourceId;
            element_to_target = c.targetId;
            element_from = parseInt(element_from_source.match(/\d+/));
            element_to = parseInt(element_to_target.match(/\d+/));
            $("#deleteConnectionStartModalForm").modal("show");
           
        });


        //обработка построения связи (добавление перехода) и связей с началом и завершением
        instance.bind("connection", function(connection) {
            if (!guest) {

                element_from_source = connection.sourceId;
                element_to_target = connection.targetId;
                element_from = parseInt(element_from_source.match(/\d+/));
                element_to = parseInt(element_to_target.match(/\d+/));
                // var source_id = connection.sourceId;
                // var target_id = connection.targetId;
                console.log(element_from)
                console.log(element_to)
                // var name_source = source_id.split('_')[0];
                // var name_target = target_id.split('_')[0];

                //параметры передаваемые на модальную форму
                current_connection = connection.connection;

                // if ((name_source == 'state') && (name_target == 'state')){
                //     id_fault_from = parseInt(source_id.match(/\d+/));
                //     id_fault_to = parseInt(target_id.match(/\d+/));

                    $.ajax({
                        //переход на экшен левел
                        url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                        '/fault-tree-diagrams/fault-connection'?>",
                        type: "post",
                        data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&element_from=" + element_from + "&element_to=" + element_to,
                        dataType: "json",
                        success: function (data) {
                            if (data['success']) {
                                //присваиваем наименование и свойства новой связи
                                current_connection.setLabel({
                                    label: message_label,
                                    location: 0.5, //расположение посередине
                                    cssClass: "connections-style",
                                });

                                //добавление новой записи в массив свойств состояний для изменений
                                var id = data['id'];
                                // var element_from = element_from;
                                // var element_to = element_to;

                                var j = 0;
                                $.each(mas_data_state_connection_fault, function (i, elem) {
                                    j = j + 1;
                                });
                                mas_data_state_connection_fault[j] = {id:id, element_from:element_from, element_to:element_to};
                            }
                        },
                        error: function () {
                            alert('Error!');
                        }
                    });
                    // $("#addTransitionModalForm").modal("show");
    
            }
        });


        //возврат связи на место если оторвалось
        instance.bind("beforeDetach", function (e) {
            //проверка является ли разрыв связи удалением
            if(removed_transition != true){
                return false;
            } else {
                removed_transition = false;
            }
        });
    });
    //-----конец кода jsPlumb-----



    //функция расширения или сужения поля visual_diagram_field для размещения элементов
    var mousemoveState = function() {
        var field = document.getElementById('visual_diagram_field');

        var width_field = field.clientWidth;
        var height_field = field.clientHeight;

        var max_w = 0;
        var max_h = 0;

        $(".div-state").each(function(i) {
            var id_state = $(this).attr('id');
            var state = document.getElementById(id_state);

            var w = state.offsetLeft + state.clientWidth;
            var h = state.offsetTop + state.clientHeight;

            if (w > max_w){max_w = w;}
            if (h > max_h){max_h = h;}

        });

        $(".div-basic-event").each(function(i) {
            var id_basic = $(this).attr('id');
            var basic = document.getElementById(id_basic);

            var w = basic.offsetLeft + basic.clientWidth;
            var h = basic.offsetTop + basic.clientHeight;
            if (w > max_w){max_w = w;}
            if (h > max_h){max_h = h;}

        });


        $(".div-and").each(function(i) {
            var id_start = $(this).attr('id');
            var start = document.getElementById(id_start);

            var w = start.offsetLeft + start.clientWidth;
            var h = start.offsetTop + start.clientHeight;

            if (w > max_w){max_w = w;}
            if (h > max_h){max_h = h;}
        });

        $(".div-or").each(function(i) {
            var id_end = $(this).attr('id');
            var end = document.getElementById(id_end);

            var w = end.offsetLeft + end.clientWidth;
            var h = end.offsetTop + end.clientHeight;

            if (w > max_w){max_w = w;}
            if (h > max_h){max_h = h;}

        });

        $(".div-prohibition").each(function(i) {
            var id_end = $(this).attr('id');
            var end = document.getElementById(id_end);

            var w = end.offsetLeft + end.clientWidth;
            var h = end.offsetTop + end.clientHeight;

            if (w > max_w){max_w = w;}
            if (h > max_h){max_h = h;}

        });

        $(".div-majority-valve").each(function(i) {
            var id_end = $(this).attr('id');
            var end = document.getElementById(id_end);

            var w = end.offsetLeft + end.clientWidth;
            var h = end.offsetTop + end.clientHeight;

            if (w > max_w){max_w = w;}
            if (h > max_h){max_h = h;}

        });

        $(".div-and-with-priority").each(function(i) {
            var id_end = $(this).attr('id');
            var end = document.getElementById(id_end);

            var w = end.offsetLeft + end.clientWidth;
            var h = end.offsetTop + end.clientHeight;

            if (w > max_w){max_w = w;}
            if (h > max_h){max_h = h;}

        });

        $(".div-undeveloped-event").each(function(i) {
            var id_end = $(this).attr('id');
            var end = document.getElementById(id_end);

            var w = end.offsetLeft + end.clientWidth;
            var h = end.offsetTop + end.clientHeight;

            if (w > max_w){max_w = w;}
            if (h > max_h){max_h = h;}

        });

        $(".div-not").each(function(i) {
            var id_end = $(this).attr('id');
            var end = document.getElementById(id_end);

            var w = end.offsetLeft + end.clientWidth;
            var h = end.offsetTop + end.clientHeight;

            if (w > max_w){max_w = w;}
            if (h > max_h){max_h = h;}

        });

        $(".div-hidden-event").each(function(i) {
            var id_end = $(this).attr('id');
            var end = document.getElementById(id_end);

            var w = end.offsetLeft + end.clientWidth;
            var h = end.offsetTop + end.clientHeight;

            if (w > max_w){max_w = w;}
            if (h > max_h){max_h = h;}

        });


        $(".div-conditional-event").each(function(i) {
            var id_end = $(this).attr('id');
            var end = document.getElementById(id_end);

            var w = end.offsetLeft + end.clientWidth;
            var h = end.offsetTop + end.clientHeight;

            if (w > max_w){max_w = w;}
            if (h > max_h){max_h = h;}

        });

        field.style.width = max_w + 20 + 'px';
        field.style.height = max_h + 20 + 'px';
    };


    //функция сохранения расположения элемента
    var saveIndent = function(state_id, indent_x, indent_y) {
        $.ajax({
            //переход на экшен левел
            url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
            '/fault-tree-diagrams/save-indent'?>",
            type: "post",
            data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&state_id=" + state_id +
            "&indent_x=" + indent_x + "&indent_y=" + indent_y,
            dataType: "json",
            success: function (data) {
                if (data['success']) {
                    //console.log("x = " + data['indent_x']);
                    //console.log("y = " + data['indent_y']);
                }
            },
            error: function () {
                alert('Error!');
            }
        });
    };


    //при движении блока состояния расширяем или сужаем поле visual_diagram_field
    $(document).on('mousemove', '.div-state', function() {
        mousemoveState();
        // Обновление формы редактора
        instance.repaintEverything();
    });

        //при движении блока состояния расширяем или сужаем поле visual_diagram_field
    $(document).on('mousemove', '.div-state-start', function() {
        mousemoveState();
        // Обновление формы редактора
        instance.repaintEverything();
    });

    $(document).on('mousemove', '.div-basic-event', function() {
        mousemoveState();
        // Обновление формы редактора
        instance.repaintEverything();
    });

    //при движении блока начала расширяем или сужаем поле visual_diagram_field
    $(document).on('mousemove', '.div-and', function() {
        mousemoveState();
        // Обновление формы редактора
        instance.repaintEverything();
    });

    //при движении блока завершения расширяем или сужаем поле visual_diagram_field
    $(document).on('mousemove', '.div-or', function() {
        mousemoveState();
        // Обновление формы редактора
        instance.repaintEverything();
    });

        //при движении блока завершения расширяем или сужаем поле visual_diagram_field
    $(document).on('mousemove', '.div-prohibition', function() {
        mousemoveState();
        // Обновление формы редактора
        instance.repaintEverything();
    });

        //при движении блока завершения расширяем или сужаем поле visual_diagram_field
    $(document).on('mousemove', '.div-majority-valve', function() {
        mousemoveState();
        // Обновление формы редактора
        instance.repaintEverything();
    });

        //при движении блока завершения расширяем или сужаем поле visual_diagram_field
    $(document).on('mousemove', '.div-and-with-priority', function() {
        mousemoveState();
        // Обновление формы редактора
        instance.repaintEverything();
    }); 

    //при движении блока завершения расширяем или сужаем поле visual_diagram_field
    $(document).on('mousemove', '.div-undeveloped-event', function() {
        mousemoveState();
        // Обновление формы редактора
        instance.repaintEverything();
    }); 

    //при движении блока завершения расширяем или сужаем поле visual_diagram_field
    $(document).on('mousemove', '.div-not', function() {
        mousemoveState();
        // Обновление формы редактора
        instance.repaintEverything();
    }); 

    //при движении блока завершения расширяем или сужаем поле visual_diagram_field
    $(document).on('mousemove', '.div-hidden-event', function() {
        mousemoveState();
        // Обновление формы редактора
        instance.repaintEverything();
    }); 

    //при движении блока завершения расширяем или сужаем поле visual_diagram_field
    $(document).on('mousemove', '.div-conditional-event', function() {
        mousemoveState();
        // Обновление формы редактора
        instance.repaintEverything();
    }); 

    //сохранение расположения элемента
    $(document).on('mouseup', '.div-state', function() {
        if (!guest) {
            var state = $(this).attr('id');
            var state_id = parseInt(state.match(/\d+/));
            var indent_x = $(this).position().left;
            var indent_y = $(this).position().top;
            //если отступ элемента отрицательный делаем его нулевым
            if (indent_x < 0){
                indent_x = 0;
            }
            if (indent_y < 0){
                indent_y = 0;
            }
            saveIndent(state_id, indent_x, indent_y);
        }
    });

        //сохранение расположения элемента
        $(document).on('mouseup', '.div-state-start', function() {
        if (!guest) {
            var state = $(this).attr('id');
            var state_id = parseInt(state.match(/\d+/));
            var indent_x = $(this).position().left;
            var indent_y = $(this).position().top;
            //если отступ элемента отрицательный делаем его нулевым
            if (indent_x < 0){
                indent_x = 0;
            }
            if (indent_y < 0){
                indent_y = 0;
            }
            saveIndent(state_id, indent_x, indent_y);
        }
    });



    // редактирование состояния
    $(document).on('click', '.edit-state', function() {
        if (!guest) {
            var state = $(this).attr('id');
            state_id_on_click = parseInt(state.match(/\d+/));

            var div_state = document.getElementById("state_" + state_id_on_click);

            $.each(mas_data_state, function (i, elem) {
                if (elem.id == state_id_on_click) {
                    document.forms["edit-state-form"].reset();
                    document.forms["edit-state-form"].elements["Element[name]"].value = elem.name;
                    document.forms["edit-state-form"].elements["Element[description]"].value = elem.description;

                    $("#editStateModalForm").modal("show");
                }
            });
        }
    });


    // удаление состояния
    $(document).on('click', '.del-state', function() {
        if (!guest) {
            var del = $(this).attr('id');
            state_id_on_click = parseInt(del.match(/\d+/));
            $("#deleteStateModalForm").modal("show");
        }
    });


    // копирование состояния
    $(document).on('click', '.copy-state', function() {
        if (!guest) {
            var state = $(this).attr('id');
            state_id_on_click = parseInt(state.match(/\d+/));

            var div_state = document.getElementById("state_" + state_id_on_click);

            $.each(mas_data_state, function (i, elem) {
                if (elem.id == state_id_on_click) {
                    document.forms["copy-state-form"].reset();
                    //document.forms["copy-state-form"].elements["State[name]"].value = elem.name;
                    document.forms["copy-state-form"].elements["Element[description]"].value = elem.description;
                    $("#copyStateModalForm").modal("show");
                }
            });
        }
    });


    // добавление свойства состояния
    $(document).on('click', '.add-state-property', function() {
        if (!guest) {
            var state = $(this).attr('id');
            state_id_on_click = parseInt(state.match(/\d+/));
            $("#addStatePropertyModalForm").modal("show");
        }
    });


    // изменение  состояния
    $(document).on('click', '.edit-state-property', function() {
        if (!guest) {
            var state_property = $(this).attr('id');
            state_property_id_on_click = parseInt(state_property.match(/\d+/));

            $.each(mas_data_state_property, function (i, elem) {
                if (elem.id == state_property_id_on_click) {
                    document.forms["edit-state-property-form"].reset();
                    document.forms["edit-state-property-form"].elements["StateProperty[name]"].value = elem.name;
                    document.forms["edit-state-property-form"].elements["StateProperty[description]"].value = elem.description;
                    document.forms["edit-state-property-form"].elements["StateProperty[operator]"].value = elem.operator;
                    document.forms["edit-state-property-form"].elements["StateProperty[value]"].value = elem.value;
                    $("#editStatePropertyModalForm").modal("show");
                }
            });
        }
    });


    // удаление состояния
    $(document).on('click', '.del-state-property', function() {
        if (!guest) {
            var state_property = $(this).attr('id');
            state_property_id_on_click = parseInt(state_property.match(/\d+/));
            $("#deleteStatePropertyModalForm").modal("show");
            // Обновление формы редактора
            instance.repaintEverything();
        }
    });

    //сохранение расположения элемента
    $(document).on('mouseup', '.div-basic-event', function() {
        var field = document.getElementById('visual_diagram_field');
        if (!guest) {
            var state = $(this).attr('id');
            var state_id = parseInt(state.match(/\d+/));
            var indent_x = $(this).position().left;
            var indent_y = $(this).position().top;
            //если отступ элемента отрицательный делаем его нулевым
            if (indent_x < 0){
                indent_x = 0;
            }
            if (indent_y < 0){
                indent_y = 0;
            }
            saveIndent(state_id, indent_x / parseFloat(field.style.transform.split('(')[1].split(')')[0]), indent_y / parseFloat(field.style.transform.split('(')[1].split(')')[0]));
         
        }
    });


     // редактирование базового события
     $(document).on('click', '.edit-basic-event', function() {
        if (!guest) {
            var basic_event = $(this).attr('id');
            basic_event_id_on_click = parseInt(basic_event.match(/\d+/));

            var div_basic_event = document.getElementById("basic_event_" + basic_event_id_on_click);

            $.each(mas_data_basic_event, function (i, elem) {
                if (elem.id == basic_event_id_on_click) {
                    document.forms["edit-basic-event-form"].reset();
                    document.forms["edit-basic-event-form"].elements["Element[name]"].value = elem.name;
                    document.forms["edit-basic-event-form"].elements["Element[description]"].value = elem.description;

                    $("#editBasicEventModalForm").modal("show");
                }
            });
        }
    });


    // удаление состояния
    $(document).on('click', '.del-basic-event', function() {
        if (!guest) {
            var del = $(this).attr('id');
            basic_event_id_on_click = parseInt(del.match(/\d+/));
            $("#deleteBasicEventModalForm").modal("show");
        }
    });


    // копирование состояния
    $(document).on('click', '.copy-basic-event', function() {
        if (!guest) {
            var state = $(this).attr('id');
            basic_event_id_on_click = parseInt(state.match(/\d+/));

            var div_state = document.getElementById("state_" + basic_event_id_on_click);

            $.each(mas_data_state, function (i, elem) {
                if (elem.id == basic_event_id_on_click) {
                    document.forms["copy-state-form"].reset();
                    //document.forms["copy-state-form"].elements["State[name]"].value = elem.name;
                    document.forms["copy-state-form"].elements["Element[description]"].value = elem.description;
                    $("#copyStateModalForm").modal("show");
                }
            });
        }
    });


    // добавление свойства состояния
    $(document).on('click', '.add-basic-event-property', function() {
        if (!guest) {
            var state = $(this).attr('id');
            basic_event_id_on_click = parseInt(state.match(/\d+/));
            $("#addStatePropertyModalForm").modal("show");
        }
    });


    // изменение  состояния
    $(document).on('click', '.edit-basic-event-property', function() {
        if (!guest) {
            var state_property = $(this).attr('id');
            state_property_id_on_click = parseInt(state_property.match(/\d+/));

            $.each(mas_data_state_property, function (i, elem) {
                if (elem.id == state_property_id_on_click) {
                    document.forms["edit-state-property-form"].reset();
                    document.forms["edit-state-property-form"].elements["StateProperty[name]"].value = elem.name;
                    document.forms["edit-state-property-form"].elements["StateProperty[description]"].value = elem.description;
                    document.forms["edit-state-property-form"].elements["StateProperty[operator]"].value = elem.operator;
                    document.forms["edit-state-property-form"].elements["StateProperty[value]"].value = elem.value;
                    $("#editStatePropertyModalForm").modal("show");
                }
            });
        }
    });


    // удаление состояния
    $(document).on('click', '.del-basic-event-property', function() {
        if (!guest) {
            var state_property = $(this).attr('id');
            state_property_id_on_click = parseInt(state_property.match(/\d+/));
            $("#deleteStatePropertyModalForm").modal("show");
            // Обновление формы редактора
            instance.repaintEverything();
        }
    });


    //скрытие блоков переходов
    $(document).on('click', '.hide-transition', function() {
        var transition = $(this).attr('id');
        var transition_id = parseInt(transition.match(/\d+/));

        //Поиск блока перехода
        var div_transition = document.getElementById("transition_" + transition_id);
        div_transition.style.visibility='hidden'
    });


    //изменение перехода
    $(document).on('click', '.edit-transition', function() {
        if (!guest) {
            var transition = $(this).attr('id');
            transition_id_on_click = parseInt(transition.match(/\d+/));

            $.each(mas_data_transition, function (i, elem) {
                if (elem.id == transition_id_on_click) {
                    document.forms["edit-transition-form"].reset();
                    document.forms["edit-transition-form"].elements["Transition[name]"].value = elem.name;
                    document.forms["edit-transition-form"].elements["Transition[description]"].value = elem.description;
                    //Скрытые обязательные поля (заполняем не пустыми значениями)
                    document.forms["edit-transition-form"].elements["Transition[name_property]"].value = "test";
                    document.forms["edit-transition-form"].elements["Transition[operator_property]"].value = 0;
                    document.forms["edit-transition-form"].elements["Transition[value_property]"].value = "test";
                    $("#editTransitionModalForm").modal("show");
                }
            });
        }
    });


    // //удаленеи перехода
    // $(document).on('click', '.del-transition', function() {
    //     if (!guest) {
    //         var transition = $(this).attr('id');
    //         transition_id_on_click = parseInt(transition.match(/\d+/));
    //         $("#deleteTransitionModalForm").modal("show");
    //     }
    // });


    // добавление условия
    $(document).on('click', '.add-transition-property', function() {
        if (!guest) {
            var transition = $(this).attr('id');
            transition_id_on_click = parseInt(transition.match(/\d+/));
            $("#addTransitionPropertyModalForm").modal("show");
        }
    });


    // изменение условия
    $(document).on('click', '.edit-transition-property', function() {
        if (!guest) {
            var transition_property = $(this).attr('id');
            transition_property_id_on_click = parseInt(transition_property.match(/\d+/));

            $.each(mas_data_transition_property, function (i, elem) {
                if (elem.id == transition_property_id_on_click) {
                    document.forms["edit-transition-property-form"].reset();
                    document.forms["edit-transition-property-form"].elements["TransitionProperty[name]"].value = elem.name;
                    document.forms["edit-transition-property-form"].elements["TransitionProperty[description]"].value = elem.description;
                    document.forms["edit-transition-property-form"].elements["TransitionProperty[operator]"].value = elem.operator;
                    document.forms["edit-transition-property-form"].elements["TransitionProperty[value]"].value = elem.value;
                    $("#editTransitionPropertyModalForm").modal("show");
                }
            });
        }
    });


    // удаление условия
    $(document).on('click', '.del-transition-property', function() {
        if (!guest) {
            var transition_property = $(this).attr('id');
            transition_property_id_on_click = parseInt(transition_property.match(/\d+/));
            $("#deleteTransitionPropertyModalForm").modal("show");
            // Обновление формы редактора
            instance.repaintEverything();
        }
    });


    //равномерное распределение и выравнивание элементов по диаграмме
    $('#nav_alignment').on('click', function() {
        //переменные отступа
        var left = 10;
        var top = 10;
        var col = 0;

        $(".div-state").each(function(i) {
            $(this).css({
                left: left,
                top: top
            });
            left = left + 300;
            col = col + 1;
            if (col == 4){
                top = top + 250;
                left = 10;
                col = 0;
            }
        });

        //сохранение местоположения
        $(".div-state").each(function(i) {
            var state = $(this).attr('id');
            var state_id = parseInt(state.match(/\d+/));
            var indent_x = $(this).position().left;
            var indent_y = $(this).position().top;
            saveIndent(state_id, indent_x, indent_y);
        });
        mousemoveState();
        // Обновление формы редактора
        instance.repaintEverything();
    });


    //автоматическое выравнивание элементов диаграммы после импорта
    //(если отступ от угла = 0)
    $(document).ready(function() {
        var sum = 0;
        $.each(mas_data_state, function (i, elem) {
            sum = sum + elem.indent_x + elem.indent_y;
        });
        //console.log(sum);
        if (sum == 0){
            //console.log("выровнить");
            $("#nav_alignment").click();
        }
    });


    //добавление элемента "начало" на диаграмму
    $('#nav_add_start').on('click', function() {
        $.ajax({
            //переход на экшен левел
            url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
            '/fault-tree-diagrams/add-start/' . $model->id ?>",
            type: "post",
            data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>",
            dataType: "json",
            success: function (data) {
                if (data['success']) {
                    //создание div состояния
                    var div_visual_diagram_field = document.getElementById('visual_diagram_field');

                    var div_and = document.createElement('div');
                    div_and.id = 'and_' + data['id'];
                    div_and.className = 'div-and';
                    div_visual_diagram_field.append(div_and);

                    var div_and_and = document.createElement('div');
                    div_and_and.className = 'div-and-and' ;
                    div_and_and.innerHTML = 'И';
                    div_and.append(div_and_and);

                    var div_content_start = document.createElement('div');
                    div_content_start.className = 'content-and';
                    div_and.append(div_content_start);

                    var div_del = document.createElement('div');
                    div_del.id = 'and_del_' + data['id'];
                    div_del.className = 'del-and' ;
                    div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>';
                    div_del.innerHTML = '<i class="fa-solid fa-trash"></i>'
                    div_content_start.append(div_del);

                    var div_connect = document.createElement('div');
                    div_connect.className = 'connect-and' ;
                    div_connect.title = '<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>' ;
                    div_connect.innerHTML = '<i class="fa-solid fa-share"></i>';
                    div_content_start.append(div_connect);

                    //сделать div двигаемым
                    var div_and = document.getElementById('and_' + data['id']);
                    instance.draggable(div_and);
                    //добавляем элемент div_and в группу с именем group_field
                    instance.addToGroup('group_field', div_and);

                    instance.makeSource(div_and, {
                            filter: ".fa-share",
                            anchor: [ 0.5, 1, 0, 1, 0, -20 ], //непрерывный анкер
                            maxConnections: -1, //ограничение на одно соединение из элемента "начала"
                            // onMaxConnections: function (info, e) {
                            //     //отображение сообщения об ограничении
                            //     var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
                            //     document.getElementById("message-text").lastChild.nodeValue = message;
                            //     $("#viewMessageErrorLinkingItemsModalForm").modal("show");
                            // }
                    });
                    instance.makeTarget(div_and, {
                        filter: ".fa-share",
                        anchor: [ 0.5, 0, 0, -1, 0, 20 ], //непрерывный анкер
                        maxConnections: 1, //ог
                        allowLoopback: false,
                        onMaxConnections: function (info, e) {
                            //отображение сообщения об ограничении
                            var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
                            document.getElementById("message-text").lastChild.nodeValue = message;
                            $("#viewMessageErrorLinkingItemsModalForm").modal("show");
                        }
                    });

                    var nav_add_start = document.getElementById('nav_add_start');
                    // Выключение кнопки добавления начала
                    nav_add_start.className = 'dropdown-item';
                }
            },
            error: function () {
                alert('Error!');
            }
        });
    });


    //удаление начала
    $(document).on('click', '.del-and', function() {
        if (!guest) {
            var start = $(this).attr('id');
            id_start = parseInt(start.match(/\d+/));

            $("#deleteStartModalForm").modal("show");
        }
    });


    //добавление элемента "завершение" на диаграмму
    $('#nav_add_end').on('click', function() {
        $.ajax({
            //переход на экшен левел
            url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
            '/fault-tree-diagrams/add-end/' . $model->id ?>",
            type: "post",
            data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>",
            dataType: "json",
            success: function (data) {
                if (data['success']) {
                    //создание div состояния
                    var div_visual_diagram_field = document.getElementById('visual_diagram_field');

                    var div_end = document.createElement('div');
                    div_end.id = 'or_' + data['id'];
                    div_end.className = 'div-or';
                    div_visual_diagram_field.append(div_end);

                    var div_or_or = document.createElement('div');
                    div_or_or.className = 'div-or-or' ;
                    div_or_or.innerHTML = 'ИЛИ';
                    div_end.append(div_or_or);

                    var div_content_end = document.createElement('div');
                    div_content_end.className = 'content-or';
                    div_end.append(div_content_end);


                    var div_del = document.createElement('div');
                    div_del.id = 'or_del_' + data['id'];
                    div_del.className = 'del-or' ;
                    div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>';
                    div_del.innerHTML = '<i class="fa-solid fa-trash"></i>'
                    div_content_end.append(div_del);

                    var div_connect = document.createElement('div');
                    div_connect.className = 'connect-or' ;
                    div_connect.title = '<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>' ;
                    div_connect.innerHTML = '<i class="fa-solid fa-share"></i>';
                    div_content_end.append(div_connect);

                    //сделать div двигаемым
                    var div_end = document.getElementById('or_' + data['id']);
                    instance.draggable(div_end);
                    //добавляем элемент div_and в группу с именем group_field
                    instance.addToGroup('group_field', div_end);

                    // instance.makeTarget(div_end, {
                    //         filter: ".fa-share",
                    //         anchor: "Top", //непрерывный анкер
                    //         maxConnections: -1,
                    // });
                    instance.makeSource(div_end, {
                        filter: ".fa-share",
                        anchor: [ 0.5, 1, 0, 1, 0, -20 ], //непрерывный анкер
                        maxConnections: -1, //ограничение на одно соединение из элемента "начала"
                    });
                    instance.makeTarget(div_end, {
                        filter: ".fa-share",
                        anchor: [ 0.5, 0, 0, -1, 0, 20 ], //непрерывный анкер
                        maxConnections: 1, //ог
                        allowLoopback: false,
                        onMaxConnections: function (info, e) {
                            //отображение сообщения об ограничении
                            var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
                            document.getElementById("message-text").lastChild.nodeValue = message;
                            $("#viewMessageErrorLinkingItemsModalForm").modal("show");
                        }
                    });
                    var nav_add_end = document.getElementById('nav_add_end');
                    // Выключение кнопки добавления завершения
                    nav_add_end.className = 'dropdown-item';
                }
            },
            error: function () {
                alert('Error!');
            }
        });
    });


    //удаление завершения
    $(document).on('click', '.del-or', function() {
        if (!guest) {
            var end = $(this).attr('id');
            id_end = parseInt(end.match(/\d+/));

            $("#deleteEndModalForm").modal("show");
        }
    });


    //функция сохранения расположения элемента начала или завершения
    var saveIndentStartOrEnd = function(start_or_end_id, indent_x, indent_y) {
        $.ajax({
            //переход на экшен левел
            url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
            '/fault-tree-diagrams/save-indent-start-or-end'?>",
            type: "post",
            data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&start_or_end_id=" + start_or_end_id +
            "&indent_x=" + indent_x + "&indent_y=" + indent_y,
            dataType: "json",
            success: function (data) {
                if (data['success']) {
                    //console.log("x = " + data['indent_x']);
                    //console.log("y = " + data['indent_y']);
                }
            },
            error: function () {
                alert('Error!');
            }
        });
    };


    //сохранение расположения элемента начала
    $(document).on('mouseup', '.div-and', function() {
        if (!guest) {
            var start_or_end = $(this).attr('id');
            var start_or_end_id = parseInt(start_or_end.match(/\d+/));
            var indent_x = $(this).position().left;
            var indent_y = $(this).position().top;
            //если отступ элемента отрицательный делаем его нулевым
            if (indent_x < 0){
                indent_x = 0;
            }
            if (indent_y < 0){
                indent_y = 0;
            }
            saveIndentStartOrEnd(start_or_end_id, indent_x, indent_y);
        }
    });


    //сохранение расположения элемента завершения
    $(document).on('mouseup', '.div-or', function() {
        if (!guest) {
            var start_or_end = $(this).attr('id');
            var start_or_end_id = parseInt(start_or_end.match(/\d+/));
            var indent_x = $(this).position().left;
            var indent_y = $(this).position().top;
            //если отступ элемента отрицательный делаем его нулевым
            if (indent_x < 0){
                indent_x = 0;
            }
            if (indent_y < 0){
                indent_y = 0;
            }
            saveIndentStartOrEnd(start_or_end_id, indent_x, indent_y);
        }
    });


    //Не
    deleteNot()
    saveIndentNot()

    //Неразвитое событие
    editUndevelopedEvent()
    deleteUndevelopedEvent() 
    copyUndevelopedEvent()
    saveIndentUndevelopedEvent()

    //Скрытое событие
    editHiddenEvent()
    deleteHiddenEvent() 
    copyHiddenEvent()
    saveIndentHiddenEvent()

    //Условное событие
    editConditionalEvent()
    deleteConditionalEvent() 
    copyConditionalEvent()
    saveIndentConditionalEvent()

    // document.addEventListener('wheel', function(e) {
    // e.ctrlKey && e.preventDefault();
    // }, {
    // passive: false,
    // });
    // document.addEventListener("DOMContentLoaded", function(){ 
   

    //         const zoomElement = document.getElementById("visual_diagram_field");
    //         console.log(zoomElement)
    //         let zoom = 1;
    //         const ZOOM_SPEED = 0.1;
    //         document.addEventListener("wheel", function(e) {  
    //             if(event.ctrlKey == true)
    //         {
    //             e.ctrlKey && e.preventDefault();
                
    //             if(e.deltaY > 0){    
    //                 zoomElement.style.transform = `scale(${zoom += ZOOM_SPEED})`;  
    //             }else{    
    //                 zoomElement.style.transform = `scale(${zoom -= ZOOM_SPEED})`;  }
    //             }

    //         }, {
    //     passive: false,
    //     });
        
    // })
    
 


        
</script>




<div class="state-transition-diagram-visual-diagram">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<div id="visual_diagram" class="visual-diagram col-md-12">

    <div id="visual_diagram_field" class="visual-diagram-top-layer" style="transform: scale(1.0);">

    <?php foreach ($start_model as $and): ?>
        <div id="and_<?= $and->id ?>" class="div-and">
            <div class="div-and-and">И</div>
            <div class="content-and">
                <div id="and_del_<?= $and->id ?>" class="del-and" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                <div class="connect-and" title="<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>"><i class="fa-solid fa-share"></i></div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- отображение состояний -->
        <?php foreach ($states_model_all as $state): ?>
            <?php if ($state->type == 3): ?>
                <div id="state-start_<?= $state->id ?>" class="div-state-start" title="<?= $state->description ?>">
            <?php else: ?>
                <div id="state_<?= $state->id ?>" class="div-state" title="<?= $state->description ?>">
            <?php endif; ?>
                <div class="content-state">
                    <div id="state_name_<?= $state->id ?>" class="div-state-name"><?= $state->name ?></div>
                    <div class="connect-state" title="<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>"><i class="fa-solid fa-share"></i></div>
                    <div id="state_del_<?= $state->id ?>" class="del-state glyphicon-trash" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                    <div id="state_edit_<?= $state->id ?>" class="edit-state glyphicon-pencil" title="<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>"><i class="fa-solid fa-pen"></i></div>
                    <div id="state_add_property_<?= $state->id ?>" class="add-state-property glyphicon-plus" title="<?php echo Yii::t('app', 'BUTTON_ADD'); ?>"><i class="fa-solid fa-plus"></i></div>
                    <div id="state_copy_<?= $state->id ?>" class="copy-state glyphicon-plus-sign" title="<?php echo Yii::t('app', 'BUTTON_COPY'); ?>"><i class="fa-solid fa-circle-plus"></i></div>
                </div>

                <!-- отображение разделительной пунктирной линии -->
                <?php
                    $line = false;
                    foreach ($states_property_model_all as $state_property){
                        if ($state_property->fault == $state->id){
                            $line = true;
                        }
                    }
                ?>
                <?php if ($line == true){ ?>
                    <div id="state_line_<?= $state->id ?>" class="div-line"></div>
                <?php } ?>

                <!-- отображение свойств состояний -->
                <?php foreach ($states_property_model_all as $state_property): ?>
                    <?php if ($state_property->fault == $state->id){ ?>
                        <div id="state_property_<?= $state_property->id ?>" class="div-state-property">
                            <div class="button-state-property">
                                <div id="state_property_edit_<?= $state_property->id ?>" class="edit-state-property glyphicon-pencil" title="<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>"><i class="fa-solid fa-pen"></i></div>
                                <div id="state_property_del_<?= $state_property->id ?>" class="del-state-property glyphicon-trash"  title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                            </div>
                            <?= $state_property->name ?> <?= $state_property->getOperatorName() ?> <?= $state_property->value ?>
                        </div>
                    <?php } ?>
                <?php endforeach; ?>

            </div>
        <?php endforeach; ?>

         <!-- отображение базовых событий -->
         <?php foreach ($basic_event_model as $basic_event): ?>
            <div id="basic_event_<?= $basic_event->id ?>" class="div-basic-event" title="<?= $basic_event->description ?>">
                <div class="content-basic-event">
                    <div id="basic_event_name_<?= $basic_event->id ?>" class="div-basic-event-name"><?= $basic_event->name ?></div>
                    <!-- <div class="connect-basic-event" title="<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>"><i class="fa-solid fa-share"></i></div> -->
                    <div id="basic_event_del_<?= $basic_event->id ?>" class="del-basic-event glyphicon-trash" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                    <div id="basic_event_edit_<?= $basic_event->id ?>" class="edit-basic-event glyphicon-pencil" title="<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>"><i class="fa-solid fa-pen"></i></div>
                    <!-- <div id="basic_event_property_<?= $basic_event->id ?>" class="add-basic-event-property glyphicon-plus" title="<?php echo Yii::t('app', 'BUTTON_ADD'); ?>"><i class="fa-solid fa-plus"></i></div> -->
                    <div id="basic_event_copy_<?= $basic_event->id ?>" class="copy-basic-event glyphicon-plus-sign" title="<?php echo Yii::t('app', 'BUTTON_COPY'); ?>"><i class="fa-solid fa-circle-plus"></i></div>
                </div>
                
                <!-- отображение разделительной пунктирной линии -->
                <?php /*
                    $line = false;
                    foreach ($states_property_model_all as $state_property){
                        if ($state_property->fault == $state->id){
                            $line = true;
                        }
                    }
                ?>
                <?php if ($line == true){ ?>
                    <div id="basic_event_line_<?= $basic_event->id ?>" class="div-line"></div>
                <?php }*/ ?>


            </div>
        <?php endforeach; ?>


        <!-- отображение блоков переходов -->
        <?php foreach ($transitions_model_all as $transition): ?>
            <div id="transition_<?= $transition->id ?>" class="div-transition" style="visibility:hidden;">
                <div class="content-transition">
                    <div id="transition_name_<?= $transition->id ?>" class="div-transition-name"><?= $transition->name ?></div>
                    <div id="transition_del_<?= $transition->id ?>" class="del-transition glyphicon-trash" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                    <div id="transition_edit_<?= $transition->id ?>" class="edit-transition glyphicon-pencil" title="<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>"><i class="fa-solid fa-pen"></i></div>
                    <div id="transition_hide_<?= $transition->id ?>" class="hide-transition glyphicon-eye-close" title="<?php echo Yii::t('app', 'BUTTON_HIDE'); ?>"><i class="fa-solid fa-eye-slash"></i></div>
                    <div id="transition_add_property_<?= $transition->id ?>" class="add-transition-property glyphicon-plus" title="<?php echo Yii::t('app', 'BUTTON_ADD'); ?>"><i class="fa-solid fa-plus"></i></div>
                </div>

                <!-- отображение разделительной пунктирной линии -->
                <?php
                    $line = false;
                    foreach ($transitions_property_model_all as $transition_property){
                        if ($transition_property->transition == $transition->id){
                            $line = true;
                        }
                    }
                ?>
                <?php if ($line == true){ ?>
                    <div id="transition_line_<?= $transition->id ?>" class="div-line"></div>
                <?php } ?>

                <!-- отображение условий -->
                <?php foreach ($transitions_property_model_all as $transition_property): ?>
                    <?php if ($transition_property->transition == $transition->id){ ?>
                        <div id="transition_property_<?= $transition_property->id ?>" class="div-transition-property">
                            <div class="button-transition-property">
                                <div id="transition_property_edit_<?= $transition_property->id ?>" class="edit-transition-property glyphicon-pencil" title="<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>"><i class="fa-solid fa-pen"></i></div>
                                <div id="transition_property_del_<?= $transition_property->id ?>" class="del-transition-property glyphicon-trash" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                            </div>
                            <?= $transition_property->name ?> <?= $transition_property->getOperatorName()?> <?= $transition_property->value ?>
                        </div>
                    <?php } ?>
                <?php endforeach; ?>

            </div>
        <?php endforeach; ?>

        <?php foreach ($end_model as $end): ?>
            <div id="or_<?= $end->id ?>" class="div-or">
            <div class="div-or-or">ИЛИ</div>
                <div class="content-or">
                    <div id="or_del_<?= $end->id ?>" class="del-or" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                    <div class="connect-or" title="<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>"><i class="fa-solid fa-share"></i></div>
                </div>
            </div>
            <?php endforeach; ?>

        <?php foreach ($prohibition_model as $prohibition): ?>
            <div id="prohibition_<?= $prohibition->id ?>" class="div-prohibition">
                <div class="content-prohibition">
                    <div id="prohibition_del_<?= $prohibition->id ?>" class="del-prohibition" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                    <div class="connect-prohibition" title="<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>"><i class="fa-solid fa-share"></i></div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php foreach ($majority_valve_model as $majority): ?>
            <div id="majority_valve_<?= $majority->id ?>" class="div-majority-valve">
            <div class="div-majority-valve-text">МАЖ</div>
                <div class="content-majority-valve">
                    <div id="majority_valve_del_<?= $majority->id ?>" class="del-majority-valve" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                    <div class="connect-majority-valve" title="<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>"><i class="fa-solid fa-share"></i></div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php foreach ($and_with_priority_model as $and_with): ?>
            <div id="and-with_priority_<?= $and_with->id ?>" class="div-and-with-priority">
            <div class="div-and-with-priority-text">ИП</div>
                <div class="content-and-with-priority">
                    <div id="and_with_priority_del_<?= $and_with->id ?>" class="del-and-with-priority" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                    <div class="connect-and-with-priority" title="<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>"><i class="fa-solid fa-share"></i></div>
                </div>
            </div>
        <?php endforeach; ?>


         <!-- отображение неразвитых событий -->
         <?php foreach ($undeveloped_event_model as $undeveloped_event): ?>
            <div id="undeveloped_event_<?= $undeveloped_event->id ?>" class="div-undeveloped-event" title="<?= $undeveloped_event->description ?>">
                <div class="content-undeveloped-event">
                    <div id="undeveloped_event_name_<?= $undeveloped_event->id ?>" class="div-undeveloped-event-name"><?= $undeveloped_event->name ?></div>
                    <!-- <div class="connect-undeveloped-event" title="<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>"><i class="fa-solid fa-share"></i></div> -->
                    <div id="undeveloped_event_del_<?= $undeveloped_event->id ?>" class="del-undeveloped-event glyphicon-trash" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                    <div id="undeveloped_event_edit_<?= $undeveloped_event->id ?>" class="edit-undeveloped-event glyphicon-pencil" title="<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>"><i class="fa-solid fa-pen"></i></div>
                    <!-- <div id="undeveloped_event_property_<?= $undeveloped_event->id ?>" class="add-undeveloped-event-property glyphicon-plus" title="<?php echo Yii::t('app', 'BUTTON_ADD'); ?>"><i class="fa-solid fa-plus"></i></div> -->
                    <div id="undeveloped_event_copy_<?= $undeveloped_event->id ?>" class="copy-undeveloped-event glyphicon-plus-sign" title="<?php echo Yii::t('app', 'BUTTON_COPY'); ?>"><i class="fa-solid fa-circle-plus"></i></div>
                </div>
                
            </div>
         <?php endforeach; ?>


         <?php foreach ($not_model as $not): ?>
            <div id="not_<?= $not->id ?>" class="div-not">
            <div class="div-not-text">НЕ</div>
                <div class="content-not">
                    <div id="not_del_<?= $not->id ?>" class="del-not" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                    <div class="connect-not" title="<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>"><i class="fa-solid fa-share"></i></div>
                </div>
            </div>
        <?php endforeach; ?>


        <?php foreach ($transfer_valve_model as $transfer_valve): ?>
            <div id="transfer_valve_<?= $transfer_valve->id ?>" class="div-transfer-valve">
                <div class="content-transfer-valve">
                    <div id="transfer_valve_name_<?= $transfer_valve->id ?>" class="div-transfer-valve-name"><?= $transfer_valve->name ?></div>
                    <div id="transfer_valve_del_<?= $transfer_valve->id ?>" class="del-transfer-valve" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                    <div id="transfer_valve_edit_<?= $transfer_valve->id ?>" class="edit-transfer-valve glyphicon-pencil" title="<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>"><i class="fa-solid fa-pen"></i></div>
                    <!-- <div class="connect-transfer-valve" title="<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>"><i class="fa-solid fa-share"></i></div> -->
                </div>
            </div>
        <?php endforeach; ?>


                 <!-- отображение неразвитых событий -->
        <?php foreach ($hidden_event_model as $hidden_event): ?>
            <div id="hidden_event_<?= $hidden_event->id ?>" class="div-hidden-event" title="<?= $hidden_event->description ?>">
                <div class="content-hidden-event">
                    <div id="hidden_event_name_<?= $hidden_event->id ?>" class="div-hidden-event-name"><?= $hidden_event->name ?></div>
                    <!-- <div class="connect-hidden-event" title="<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>"><i class="fa-solid fa-share"></i></div> -->
                    <div id="hidden_event_del_<?= $hidden_event->id ?>" class="del-hidden-event glyphicon-trash" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                    <div id="hidden_event_edit_<?= $hidden_event->id ?>" class="edit-hidden-event glyphicon-pencil" title="<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>"><i class="fa-solid fa-pen"></i></div>
                    <!-- <div id="hidden_event_property_<?= $hidden_event->id ?>" class="add-hidden-event-property glyphicon-plus" title="<?php echo Yii::t('app', 'BUTTON_ADD'); ?>"><i class="fa-solid fa-plus"></i></div> -->
                    <div id="hidden_event_copy_<?= $hidden_event->id ?>" class="copy-hidden-event glyphicon-plus-sign" title="<?php echo Yii::t('app', 'BUTTON_COPY'); ?>"><i class="fa-solid fa-circle-plus"></i></div>
                </div>
                
            </div>
        <?php endforeach; ?>


                 <!-- отображение неразвитых событий -->
        <?php foreach ($conditional_event_model as $conditional_event): ?>
            <div id="conditional_event_<?= $conditional_event->id ?>" class="div-conditional-event" title="<?= $conditional_event->description ?>">
                <div class="content-conditional-event">
                    <div id="conditional_event_name_<?= $conditional_event->id ?>" class="div-conditional-event-name"><?= $conditional_event->name ?></div>
                    <!-- <div class="connect-conditional-event" title="<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>"><i class="fa-solid fa-share"></i></div> -->
                    <div id="conditional_event_del_<?= $conditional_event->id ?>" class="del-conditional-event glyphicon-trash" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                    <div id="conditional_event_edit_<?= $conditional_event->id ?>" class="edit-conditional-event glyphicon-pencil" title="<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>"><i class="fa-solid fa-pen"></i></div>
                    <!-- <div id="conditional_event_property_<?= $conditional_event->id ?>" class="add-conditional-event-property glyphicon-plus" title="<?php echo Yii::t('app', 'BUTTON_ADD'); ?>"><i class="fa-solid fa-plus"></i></div> -->
                    <div id="conditional_event_copy_<?= $conditional_event->id ?>" class="copy-conditional-event glyphicon-plus-sign" title="<?php echo Yii::t('app', 'BUTTON_COPY'); ?>"><i class="fa-solid fa-circle-plus"></i></div>
                </div>
                
            </div>
        <?php endforeach; ?>

    </div>

</div>
