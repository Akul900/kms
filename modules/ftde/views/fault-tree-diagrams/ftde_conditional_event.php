<script type="text/javascript">

function conditionalEventConnectionsStyle(){
var windows_conditional = jsPlumb.getSelector(".div-conditional-event");

for (var i = 0; i < windows_conditional.length; i++) {

    instance.makeTarget(windows_conditional[i], {
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

function conditionalEventConnections(){

    $.each(mas_data_state_connection_fault, function (j, elem) {
        var c = instance.connect({
            source: "state_" + elem.element_from,
            target: "conditional_event_" + elem.element_to,
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

function deleteConditionalEvent() {
    // удаление состояния
    $(document).on('click', '.del-conditional-event', function() {
            if (!guest) {
                var del = $(this).attr('id');
                conditional_event_id_on_click = parseInt(del.match(/\d+/));
                $("#deleteConditionalEventModalForm").modal("show");
            }
    });
}


function editConditionalEvent(){
    // редактирование базового события
    $(document).on('click', '.edit-conditional-event', function() {
        if (!guest) {
            var conditional_event = $(this).attr('id');
            conditional_event_id_on_click = parseInt(conditional_event.match(/\d+/));

            var div_conditional_event = document.getElementById("conditional_event_" + conditional_event_id_on_click);

            $.each(mas_data_conditional_event, function (i, elem) {
                if (elem.id == conditional_event_id_on_click) {
                    document.forms["edit-conditional-event-form"].reset();
                    document.forms["edit-conditional-event-form"].elements["Element[name]"].value = elem.name;
                    document.forms["edit-conditional-event-form"].elements["Element[description]"].value = elem.description;

                    $("#editConditionalEventModalForm").modal("show");
                }
            });
        }
    });
}

function copyConditionalEvent(){
      // копирование состояния
      $(document).on('click', '.copy-conditional-event', function() {
        if (!guest) {
            var state = $(this).attr('id');
            conditional_event_id_on_click = parseInt(state.match(/\d+/));

            var div_state = document.getElementById("state_" + conditional_event_id_on_click);

            $.each(mas_data_state, function (i, elem) {
                if (elem.id == conditional_event_id_on_click) {
                    document.forms["copy-state-form"].reset();
                    //document.forms["copy-state-form"].elements["State[name]"].value = elem.name;
                    document.forms["copy-state-form"].elements["Element[description]"].value = elem.description;
                    $("#copyStateModalForm").modal("show");
                }
            });
        }
    });
}


function saveIndentConditionalEvent(){
        //сохранение расположения элемента
        $(document).on('mouseup', '.div-conditional-event', function() {
            var field = document.getElementById('visual_diagram_field');
            if (!guest) {
                var state = $(this).attr('id');
                var state_id = parseInt(state.match(/\d+/));
                var indent_x = $(this).position().left;
                var indent_y = $(this).position().top;
                //если отступ элемента отрицательный делаем его нулевым
                if (indent_x < 0){
                    indent_x = 0;
                }
                if (indent_y < 0){
                    indent_y = 0;
                }
                saveIndent(state_id, indent_x / parseFloat(field.style.transform.split('(')[1].split(')')[0]), indent_y / parseFloat(field.style.transform.split('(')[1].split(')')[0]));
             
            }
        });
    
}

</script>

