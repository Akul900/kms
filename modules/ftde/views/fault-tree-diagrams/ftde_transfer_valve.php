<?php
use app\modules\main\models\Lang;
?>
<script type="text/javascript">

function transferValveConnectionsStyle(){
    var windows_transfer_valve = jsPlumb.getSelector(".div-transfer-valve");



    //стиль точки выхода линии "Запрета"
    for (var i = 0; i < windows_transfer_valve.length; i++) {

        instance.makeTarget(windows_transfer_valve, {
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
    }
}

function transferValveConnections(){

    $.each(mas_data_state_connection_fault, function (j, elem) {
        var c = instance.connect({
            source: "state_" + elem.element_from,
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
            source: "transfer_valve_" + elem.element_from,
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

}


    //удаление запрета
    $(document).on('click', '.del-transfer-valve', function() {
        if (!guest) {
            var transfer_valve = $(this).attr('id');
            id_transfer_valve = parseInt(transfer_valve.match(/\d+/));

            $("#deleteTransferValveModalForm").modal("show");
        }
    });

    //сохранение расположения элемента запрета
    $(document).on('mouseup', '.div-transfer-valve', function() {
        var field = document.getElementById('visual_diagram_field');

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
            saveIndentStartOrEnd(start_or_end_id, indent_x / parseFloat(field.style.transform.split('(')[1].split(')')[0]), indent_y / parseFloat(field.style.transform.split('(')[1].split(')')[0]));
        }
    });


    // редактирование базового события
     $(document).on('click', '.edit-transfer-valve', function() {
        if (!guest) {
            var transfer_valve = $(this).attr('id');
            transfer_valve_id_on_click = parseInt(transfer_valve.match(/\d+/));

            var div_transfer_valve = document.getElementById("transfer_valve_" + transfer_valve_id_on_click);

            $.each(mas_data_transfer_valve, function (i, elem) {
                if (elem.id == transfer_valve_id_on_click) {
                    document.forms["edit-transfer-valve-form"].reset();
                    document.forms["edit-transfer-valve-form"].elements["Element[name]"].value = elem.name;
                    document.forms["edit-transfer-valve-form"].elements["Element[description]"].value = elem.description;

                    $("#editTransferValveModalForm").modal("show");
                }
            });
        }
    });
</script>