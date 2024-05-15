<?php
use app\modules\main\models\Lang;
?>
<script type="text/javascript">


function prohibitionConnectionsStyle(){
    var windows_prohibition = jsPlumb.getSelector(".div-prohibition");



 //стиль точки выхода линии "Запрета"
        for (var i = 0; i < windows_prohibition.length; i++) {
                instance.makeSource(windows_prohibition[i], {
                    filter: ".fa-share",
                    anchor: [ 0.53, 1, 0, 1, 0, 0 ], //непрерывный анкер
                    maxConnections: -1, //ограничение на одно соединение из элемента "начала"
                    // onMaxConnections: function (info, e) {
                    //     //отображение сообщения об ограничении
                    //     var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
                    //     document.getElementById("message-text").lastChild.nodeValue = message;
                    //     $("#viewMessageErrorLinkingItemsModalForm").modal("show");
                    // }
                });

                instance.makeTarget(windows_prohibition[i], {
                    filter: ".fa-share",
                    dropOptions: { hoverClass: "dragHover" },
                    anchor: [ 0.51, 0, 0, -1, 0, 0 ], //непрерывный анкер
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

}

function prohibitionConnections(){

    $.each(mas_data_state_connection_fault, function (j, elem) {
        var c = instance.connect({
            source: "prohibition_" + elem.element_from,
            target: "state_" + elem.element_to,
            overlays: [
                ['Label', {
                    label: message_label,
                    location: 0.5, //расположение посередине
                    cssClass: "connections-style",
                }]
            ],
        });

        var c = instance.connect({
            source: "prohibition_" + elem.element_from,
            target: "hidden_event_" + elem.element_to,
            overlays: [
                ['Label', {
                    label: message_label,
                    location: 0.5, //расположение посередине
                    cssClass: "connections-style",
                }]
            ],
        });

        var c = instance.connect({
            source: "prohibition_" + elem.element_from,
            target: "transfer_valve_" + elem.element_to,
            overlays: [
                ['Label', {
                    label: message_label,
                    location: 0.5, //расположение посередине
                    cssClass: "connections-style",
                }]
            ],
        });


        var c = instance.connect({
            source: "prohibition_" + elem.element_from,
            target: "conditional_event_" + elem.element_to,
            overlays: [
                ['Label', {
                    label: message_label,
                    location: 0.5, //расположение посередине
                    cssClass: "connections-style",
                }]
            ],
        });


        var c = instance.connect({
            source: "prohibition_" + elem.element_from,
            target: "undeveloped_event_" + elem.element_to,
            overlays: [
                ['Label', {
                    label: message_label,
                    location: 0.5, //расположение посередине
                    cssClass: "connections-style",
                }]
            ],
        });

        var c = instance.connect({
            source: "prohibition_" + elem.element_from,
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

}


    //добавление элемента "начало" на диаграмму
    $('#nav_add_prohibition').on('click', function() {
        $.ajax({
            //переход на экшен левел
            url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
            '/fault-tree-diagrams/add-prohibition/' . $model->id ?>",
            type: "post",
            data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>",
            dataType: "json",
            success: function (data) {
                if (data['success']) {
                    //создание div состояния
                    var div_visual_diagram_field = document.getElementById('visual_diagram_field');

                    var div_prohibition = document.createElement('div');
                    div_prohibition.id = 'prohibition_' + data['id'];
                    div_prohibition.className = 'div-prohibition';
                    div_visual_diagram_field.append(div_prohibition);

                    // var div_prohibition_and = document.createElement('div');
                    // div_and_and.className = 'div-and-and' ;
                    // div_and_and.innerHTML = 'И';
                    // div_and.append(div_and_and);

                    var div_content_start = document.createElement('div');
                    div_content_start.className = 'content-prohibition';
                    div_prohibition.append(div_content_start);

                    var div_del = document.createElement('div');
                    div_del.id = 'prohibition_del_' + data['id'];
                    div_del.className = 'del-prohibition' ;
                    div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>';
                    div_del.innerHTML = '<i class="fa-solid fa-trash"></i>'
                    div_content_start.append(div_del);

                    var div_connect = document.createElement('div');
                    div_connect.className = 'connect-prohibition' ;
                    div_connect.title = '<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>' ;
                    div_connect.innerHTML = '<i class="fa-solid fa-share"></i>';
                    div_content_start.append(div_connect);

                    //сделать div двигаемым
                    var div_prohibition = document.getElementById('prohibition_' + data['id']);
                    instance.draggable(div_prohibition);
                    //добавляем элемент div_prohibition в группу с именем group_field
                    instance.addToGroup('group_field', div_prohibition);

                    instance.makeSource(div_prohibition, {
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
                    instance.makeTarget(div_prohibition, {
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

                    var nav_add_prohibition = document.getElementById('nav_add_prohibition');
                    // Выключение кнопки добавления начала
                    nav_add_prohibition.className = 'dropdown-item';
                }
            },
            error: function () {
                alert('Error!');
            }
        });
    });

    //удаление запрета
    $(document).on('click', '.del-prohibition', function() {
        if (!guest) {
            var prohibition = $(this).attr('id');
            id_prohibition = parseInt(prohibition.match(/\d+/));

            $("#deleteProhibitionModalForm").modal("show");
        }
    });

    //сохранение расположения элемента запрета
    $(document).on('mouseup', '.div-prohibition', function() {
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