<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Button;
use app\modules\main\models\Lang;

/* @var $state_model app\modules\ftde\models\Transition */

?>


<!-- Модальное окно добавления нового состояния -->
<?php Modal::begin([
    'id' => 'addBasicEventModalForm',
    'title' => '<h3>' . Yii::t('app', 'FAULT_ADD_NEW_BASIC_EVENT') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#add-basic-event-button").click(function(e) {
            e.preventDefault();
            var form = $("#add-basic-event-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/fault-tree-diagrams/add-basic-event/' . $model->id ?>",
                type: "post",
                data: form.serialize(),
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#addBasicEventModalForm").modal("hide");

                        //создание div состояния
                        var div_visual_diagram_field = document.getElementById('visual_diagram_field');

                        var div_basic_event = document.createElement('div');
                        div_basic_event.id = 'basic_event_' + data['id'];
                        div_basic_event.className = 'div-basic-event';
                        div_basic_event.title = data['description'];
                        div_visual_diagram_field.append(div_basic_event);

                        var div_content_basic_event = document.createElement('div');
                        div_content_basic_event.className = 'content-basic-event';
                        div_basic_event.append(div_content_basic_event);

                        var div_basic_event_name = document.createElement('div');
                        div_basic_event_name.id = 'basic_event_name_' + data['id'];
                        div_basic_event_name.className = 'div-basic-event-name' ;
                        div_basic_event_name.innerHTML = data['name'];
                        div_content_basic_event.append(div_basic_event_name);

                        var div_connect = document.createElement('div');
                        div_connect.className = 'connect-basic-event' ;
                        div_connect.title = '<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>' ;
                        div_connect.innerHTML = '<i class="fa-solid fa-share"></i>';
                        div_content_basic_event.append(div_connect);

                        var div_del = document.createElement('div');
                        div_del.id = 'basic_event_del_' + data['id'];
                        div_del.className = 'del-basic-event glyphicon-trash' ;
                        div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                        div_del.innerHTML = '<i class="fa-solid fa-trash"></i>'
                        div_content_basic_event.append(div_del);

                        var div_edit = document.createElement('div');
                        div_edit.id = 'basic_event_edit_' + data['id'];
                        div_edit.className = 'edit-basic-event glyphicon-pencil' ;
                        div_edit.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_edit.innerHTML = '<i class="fa-solid fa-pen"></i>'
                        div_content_basic_event.append(div_edit);

                        var div_add_parameter = document.createElement('div');
                        div_add_parameter.id = 'basic_event_add_property_' + data['id'];
                        div_add_parameter.className = 'add-basic-event-property glyphicon-plus' ;
                        div_add_parameter.title = '<?php echo Yii::t('app', 'BUTTON_ADD'); ?>' ;
                        div_add_parameter.innerHTML = '<i class="fa-solid fa-plus"></i>'
                        div_content_basic_event.append(div_add_parameter);

                        var div_copy = document.createElement('div');
                        div_copy.id = 'basic_event_copy_' + data['id'];
                        div_copy.className = 'copy-basic-event glyphicon-plus-sign' ;
                        div_copy.title = '<?php echo Yii::t('app', 'BUTTON_COPY'); ?>' ;
                        div_copy.innerHTML = '<i class="fa-solid fa-circle-plus"></i>'
                        div_content_basic_event.append(div_copy);

                        //сделать div двигаемым
                        var div_basic_event = document.getElementById('basic_event_' + data['id']);
                        instance.draggable(div_basic_event);
                        //добавляем элемент div_basic_event в группу с именем group_field
                        instance.addToGroup('group_field', div_basic_event);

                        instance.makeSource(div_basic_event, {
                            filter: ".fa-share",
                            anchor: "Continuous", //непрерывный анкер
                        });

                        instance.makeTarget(div_basic_event, {
                            dropOptions: { hoverClass: "dragHover" },
                            anchor: "Continuous", //непрерывный анкер
                            allowLoopback: true, // Разрешение создавать кольцевую связь
                        });


                        //добавлены новые записи в массив состояний для изменений
                        var j = 0;
                        $.each(mas_data_basic_event, function (i, elem) {
                            j = j + 1;
                        });
                        mas_data_basic_event[j] = {id:data['id'], indent_x:parseInt(data['indent_x'], 10), indent_y:parseInt(data['indent_y'], 10), name:data['name'], description:data['description']};


                        document.getElementById('add-basic-event-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#add-basic-event-form", data);
                    }
                },
                error: function() {
                    alert('Error!');
                }
            });
        });
    });
</script>

<?php $form = ActiveForm::begin([
    'id' => 'add-basic-event-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>


<?= $form->errorSummary($state_model); ?>

<?= $form->field($state_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($state_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>


<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_ADD'),
    'options' => [
        'id' => 'add-basic-event-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>
<!--
<= Button::widget([
    'label' => Yii::t('app', 'BUTTON_CANCEL'),
    'options' => [
        'class' => 'btn btn-danger',
        'style' => 'margin:5px',
        'data-bs-dismiss'=>'modal'
    ]
]); ?>
-->

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно изменения состояния -->
<?php Modal::begin([
    'id' => 'editBasicEventModalForm',
    'title' => '<h3>' . Yii::t('app', 'BASIC_EVENT_EDIT_BASIC_EVENT') . '</h3>',
]); ?>

    <!-- Скрипт модального окна -->
    <script type="text/javascript">
        // Выполнение скрипта при загрузке страницы
        $(document).ready(function() {
            // Обработка нажатия кнопки сохранения
            $("#edit-basic-event-button").click(function(e) {
                e.preventDefault();
                var form = $("#edit-basic-event-form");
                // Ajax-запрос
                $.ajax({
                    //переход на экшен левел
                    url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                    '/fault-tree-diagrams/edit-basic-event'?>",
                    type: "post",
                    data: form.serialize() + "&basic_event_id_on_click=" + basic_event_id_on_click,
                    dataType: "json",
                    success: function(data) {
                        // Если валидация прошла успешно (нет ошибок ввода)
                        if (data['success']) {
                            // Скрывание модального окна
                            $("#editBasicEventModalForm").modal("hide");

                            //изменение div состояния
                            var div_basic_event_name = document.getElementById('basic_event_name_' + data['id']);
                            div_basic_event_name.innerHTML = data['name'];

                            var div_basic_event = document.getElementById('basic_event_' + data['id']);
                            div_basic_event.title = data['description'];


                            //изменена запись в массиве состояний
                            $.each(mas_data_basic_event, function (i, elem) {
                                if (elem.id == data['id']){
                                    mas_data_basic_event[i].name = data['name'];
                                    mas_data_basic_event[i].description = data['description'];
                                }
                            });

                            document.getElementById('edit-basic-event-form').reset();
                        } else {
                            // Отображение ошибок ввода
                            viewErrors("#edit-basic-event-form", data);
                        }
                    },
                    error: function() {
                        alert('Error!');
                    }
                });
            });
        });
    </script>

<?php $form = ActiveForm::begin([
    'id' => 'edit-basic-event-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>

<?= $form->errorSummary($state_model); ?>

<?= $form->field($state_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($state_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_SAVE'),
    'options' => [
        'id' => 'edit-basic-event-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<!-- Теперь не работает
<= Button::widget([
    'label' => Yii::t('app', 'BUTTON_CANCEL'),
    'options' => [
        'class' => 'btn btn-danger',
        'style' => 'margin:5px',
        'data-bs-dismiss'=>'modal'
    ]
]); ?>-->

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно удаления состояния -->
<?php Modal::begin([
    'id' => 'deleteBasicEventModalForm',
    'title' => '<h3>' . Yii::t('app', 'BASIC_EVENT_DELETE_BASIC_EVENT') . '</h3>',
]); ?>

    <!-- Скрипт модального окна -->
    <script type="text/javascript">
        // Выполнение скрипта при загрузке страницы
        $(document).ready(function() {
            // Обработка нажатия кнопки сохранения
            $("#delete-basic-event-button").click(function(e) {
                e.preventDefault();
                // Ajax-запрос
                $.ajax({
                    //переход на экшен левел
                    url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                    '/fault-tree-diagrams/delete-basic-event'?>",
                    type: "post",
                    data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&basic_event_id_on_click=" + basic_event_id_on_click,
                    dataType: "json",
                    success: function(data) {
                        // Если валидация прошла успешно (нет ошибок ввода)
                        if (data['success']) {
                            // Скрывание модального окна
                            $("#deleteBasicEventModalForm").modal("hide");

                            //удаление div состояния
                            var div_basic_event = document.getElementById('basic_event_' + basic_event_id_on_click);
                            instance.removeFromGroup(div_basic_event);//удаляем из группы
                            instance.remove(div_basic_event);// удаляем состояние


                            //удалена запись в массиве состояний
                            var temporary_mas_data_basic_event = {};
                            var q = 0;
                            $.each(mas_data_basic_event, function (i, elem) {
                                if (basic_event_id_on_click != elem.id){
                                    temporary_mas_data_basic_event[q] = {
                                        "id":elem.id,
                                        "indent_x":elem.indent_x,
                                        "indent_y":elem.indent_y,
                                        "name":elem.name,
                                        "description":elem.description,

                                    };
                                    q = q+1;
                                }
                            });
                            mas_data_basic_event = temporary_mas_data_basic_event;


                            // ------------------- возможно нужно удалять запись из массива связей mas_data_transition

                        }
                    },
                    error: function() {
                        alert('Error!');
                    }
                });
            });
        });
    </script>

<?php $form = ActiveForm::begin([
    'id' => 'delete-basic-event-form',
]); ?>

    <div class="modal-body">
        <p style="font-size: 14px">
            <?php echo Yii::t('app', 'DELETE_BASIC_EVENT_TEXT'); ?>
        </p>
    </div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-basic-event-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно копирования состояния -->
<?php Modal::begin([
    'id' => 'copyBasicEventModalForm',
    'title' => '<h3>' . Yii::t('app', 'basic_event_COPY_basic-event') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#copy-basic-event-button").click(function(e) {
            e.preventDefault();
            var form = $("#copy-basic-event-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/fault-tree-diagrams/copy-basic-event'?>",
                type: "post",
                data: form.serialize() + "&basic_event_id_on_click=" + basic_event_id_on_click,
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#copybasic-eventModalForm").modal("hide");

                        //создание div состояния
                        var div_visual_diagram_field = document.getElementById('visual_diagram_field');

                        var div_basic_event = document.createElement('div');
                        div_basic_event.id = 'basic_event_' + data['id'];
                        div_basic_event.className = 'div-basic-event';
                        div_basic_event.title = data['description'];
                        div_visual_diagram_field.append(div_basic_event);

                        var div_content_basic_event = document.createElement('div');
                        div_content_basic_event.className = 'content-basic-event';
                        div_basic_event.append(div_content_basic_event);

                        var div_basic_event_name = document.createElement('div');
                        div_basic_event_name.id = 'basic_event_name_' + data['id'];
                        div_basic_event_name.className = 'div-basic-event-name' ;
                        div_basic_event_name.innerHTML = data['name'];
                        div_content_basic_event.append(div_basic_event_name);

                        var div_connect = document.createElement('div');
                        div_connect.className = 'connect-basic-event' ;
                        div_connect.title = '<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>' ;
                        div_connect.innerHTML = '<i class="fa-solid fa-share"></i>';
                        div_content_basic_event.append(div_connect);

                        var div_del = document.createElement('div');
                        div_del.id = 'basic_event_del_' + data['id'];
                        div_del.className = 'del-basic-event glyphicon-trash' ;
                        div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                        div_del.innerHTML = '<i class="fa-solid fa-trash"></i>'
                        div_content_basic_event.append(div_del);

                        var div_edit = document.createElement('div');
                        div_edit.id = 'basic_event_edit_' + data['id'];
                        div_edit.className = 'edit-basic-event glyphicon-pencil' ;
                        div_edit.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_edit.innerHTML = '<i class="fa-solid fa-pen"></i>'
                        div_content_basic_event.append(div_edit);

                        var div_add_parameter = document.createElement('div');
                        div_add_parameter.id = 'basic_event_add_property_' + data['id'];
                        div_add_parameter.className = 'add-basic-event-property glyphicon-plus' ;
                        div_add_parameter.title = '<?php echo Yii::t('app', 'BUTTON_ADD'); ?>' ;
                        div_add_parameter.innerHTML = '<i class="fa-solid fa-plus"></i>'
                        div_content_basic_event.append(div_add_parameter);

                        var div_copy = document.createElement('div');
                        div_copy.id = 'basic_event_copy_' + data['id'];
                        div_copy.className = 'copy-basic-event glyphicon-plus-sign' ;
                        div_copy.title = '<?php echo Yii::t('app', 'BUTTON_COPY'); ?>' ;
                        div_copy.innerHTML = '<i class="fa-solid fa-circle-plus"></i>'
                        div_content_basic_event.append(div_copy);

                        //сделать div двигаемым
                        var div_basic_event = document.getElementById('basic_event_' + data['id']);
                        instance.draggable(div_basic_event);
                        //добавляем элемент div_basic_event в группу с именем group_field
                        instance.addToGroup('group_field', div_basic_event);

                        instance.makeSource(div_basic_event, {
                            filter: ".fa-share",
                            anchor: "Continuous", //непрерывный анкер
                        });

                        instance.makeTarget(div_basic_event, {
                            dropOptions: { hoverClass: "dragHover" },
                            anchor: "Continuous", //непрерывный анкер
                            allowLoopback: true, // Разрешение создавать кольцевую связь
                        });


                        //добавлены новые записи в массив состояний для изменений
                        var j = 0;
                        $.each(mas_data_basic_event, function (i, elem) {
                            j = j + 1;
                        });
                        mas_data_basic_event[j] = {id:data['id'], indent_x:parseInt(data['indent_x'], 10), indent_y:parseInt(data['indent_y'], 10), name:data['name'], description:data['description']};


                        //добавляем свойства состояний для копируемого состояния
                        for (var i = 0; i < data['i']; i++) {
                            //добавляем div разделительной линии для первого свойства состояния
                            if (i == 0){
                                var div_line = document.createElement('div');
                                div_line.id = 'basic_event_line_' + data['id'];
                                div_line.className = 'div-line';
                                div_basic_event.append(div_line);
                            }

                            var div_property = document.createElement('div');
                            div_property.id = 'basic_event_property_' + data['basic_event_property_id_'+i];
                            div_property.className = 'div-basic-event-property';
                            div_property.innerHTML = data['basic_event_property_name_'+i] + " " + data['basic_event_property_operator_name_'+i] + " " + data['basic_event_property_value_'+i];
                            div_basic_event.append(div_property);

                            var div_button_property = document.createElement('div');
                            div_button_property.className = 'button-basic-event-property';
                            div_property.prepend(div_button_property);

                            var div_edit_property = document.createElement('div');
                            div_edit_property.id = 'basic_event_property_edit_' + data['basic_event_property_id_'+i];
                            div_edit_property.className = 'edit-basic-event-property glyphicon-pencil';
                            div_edit_property.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                            div_edit_property.innerHTML = '<i class="fa-solid fa-pen"></i>';
                            div_button_property.append(div_edit_property);

                            var div_del_property = document.createElement('div');
                            div_del_property.id = 'basic_event_property_del_' + data['basic_event_property_id_'+i];
                            div_del_property.className = 'del-basic-event-property glyphicon-trash';
                            div_del_property.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                            div_del_property.innerHTML = '<i class="fa-solid fa-trash"></i>';
                            div_button_property.append(div_del_property);


                            //добавлены новые записи в массив свойств состояний для изменений
                            var id = data['basic_event_property_id_'+i];
                            var name = data['basic_event_property_name_'+i];
                            var description = data['basic_event_property_description_'+i];
                            var operator = parseInt(data['basic_event_property_operator_'+i], 10);
                            var value = data['basic_event_property_value_'+i];
                            var basic_event = data['basic_event_property_basic_event_'+i];

                            var j = 0;
                            $.each(mas_data_basic_event_property, function (i, elem) {
                                j = j + 1;
                            });
                            mas_data_basic_event_property[j] = {id:id, name:name, description:description, operator:operator, value:value, basic-event:basic-event};
                        }

                        //разместить новый basic-event по новым координатам
                        div_basic_event.style.left = parseInt(data['indent_x'], 10) + 'px';
                        div_basic_event.style.top = parseInt(data['indent_y'], 10) + 'px';

                        //обновление поля visual_diagram_field для размещения элементов
                        mousemovebasic-event();
                        // Обновление формы редактора
                        instance.repaintEverything();

                        document.getElementById('copy-basic-event-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#copy-basic-event-form", data);
                    }
                },
                error: function() {
                    alert('Error!');
                }
            });
        });
    });
</script>

<?php $form = ActiveForm::begin([
    'id' => 'copy-basic-event-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>

<?= $form->errorSummary($state_model); ?>

<?= $form->field($state_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($state_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_SAVE'),
    'options' => [
        'id' => 'copy-basic-event-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>
