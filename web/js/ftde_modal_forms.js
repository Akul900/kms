function processingOfModalForms(){
    $(document).ready(function() {
        if (!guest){
            var nav_add_start = document.getElementById('nav_add_start');
            var nav_add_end = document.getElementById('nav_add_end');

            // Включение кнопок добавления начала и завершения если их нет
            if ('<?php echo $start_count; ?>' == 0){
                nav_add_start.className = 'dropdown-item';
            }
            if ('<?php echo $end_count; ?>' == 0){
                nav_add_end.className = 'dropdown-item';
            }

            // Обработка закрытия модального окна добавления нового состояния
            $("#addFaultModalForm").on("hidden.bs.modal", function() {
                // Скрытие списка ошибок ввода в модальном окне
                $("#add-state-form .error-summary").hide();
                var elem = document.getElementById("add-state-form").elements;
                $.each(elem, function (i, e) {
                    e.classList.remove("is-invalid");
                    e.classList.remove("is-valid");
                    e.removeAttribute("aria-invalid");
                });
                $("#add-state-form .invalid-feedback").each(function() {
                    $(this).text("");
                });
            });

            // Обработка закрытия модального окна добавления нового состояния
            $("#addBasicEventModalForm").on("hidden.bs.modal", function() {
                // Скрытие списка ошибок ввода в модальном окне
                $("#add-basic-event-form .error-summary").hide();
                var elem = document.getElementById("add-basic-event-form").elements;
                $.each(elem, function (i, e) {
                    e.classList.remove("is-invalid");
                    e.classList.remove("is-valid");
                    e.removeAttribute("aria-invalid");
                });
                $("#add-basic-event-form .invalid-feedback").each(function() {
                    $(this).text("");
                });
            });


            // Обработка закрытия модального окна добавления нового состояния
            $("#addUndevelopedEventModalForm").on("hidden.bs.modal", function() {
                // Скрытие списка ошибок ввода в модальном окне
                $("#add-undeveloped-event-form .error-summary").hide();
                var elem = document.getElementById("add-undeveloped-event-form").elements;
                $.each(elem, function (i, e) {
                    e.classList.remove("is-invalid");
                    e.classList.remove("is-valid");
                    e.removeAttribute("aria-invalid");
                });
                $("#add-undeveloped-event-form .invalid-feedback").each(function() {
                    $(this).text("");
                });
            });

            // Обработка закрытия модального окна добавления нового состояния
            $("#addTransferValveModalForm").on("hidden.bs.modal", function() {
                // Скрытие списка ошибок ввода в модальном окне
                $("#add-transfer-valve-form .error-summary").hide();
                var elem = document.getElementById("add-transfer-valve-form").elements;
                $.each(elem, function (i, e) {
                    e.classList.remove("is-invalid");
                    e.classList.remove("is-valid");
                    e.removeAttribute("aria-invalid");
                });
                $("#add-transfer-valve-form .invalid-feedback").each(function() {
                    $(this).text("");
                });
            });


            // Обработка закрытия модального окна добавления нового состояния
            $("#addHiddenEventModalForm").on("hidden.bs.modal", function() {
                // Скрытие списка ошибок ввода в модальном окне
                $("#add-hidden-event-form .error-summary").hide();
                var elem = document.getElementById("add-hidden-event-form").elements;
                $.each(elem, function (i, e) {
                    e.classList.remove("is-invalid");
                    e.classList.remove("is-valid");
                    e.removeAttribute("aria-invalid");
                });
                $("#add-hidden-event-form .invalid-feedback").each(function() {
                    $(this).text("");
                });
            });


            // Обработка закрытия модального окна добавления нового состояния
            $("#addConditionalEventModalForm").on("hidden.bs.modal", function() {
                // Скрытие списка ошибок ввода в модальном окне
                $("#add-conditional-event-form .error-summary").hide();
                var elem = document.getElementById("add-conditional-event-form").elements;
                $.each(elem, function (i, e) {
                    e.classList.remove("is-invalid");
                    e.classList.remove("is-valid");
                    e.removeAttribute("aria-invalid");
                });
                $("#add-conditional-event-form .invalid-feedback").each(function() {
                    $(this).text("");
                });
            });

            // Обработка закрытия модального окна добавления нового свойства состояния
            $("#addStatePropertyModalForm").on("hidden.bs.modal", function() {
                // Скрытие списка ошибок ввода в модальном окне
                $("#add-state-property-form .error-summary").hide();
                var elem = document.getElementById("add-state-property-form").elements;
                $.each(elem, function (i, e) {
                    e.classList.remove("is-invalid");
                    e.classList.remove("is-valid");
                    e.removeAttribute("aria-invalid");
                });
                $("#add-state-property-form .invalid-feedback").each(function() {
                    $(this).text("");
                });
            });

            // Обработка закрытия модального окна добавления нового перехода
            $("#addTransitionModalForm").on("hidden.bs.modal", function() {
                //если это не добавление новой связи
                if(added_transition != true){
                    removed_transition = true;
                    //то удаляем связь
                    instance.deleteConnection(current_connection);
                }
                added_transition = false;

                // Скрытие списка ошибок ввода в модальном окне
                $("#add-transition-form .error-summary").hide();
                var elem = document.getElementById("add-transition-form").elements;
                $.each(elem, function (i, e) {
                    e.classList.remove("is-invalid");
                    e.classList.remove("is-valid");
                    e.removeAttribute("aria-invalid");
                });
                $("#add-transition-form .invalid-feedback").each(function() {
                    $(this).text("");
                });
            });

            // Обработка закрытия модального окна добавления нового условия
            $("#addTransitionPropertyModalForm").on("hidden.bs.modal", function() {
                // Скрытие списка ошибок ввода в модальном окне
                $("#add-transition-property-form .error-summary").hide();
                var elem = document.getElementById("add-state-form").elements;
                $.each(elem, function (i, e) {
                    e.classList.remove("is-invalid");
                    e.classList.remove("is-valid");
                    e.removeAttribute("aria-invalid");
                });
                $("#add-transition-property-form .invalid-feedback").each(function() {
                    $(this).text("");
                });
            });
        }
    });
}