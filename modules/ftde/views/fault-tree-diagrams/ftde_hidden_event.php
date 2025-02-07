<script type="text/javascript">

function hiddenEventConnectionsStyle(){
var windows_hidden = jsPlumb.getSelector(".div-hidden-event");

    for (var i = 0; i < windows_hidden.length; i++) {

        instance.makeSource(windows_hidden[i], {
            filter: ".fa-share",
            anchor: [ 0.5, 0.82, 0, 0, 0, 0 ], //непрерывный анкер
            maxConnections: 1,
            onMaxConnections: function (info, e) {
                //отображение сообщения об ограничении
                var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
                document.getElementById("message-text").lastChild.nodeValue = message;
                $("#viewMessageErrorLinkingItemsModalForm").modal("show");
            }
        });

        instance.makeTarget(windows_hidden[i], {
            dropOptions: { hoverClass: "dragHover" },
            anchor: [ 0.5, 0.16, 0, 0, 0, 0 ], //непрерывный анкер
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

function hiddenEventConnections(){
    $.each(mas_data_state_connection_fault, function (j, elem) {
        var c = instance.connect({
            source: "state_" + elem.element_from,
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
            source: "hidden_event_" + elem.element_from,
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
            source: "hidden_event_" + elem.element_from,
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
            source: "hidden_event_" + elem.element_from,
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
            source: "hidden_event_" + elem.element_from,
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
            source: "hidden_event_" + elem.element_from,
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
            source: "hidden_event_" + elem.element_from,
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
            source: "hidden_event_" + elem.element_from,
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
            source: "hidden_event_" + elem.element_from,
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
            source: "hidden_event_" + elem.element_from,
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
            source: "hidden_event_" + elem.element_from,
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
            source: "hidden_event_" + elem.element_from,
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
            source: "hidden_event_" + elem.element_from,
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

function deleteHiddenEvent() {
    // удаление состояния
    $(document).on('click', '.del-hidden-event', function() {
            if (!guest) {
                var del = $(this).attr('id');
                hidden_event_id_on_click = parseInt(del.match(/\d+/));
                $("#deleteHiddenEventModalForm").modal("show");
            }
    });
}


function editHiddenEvent(){
    // редактирование базового события
    $(document).on('click', '.edit-hidden-event', function() {
        if (!guest) {
            var hidden_event = $(this).attr('id');
            hidden_event_id_on_click = parseInt(hidden_event.match(/\d+/));

            var div_hidden_event = document.getElementById("hidden_event_" + hidden_event_id_on_click);

            $.each(mas_data_hidden_event, function (i, elem) {
                if (elem.id == hidden_event_id_on_click) {
                    document.forms["edit-hidden-event-form"].reset();
                    document.forms["edit-hidden-event-form"].elements["Element[name]"].value = elem.name;
                    document.forms["edit-hidden-event-form"].elements["Element[description]"].value = elem.description;

                    $("#editHiddenEventModalForm").modal("show");
                }
            });
        }
    });
}

function copyHiddenEvent(){
      // копирование состояния
      $(document).on('click', '.copy-hidden-event', function() {
        if (!guest) {
            var state = $(this).attr('id');
            hidden_event_id_on_click = parseInt(state.match(/\d+/));

            var div_state = document.getElementById("state_" + hidden_event_id_on_click);

            $.each(mas_data_state, function (i, elem) {
                if (elem.id == hidden_event_id_on_click) {
                    document.forms["copy-state-form"].reset();
                    //document.forms["copy-state-form"].elements["State[name]"].value = elem.name;
                    document.forms["copy-state-form"].elements["Element[description]"].value = elem.description;
                    $("#copyStateModalForm").modal("show");
                }
            });
        }
    });
}


function saveIndentHiddenEvent(){
        //сохранение расположения элемента
        $(document).on('mouseup', '.div-hidden-event', function() {
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

