function draggableElements(){

    var div_visual_diagram_field = document.getElementById('visual_diagram_field');
    //создаем группу с определенным именем group
    instance.addGroup({
        el: div_visual_diagram_field,
        id: 'group_field',
        draggable: false, //перетаскивание группы
        dropOverride:true,
    });


    //находим все элементы с классом div-state и делаем их двигаемыми
    $(".div-state").each(function(i) {
        var id_state = $(this).attr('id');
        var state = document.getElementById(id_state);
        //делаем state перетаскиваемыми
        instance.draggable(state);
        //добавляем элемент state в группу с именем group_field
        instance.addToGroup('group_field', state);
    });

            //находим все элементы с классом div-state и делаем их двигаемыми
    $(".div-state-start").each(function(i) {
        var id_state = $(this).attr('id');
        var state = document.getElementById(id_state);
        //делаем state перетаскиваемыми
        instance.draggable(state);
        //добавляем элемент state в группу с именем group_field
        instance.addToGroup('group_field', state);
    });

    //находим все элементы с классом div-state и делаем их двигаемыми
    $(".div-basic-event").each(function(i) {
        var id_state = $(this).attr('id');
        var state = document.getElementById(id_state);
        //делаем state перетаскиваемыми
        instance.draggable(state);
        //добавляем элемент state в группу с именем group_field
        instance.addToGroup('group_field', state);
    });


    //находим все элементы с классом div-and и делаем их двигаемыми
    $(".div-and").each(function(i) {
        var id_start = $(this).attr('id');
        var start = document.getElementById(id_start);
        //делаем start перетаскиваемыми
        instance.draggable(start);
        //добавляем элемент start в группу с именем group_field
        instance.addToGroup('group_field', start);
    });


    //находим все элементы с классом div-or и делаем их двигаемыми
    $(".div-or").each(function(i) {
        var id_end = $(this).attr('id');
        var end = document.getElementById(id_end);
        //делаем end перетаскиваемыми
        instance.draggable(end);
        //добавляем элемент end в группу с именем group_field
        instance.addToGroup('group_field', end);
    });

    //находим все элементы с классом div-prohibition и делаем их двигаемыми
    $(".div-prohibition").each(function(i) {
        var id_prohibition = $(this).attr('id');
        var prohibition = document.getElementById(id_prohibition);
        //делаем end перетаскиваемыми
        instance.draggable(prohibition);
        //добавляем элемент end в группу с именем group_field
        instance.addToGroup('group_field', prohibition);
    });

    //находим все элементы с классом div-majority-valve и делаем их двигаемыми
    $(".div-majority-valve").each(function(i) {
        var id_majority_valve = $(this).attr('id');
        var majority_valve = document.getElementById(id_majority_valve);
        //делаем end перетаскиваемыми
        instance.draggable(majority_valve);
        //добавляем элемент end в группу с именем group_field
        instance.addToGroup('group_field', majority_valve);
    });

    //находим все элементы с классом div-and-with-priority и делаем их двигаемыми
    $(".div-and-with-priority").each(function(i) {
        var id_and_with_priority = $(this).attr('id');
        var and_with_priority = document.getElementById(id_and_with_priority);
        //делаем end перетаскиваемыми
        instance.draggable(and_with_priority);
        //добавляем элемент end в группу с именем group_field
        instance.addToGroup('group_field', and_with_priority);
    });

    //находим все элементы с классом div-undeveloped и делаем их двигаемыми
    $(".div-undeveloped-event").each(function(i) {
        var id_undeveloped = $(this).attr('id');
        var undeveloped = document.getElementById(id_undeveloped);
        //делаем state перетаскиваемыми
        instance.draggable(undeveloped);
        //добавляем элемент state в группу с именем group_field
        instance.addToGroup('group_field', undeveloped);
    });

    //находим все элементы с классом div-undeveloped и делаем их двигаемыми
    $(".div-not").each(function(i) {
        var id_not = $(this).attr('id');
        var not = document.getElementById(id_not);
        //делаем state перетаскиваемыми
        instance.draggable(not);
        //добавляем элемент state в группу с именем group_field
        instance.addToGroup('group_field', not);
    });


    //находим все элементы с классом div-undeveloped и делаем их двигаемыми
    $(".div-transfer-valve").each(function(i) {
        var id_transfer_valve = $(this).attr('id');
        var transfer_valve = document.getElementById(id_transfer_valve);
        //делаем state перетаскиваемыми
        instance.draggable(transfer_valve);
        //добавляем элемент state в группу с именем group_field
        instance.addToGroup('group_field', transfer_valve);
    });

        //находим все элементы с классом div-undeveloped и делаем их двигаемыми
    $(".div-hidden-event").each(function(i) {
        var id_hidden_event = $(this).attr('id');
        var hidden_event = document.getElementById(id_hidden_event);
        //делаем state перетаскиваемыми
        instance.draggable(hidden_event);
        //добавляем элемент state в группу с именем group_field
        instance.addToGroup('group_field', hidden_event);
    });


        //находим все элементы с классом div-undeveloped и делаем их двигаемыми
    $(".div-conditional-event").each(function(i) {
        var id_conditional_event = $(this).attr('id');
        var conditional_event = document.getElementById(id_conditional_event);
        //делаем state перетаскиваемыми
        instance.draggable(conditional_event);
        //добавляем элемент state в группу с именем group_field
        instance.addToGroup('group_field', conditional_event);
    });
    
}