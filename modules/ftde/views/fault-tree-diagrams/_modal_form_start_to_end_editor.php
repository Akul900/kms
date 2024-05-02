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