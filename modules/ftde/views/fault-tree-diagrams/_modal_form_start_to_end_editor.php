<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Button;
use app\modules\main\models\Lang;

?>

<!-- Модальное окно удаления начала -->
<?php Modal::begin([
    'id' => 'deleteStartModalForm',
    'title' => '<h3>' . Yii::t('app', 'AND_DELETE_AND') . '</h3>',
]); ?>

    <!-- Скрипт модального окна -->
    <script type="text/javascript">
        // Выполнение скрипта при загрузке страницы
        $(document).ready(function() {
            // Обработка нажатия кнопки удаления
            $("#delete-start-button").click(function(e) {
                e.preventDefault();
                // Ajax-запрос
                $.ajax({
                    //переход на экшен левел
                    url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                    '/fault-tree-diagrams/delete-start'?>",
                    type: "post",
                    data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&id_start=" + id_start,
                    dataType: "json",
                    success: function (data) {
                        if (data['success']) {
                            $("#deleteStartModalForm").modal("hide");
                            //удаление div начала
                            var div_start = document.getElementById('and_' + data['id']);
                            instance.removeFromGroup(div_start);//удаляем из группы
                            instance.remove(div_start);// удаляем начало

                            var nav_add_start = document.getElementById('nav_add_start');
                            // Включение добавления начала
                            nav_add_start.className = 'dropdown-item';

                            //----------возможно нужно удалять записи из массива mas_data_state_connection_start
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
    'id' => 'delete-start-form',
]); ?>

    <div class="modal-body">
        <p style="font-size: 14px">
            <?php echo Yii::t('app', 'DELETE_AND_TEXT'); ?>
        </p>
    </div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-start-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно удаления завершения -->
<?php Modal::begin([
    'id' => 'deleteEndModalForm',
    'title' => '<h3>' . Yii::t('app', 'OR_DELETE_OR') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки удаления
        $("#delete-end-button").click(function(e) {
            e.preventDefault();
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/fault-tree-diagrams/delete-end'?>",
                type: "post",
                data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&id_end=" + id_end,
                dataType: "json",
                success: function (data) {
                    if (data['success']) {
                        $("#deleteEndModalForm").modal("hide");
                        //удаление div завершения
                        var div_end = document.getElementById('or_' + data['id']);
                        instance.removeFromGroup(div_end);//удаляем из группы
                        instance.remove(div_end);// удаляем завершение

                        var nav_add_end = document.getElementById('nav_add_end');
                        // Включение добавления завершения
                        nav_add_end.className = 'dropdown-item';

                        //----------возможно нужно удалять записи из массива mas_data_state_connection_end
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
    'id' => 'delete-end-form',
]); ?>

<div class="modal-body">
    <p style="font-size: 14px">
        <?php echo Yii::t('app', 'DELETE_OR_TEXT'); ?>
    </p>
</div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-end-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно удаления запрета -->
<?php Modal::begin([
    'id' => 'deleteProhibitionModalForm',
    'title' => '<h3>' . Yii::t('app', 'PROHIBITION_DELETE_PROHIBITION') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки удаления
        $("#delete-prohibition-button").click(function(e) {
            e.preventDefault();
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/fault-tree-diagrams/delete-prohibition'?>",
                type: "post",
                data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&id_prohibition=" + id_prohibition,
                dataType: "json",
                success: function (data) {
                    if (data['success']) {
                        $("#deleteProhibitionModalForm").modal("hide");
                        //удаление div завершения
                        var div_prohibition = document.getElementById('prohibition_' + data['id']);
                        instance.removeFromGroup(div_prohibition);//удаляем из группы
                        instance.remove(div_prohibition);// удаляем завершение

                        var nav_add_end = document.getElementById('nav_add_prohibition');
                        // Включение добавления завершения
                        nav_add_end.className = 'dropdown-item';

                        //----------возможно нужно удалять записи из массива mas_data_state_connection_end
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
    'id' => 'delete-prohibition-form',
]); ?>

<div class="modal-body">
    <p style="font-size: 14px">
        <?php echo Yii::t('app', 'DELETE_PROHIBITION_TEXT'); ?>
    </p>
</div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-prohibition-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>


<!-- Модальное окно удаления Мажоритарного вентиля  -->
<?php Modal::begin([
    'id' => 'deleteMajorityValveModalForm',
    'title' => '<h3>' . Yii::t('app', 'MAJORITY_VALVE_DELETE_MAJORITY_VALVE') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки удаления
        $("#delete-majority-valve-button").click(function(e) {
            e.preventDefault();
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/fault-tree-diagrams/delete-majority-valve'?>",
                type: "post",
                data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&id_majority_valve=" + id_majority_valve,
                dataType: "json",
                success: function (data) {
                    if (data['success']) {
                        $("#deleteMajorityValveModalForm").modal("hide");
                        //удаление div завершения
                        var div_majority_valve = document.getElementById('majority_valve_' + data['id']);
                        instance.removeFromGroup(div_majority_valve);//удаляем из группы
                        instance.remove(div_majority_valve);// удаляем завершение

                        var nav_majority_valve = document.getElementById('nav_add_majority_valve');
                        // Включение добавления завершения
                        nav_majority_valve.className = 'dropdown-item';

                        //----------возможно нужно удалять записи из массива mas_data_state_connection_end
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
    'id' => 'delete-majority-valve-form',
]); ?>

<div class="modal-body">
    <p style="font-size: 14px">
        <?php echo Yii::t('app', 'DELETE_MAJORITY_VALVE_TEXT'); ?>
    </p>
</div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-majority-valve-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно удаления И с приоритетом  -->
<?php Modal::begin([
    'id' => 'deleteaAndWithPriorityModalForm',
    'title' => '<h3>' . Yii::t('app', 'AND_WITH_PRIORITY_DELETE_AND_WITH_PRIORITY') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки удаления
        $("#delete-and-with-priority-button").click(function(e) {
            e.preventDefault();
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/fault-tree-diagrams/delete-and-with-priority'?>",
                type: "post",
                data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&id_and_with_priority=" + id_and_with_priority,
                dataType: "json",
                success: function (data) {
                    if (data['success']) {
                        $("#deleteaAndWithPriorityModalForm").modal("hide");
                        //удаление div завершения
                        var div_and_with_priority = document.getElementById('and-with_priority_' + data['id']);
                        instance.removeFromGroup(div_and_with_priority);//удаляем из группы
                        instance.remove(div_and_with_priority);// удаляем завершение

                        var nav_and_with_priority = document.getElementById('nav_add_and_with_priority');
                        // Включение добавления завершения
                        nav_and_with_priority.className = 'dropdown-item';

                        //----------возможно нужно удалять записи из массива mas_data_state_connection_end
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
    'id' => 'delete-and-with-priority-form',
]); ?>

<div class="modal-body">
    <p style="font-size: 14px">
        <?php echo Yii::t('app', 'DELETE_ADD_AND_WITH_PRIORITY_TEXT'); ?>
    </p>
</div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-and-with-priority-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>


<!-- Модальное окно удаления Не  -->
<?php Modal::begin([
    'id' => 'deleteNotModalForm',
    'title' => '<h3>' . Yii::t('app', 'NOT_DELETE_NOT') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки удаления
        $("#delete-not-button").click(function(e) {
            e.preventDefault();
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/fault-tree-diagrams/delete-not'?>",
                type: "post",
                data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&id_not=" + id_not,
                dataType: "json",
                success: function (data) {
                    if (data['success']) {
                        $("#deleteNotModalForm").modal("hide");
                        //удаление div завершения
                        var div_not = document.getElementById('not_' + data['id']);
                        instance.removeFromGroup(div_not);//удаляем из группы
                        instance.remove(div_not);// удаляем завершение

                        var nav_not = document.getElementById('nav_add_not');
                        // Включение добавления завершения
                        nav_not.className = 'dropdown-item';

                        //----------возможно нужно удалять записи из массива mas_data_state_connection_end
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
    'id' => 'delete-not-form',
]); ?>

<div class="modal-body">
    <p style="font-size: 14px">
        <?php echo Yii::t('app', 'DELETE_NOT_TEXT'); ?>
    </p>
</div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-not-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>


