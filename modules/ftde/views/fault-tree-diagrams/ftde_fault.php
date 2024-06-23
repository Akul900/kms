<?php
use app\modules\main\models\Lang;
?>
<script type="text/javascript">


function faultConnectionsStyle(){
    var windows = jsPlumb.getSelector(".div-state");
   

    //стиль точки выхода линии "Запрета"
   for (var i = 0; i < windows.length; i++) {

                instance.makeSource(windows[i], {
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

                instance.makeTarget(windows[i], {
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

function faultConnections(){


    //построение связей из mas_data_state_connection_fault
    $.each(mas_data_state_connection_fault, function (j, elem) {
       
        // var c = instance.connect({
        //     source: "state_" + elem.element_from,
        //     target: "state_" + elem.element_to,
        //     overlays: [
        //         ['Label', {
        //             label: message_label,
        //             location: 0.5, //расположение посередине
        //             cssClass: "connections-style",
        //         }]
        //     ],
            
        // });
       // console.log(c)
        var c = instance.connect({
            source: "state_" + elem.element_from,
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
            source: "state_" + elem.element_from,
            target: "and_" + elem.element_to,
            overlays: [
                ['Label', {
                    label: message_label,
                    location: 0.5, //расположение посередине
                    cssClass: "connections-style",
                }]
            ],
        })
        var c = instance.connect({
            source: "state_" + elem.element_from,
            target: "prohibition_" + elem.element_to,
            overlays: [
                ['Label', {
                    label: message_label,
                    location: 0.5, //расположение посередине
                    cssClass: "connections-style",
                }]
            ],
        })
    });

    
}




</script> 
