function deleteDistributionOfElements() {
    //Распределение state (состояний) на диаграмме
    $.each(mas_data_state, function (j, elem) {

    console.log(elem)
    if(elem["type"] == 3){
            $(".div-state-start").each(function(i) {
                var state = $(this).attr('id');
                var state_id = parseInt(state.match(/\d+/));

                if (elem.id == state_id) {
                    $(this).css({
                        left: parseInt(elem.indent_x),
                        top: parseInt(elem.indent_y)
                    });
                }
            });
        }else{
            $(".div-state").each(function(i) {
                var state = $(this).attr('id');
                var state_id = parseInt(state.match(/\d+/));

                if (elem.id == state_id) {
                    $(this).css({
                        left: parseInt(elem.indent_x),
                        top: parseInt(elem.indent_y)
                    });
                }
            });
        }
    
    });

    //Распределение basic_event (базовых состояний) на диаграмме
    $.each(mas_data_basic_event, function (j, elem) {
        $(".div-basic-event").each(function(i) {
            var basic_event = $(this).attr('id');
            var basic_event_id = parseInt(basic_event.match(/\d+/));

            if (elem.id == basic_event_id) {
                $(this).css({
                    left: parseInt(elem.indent_x),
                    top: parseInt(elem.indent_y)
                });
            }
        });
    });

    //Распределение start (начала) на диаграмме
    $.each(mas_data_start, function (j, elem) {
        $(".div-and").each(function(i) {
            var start = $(this).attr('id');
            var start_id = parseInt(start.match(/\d+/));

            if (elem.id == start_id) {
                $(this).css({
                    left: parseInt(elem.indent_x),
                    top: parseInt(elem.indent_y)
                });
            }
        });
    });


    //Распределение end (завершения) на диаграмме
    $.each(mas_data_end, function (j, elem) {
        $(".div-or").each(function(i) {
            var end = $(this).attr('id');
            var end_id = parseInt(end.match(/\d+/));

            if (elem.id == end_id) {
                $(this).css({
                    left: parseInt(elem.indent_x),
                    top: parseInt(elem.indent_y)
                });
            }
        });
    });

    $.each(mas_data_prohibition, function (j, elem) {
        $(".div-prohibition").each(function(i) {
            var prohibition = $(this).attr('id');
            var prohibition_id = parseInt(prohibition.match(/\d+/));

            if (elem.id == prohibition_id) {
                $(this).css({
                    left: parseInt(elem.indent_x),
                    top: parseInt(elem.indent_y)
                });
            }
        });
    });

    $.each(mas_data_majority_valve, function (j, elem) {
        $(".div-majority-valve").each(function(i) {
            var majority_valve = $(this).attr('id');
            var majority_valve_id = parseInt(majority_valve.match(/\d+/));

            if (elem.id == majority_valve_id) {
                $(this).css({
                    left: parseInt(elem.indent_x),
                    top: parseInt(elem.indent_y)
                });
            }
        });
    });

    $.each(mas_data_and_with_priority, function (j, elem) {
        $(".div-and-with-priority").each(function(i) {
            var and_with_priority = $(this).attr('id');
            var and_with_priority_id = parseInt(and_with_priority.match(/\d+/));

            if (elem.id == and_with_priority_id) {
                $(this).css({
                    left: parseInt(elem.indent_x),
                    top: parseInt(elem.indent_y)
                });
            }
        });
    });

    //Распределение undeveloped_event (базовых состояний) на диаграмме
    $.each(mas_data_undeveloped_event, function (j, elem) {
        $(".div-undeveloped-event").each(function(i) {
            var undeveloped_event = $(this).attr('id');
            var undeveloped_event_id = parseInt(undeveloped_event.match(/\d+/));

            if (elem.id == undeveloped_event_id) {
                $(this).css({
                    left: parseInt(elem.indent_x),
                    top: parseInt(elem.indent_y)
                });
            }
        });
    });

    //Распределение undeveloped_event (базовых состояний) на диаграмме
    $.each(mas_data_not, function (j, elem) {
         $(".div-not").each(function(i) {
            var not = $(this).attr('id');
            var not_id = parseInt(not.match(/\d+/));
    
            if (elem.id == not_id) {
                $(this).css({
                     left: parseInt(elem.indent_x),
                     top: parseInt(elem.indent_y)
                });
            }
        });
    });

    //Распределение undeveloped_event (базовых состояний) на диаграмме
    $.each(mas_data_transfer_valve, function (j, elem) {
            $(".div-transfer-valve").each(function(i) {
               var transfer_valve = $(this).attr('id');
               var transfer_valve_id = parseInt(transfer_valve.match(/\d+/));
       
               if (elem.id == transfer_valve_id) {
                   $(this).css({
                        left: parseInt(elem.indent_x),
                        top: parseInt(elem.indent_y)
                   });
               }
           });
       });


        //Распределение hidden_event(базовых состояний) на диаграмме
    $.each(mas_data_hidden_event, function (j, elem) {
            $(".div-hidden-event").each(function(i) {
               var hidden_event = $(this).attr('id');
               var hidden_event_id = parseInt(hidden_event.match(/\d+/));
       
               if (elem.id == hidden_event_id) {
                   $(this).css({
                        left: parseInt(elem.indent_x),
                        top: parseInt(elem.indent_y)
                   });
               }
           });
       });


        //Распределение conditional_event (базовых состояний) на диаграмме
    $.each(mas_data_conditional_event, function (j, elem) {
            $(".div-conditional-event").each(function(i) {
               var conditional_event = $(this).attr('id');
               var conditional_event_id = parseInt(conditional_event.match(/\d+/));
       
               if (elem.id == conditional_event_id) {
                   $(this).css({
                        left: parseInt(elem.indent_x),
                        top: parseInt(elem.indent_y)
                   });
               }
           });
       });
}