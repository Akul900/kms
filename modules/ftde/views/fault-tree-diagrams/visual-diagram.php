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

        ['label' => Yii::t('app', 'NAV_BASIC_EVENT'), 'url' => '#',
        'linkOptions' => ['data-bs-toggle'=>'modal', 'data-bs-target'=>'#addBasicEventModalForm']],
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
    array_push($states_mas, [$s->id, $s->indent_x, $s->indent_y, $s->name, $s->description]);
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

// создаем массив из states_connection_start для передачи в js
$states_connection_start_mas = array();
foreach ($states_connection_start_model_all as $s){
    array_push($states_connection_start_mas, [$s->id, $s->element_from, $s->element_to]);
}

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

$basic_event_connection_mas = array();
foreach ($connection_basic_event_model_all as $s){
    array_push($basic_event_mas, [$s->id, $s->element_from, $s->element_to]);
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

<?= $this->render('_modal_form_view_message', [
]) ?>

<?= $this->render('_modal_form_delete_connection', [
]) ?>

<?= $this->render('_modal_form_start_to_end_editor', [
]) ?>


<!-- Подключение скрипта для модальных форм -->
<?php
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
    var states_connection_start_mas = <?php echo json_encode($states_connection_start_mas); ?>;//прием массива соединений с началом из php
    var states_connection_end_mas = <?php echo json_encode($states_connection_end_mas); ?>;//прием массива соединений завершений из php
    var states_connection_fault_mas = <?php echo json_encode($states_connection_fault_mas); ?>;//прием массива соединений завершений из php
    var basic_event_connection_mas = <?php echo json_encode($basic_event_connection_mas); ?>;//прием массива соединений завершений из php
    var basic_event_mas = <?php echo json_encode($basic_event_mas); ?>;//прием массивабазовых событий из php
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
    $.each(states_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {indent_x = elem;}
            if (j == 2) {indent_y = elem;}
            if (j == 3) {name = elem;}
            if (j == 4) {description = elem;}
            mas_data_state[q] = {
                "id":id,
                "indent_x":indent_x,
                "indent_y":indent_y,
                "name":name,
                "description":description,
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


    var mas_data_state_connection_start = {};
    var q = 0;
    var id = "";
    var element_from = "";
    var element_to = "";
    $.each(states_connection_start_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {element_from = elem;}
            if (j == 2) {element_to = elem;}
            mas_data_state_connection_start[q] = {
                "id":id,
                "element_from":element_from,
                "element_to":element_to,
            }
        });
        q = q+1;
    });


    var mas_data_basic_event_connection = {};
    var q = 0;
    var id = "";
    var element_from = "";
    var element_to = "";
    $.each(basic_event_connection_mas, function (i, mas) {
        $.each(mas, function (j, elem) {
            if (j == 0) {id = elem;}//записываем id
            if (j == 1) {element_from = elem;}
            if (j == 2) {element_to = elem;}
            mas_data_basic_event_connection[q] = {
                "id":id,
                "element_from":element_from,
                "element_to":element_to,
            }
        });
        q = q+1;
    });

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


    $(document).ready(function() {
        if (!guest){
            var nav_add_start = document.getElementById('nav_add_start');
            var nav_add_end = document.getElementById('nav_add_end');

            // Включение кнопок добавления начала и завершения если их нет
            if ('<?php echo $start_count; ?>' == 0){
                nav_add_start.className = 'dropdown-item';
            }
            if ('<?php echo $end_count; ?>' == 0){
                nav_add_end.className = 'dropdown-item';
            }

            // Обработка закрытия модального окна добавления нового состояния
            $("#addFaultModalForm").on("hidden.bs.modal", function() {
                // Скрытие списка ошибок ввода в модальном окне
                $("#add-state-form .error-summary").hide();
                var elem = document.getElementById("add-state-form").elements;
                $.each(elem, function (i, e) {
                    e.classList.remove("is-invalid");
                    e.classList.remove("is-valid");
                    e.removeAttribute("aria-invalid");
                });
                $("#add-state-form .invalid-feedback").each(function() {
                    $(this).text("");
                });
            });

            // Обработка закрытия модального окна добавления нового состояния
            $("#addBasicEventModalForm").on("hidden.bs.modal", function() {
                // Скрытие списка ошибок ввода в модальном окне
                $("#add-basic-event-form .error-summary").hide();
                var elem = document.getElementById("add-basic-event-form").elements;
                $.each(elem, function (i, e) {
                    e.classList.remove("is-invalid");
                    e.classList.remove("is-valid");
                    e.removeAttribute("aria-invalid");
                });
                $("#add-basic-event-form .invalid-feedback").each(function() {
                    $(this).text("");
                });
            });

            // Обработка закрытия модального окна добавления нового свойства состояния
            $("#addStatePropertyModalForm").on("hidden.bs.modal", function() {
                // Скрытие списка ошибок ввода в модальном окне
                $("#add-state-property-form .error-summary").hide();
                var elem = document.getElementById("add-state-property-form").elements;
                $.each(elem, function (i, e) {
                    e.classList.remove("is-invalid");
                    e.classList.remove("is-valid");
                    e.removeAttribute("aria-invalid");
                });
                $("#add-state-property-form .invalid-feedback").each(function() {
                    $(this).text("");
                });
            });

            // Обработка закрытия модального окна добавления нового перехода
            $("#addTransitionModalForm").on("hidden.bs.modal", function() {
                //если это не добавление новой связи
                if(added_transition != true){
                    removed_transition = true;
                    //то удаляем связь
                    instance.deleteConnection(current_connection);
                }
                added_transition = false;

                // Скрытие списка ошибок ввода в модальном окне
                $("#add-transition-form .error-summary").hide();
                var elem = document.getElementById("add-transition-form").elements;
                $.each(elem, function (i, e) {
                    e.classList.remove("is-invalid");
                    e.classList.remove("is-valid");
                    e.removeAttribute("aria-invalid");
                });
                $("#add-transition-form .invalid-feedback").each(function() {
                    $(this).text("");
                });
            });

            // Обработка закрытия модального окна добавления нового условия
            $("#addTransitionPropertyModalForm").on("hidden.bs.modal", function() {
                // Скрытие списка ошибок ввода в модальном окне
                $("#add-transition-property-form .error-summary").hide();
                var elem = document.getElementById("add-state-form").elements;
                $.each(elem, function (i, e) {
                    e.classList.remove("is-invalid");
                    e.classList.remove("is-valid");
                    e.removeAttribute("aria-invalid");
                });
                $("#add-transition-property-form .invalid-feedback").each(function() {
                    $(this).text("");
                });
            });
        }
    });


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


        //Распределение state (состояний) на диаграмме
        $.each(mas_data_state, function (j, elem) {
            $(".div-state").each(function(i) {
                var state = $(this).attr('id');
                var state_id = parseInt(state.match(/\d+/));

                if (elem.id == state_id) {
                    $(this).css({
                        left: parseInt(elem.indent_x),
                        top: parseInt(elem.indent_y)
                    });
                }
            });
        });

        //Распределение basic_event (базовых состояний) на диаграмме
        $.each(mas_data_basic_event, function (j, elem) {
            $(".div-basic-event").each(function(i) {
                var basic_event = $(this).attr('id');
                var basic_event_id = parseInt(basic_event.match(/\d+/));

                if (elem.id == basic_event_id) {
                    $(this).css({
                        left: parseInt(elem.indent_x),
                        top: parseInt(elem.indent_y)
                    });
                }
            });
        });

        //Распределение start (начала) на диаграмме
        $.each(mas_data_start, function (j, elem) {
            $(".div-and").each(function(i) {
                var start = $(this).attr('id');
                var start_id = parseInt(start.match(/\d+/));

                if (elem.id == start_id) {
                    $(this).css({
                        left: parseInt(elem.indent_x),
                        top: parseInt(elem.indent_y)
                    });
                }
            });
        });


        //Распределение end (завершения) на диаграмме
        $.each(mas_data_end, function (j, elem) {
            $(".div-or").each(function(i) {
                var end = $(this).attr('id');
                var end_id = parseInt(end.match(/\d+/));

                if (elem.id == end_id) {
                    $(this).css({
                        left: parseInt(elem.indent_x),
                        top: parseInt(elem.indent_y)
                    });
                }
            });
        });
        mousemoveState();


        var div_visual_diagram_field = document.getElementById('visual_diagram_field');
        //создаем группу с определенным именем group
        instance.addGroup({
            el: div_visual_diagram_field,
            id: 'group_field',
            draggable: false, //перетаскивание группы
            dropOverride:true,
        });


        //находим все элементы с классом div-state и делаем их двигаемыми
        $(".div-state").each(function(i) {
            var id_state = $(this).attr('id');
            var state = document.getElementById(id_state);
            //делаем state перетаскиваемыми
            instance.draggable(state);
            //добавляем элемент state в группу с именем group_field
            instance.addToGroup('group_field', state);
        });

                //находим все элементы с классом div-state и делаем их двигаемыми
        $(".div-basic-event").each(function(i) {
            var id_state = $(this).attr('id');
            var state = document.getElementById(id_state);
            //делаем state перетаскиваемыми
            instance.draggable(state);
            //добавляем элемент state в группу с именем group_field
            instance.addToGroup('group_field', state);
        });


        //находим все элементы с классом div-and и делаем их двигаемыми
        $(".div-and").each(function(i) {
            var id_start = $(this).attr('id');
            var start = document.getElementById(id_start);
            //делаем start перетаскиваемыми
            instance.draggable(start);
            //добавляем элемент start в группу с именем group_field
            instance.addToGroup('group_field', start);
        });


        //находим все элементы с классом div-or и делаем их двигаемыми
        $(".div-or").each(function(i) {
            var id_end = $(this).attr('id');
            var end = document.getElementById(id_end);
            //делаем end перетаскиваемыми
            instance.draggable(end);
            //добавляем элемент end в группу с именем group_field
            instance.addToGroup('group_field', end);
        });


        //находим все элементы с классом div-transition и делаем их двигаемыми
        //$(".div-transition").each(function(i) {
        //    var id_state = $(this).attr('id');
        //    var state = document.getElementById(id_state);
        //    //делаем state перетаскиваемыми
        //    instance.draggable(state);
        //    //добавляем элемент state в группу с именем group_field
        //    instance.addToGroup('group_field', state);
        //});


        var windows = jsPlumb.getSelector(".div-state");
        var windows_start = jsPlumb.getSelector(".div-and");
        var windows_end = jsPlumb.getSelector(".div-or");
        var windows_basic_event = jsPlumb.getSelector(".div-basic-event");

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

            // Запрет на соединение начала и завершения
            if ((name_source == 'and') && (name_target == 'or')){
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

            for (var i = 0; i < windows.length; i++) {

                instance.makeSource(windows[i], {
                    filter: ".fa-share",
                    anchor: "Bottom", //непрерывный анкер
                });

                instance.makeTarget(windows[i], {
                    dropOptions: { hoverClass: "dragHover" },
                    anchor: "Top", //непрерывный анкер
                    allowLoopback: true, // Разрешение создавать кольцевую связь
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


            for (var i = 0; i < windows_basic_event.length; i++) {

                instance.makeSource(windows_basic_event[i], {
                    filter: ".fa-share",
                    anchor: "Bottom", //непрерывный анкер
                });

                instance.makeTarget(windows_basic_event[i], {
                    dropOptions: { hoverClass: "dragHover" },
                    anchor: "Top", //непрерывный анкер
                    allowLoopback: true, // Разрешение создавать кольцевую связь
                });
            }

                 //построение связей из mas_data_state_connection_fault
                $.each(mas_data_state_connection_fault, function (j, elem) {
                var c = instance.connect({
                    source: "basic_event_" + elem.element_from,
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

            $.each(mas_data_state_connection_fault, function (j, elem) {
                var c = instance.connect({
                    source: "state_" + elem.element_from,
                    target: "basic_event_" + elem.element_to,
                    overlays: [
                        ['Label', {
                            label: message_label,
                            location: 0.5, //расположение посередине
                            cssClass: "connections-style",
                        }]
                    ],
                });
            });




   

            


            // //построение связей из mas_data_transition
            // $.each(mas_data_transition, function (j, elem) {
            //     var c = instance.connect({
            //         source: "state_" + elem.state_from,
            //         target: "state_" + elem.state_to,
            //         overlays: [
            //             ['Label', {
            //                 label: elem.name,
            //                 location: 0.5, //расположение посередине
            //                 cssClass: "transitions-style",
            //                 id:"label_id_"+ elem.id
            //             }]
            //         ],
            //     });
            //     //создаем параметр для связи id_transition куда прописываем название связи "transition_connect_" +  data['id'] (как замена id)
            //     c.setParameter('id_transition',"transition_connect_" +  elem.id);
            // });


            //стиль точки выхода линии "начала"
            for (var i = 0; i < windows_start.length; i++) {
                instance.makeSource(windows_start[i], {
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

                instance.makeTarget(windows_start[i], {
                    filter: ".fa-share",
                    dropOptions: { hoverClass: "dragHover" },
                    anchor: [ 0.5, 0, 0, -1, 0, 20 ], //непрерывный анкер
                    maxConnections: 1,
                    allowLoopback: false,
                    onMaxConnections: function (info, e) {
                        //отображение сообщения об ограничении
                        var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
                        document.getElementById("message-text").lastChild.nodeValue = message;
                        $("#viewMessageErrorLinkingItemsModalForm").modal("show");
                    }
                });
            }

            //построение связей из mas_data_state_connection_start
            $.each(mas_data_state_connection_fault, function (j, elem) {
                var c = instance.connect({
                    source: "and_" + elem.element_from,
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



            // //стиль точки выхода линии "or"
            // for (var i = 0; i < windows_end.length; i++) {
            //     instance.makeSource(windows_end[i], {
            //         filter: ".fa-share",
            //         anchor: "Bottom", //непрерывный анкер
            //         maxConnections: -1, //ограничение на одно соединение из элемента "начала"
            //         // onMaxConnections: function (info, e) {
            //         //     //отображение сообщения об ограничении
            //         //     var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
            //         //     document.getElementById("message-text").lastChild.nodeValue = message;
            //         //     $("#viewMessageErrorLinkingItemsModalForm").modal("show");
            //         // }
            //     });
            // }






            //стиль точки входа линии завершения
            for (var i = 0; i < windows_end.length; i++) {
                instance.makeSource(windows_end[i], {
                    filter: ".fa-share",
                    anchor: [ 0.5, 1, 0, 1, 0, -20 ], //непрерывный анкер
                    maxConnections: -1, //ограничение на одно соединение из элемента "начала"
                });
                instance.makeTarget(windows_end[i], {
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
                
            }

            $.each(mas_data_state_connection_fault, function (j, elem) {
                var c = instance.connect({
                    source: "or_" + elem.element_from,
                    target: "basic_event_" + elem.element_to,
                    overlays: [
                        ['Label', {
                            label: message_label,
                            location: 0.5, //расположение посередине
                            cssClass: "connections-style",
                        }]
                    ],
                });
            });

            $.each(mas_data_state_connection_fault, function (j, elem) {
                var c = instance.connect({
                    source: "and_" + elem.element_from,
                    target: "basic_event_" + elem.element_to,
                    overlays: [
                        ['Label', {
                            label: message_label,
                            location: 0.5, //расположение посередине
                            cssClass: "connections-style",
                        }]
                    ],
                });
            });

            
            //построение связей из mas_data_state_connection_or
            $.each(mas_data_state_connection_fault, function (j, elem) {
                var c = instance.connect({
                    source: "or_" + elem.element_from,
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
            });
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
            // // console.log(element_from_source)
            // // console.log(element_to_source)
            // var name_source = source_id.split('_')[0];
            // var name_target = target_id.split('_')[0];
   


            // if ((name_source == 'state') && (name_target == 'state')){
            //     aaa = source_id
            //     bbb = target_id
            //     element_from = parseInt(source_id.match(/\d+/));
            //     element_to = parseInt(target_id.match(/\d+/));
            //     $("#deleteConnectionStartModalForm").modal("show");
            //     // var ind_x;
            //     // var ind_y;
            //     // //получение id_transition параметра для идентификации связи
            //     // var id_transition = parseInt(c.getParameters().id_transition.match(/\d+/));

            //     // //поиск перехода относящегося к выбранной связи
            //     // var transition = document.getElementById("transition_" + id_transition);
            //     // if (transition.style.visibility == 'hidden'){
            //     //     transition.style.visibility = 'visible';//делаем переход видимым
            //     // } else {
            //     //     transition.style.visibility='hidden';//скрываем переход
            //     // }

            //     // //поиск связанных элементов
            //     // var source = document.getElementById(c.sourceId);
            //     // var target = document.getElementById(c.targetId);

            //     // var x_source = source.offsetLeft;//нахождение отступа слева от первого элемента
            //     // var x_target = target.offsetLeft;//нахождение отступа слева от второго элемента
            //     // var distance_x = Math.abs(x_source - x_target); //расстояние между элементами
            //     // //нахождение отступа от крайнего слева элемента
            //     // if (x_source < x_target){
            //     //     ind_x = x_source;
            //     // } else {
            //     //     ind_x = x_target;
            //     // }

            //     // var y_source = source.offsetTop;//нахождение отступа сверху от первого элемента
            //     // var y_target = target.offsetTop;//нахождение отступа сверху от второго элемента
            //     // var distance_y = Math.abs(y_source - y_target); //расстояние между элементами
            //     // //нахождение отступа от крайнего слева элемента
            //     // if (y_source < y_target){
            //     //     ind_y = y_source;
            //     // } else {
            //     //     ind_y = y_target;
            //     // }

            //     // if ((distance_x == 0)&&(distance_y == 0)){
            //     //     transition.style.left = ind_x + 'px';
            //     //     transition.style.top = ind_y - 120 + 'px';
            //     // } else {
            //     //     //выравниваем переход по центру между связанными элементами
            //     //     transition.style.left = distance_x/2 + ind_x + 'px';
            //     //     transition.style.top = distance_y/2 + ind_y + 'px';
            //     // }
            // } else if ((name_source == 'and') && (name_target == 'state')){//связь из начала
            //     aaa = source_id
            //     bbb = target_id
            //     element_from = parseInt(source_id.match(/\d+/));
            //     element_to = parseInt(target_id.match(/\d+/));
            //     $("#deleteConnectionStartModalForm").modal("show");
                
            // }else if ((name_source == 'state') && (name_target == 'and')){//связь из начала
            //     aaa = source_id
            //     bbb = target_id
            //     element_from = parseInt(source_id.match(/\d+/));
            //     element_to = parseInt(target_id.match(/\d+/));
            //     $("#deleteConnectionStartModalForm").modal("show");
            // }else if ((name_source == 'or') && (name_target == 'state')){//связь из or
            //     aaa = source_id
            //     bbb = target_id
            //     element_from = parseInt(source_id.match(/\d+/));
            //     element_to = parseInt(target_id.match(/\d+/));
            //     $("#deleteConnectionStartModalForm").modal("show");
            // }else if ((name_source == 'state') && (name_target == 'or')){//связь в конец
            //     aaa = source_id
            //     bbb = target_id
            //     element_from = parseInt(source_id.match(/\d+/));
            //     element_to  = parseInt(target_id.match(/\d+/));
            //     $("#deleteConnectionStartModalForm").modal("show");
            // }
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
            var id_state = $(this).attr('id');
            var state = document.getElementById(id_state);

            var w = state.offsetLeft + state.clientWidth;
            var h = state.offsetTop + state.clientHeight;

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



    //перемещение элементов div-transition при перемещении элемента div-state
    // $(document).on('mousemove', '.div-state', function() {
    //     var state = $(this).attr('id');
    //     var state_id = parseInt(state.match(/\d+/));

    //     //поиск div-transition связанных с div-state по значению state_id
    //     $.each(mas_data_transition, function (i, elem) {
    //         if ((elem.state_from == state_id)||(elem.state_to == state_id)) {
    //             var ind_x;
    //             var ind_y;

    //             //поиск перехода относящегося к выбранной связи
    //             var transition = document.getElementById("transition_" + elem.id);

    //             //поиск связанных элементов
    //             var source = document.getElementById("state_" + elem.state_from);
    //             var target = document.getElementById("state_" + elem.state_to);

    //             var x_source = source.offsetLeft;//нахождение отступа слева от первого элемента
    //             var x_target = target.offsetLeft;//нахождение отступа слева от второго элемента
    //             var distance_x = Math.abs(x_source - x_target); //расстояние между элементами
    //             //нахождение отступа от крайнего слева элемента
    //             if (x_source < x_target){
    //                 ind_x = x_source;
    //             } else {
    //                 ind_x = x_target;
    //             }

    //             var y_source = source.offsetTop;//нахождение отступа сверху от первого элемента
    //             var y_target = target.offsetTop;//нахождение отступа сверху от второго элемента
    //             var distance_y = Math.abs(y_source - y_target); //расстояние между элементами
    //             //нахождение отступа от крайнего слева элемента
    //             if (y_source < y_target){
    //                 ind_y = y_source;
    //             } else {
    //                 ind_y = y_target;
    //             }

    //             if ((distance_x == 0)&&(distance_y == 0)){
    //                 transition.style.left = ind_x + 'px';
    //                 transition.style.top = ind_y - 120 + 'px';
    //             } else {
    //                 //выравниваем переход по центру между связанными элементами
    //                 transition.style.left = distance_x/2 + ind_x + 'px';
    //                 transition.style.top = distance_y/2 + ind_y + 'px';
    //             }
    //         }
    //     });
    // });


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
</script>




<div class="state-transition-diagram-visual-diagram">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<div id="visual_diagram" class="visual-diagram col-md-12">

    <div id="visual_diagram_field" class="visual-diagram-top-layer">

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
            <div id="state_<?= $state->id ?>" class="div-state" title="<?= $state->description ?>">
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
                    <div class="connect-basic-event" title="<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>"><i class="fa-solid fa-share"></i></div>
                    <div id="basic_event_del_<?= $basic_event->id ?>" class="del-basic-event glyphicon-trash" title="<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>"><i class="fa-solid fa-trash"></i></div>
                    <div id="basic_event_edit_<?= $basic_event->id ?>" class="edit-basic-event glyphicon-pencil" title="<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>"><i class="fa-solid fa-pen"></i></div>
                    <div id="basic_event_property_<?= $basic_event->id ?>" class="add-basic-event-property glyphicon-plus" title="<?php echo Yii::t('app', 'BUTTON_ADD'); ?>"><i class="fa-solid fa-plus"></i></div>
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

    </div>

</div>
