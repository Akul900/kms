<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Button;
use app\modules\main\models\Lang;

/* @var $state_model app\modules\ftde\models\Transition */

?>


<!-- Модальное окно добавления нового состояния -->
<?php Modal::begin([
    'id' => 'addTransferValveModalForm',
    'title' => '<h3>' . Yii::t('app', 'FAULT_ADD_NEW_TRANSFER_VALVE') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#add-transfer-valve-button").click(function(e) {
            e.preventDefault();
            var form = $("#add-transfer-valve-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/fault-tree-diagrams/add-transfer-valve/' . $model->id ?>",
                type: "post",
                data: form.serialize(),
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#addTransferValveModalForm").modal("hide");

                        //создание div состояния
                        var div_visual_diagram_field = document.getElementById('visual_diagram_field');

                        var div_transfer_valve = document.createElement('div');
                        div_transfer_valve.id = 'transfer_valve_' + data['id'];
                        div_transfer_valve.className = 'div-transfer-valve';
                        div_transfer_valve.title = data['description'];
                        div_visual_diagram_field.append(div_transfer_valve);

                        var div_content_transfer_valve = document.createElement('div');
                        div_content_transfer_valve.className = 'content-transfer-valve';
                        div_transfer_valve.append(div_content_transfer_valve);

                        var div_transfer_valve_name = document.createElement('div');
                        div_transfer_valve_name.id = 'transfer_valve_name_' + data['id'];
                        div_transfer_valve_name.className = 'div-transfer-valve-name' ;
                        div_transfer_valve_name.innerHTML = data['name'];
                        div_content_transfer_valve.append(div_transfer_valve_name);

                        // var div_connect = document.createElement('div');
                        // div_connect.className = 'connect-transfer-valve' ;
                        // div_connect.title = '<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>' ;
                        // div_connect.innerHTML = '<i class="fa-solid fa-share"></i>';
                        // div_content_transfer_valve.append(div_connect);

                        var div_del = document.createElement('div');
                        div_del.id = 'transfer_valve_del_' + data['id'];
                        div_del.className = 'del-transfer-valve glyphicon-trash' ;
                        div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                        div_del.innerHTML = '<i class="fa-solid fa-trash"></i>'
                        div_content_transfer_valve.append(div_del);

                        var div_edit = document.createElement('div');
                        div_edit.id = 'transfer_valve_edit_' + data['id'];
                        div_edit.className = 'edit-transfer-valve glyphicon-pencil' ;
                        div_edit.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_edit.innerHTML = '<i class="fa-solid fa-pen"></i>'
                        div_content_transfer_valve.append(div_edit);

                        // var div_add_parameter = document.createElement('div');
                        // div_add_parameter.id = 'transfer_valve_add_property_' + data['id'];
                        // div_add_parameter.className = 'add-transfer-valve-property glyphicon-plus' ;
                        // div_add_parameter.title = '<?php echo Yii::t('app', 'BUTTON_ADD'); ?>' ;
                        // div_add_parameter.innerHTML = '<i class="fa-solid fa-plus"></i>'
                        // div_content_transfer_valve.append(div_add_parameter);

                        // var div_copy = document.createElement('div');
                        // div_copy.id = 'transfer_valve_copy_' + data['id'];
                        // div_copy.className = 'copy-transfer-valve glyphicon-plus-sign' ;
                        // div_copy.title = '<?php echo Yii::t('app', 'BUTTON_COPY'); ?>' ;
                        // div_copy.innerHTML = '<i class="fa-solid fa-circle-plus"></i>'
                        // div_content_transfer_valve.append(div_copy);

                        //сделать div двигаемым
                        var div_transfer_valve = document.getElementById('transfer_valve_' + data['id']);
                        instance.draggable(div_transfer_valve);
                        //добавляем элемент div_transfer_valve в группу с именем group_field
                        instance.addToGroup('group_field', div_transfer_valve);

                        instance.makeSource(div_transfer_valve, {
                            filter: ".fa-share",
                            anchor: [0.53, 1, 0, 1, 0, 0], //непрерывный анкер
                            maxConnections: -1, //ограничение на одно соединение из элемента "начала"
                            // onMaxConnections: function (info, e) {
                            //     //отображение сообщения об ограничении
                            //     var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
                            //     document.getElementById("message-text").lastChild.nodeValue = message;
                            //     $("#viewMessageErrorLinkingItemsModalForm").modal("show");
                            // }
                        });
                        instance.makeTarget(div_transfer_valve, {
                            filter: ".fa-share",
                            anchor: [ 0.51, 0, 0, -1, 0, 0 ], //непрерывный анкер
                            maxConnections: 1, //ог
                            allowLoopback: false,
                            onMaxConnections: function (info, e) {
                                //отображение сообщения об ограничении
                                var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
                                document.getElementById("message-text").lastChild.nodeValue = message;
                                $("#viewMessageErrorLinkingItemsModalForm").modal("show");
                            }
                        });



                        //добавлены новые записи в массив состояний для изменений
                        var j = 0;
                        $.each(mas_data_transfer_valve, function (i, elem) {
                            j = j + 1;
                        });
                        mas_data_transfer_valve[j] = {id:data['id'], indent_x:parseInt(data['indent_x'], 10), indent_y:parseInt(data['indent_y'], 10), name:data['name'], description:data['description']};


                        document.getElementById('add-transfer-valve-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#add-transfer-valve-form", data);
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
    'id' => 'add-transfer-valve-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>


<?= $form->errorSummary($state_model); ?>

<?= $form->field($state_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($state_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>


<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_ADD'),
    'options' => [
        'id' => 'add-transfer-valve-button',
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
    'id' => 'editTransferValveModalForm',
    'title' => '<h3>' . Yii::t('app', 'TRANSFER_VALVE_EDIT_TRANSFER_VALVE') . '</h3>',
]); ?>

    <!-- Скрипт модального окна -->
    <script type="text/javascript">
        // Выполнение скрипта при загрузке страницы
        $(document).ready(function() {
            // Обработка нажатия кнопки сохранения
            $("#edit-transfer-valve-button").click(function(e) {
                e.preventDefault();
                var form = $("#edit-transfer-valve-form");
                // Ajax-запрос
                $.ajax({
                    //переход на экшен левел
                    url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                    '/fault-tree-diagrams/edit-transfer-valve'?>",
                    type: "post",
                    data: form.serialize() + "&transfer_valve_id_on_click=" + transfer_valve_id_on_click,
                    dataType: "json",
                    success: function(data) {
                        // Если валидация прошла успешно (нет ошибок ввода)
                        if (data['success']) {
                            // Скрывание модального окна
                            $("#editTransferValveModalForm").modal("hide");

                            //изменение div состояния
                            var div_transfer_valve_name = document.getElementById('transfer_valve_name_' + data['id']);
                            div_transfer_valve_name.innerHTML = data['name'];

                            var div_transfer_valve = document.getElementById('transfer_valve_' + data['id']);
                            div_transfer_valve.title = data['description'];


                            //изменена запись в массиве состояний
                            $.each(mas_data_transfer_valve, function (i, elem) {
                                if (elem.id == data['id']){
                                    mas_data_transfer_valve[i].name = data['name'];
                                    mas_data_transfer_valve[i].description = data['description'];
                                }
                            });

                            document.getElementById('edit-transfer-valve-form').reset();
                        } else {
                            // Отображение ошибок ввода
                            viewErrors("#edit-transfer-valve-form", data);
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
    'id' => 'edit-transfer-valve-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>

<?= $form->errorSummary($state_model); ?>

<?= $form->field($state_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($state_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_SAVE'),
    'options' => [
        'id' => 'edit-transfer-valve-button',
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
    'id' => 'deleteTransferValveModalForm',
    'title' => '<h3>' . Yii::t('app', 'TRANSFER_VALVE_DELETE_TRANSFER_VALVE') . '</h3>',
]); ?>

    <!-- Скрипт модального окна -->
    <script type="text/javascript">
        // Выполнение скрипта при загрузке страницы
        $(document).ready(function() {
            // Обработка нажатия кнопки сохранения
            $("#delete-transfer-valve-button").click(function(e) {
                e.preventDefault();
                // Ajax-запрос
                $.ajax({
                    //переход на экшен левел
                    url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                    '/fault-tree-diagrams/delete-transfer-valve'?>",
                    type: "post",
                    data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&id_transfer_valve=" + id_transfer_valve,
                    dataType: "json",
                    success: function(data) {
                        // Если валидация прошла успешно (нет ошибок ввода)
                        if (data['success']) {
                            // Скрывание модального окна
                            $("#deleteTransferValveModalForm").modal("hide");

                            //удаление div состояния
                            var div_transfer_valve = document.getElementById('transfer_valve_' + id_transfer_valve);
                            instance.removeFromGroup(div_transfer_valve);//удаляем из группы
                            instance.remove(div_transfer_valve);// удаляем состояние


                            //удалена запись в массиве состояний
                            var temporary_mas_data_transfer_valve = {};
                            var q = 0;
                            $.each(mas_data_transfer_valve, function (i, elem) {
                                if (id_transfer_valve != elem.id){
                                    temporary_mas_data_transfer_valve[q] = {
                                        "id":elem.id,
                                        "indent_x":elem.indent_x,
                                        "indent_y":elem.indent_y,
                                        "name":elem.name,
                                        "description":elem.description,

                                    };
                                    q = q+1;
                                }
                            });
                            mas_data_transfer_valve = temporary_mas_data_transfer_valve;


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
    'id' => 'delete-transfer-valve-form',
]); ?>

    <div class="modal-body">
        <p style="font-size: 14px">
            <?php echo Yii::t('app', 'DELETE_TRANSFER_VALVE_TEXT'); ?>
        </p>
    </div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-transfer-valve-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>


