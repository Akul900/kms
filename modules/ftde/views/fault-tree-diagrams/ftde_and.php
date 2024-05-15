<?php
use app\modules\main\models\Lang;
?>
<script type="text/javascript">


function andConnectionsStyle(){
    var windows_start = jsPlumb.getSelector(".div-and");



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
}

function andConnections(){


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

        var c = instance.connect({
            source: "and_" + elem.element_from,
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
            source: "and_" + elem.element_from,
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
            source: "and_" + elem.element_from,
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
            source: "and_" + elem.element_from,
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

}


</script> 
