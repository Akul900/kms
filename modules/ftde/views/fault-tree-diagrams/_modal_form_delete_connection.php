<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Button;
use app\modules\main\models\Lang;

?>

<!-- Модальное окно удаления связи из начала -->
<?php Modal::begin([
    'id' => 'deleteConnectionStartModalForm',
    'title' => '<h3>' . Yii::t('app', 'CONNECTION_DELETE_CONNECTION') . '</h3>',
]); ?>

    <!-- Скрипт модального окна -->
    <script type="text/javascript">
        // Выполнение скрипта при загрузке страницы
        $(document).ready(function() {
            // Обработка нажатия кнопки удаления
            $("#delete-connection-start-button").click(function(e) {
                e.preventDefault();
                // Ajax-запрос
                $.ajax({
                    //переход на экшен левел
                    url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                    '/fault-tree-diagrams/del-state-connection'?>",
                    type: "post",
                    data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&element_from=" + element_from + "&element_to=" + element_to + "&source_id=" + element_from_source + "&target_id=" + element_to_target,
                    dataType: "json",
                    success: function (data) {
                        if (data['success']) {
                            // Скрывание модального окна
                            $("#deleteConnectionStartModalForm").modal("hide");
                            //поиск связи
                            var id_source = data['source_id']
                            var id_target = data['target_id']
  
                            // if ((data['element_from_type'] == 0) && (data['element_to_type'] == 2)){//связь из начала
                            //     id_source = "and_" + element_from;
                            //     id_target = "state_" + element_to;
                            // }else if ((data['element_from_type'] == 2 || data['element_from_type'] == 3 ) && (data['element_to_type'] == 0)){//связь из начала
                            //     id_source = "state_" + element_from;
                            //     id_target = "and_" + element_to;
                            // }else if ((data['element_from_type'] == 1) && (data['element_to_type'] == 2)){//связь из or
                            //     id_source = "or_" + element_from;
                            //     id_target = "state_" + element_to;
                            // }else if ((data['element_from_type'] == 2 || data['element_from_type'] == 3) && (data['element_to_type'] == 1)){//связь в конец
                            //     id_source = "state_" + element_from;
                            //     id_target = "or_" + element_to;
                            // }else if ((data['element_from_type'] == 2 || data['element_from_type'] == 3) && (data['element_from_type'] == 2 || data['element_from_type'] == 3)){//связь в конец
                            //     id_source = "state_" + element_from;
                            //     id_target = "state_" + element_to;
                            // }
                            // if ((data['source_id'] == "state_9")){//связь из начала
                            //     console.log('hfgh')
                            // }
                       
                            var connection = instance.getConnections({
                                source: id_source,
                                target: id_target
                            })[ 0 ];

                            //удаление связи
                            removed_transition = true;//без этого флага нижнее удаление связи в реальном времени отменяется
                            instance.deleteConnection(connection);

                            //удалена запись в массиве связей с началом

                            var temporary_mas_data_state_connection_fault = {};
                            var q = 0;
                            $.each(mas_data_state_connection_fault, function (i, elem) {
                                if (data['id'] != elem.id){
                                    temporary_mas_data_state_connection_fault[q] = {
                                        "id":elem.id,
                                        "element_from":elem.element_from,
                                        "element_to":elem.element_to,
                                    };
                                    q = q+1;
                                }
                            });
                            mas_data_state_connection_fault = temporary_mas_data_state_connection_fault;
                       


                            // var temporary_mas_data_state_connection_start = {};
                            // var q = 0;
                            // $.each(mas_data_state_connection_start, function (i, elem) {
                            //     if (data['id'] != elem.id){
                            //         temporary_mas_data_state_connection_start[q] = {
                            //             "id":elem.id,
                            //             "element_from":elem.element_from,
                            //             "element_to":elem.element_to,
                            //         };
                            //         q = q+1;
                            //     }
                            // });
                            // mas_data_state_connection_start = temporary_mas_data_state_connection_start;

                   
                            // var temporary_mas_data_state_connection_end = {};
                            // var q = 0;
                            // $.each(mas_data_state_connection_end, function (i, elem) {
                            //     if (data['id'] != elem.id){
                            //         temporary_mas_data_state_connection_end[q] = {
                            //             "id":elem.id,
                            //             "element_from":elem.element_from,
                            //             "element_to":elem.element_to,
                            //         };
                            //         q = q+1;
                            //     }
                            // });
                            // mas_data_state_connection_end = temporary_mas_data_state_connection_end;
                        }
                    },
                    error: function () {
                        alert('Error!');
                    }
                });
            });
        });
    </script>

<?php $form = ActiveForm::begin([
    'id' => 'delete-connection-start-form',
]); ?>

    <div class="modal-body">
        <p style="font-size: 14px">
            <?php echo Yii::t('app', 'DELETE_CONNECTION_TEXT'); ?>
        </p>
    </div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-connection-start-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно удаления связи в конец -->
<?php Modal::begin([
    'id' => 'deleteConnectionEndModalForm',
    'title' => '<h3>' . Yii::t('app', 'CONNECTION_DELETE_CONNECTION') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки удаления
        $("#delete-connection-end-button").click(function(e) {
            e.preventDefault();
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/fault-tree-diagrams/del-state-connection'?>",
                type: "post",
                data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&element_from=" + element_from + "&element_to=" + element_to,
                dataType: "json",
                success: function (data) {
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#deleteConnectionEndModalForm").modal("hide");
                        //поиск связи
                        var id_source = "state_" + element_from;
                        var id_target = "or_" + element_to;

                        var connection = instance.getConnections({
                            source: id_source,
                            target: id_target
                        })[ 0 ];

                        //удаление связи
                        removed_transition = true;//без этого флага нижнее удаление связи в реальном времени отменяется
                        instance.deleteConnection(connection);

                        //удалена запись в массиве связей с концом
                        var temporary_mas_data_state_connection_end = {};
                        var q = 0;
                        $.each(mas_data_state_connection_end, function (i, elem) {
                            if (data['id'] != elem.id){
                                temporary_mas_data_state_connection_end[q] = {
                                    "id":elem.id,
                                    "element_from":elem.element_from,
                                    "element_to":elem.element_to,
                                };
                                q = q+1;
                            }
                        });
                        mas_data_state_connection_end = temporary_mas_data_state_connection_end;
                    }
                },
                error: function () {
                    alert('Error!');
                }
            });
        });
    });
</script>

<?php $form = ActiveForm::begin([
    'id' => 'delete-connection-end-form',
]); ?>

<div class="modal-body">
    <p style="font-size: 14px">
        <?php echo Yii::t('app', 'DELETE_CONNECTION_TEXT'); ?>
    </p>
</div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-connection-end-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>







<!-- Модальное окно удаления связи отказ-отказ -->
<?php Modal::begin([
    'id' => 'deleteConnectionModalForm',
    'title' => '<h3>' . Yii::t('app', 'CONNECTION_DELETE_CONNECTION') . '</h3>',
]); ?>

    <!-- Скрипт модального окна -->
    <script type="text/javascript">
        // Выполнение скрипта при загрузке страницы
        $(document).ready(function() {
            
            // Обработка нажатия кнопки удаления
            $("#delete-connection-button").click(function(e) {
                e.preventDefault();
                // Ajax-запрос
                $.ajax({
                    //переход на экшен левел
                    url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                    '/fault-tree-diagrams/del-connection'?>",
                    type: "post",
                    data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&element_from=" + element_from + "&element_to=" + element_to,
                    dataType: "json",
                    success: function (data) {
                        if (data['success']) {
                            // Скрывание модального окна
                            $("#deleteConnectionModalForm").modal("hide");
                            //поиск связи
                            var id_source = "state_" + element_from;
                            var id_target = "state_" + element_to;

                            var connection = instance.getConnections({
                                source: id_source,
                                target: id_target
                            })[ 0 ];

                            //удаление связи
                            removed_transition = true;//без этого флага нижнее удаление связи в реальном времени отменяется
                            instance.deleteConnection(connection);

                            //удалена запись в массиве связей с началом
                            var temporary_mas_data_state_connection_fault = {};
                            var q = 0;
                            $.each(mas_data_state_connection_fault, function (i, elem) {
                                if (data['id'] != elem.id){
                                    temporary_mas_data_state_connection_fault[q] = {
                                        "id":elem.id,
                                        "element_from":elem.element_from,
                                        "element_to":elem.element_to,
                                    };
                                    q = q+1;
                                }
                            });
                            mas_data_state_connection_fault = temporary_mas_data_state_connection_fault;
                        }
                    },
                    error: function () {
                        alert('Error!');
                    }
                });
            });
        });
    </script>

<?php $form = ActiveForm::begin([
    'id' => 'delete-connection-form',
]); ?>

    <div class="modal-body">
        <p style="font-size: 14px">
            <?php echo Yii::t('app', 'DELETE_CONNECTION_TEXT'); ?>
        </p>
    </div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-connection-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>