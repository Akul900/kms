<?php
use app\modules\main\models\Lang;
?>
<script type="text/javascript">


function basicEventConnectionsStyle(){
    var windows_basic_event = jsPlumb.getSelector(".div-basic-event");


    //стиль точки выхода линии "Запрета"
    for (var i = 0; i < windows_basic_event.length; i++) {

        instance.makeTarget(windows_basic_event[i], {
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
    }
}

function basicEventConnections(){

    $.each(mas_data_state_connection_fault, function (j, elem) {
                var c = instance.connect({
                    source: "state_" + elem.element_from,
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
