<?php
use app\modules\main\models\Lang;
?>
<script type="text/javascript">


function stateStartConnectionsStyle(){
    var windows_fault_start = jsPlumb.getSelector(".div-state-start");



    //стиль точки выхода линии "Запрета"
    for (var i = 0; i < windows_fault_start.length; i++) {

        instance.makeSource(windows_fault_start[i], {
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

    }
}

function stateStartConnections(){
        //построение связей из mas_data_state_connection_fault
    $.each(mas_data_state_connection_fault, function (j, elem) {
        var c = instance.connect({
            source: "state-start_" + elem.element_from,
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
            source: "state-start_" + elem.element_from,
            target: "and_" + elem.element_to,
            overlays: [
                ['Label', {
                    label: message_label,
                    location: 0.5, //расположение посередине
                    cssClass: "connections-style",
                }]
            ],
        });


        var c = instance.connect({
            source: "state-start_" + elem.element_from,
            target: "or_" + elem.element_to,
            overlays: [
                ['Label', {
                    label: message_label,
                    location: 0.5, //расположение посередине
                    cssClass: "connections-style",
                }]
            ],
        });


        var c = instance.connect({
            source: "state-start_" + elem.element_from,
            target: "prohibition_" + elem.element_to,
            overlays: [
                ['Label', {
                    label: message_label,
                    location: 0.5, //расположение посередине
                    cssClass: "connections-style",
                }]
            ],
        });


        var c = instance.connect({
            source: "state-start_" + elem.element_from,
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
            source: "state-start_" + elem.element_from,
            target: "and-with_priority_" + elem.element_to,
            overlays: [
                ['Label', {
                    label: message_label,
                    location: 0.5, //расположение посередине
                    cssClass: "connections-style",
                }]
            ],
        });


        var c = instance.connect({
            source: "state-start_" + elem.element_from,
            target: "majority_valve_" + elem.element_to,
            overlays: [
                ['Label', {
                    label: message_label,
                    location: 0.5, //расположение посередине
                    cssClass: "connections-style",
                }]
            ],
        });

        var c = instance.connect({
            source: "state-start_" + elem.element_from,
            target: "not_" + elem.element_to,
            overlays: [
                ['Label', {
                    label: message_label,
                    location: 0.5, //расположение посередине
                    cssClass: "connections-style",
                }]
            ],
        });

        var c = instance.connect({
            source: "state-start_" + elem.element_from,
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
            source: "state-start_" + elem.element_from,
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
            source: "state-start_" + elem.element_from,
            target: "undeveloped_event_" + elem.element_to,
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
