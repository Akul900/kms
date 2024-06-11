<?php
use app\modules\main\models\Lang;
?>
<script type="text/javascript">
function majorityConnectionsStyle(){
    var windows_majority_valve = jsPlumb.getSelector(".div-majority-valve");

    //стиль точки выхода линии "мажоритароного вентеля"
    for (var i = 0; i < windows_majority_valve.length; i++) {
        instance.makeSource(windows_majority_valve[i], {
            filter: ".fa-share",
            anchor: [ 0.5, 1, 0, 1, 0, -20 ], //непрерывный анкер
            maxConnections: -1, //ограничение на одно соединение из элемента "начала"
        });
        instance.makeTarget(windows_majority_valve[i], {
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

}

function majorityConnections(){
    $.each(mas_data_state_connection_fault, function (j, elem) {
        var c = instance.connect({
            source: "majority_valve_" + elem.element_from,
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
            source: "majority_valve_" + elem.element_from,
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
            source: "majority_valve_" + elem.element_from,
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
            source: "majority_valve_" + elem.element_from,
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
            source: "majority_valve_" + elem.element_from,
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
            source: "majority_valve_" + elem.element_from,
            target: "basic_event_" + elem.element_to,
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
            target: "majority_valve_" + elem.element_to,
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


    //удаление мажоритароного вентеля
    $(document).on('click', '.del-majority-valve', function() {
        if (!guest) {
            var majority_valve = $(this).attr('id');
            id_majority_valve = parseInt(majority_valve.match(/\d+/));

            $("#deleteMajorityValveModalForm").modal("show");
        }
    });

    //   //сохранение расположения мажоритароного вентеля
    //   $(document).on('mouseup', '.div-majority-valve', function() {
    //     var field = document.getElementById('visual_diagram_field');
    //     if (!guest) {
    //         var start_or_end = $(this).attr('id');
    //         var start_or_end_id = parseInt(start_or_end.match(/\d+/));
    //         var indent_x = $(this).position().left;
    //         var indent_y = $(this).position().top;
    //         //если отступ элемента отрицательный делаем его нулевым
    //         if (indent_x < 0){
    //             indent_x = 0;
    //         }
    //         if (indent_y < 0){
    //             indent_y = 0;
    //         }
    //         saveIndentStartOrEnd(start_or_end_id, indent_x / parseFloat(field.style.transform.split('(')[1].split(')')[0]), indent_y / parseFloat(field.style.transform.split('(')[1].split(')')[0]));
    //     }
    // });


  //добавление элемента мажоритароного вентеля на диаграмму
  $('#nav_add_majority_valve').on('click', function() {
        $.ajax({
            //переход на экшен левел
            url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
            '/fault-tree-diagrams/add-majority-valve/' . $model->id ?>",
            type: "post",
            data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>",
            dataType: "json",
            success: function (data) {
                if (data['success']) {
                    //создание div состояния
                    var div_visual_diagram_field = document.getElementById('visual_diagram_field');

                    var div_majority_valve = document.createElement('div');
                    div_majority_valve.id = 'majority_valve_' + data['id'];
                    div_majority_valve.className = 'div-majority-valve';
                    div_visual_diagram_field.append(div_majority_valve);

                    var div_majority_valve_text = document.createElement('div');
                    div_majority_valve_text.className = 'div-majority-valve-text' ;
                    div_majority_valve_text.innerHTML = 'МАЖ';
                    div_majority_valve.append(div_majority_valve_text);

                    var div_content_start = document.createElement('div');
                    div_content_start.className = 'content-majority-valve';
                    div_majority_valve.append(div_content_start);

                    var div_del = document.createElement('div');
                    div_del.id = 'majority_valve_del_' + data['id'];
                    div_del.className = 'del-majority-valve' ;
                    div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>';
                    div_del.innerHTML = '<i class="fa-solid fa-trash"></i>'
                    div_content_start.append(div_del);

                    var div_connect = document.createElement('div');
                    div_connect.className = 'connect-majority-valve' ;
                    div_connect.title = '<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>' ;
                    div_connect.innerHTML = '<i class="fa-solid fa-share"></i>';
                    div_content_start.append(div_connect);

                    //сделать div двигаемым
                    var div_majority_valve = document.getElementById('majority_valve_' + data['id']);
                    instance.draggable(div_majority_valve);
                    //добавляем элемент div_majority_valve в группу с именем group_field
                    instance.addToGroup('group_field', div_majority_valve);

                    instance.makeSource(div_majority_valve, {
                        filter: ".fa-share",
                        anchor: [ 0.5, 1, 0, 1, 0, -20 ], //непрерывный анкер
                        maxConnections: -1, //ограничение на одно соединение из элемента "начала"
                    });
                    instance.makeTarget(div_majority_valve, {
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

                    var nav_add_majority_valve = document.getElementById('nav_add_majority_valve');
                    // Выключение кнопки добавления начала
                    nav_add_majority_valve.className = 'dropdown-item';
                }
            },
            error: function () {
                alert('Error!');
            }
        });
    });

</script>