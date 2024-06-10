<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Button;
use app\modules\main\models\Lang;

/* @var $state_model app\modules\ftde\models\Transition */

?>


<!-- Модальное окно добавления нового состояния -->
<?php Modal::begin([
    'id' => 'addUndevelopedEventModalForm',
    'title' => '<h3>' . Yii::t('app', 'FAULT_ADD_NEW_UNDEVELOPED_EVENT') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#add-undeveloped-event-button").click(function(e) {
            e.preventDefault();
            var form = $("#add-undeveloped-event-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/fault-tree-diagrams/add-undeveloped-event/' . $model->id ?>",
                type: "post",
                data: form.serialize(),
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#addUndevelopedEventModalForm").modal("hide");

                        //создание div состояния
                        var div_visual_diagram_field = document.getElementById('visual_diagram_field');

                        var div_undeveloped_event = document.createElement('div');
                        div_undeveloped_event.id = 'undeveloped_event_' + data['id'];
                        div_undeveloped_event.className = 'div-undeveloped-event';
                        div_undeveloped_event.title = data['description'];
                        div_visual_diagram_field.append(div_undeveloped_event);

                        var div_content_undeveloped_event = document.createElement('div');
                        div_content_undeveloped_event.className = 'content-undeveloped-event';
                        div_undeveloped_event.append(div_content_undeveloped_event);

                        var div_undeveloped_event_name = document.createElement('div');
                        div_undeveloped_event_name.id = 'undeveloped_event_name_' + data['id'];
                        div_undeveloped_event_name.className = 'div-undeveloped-event-name' ;
                        div_undeveloped_event_name.innerHTML = data['name'];
                        div_content_undeveloped_event.append(div_undeveloped_event_name);

                        var div_connect = document.createElement('div');
                        div_connect.className = 'connect-undeveloped-event' ;
                        div_connect.title = '<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>' ;
                        div_connect.innerHTML = '<i class="fa-solid fa-share"></i>';
                        div_content_undeveloped_event.append(div_connect);

                        var div_del = document.createElement('div');
                        div_del.id = 'undeveloped_event_del_' + data['id'];
                        div_del.className = 'del-undeveloped-event glyphicon-trash' ;
                        div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                        div_del.innerHTML = '<i class="fa-solid fa-trash"></i>'
                        div_content_undeveloped_event.append(div_del);

                        var div_edit = document.createElement('div');
                        div_edit.id = 'undeveloped_event_edit_' + data['id'];
                        div_edit.className = 'edit-undeveloped-event glyphicon-pencil' ;
                        div_edit.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_edit.innerHTML = '<i class="fa-solid fa-pen"></i>'
                        div_content_undeveloped_event.append(div_edit);

                        // var div_add_parameter = document.createElement('div');
                        // div_add_parameter.id = 'undeveloped_event_add_property_' + data['id'];
                        // div_add_parameter.className = 'add-undeveloped-event-property glyphicon-plus' ;
                        // div_add_parameter.title = '<?php echo Yii::t('app', 'BUTTON_ADD'); ?>' ;
                        // div_add_parameter.innerHTML = '<i class="fa-solid fa-plus"></i>'
                        // div_content_undeveloped_event.append(div_add_parameter);

                        var div_copy = document.createElement('div');
                        div_copy.id = 'undeveloped_event_copy_' + data['id'];
                        div_copy.className = 'copy-undeveloped-event glyphicon-plus-sign' ;
                        div_copy.title = '<?php echo Yii::t('app', 'BUTTON_COPY'); ?>' ;
                        div_copy.innerHTML = '<i class="fa-solid fa-circle-plus"></i>'
                        div_content_undeveloped_event.append(div_copy);

                        //сделать div двигаемым
                        var div_undeveloped_event = document.getElementById('undeveloped_event_' + data['id']);
                        instance.draggable(div_undeveloped_event);
                        //добавляем элемент div_undeveloped_event в группу с именем group_field
                        instance.addToGroup('group_field', div_undeveloped_event);

                        instance.makeSource(div_undeveloped_event, {
                            filter: ".fa-share",
                            anchor: "Bottom", //непрерывный анкер
                            maxConnections: 1,
                            onMaxConnections: function (info, e) {
                                //отображение сообщения об ограничении
                                var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
                                document.getElementById("message-text").lastChild.nodeValue = message;
                                $("#viewMessageErrorLinkingItemsModalForm").modal("show");
                            }
                        });

                        instance.makeTarget(div_undeveloped_event, {
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


                        //добавлены новые записи в массив состояний для изменений
                        var j = 0;
                        $.each(mas_data_undeveloped_event, function (i, elem) {
                            j = j + 1;
                        });
                        mas_data_undeveloped_event[j] = {id:data['id'], indent_x:parseInt(data['indent_x'], 10), indent_y:parseInt(data['indent_y'], 10), name:data['name'], description:data['description']};


                        document.getElementById('add-undeveloped-event-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#add-undeveloped-event-form", data);
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
    'id' => 'add-undeveloped-event-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>


<?= $form->errorSummary($state_model); ?>

<?= $form->field($state_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($state_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>


<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_ADD'),
    'options' => [
        'id' => 'add-undeveloped-event-button',
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
    'id' => 'editUndevelopedEventModalForm',
    'title' => '<h3>' . Yii::t('app', 'UNDEVELOPED_EVENT_EDIT_UNDEVELOPED_EVENT') . '</h3>',
]); ?>

    <!-- Скрипт модального окна -->
    <script type="text/javascript">
        // Выполнение скрипта при загрузке страницы
        $(document).ready(function() {
            // Обработка нажатия кнопки сохранения
            $("#edit-undeveloped-event-button").click(function(e) {
                e.preventDefault();
                var form = $("#edit-undeveloped-event-form");
                // Ajax-запрос
                $.ajax({
                    //переход на экшен левел
                    url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                    '/fault-tree-diagrams/edit-undeveloped-event'?>",
                    type: "post",
                    data: form.serialize() + "&undeveloped_event_id_on_click=" + undeveloped_event_id_on_click,
                    dataType: "json",
                    success: function(data) {
                        // Если валидация прошла успешно (нет ошибок ввода)
                        if (data['success']) {
                            // Скрывание модального окна
                            $("#editUndevelopedEventModalForm").modal("hide");

                            //изменение div состояния
                            var div_undeveloped_event_name = document.getElementById('undeveloped_event_name_' + data['id']);
                            div_undeveloped_event_name.innerHTML = data['name'];

                            var div_undeveloped_event = document.getElementById('undeveloped_event_' + data['id']);
                            div_undeveloped_event.title = data['description'];


                            //изменена запись в массиве состояний
                            $.each(mas_data_undeveloped_event, function (i, elem) {
                                if (elem.id == data['id']){
                                    mas_data_undeveloped_event[i].name = data['name'];
                                    mas_data_undeveloped_event[i].description = data['description'];
                                }
                            });

                            document.getElementById('edit-undeveloped-event-form').reset();
                        } else {
                            // Отображение ошибок ввода
                            viewErrors("#edit-undeveloped-event-form", data);
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
    'id' => 'edit-undeveloped-event-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>

<?= $form->errorSummary($state_model); ?>

<?= $form->field($state_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($state_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_SAVE'),
    'options' => [
        'id' => 'edit-undeveloped-event-button',
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
    'id' => 'deleteUndevelopedEventModalForm',
    'title' => '<h3>' . Yii::t('app', 'UNDEVELOPED_EVENT_DELETE_UNDEVELOPED_EVENT') . '</h3>',
]); ?>

    <!-- Скрипт модального окна -->
    <script type="text/javascript">
        // Выполнение скрипта при загрузке страницы
        $(document).ready(function() {
            // Обработка нажатия кнопки сохранения
            $("#delete-undeveloped-event-button").click(function(e) {
                e.preventDefault();
                // Ajax-запрос
                $.ajax({
                    //переход на экшен левел
                    url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                    '/fault-tree-diagrams/delete-undeveloped-event'?>",
                    type: "post",
                    data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&undeveloped_event_id_on_click=" + undeveloped_event_id_on_click,
                    dataType: "json",
                    success: function(data) {
                        // Если валидация прошла успешно (нет ошибок ввода)
                        if (data['success']) {
                            // Скрывание модального окна
                            $("#deleteUndevelopedEventModalForm").modal("hide");

                            //удаление div состояния
                            var div_undeveloped_event = document.getElementById('undeveloped_event_' + undeveloped_event_id_on_click);
                            instance.removeFromGroup(div_undeveloped_event);//удаляем из группы
                            instance.remove(div_undeveloped_event);// удаляем состояние


                            //удалена запись в массиве состояний
                            var temporary_mas_data_undeveloped_event = {};
                            var q = 0;
                            $.each(mas_data_undeveloped_event, function (i, elem) {
                                if (undeveloped_event_id_on_click != elem.id){
                                    temporary_mas_data_undeveloped_event[q] = {
                                        "id":elem.id,
                                        "indent_x":elem.indent_x,
                                        "indent_y":elem.indent_y,
                                        "name":elem.name,
                                        "description":elem.description,

                                    };
                                    q = q+1;
                                }
                            });
                            mas_data_undeveloped_event = temporary_mas_data_undeveloped_event;


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
    'id' => 'delete-undeveloped-event-form',
]); ?>

    <div class="modal-body">
        <p style="font-size: 14px">
            <?php echo Yii::t('app', 'DELETE_UNDEVELOPED_EVENT_TEXT'); ?>
        </p>
    </div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-undeveloped-event-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно копирования состояния -->
<?php Modal::begin([
    'id' => 'copyUndevelopedEventModalForm',
    'title' => '<h3>' . Yii::t('app', 'UNDEVELOPED_EVENT_COPY_UNDEVELOPED_EVENT') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#copy-undeveloped-event-button").click(function(e) {
            e.preventDefault();
            var form = $("#copy-undeveloped-event-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/fault-tree-diagrams/copy-undeveloped-event'?>",
                type: "post",
                data: form.serialize() + "&undeveloped_event_id_on_click=" + undeveloped_event_id_on_click,
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#copyundeveloped-eventModalForm").modal("hide");

                        //создание div состояния
                        var div_visual_diagram_field = document.getElementById('visual_diagram_field');

                        var div_undeveloped_event = document.createElement('div');
                        div_undeveloped_event.id = 'undeveloped_event_' + data['id'];
                        div_undeveloped_event.className = 'div-undeveloped-event';
                        div_undeveloped_event.title = data['description'];
                        div_visual_diagram_field.append(div_undeveloped_event);

                        var div_content_undeveloped_event = document.createElement('div');
                        div_content_undeveloped_event.className = 'content-undeveloped-event';
                        div_undeveloped_event.append(div_content_undeveloped_event);

                        var div_undeveloped_event_name = document.createElement('div');
                        div_undeveloped_event_name.id = 'undeveloped_event_name_' + data['id'];
                        div_undeveloped_event_name.className = 'div-undeveloped-event-name' ;
                        div_undeveloped_event_name.innerHTML = data['name'];
                        div_content_undeveloped_event.append(div_undeveloped_event_name);

                        var div_connect = document.createElement('div');
                        div_connect.className = 'connect-undeveloped-event' ;
                        div_connect.title = '<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>' ;
                        div_connect.innerHTML = '<i class="fa-solid fa-share"></i>';
                        div_content_undeveloped_event.append(div_connect);

                        var div_del = document.createElement('div');
                        div_del.id = 'undeveloped_event_del_' + data['id'];
                        div_del.className = 'del-undeveloped-event glyphicon-trash' ;
                        div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                        div_del.innerHTML = '<i class="fa-solid fa-trash"></i>'
                        div_content_undeveloped_event.append(div_del);

                        var div_edit = document.createElement('div');
                        div_edit.id = 'undeveloped_event_edit_' + data['id'];
                        div_edit.className = 'edit-undeveloped-event glyphicon-pencil' ;
                        div_edit.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_edit.innerHTML = '<i class="fa-solid fa-pen"></i>'
                        div_content_undeveloped_event.append(div_edit);

                        var div_add_parameter = document.createElement('div');
                        div_add_parameter.id = 'undeveloped_event_add_property_' + data['id'];
                        div_add_parameter.className = 'add-undeveloped-event-property glyphicon-plus' ;
                        div_add_parameter.title = '<?php echo Yii::t('app', 'BUTTON_ADD'); ?>' ;
                        div_add_parameter.innerHTML = '<i class="fa-solid fa-plus"></i>'
                        div_content_undeveloped_event.append(div_add_parameter);

                        var div_copy = document.createElement('div');
                        div_copy.id = 'undeveloped_event_copy_' + data['id'];
                        div_copy.className = 'copy-undeveloped-event glyphicon-plus-sign' ;
                        div_copy.title = '<?php echo Yii::t('app', 'BUTTON_COPY'); ?>' ;
                        div_copy.innerHTML = '<i class="fa-solid fa-circle-plus"></i>'
                        div_content_undeveloped_event.append(div_copy);

                        //сделать div двигаемым
                        var div_undeveloped_event = document.getElementById('undeveloped_event_' + data['id']);
                        instance.draggable(div_undeveloped_event);
                        //добавляем элемент div_undeveloped_event в группу с именем group_field
                        instance.addToGroup('group_field', div_undeveloped_event);

                        instance.makeSource(div_undeveloped_event, {
                            filter: ".fa-share",
                            anchor: "Continuous", //непрерывный анкер
                        });

                        instance.makeTarget(div_undeveloped_event, {
                            dropOptions: { hoverClass: "dragHover" },
                            anchor: "Continuous", //непрерывный анкер
                            allowLoopback: true, // Разрешение создавать кольцевую связь
                        });


                        //добавлены новые записи в массив состояний для изменений
                        var j = 0;
                        $.each(mas_data_undeveloped_event, function (i, elem) {
                            j = j + 1;
                        });
                        mas_data_undeveloped_event[j] = {id:data['id'], indent_x:parseInt(data['indent_x'], 10), indent_y:parseInt(data['indent_y'], 10), name:data['name'], description:data['description']};


                        //добавляем свойства состояний для копируемого состояния
                        for (var i = 0; i < data['i']; i++) {
                            //добавляем div разделительной линии для первого свойства состояния
                            if (i == 0){
                                var div_line = document.createElement('div');
                                div_line.id = 'undeveloped_event_line_' + data['id'];
                                div_line.className = 'div-line';
                                div_undeveloped_event.append(div_line);
                            }

                            var div_property = document.createElement('div');
                            div_property.id = 'undeveloped_event_property_' + data['undeveloped_event_property_id_'+i];
                            div_property.className = 'div-undeveloped-event-property';
                            div_property.innerHTML = data['undeveloped_event_property_name_'+i] + " " + data['undeveloped_event_property_operator_name_'+i] + " " + data['undeveloped_event_property_value_'+i];
                            div_undeveloped_event.append(div_property);

                            var div_button_property = document.createElement('div');
                            div_button_property.className = 'button-undeveloped-event-property';
                            div_property.prepend(div_button_property);

                            var div_edit_property = document.createElement('div');
                            div_edit_property.id = 'undeveloped_event_property_edit_' + data['undeveloped_event_property_id_'+i];
                            div_edit_property.className = 'edit-undeveloped-event-property glyphicon-pencil';
                            div_edit_property.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                            div_edit_property.innerHTML = '<i class="fa-solid fa-pen"></i>';
                            div_button_property.append(div_edit_property);

                            var div_del_property = document.createElement('div');
                            div_del_property.id = 'undeveloped_event_property_del_' + data['undeveloped_event_property_id_'+i];
                            div_del_property.className = 'del-undeveloped-event-property glyphicon-trash';
                            div_del_property.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                            div_del_property.innerHTML = '<i class="fa-solid fa-trash"></i>';
                            div_button_property.append(div_del_property);


                            //добавлены новые записи в массив свойств состояний для изменений
                            var id = data['undeveloped_event_property_id_'+i];
                            var name = data['undeveloped_event_property_name_'+i];
                            var description = data['undeveloped_event_property_description_'+i];
                            var operator = parseInt(data['undeveloped_event_property_operator_'+i], 10);
                            var value = data['undeveloped_event_property_value_'+i];
                            var undeveloped_event = data['undeveloped_event_property_undeveloped_event_'+i];

                            var j = 0;
                            $.each(mas_data_undeveloped_event_property, function (i, elem) {
                                j = j + 1;
                            });
                            mas_data_undeveloped_event_property[j] = {id:id, name:name, description:description, operator:operator, value:value, undeveloped-event:undeveloped-event};
                        }

                        //разместить новый undeveloped-event по новым координатам
                        div_undeveloped_event.style.left = parseInt(data['indent_x'], 10) + 'px';
                        div_undeveloped_event.style.top = parseInt(data['indent_y'], 10) + 'px';

                        //обновление поля visual_diagram_field для размещения элементов
                        mousemoveundeveloped-event();
                        // Обновление формы редактора
                        instance.repaintEverything();

                        document.getElementById('copy-undeveloped-event-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#copy-undeveloped-event-form", data);
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
    'id' => 'copy-undeveloped-event-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>

<?= $form->errorSummary($state_model); ?>

<?= $form->field($state_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($state_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_SAVE'),
    'options' => [
        'id' => 'copy-undeveloped-event-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>
