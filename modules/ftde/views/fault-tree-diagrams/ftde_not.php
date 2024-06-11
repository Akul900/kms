<?php
use app\modules\main\models\Lang;
?>
<script type="text/javascript">
function deleteNot(){
       //удаление запрета
    $(document).on('click', '.del-not', function() {
        if (!guest) {
            var not = $(this).attr('id');
            id_not = parseInt(not.match(/\d+/));

            $("#deleteNotModalForm").modal("show");
        }
    });
}
   
// function saveIndentNot(){
//     //сохранение расположения элемента запрета
//       $(document).on('mouseup', '.div-not', function() {
//         var field = document.getElementById('visual_diagram_field');
//         if (!guest) {
//             var start_or_end = $(this).attr('id');
//             var start_or_end_id = parseInt(start_or_end.match(/\d+/));
//             var indent_x = $(this).position().left;
//             var indent_y = $(this).position().top;
//             //если отступ элемента отрицательный делаем его нулевым
//             if (indent_x < 0){
//                 indent_x = 0;
//             }
//             if (indent_y < 0){
//                 indent_y = 0;
//             }
//             saveIndentStartOrEnd(start_or_end_id, indent_x / parseFloat(field.style.transform.split('(')[1].split(')')[0]), indent_y / parseFloat(field.style.transform.split('(')[1].split(')')[0]));
//         }
//     });
// }


function notConnectionsStyle(){
    var windows_not = jsPlumb.getSelector(".div-not");



    //стиль точки выхода линии "Запрета"
    for (var i = 0; i < windows_not.length; i++) {
        instance.makeSource(windows_not[i], {
            filter: ".fa-share",
            anchor: [ 0.5, 1, 0, 1, 0, -20 ], //непрерывный анкер
            maxConnections: 1, //ограничение на одно соединение из элемента "начала"
            onMaxConnections: function (info, e) {
                //отображение сообщения об ограничении
                var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
                document.getElementById("message-text").lastChild.nodeValue = message;
                $("#viewMessageErrorLinkingItemsModalForm").modal("show");
            }
        });
        instance.makeTarget(windows_not[i], {
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

function notConnections(){

    $.each(mas_data_state_connection_fault, function (j, elem) {
        var c = instance.connect({
            source: "not_" + elem.element_from,
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
            source: "not_" + elem.element_from,
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
            source: "not_" + elem.element_from,
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
            source: "not_" + elem.element_from,
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
            source: "not_" + elem.element_from,
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
            source: "not_" + elem.element_from,
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
            target: "not_" + elem.element_to,
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
       $('#nav_add_not').on('click', function() {
        $.ajax({
            //переход на экшен левел
            url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
            '/fault-tree-diagrams/add-not/' . $model->id ?>",
            type: "post",
            data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>",
            dataType: "json",
            success: function (data) {
                if (data['success']) {
                    //создание div состояния
                    var div_visual_diagram_field = document.getElementById('visual_diagram_field');

                    var div_not  = document.createElement('div');
                    div_not .id = 'not_' + data['id'];
                    div_not .className = 'div-and-with-priority';
                    div_visual_diagram_field.append(div_not );

                    var div_not_text = document.createElement('div');
                    div_not_text.className = 'div-and-with-priority-text' ;
                    div_not_text.innerHTML = 'НЕ';
                    div_not.append(div_not_text);

                    var div_content_start = document.createElement('div');
                    div_content_start.className = 'content-and-with-priority';
                    div_not.append(div_content_start);

                    var div_del = document.createElement('div');
                    div_del.id = 'not_del_' + data['id'];
                    div_del.className = 'del-and-with-priority' ;
                    div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>';
                    div_del.innerHTML = '<i class="fa-solid fa-trash"></i>'
                    div_content_start.append(div_del);

                    var div_connect = document.createElement('div');
                    div_connect.className = 'connect-and-with-priority' ;
                    div_connect.title = '<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>' ;
                    div_connect.innerHTML = '<i class="fa-solid fa-share"></i>';
                    div_content_start.append(div_connect);

                    //сделать div двигаемым
                    var div_not = document.getElementById('not_' + data['id']);
                    instance.draggable(div_not);
                    //добавляем элемент div_not в группу с именем group_field
                    instance.addToGroup('group_field', div_not);

                    instance.makeSource(div_not, {
                        filter: ".fa-share",
                        anchor: [ 0.5, 1, 0, 1, 0, -20 ], //непрерывный анкер
                        maxConnections: -1, //ограничение на одно соединение из элемента "начала"
                    });
                    instance.makeTarget(div_not, {
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

                    var nav_add_not = document.getElementById('nav_add_not');
                    // Выключение кнопки добавления начала
                    nav_add_not.className = 'dropdown-item';
                }
            },
            error: function () {
                alert('Error!');
            }
        });
    });

</script> 
