<?php
use yii\bootstrap5\Modal;
use app\modules\main\models\Lang;


?>


<!-- Модальное окно для вывода сообщений об ошибках при связывании элементов -->
<?php Modal::begin([
    'id' => 'minimumCrossSectionModalForm',
    'title' => '<h4>' . Yii::t('app', 'MCS_TITLE') . '</h4>',
    'class'=>'mcs-modal',
    'clientOptions' => ['backdrop' => false, 'keyboard' => true]

    
]); 

echo "<div id='modalContent'>Loading...</div>";
?>

<script type="text/javascript">
    let originalStyles = {};
    let currentElements = [];
    let currentHeader = null;
    
    function changeColor(elements) {
        elements.each(function() {
            let element = $(this);
            let id = element.attr('id');

      

            // Проверяем только основные элементы, исключая дочерние элементы
            if (/^(undeveloped_event|hidden_event|basic_event|conditional_event|state|state-start)_\d+$/.test(id)) {
                   // Применяем стили только к основному элементу
                 
                    if (!originalStyles[id]) {
                        originalStyles[id] = {
                            backgroundColor: element.css('background-color'),
                            backgroundImage: element.css('background-image')
                        };
                    }
                



           
                if (id.startsWith('undeveloped_event_')) {
                    element.css('background-image', 'url(/images/undeveloped_event_mcs.png)');
                    element1 = element.children()
                    element1.children().css('background-image', '');
       
             
                } else if (id.startsWith('hidden_event_')) {
                    element.css('background-image', 'url(/images/hidden_event_mcs.png)');
                } else {
                    element.css('background-color', 'yellow');
                }

            }
        });
    }

    function resetColors(elements) {
        elements.each(function() {
            let element = $(this);
            let id = element.attr('id');
            if (originalStyles[id]) {
                if (id.startsWith('undeveloped_event_')) {
                    element.css('background-image', originalStyles[id].backgroundImage);
                    element1 = element.children()
                    element1.children().css('background-image', '');
       
             
                }else{
                element.css('background-color', originalStyles[id].backgroundColor);
                element.css('background-image', originalStyles[id].backgroundImage);
                delete originalStyles[id];
                }
            }
        });
    }
    $('#modalButton').click(function() {
            $.ajax({
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/fault-tree-diagrams/mcs/' . $model->id ?>",
                type: "get",        
                dataType: "json",
                success: function(data) {
                var content = '';
                var minArrays = data;
                if (minArrays && minArrays.length > 0) {
                    var content = '';
                    for (var i = 0; i < minArrays.length; i++) {
                        content += '<h5 class="array-header" data-index="' + i + '">Минимальное сечение ' + (i + 1) + '</h5>';
                        content += '<ul class="element-list" data-index="' + i + '">';
                        for (var j = 0; j < minArrays[i].length; j++) {
                            content += '<li class="element-item" data-id="' + minArrays[i][j].id + '">' + minArrays[i][j].name + '</li>';
                        }
                        content += '</ul>';
                    }

                    $('#modalContent').html(content);

                    // Добавляем обработчик событий для заголовков массивов
                    $('.array-header').click(function() {
                        var index = $(this).data('index');
                        var elements = $(`.element-list[data-index='${index}'] .element-item`).map(function() {
                            return $('[id$=\"_' + $(this).data('id') + '\"]');
                        });

                        // Сброс цветов предыдущих выбранных элементов
                        resetColors($(currentElements));
                        
                        // Удаление активного класса с текущего заголовка
                        if (currentHeader) {
                            $(currentHeader).removeClass('active-header');
                        }

                        // Обновляем текущие элементы и заголовок, меняем их цвет и добавляем активный класс
                        currentElements = elements;
                        currentHeader = this;
                  
                        changeColor($(currentElements));
                        $(currentHeader).addClass('active-header');
                    });
                } else {
                    $('#modalContent').html('No data found.');
                }
                },
                error: function() {
                    $('#modalContent').html('Error loading data.');
                }
            });
        });



    // Сброс цветов при закрытии модального окна
    $('#minimumCrossSectionModalForm').on('hidden.bs.modal', function () {
        resetColors($(currentElements));
        currentElements = [];
        if (currentHeader) {
            $(currentHeader).removeClass('active-header');
            currentHeader = null;
        }
    });

    



</script>




<?php Modal::end(); 


// Добавление CSS стилей
$this->registerCss("
    .array-header {
        border: 1px solid rgba(0, 0, 0, 0.3);
        border-radius: 5px;
        padding: 5px;
        cursor: pointer;
        
    }
    .array-header:hover, .array-header.active-header {
        background-color: rgba(0, 0, 0, 0.1);
    }
");

?>


