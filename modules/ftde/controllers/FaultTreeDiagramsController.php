<?php

namespace app\modules\ftde\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\bootstrap5\ActiveForm;
use app\modules\main\models\Diagram;
use app\modules\ftde\models\StateProperty;
use app\modules\ftde\models\Transition;
use app\modules\ftde\models\TransitionProperty;
use app\modules\ftde\models\Element;
use app\modules\ftde\models\StateConnection;
use app\components\FaultTreeXMLGenerator;
use app\components\DecisionTableGenerator;
use Psy\Readline\Hoa\ConsoleInput;
use yii\helpers\Console;

/**
 * StateTransitionDiagramsController implements the CRUD actions for State Transition Diagram model.
 */
class FaultTreeDiagramsController extends Controller
{
    public $layout = '@app/modules/main/views/layouts/main';


    
    /**
     * Страница визуального редактора диаграмм переходов состояний.
     *
     * @param $id - id диаграммы перехода состояний
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionVisualDiagram($id)
    {

        $state_model = new Element();
        $state_property_model = new StateProperty();
        $transition_model = new Transition();
        $transition_property_model = new TransitionProperty();

        $states_model_all = Element::find()->where(['diagram' => $id, 'type' => Element::COMMON_FAULT] )->orWhere(['diagram' => $id, 'type' => Element::INITIAL_FAULT])->all();

        $states_property_all = StateProperty::find()->all();
        $states_property_model_all = array();//массив связей
        foreach ($states_property_all as $sp){
            foreach ($states_model_all as $s){
                if ($sp->fault == $s->id){
                    array_push($states_property_model_all, $sp);
                }
            }
        }

        $transitions_all = Transition::find()->all();
        $transitions_model_all = array();//массив связей
        foreach ($transitions_all as $t){
            foreach ($states_model_all as $s){
                if ($t->state_from == $s->id){
                    array_push($transitions_model_all, $t);
                }
            }
        }

        $transitions_property_all = TransitionProperty::find()->all();
        $transitions_property_model_all = array();//массив условий
        foreach ($transitions_property_all as $p){
            foreach ($transitions_model_all as $t){
                if ($p->transition == $t->id){
                    array_push($transitions_property_model_all, $p);
                }
            }
        }

        //экспорт диаграммы
        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->post('value', null) == 'xml'){
                $code_generator = new FaultTreeXMLGenerator();
                $code_generator->generateSTDXMLCode($id);
            }
            if (Yii::$app->request->post('value', null) == 'csv'){
                $code_generator = new DecisionTableGenerator();
                $code_generator->generateCSVCode($id);
            }
        }

        //все связи между StartToEnd и State
        $state_connections_all = StateConnection::find()->all();

        //начало диаграммы
        $start_model = Element::find()->where(['diagram' => $id, 'type' => Element::START_TYPE])->all();
        //связи между началом StartToEnd и State диаграммы
        $states_connection_start_model_all = array();//массив связей
        if ($start_model != null){
            foreach ($state_connections_all as $sc){
                foreach ($start_model as $sm){
                    if ($sc->element_from == $sm->id) {
                        array_push($states_connection_start_model_all, $sc);
                    }
                }
            }
        }

        $states_connection_fault_model_all = array();//массив связей
        if ($states_model_all != null){
            foreach ($state_connections_all as $sc){
                foreach ($states_model_all as $s){
                    if ($sc->element_from == $s->id){
                        array_push($states_connection_fault_model_all, $sc);
                    }
                }
            }
        }



        // $states_connection_fault_model_all = array();//массив связей
        // if ($state_model != null){
        //     foreach ($state_connections_all as $sc){
        //         if ($sc->element_from == $states_model_all->id) {
        //             array_push($states_connection_fault_model_all, $sc);
        //         }
        //     }
        // }

        //завершение диаграммы
        $end_model = Element::find()->where(['diagram' => $id, 'type' => Element::END_TYPE])->all();
        //связи между завершением StartToEnd и State диаграммы
        $states_connection_end_model_all = array();//массив связей
        if ($end_model != null){
            foreach ($state_connections_all as $sc){
                foreach ($end_model as $em){
                    if ($sc->element_from == $em->id) {
                        array_push($states_connection_end_model_all, $sc);
                    }
                }
            }
        }

        //базовое событие
        $basic_event_model = Element::find()->where(['diagram' => $id, 'type' => Element::BASIC_EVENT])->all();
        $connection_basic_event_model_all = array();//массив связей
        if ($states_model_all != null){
            foreach ($state_connections_all as $sc){
                foreach ($basic_event_model as $be){
                    if ($sc->element_from == $be->id){
                        array_push($states_connection_fault_model_all, $sc);
                    }
                }
            }
        }

        $start_count = Element::find()->where(['diagram' => $id, 'type' => Element::START_TYPE])->count();//количество начал
        $end_count = Element::find()->where(['diagram' => $id, 'type' => Element::END_TYPE])->count();//количество завершений

        return $this->render('visual-diagram', [
            'model' => $this->findModel($id),
            'state_model' => $state_model,
            'state_property_model' => $state_property_model,
            'transition_model' => $transition_model,
            'transition_property_model' => $transition_property_model,
            'states_model_all' => $states_model_all,
            'states_property_model_all' => $states_property_model_all,
            'transitions_model_all' => $transitions_model_all,
            'transitions_property_model_all' => $transitions_property_model_all,
            'start_model' => $start_model,
            'end_model' => $end_model,
            'states_connection_start_model_all' => $states_connection_start_model_all,
            'states_connection_end_model_all' => $states_connection_end_model_all,
            'states_connection_fault_model_all' => $states_connection_fault_model_all,
            'start_count' => $start_count,
            'end_count' => $end_count,
            'basic_event_model' => $basic_event_model,
            'connection_basic_event_model_all' => $connection_basic_event_model_all
        ]);
    }

    /**
     * Finds the Diagram model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param $id
     * @return Diagram|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Diagram::findOne($id)) !== null)
            return $model;

        throw new NotFoundHttpException('The requested page does not exist.');
    }

  
    /**
     * Добавление нового состояния.
     *
     * @param $id - id дерева событий
     * @return bool|\yii\console\Response|Response
     */
    public function actionAddState($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели уровня
            $model = new Element();
            // Задание id диаграммы
            $model->diagram = $id;
            $model->type = Element::INITIAL_FAULT;
            // Определение полей модели уровня и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Условие проверки является ли состояние инициирующим
                $i = Element::find()->where(['diagram' => $id, 'type' => Element::INITIAL_FAULT])->count();
                // Если инициирующие состояние есть
                if ($i > '0') {
                    // Тип присваивается константа "COMMON_STATE_TYPE" как обычное состояние
                    $model->type = Element::COMMON_FAULT;
                } else {
                    // Тип присваивается константа "INITIAL_STATE_TYPE" как начальное (инициирующее) состояния
                    $model->type = Element::INITIAL_FAULT;
                }

                // Успешный ввод данных
                $data["success"] = true;
                // Добавление нового состояния в БД
                $model->save();
                // Формирование данных о новом состоянии
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["indent_x"] = $model->indent_x;
                $data["indent_y"] = $model->indent_y;
            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


     /**
     * Добавление нового базвовго события.
     *
     * @param $id - id дерева 
     * @return bool|\yii\console\Response|Response
     */
    public function actionAddBasicEvent($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели уровня
            $model = new Element();
            // Задание id диаграммы
            $model->diagram = $id;
            $model->type = Element::BASIC_EVENT;
            // Определение полей модели уровня и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Условие проверки является ли состояние инициирующим

                // Успешный ввод данных
                $data["success"] = true;
                // Добавление нового состояния в БД
                $model->save();
                // Формирование данных о новом состоянии
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["indent_x"] = $model->indent_x;
                $data["indent_y"] = $model->indent_y;
            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }




    /**
     * Изменение состояния.
     */
    public function actionEditState()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Element::find()->where(['id' => Yii::$app->request->post('state_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Формирование данных об измененном событии
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }

     /**
     * Изменение состояния.
     */
    public function actionEditBasicEvent()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Element::find()->where(['id' => Yii::$app->request->post('basic_event_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Формирование данных об измененном событии
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Удаление состояния.
     */
    public function actionDeleteState()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $state_id_on_click = Yii::$app->request->post('state_id_on_click');

            $state = Element::find()->where(['id' => $state_id_on_click])->one();
            $state -> delete();

            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }

       /**
     * Удаление базового соббытия.
     */
    public function actionDeleteBasicEvent()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $basic_event_id_on_click = Yii::$app->request->post('basic_event_id_on_click');

            $state = Element::find()->where(['id' => $basic_event_id_on_click])->one();
            $state -> delete();

            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Копирование состояния.
     */
    public function actionCopyState()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $state = Element::find()->where(['id' => Yii::$app->request->post('state_id_on_click')])->one();

            // Формирование модели состояния
            $model = new Element();
            // Задание id диаграммы
            $model->diagram = $state->diagram;
            // Присваивает новому состоянию местопоожение правее копируемого
            $model->indent_x = $state->indent_x + 160;
            $model->indent_y = $state->indent_y;

            // Определение полей модели уровня и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Тип присваивается константа "COMMON_STATE_TYPE" как обычное состояние
                $model->type = Element::COMMON_FAULT;
                // Добавление нового состояния в БД
                $model->save();

                $i = 0;
                //копирование свойств состояний
                $state_property = StateProperty::find()->where(['state' => $state->id])->all();
                foreach ($state_property as $sp){
                    $new_state_property = new StateProperty();
                    $new_state_property->name = $sp->name;
                    $new_state_property->description = $sp->description;
                    $new_state_property->operator = $sp->operator;
                    $new_state_property->value = $sp->value;
                    $new_state_property->state = $model->id;
                    $new_state_property->save();

                    $data["state_property_id_$i"] = $new_state_property->id;
                    $data["state_property_name_$i"] = $new_state_property->name;
                    $data["state_property_description_$i"] = $new_state_property->description;
                    $data["state_property_operator_$i"] = $new_state_property->operator;
                    $data["state_property_operator_name_$i"] = $new_state_property->getOperatorName();
                    $data["state_property_value_$i"] = $new_state_property->value;

                    $i = $i + 1;
                }

                // Успешный ввод данных
                $data["success"] = true;

                // Формирование данных о новом состоянии
                $data["id"] = $model->id;

                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["indent_x"] = $model->indent_x;
                $data["indent_y"] = $model->indent_y;
                $data["i"] = $i;
            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление нового свойства состояния.
     *
     * @param $id - id дерева событий
     * @return bool|\yii\console\Response|Response
     */
    public function actionAddFaultProperty()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели свойства состояния
            $model = new StateProperty();

            $model->fault = Yii::$app->request->post('state_id_on_click');

            //поиск количества свойст у выбранного состояния
            $state_property_count = StateProperty::find()->where(['fault' => Yii::$app->request->post('state_id_on_click')])->count();
            $data["state_property_count"] = $state_property_count;

            // Определение полей модели уровня и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Добавление нового уровня в БД
                $model->save();
                // Формирование данных о новом уровне
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["operator"] = $model->operator;
                $data["operator_name"] = $model->getOperatorName();
                $data["value"] = $model->value;
                $data["fault"] = $model->fault;

            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Изменение свойства состояния.
     */
    public function actionEditFaultProperty()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = StateProperty::find()->where(['id' => Yii::$app->request->post('state_property_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;

                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["operator_name"] = $model->getOperatorName();
                $data["operator"] = $model->operator;
                $data["value"] = $model->value;

            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Удаление свойства состояния.
     */
    public function actionDeleteFaultProperty()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = StateProperty::find()->where(['id' => Yii::$app->request->post('state_property_id_on_click')])->one();
            $state_id = $model->fault;
            $model -> delete();

            //поиск количества свойст у выбранного состояния
            $state_property_count = StateProperty::find()->where(['fault' => $state_id])->count();
            $data["state_property_count"] = $state_property_count;
            $data["state_id"] = $state_id;

            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление нового перехода.
     *
     */
    public function actionAddTransition()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            // Формирование модели перехода
            $model = new Transition();

            $model->state_from = Yii::$app->request->post('id_state_from');
            $model->state_to = Yii::$app->request->post('id_state_to');

            // Определение полей модели перехода и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Добавление нового перехода в БД
                $model->save();
                // Формирование данных о новом переходе
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["state_from"] = $model->state_from;
                $data["state_to"] = $model->state_to;

                // ----------Формирование модели условия
                $transition_property = new TransitionProperty();
                $transition_property->name = $model->name_property;
                $transition_property->description = $model->description_property;
                $transition_property->operator = $model->operator_property;
                $transition_property->value = $model->value_property;
                $transition_property->transition = $model->id;
                $transition_property->save();

                // --------------Формирование данных о новом переходе
                $data["id_property"] = $transition_property->id;
                $data["name_property"] = $transition_property->name;
                $data["description_property"] = $transition_property->description;
                $data["operator_property"] = $transition_property->getOperatorName();
                $data["value_property"] = $transition_property->value;

            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Изменение перехода.
     */
    public function actionEditTransition()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Transition::find()->where(['id' => Yii::$app->request->post('transition_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Формирование данных об измененном событии
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["state_from"] = $model->state_from;
                $data["state_to"] = $model->state_to;
            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Удаление перехода.
     */
    public function actionDeleteTransition()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Transition::find()->where(['id' => Yii::$app->request->post('transition_id_on_click')])->one();
            $data["state_from"] = $model->state_from;
            $data["state_to"] = $model->state_to;
            $model -> delete();

            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление нового условия.
     *
     */
    public function actionAddTransitionProperty()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели уровня
            $model = new TransitionProperty();

            $model->transition = Yii::$app->request->post('transition_id_on_click');

            //поиск количества условий
            $transition_property_count = TransitionProperty::find()->where(['transition' => Yii::$app->request->post('transition_id_on_click')])->count();
            $data["transition_property_count"] = $transition_property_count;

            // Определение полей модели уровня и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Добавление нового уровня в БД
                $model->save();
                // Формирование данных о новом уровне
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["operator"] = $model->operator;
                $data["operator_name"] = $model->getOperatorName();
                $data["value"] = $model->value;
                $data["transition"] = $model->transition;

            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Изменение условия.
     */
    public function actionEditTransitionProperty()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = TransitionProperty::find()->where(['id' => Yii::$app->request->post('transition_property_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;

                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["operator_name"] = $model->getOperatorName();
                $data["operator"] = $model->operator;
                $data["value"] = $model->value;

            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Удаление условия.
     */
    public function actionDeleteTransitionProperty()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = TransitionProperty::find()->where(['id' => Yii::$app->request->post('transition_property_id_on_click')])->one();
            $transition_id = $model->transition;
            $model -> delete();

            //поиск количества свойст у выбранного состояния
            $transition_property_count = TransitionProperty::find()->where(['transition' => $transition_id])->count();
            $data["transition_property_count"] = $transition_property_count;
            $data["transition_id"] = $transition_id;

            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Сохранение отступов.
     *
     */
    public function actionSaveIndent()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $state = Element::find()->where(['id' => Yii::$app->request->post('state_id')])->one();
            $state->indent_x = Yii::$app->request->post('indent_x');
            $state->indent_y = Yii::$app->request->post('indent_y');
            $state->updateAttributes(['indent_x']);
            $state->updateAttributes(['indent_y']);

            $data["indent_x"] = $state->indent_x;
            $data["indent_y"] = $state->indent_y;
            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление начала.
     *
     * @param $id - id дерева перехода состояний
     * @return bool|\yii\console\Response|Response
     */
    public function actionAddStart($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели уровня
            $model = new Element();
            // Задание id диаграммы
            $model->diagram = $id;
            $model->type = Element::START_TYPE;
            // Успешный ввод данных
            $data["success"] = true;
            // Добавление нового состояния в БД
            $model->save();
            $data["id"] = $model->id;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Удаление начала.
     *
     * @param $id - id дерева перехода состояний
     * @return bool|\yii\console\Response|Response
     */
    public function actionDeleteStart()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $start = Element::find()->where(['id' => Yii::$app->request->post('id_start')])->one();
            $start_id = $start->id;
            $start -> delete();

            // Успешный ввод данных
            $data["success"] = true;
            $data["id"] = $start_id;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление завешения.
     *
     * @param $id - id дерева перехода состояний
     * @return bool|\yii\console\Response|Response
     */
    public function actionAddEnd($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели уровня
            $model = new Element();
            // Задание id диаграммы
            $model->diagram = $id;
            $model->type = Element::END_TYPE;
            // Успешный ввод данных
            $data["success"] = true;
            // Добавление нового состояния в БД
            $model->save();
            $data["id"] = $model->id;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Удаление завершения.
     *
     * @param $id - id дерева перехода состояний
     * @return bool|\yii\console\Response|Response
     */
    public function actionDeleteEnd()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $end = Element::find()->where(['id' => Yii::$app->request->post('id_end')])->one();
            $end_id = $end->id;
            $end -> delete();

            // Успешный ввод данных
            $data["success"] = true;
            $data["id"] = $end_id;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление связи с началом
     *
     * @return bool|\yii\console\Response|Response
     */
    public function actionStartConnection()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            // Формирование модели уровня
            $model = new StateConnection();
            $model->element_from = Yii::$app->request->post('element_from');
            $model->element_to = Yii::$app->request->post('element_to');

            // Успешный ввод данных
            $data["success"] = true;
            // Добавление нового состояния в БД
            $model->save();
            $data["id"] = $model->id;
            $data["element_from"] = $model->element_from;
            $data["element_to"] = $model->element_to;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление связи из OR
     *
     * @return bool|\yii\console\Response|Response
     */
    public function actionOrOutConnection()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            // Формирование модели уровня
            $model = new StateConnection();
            $model->element_from = Yii::$app->request->post('element_from');
            $model->element_to = Yii::$app->request->post('element_to');

            // Успешный ввод данных
            $data["success"] = true;
            // Добавление нового состояния в БД
            $model->save();
            $data["id"] = $model->id;
            $data["element_from"] = $model->element_from;
            $data["element_to"] = $model->element_to;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


     /**
     * Добавление связи Fault
     *
     * @return bool|\yii\console\Response|Response
     */
    public function actionFaultConnection()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            // Формирование модели уровня
            $model = new StateConnection();
            $model->element_from = Yii::$app->request->post('element_from');
            $model->element_to = Yii::$app->request->post('element_to');

            // Успешный ввод данных
            $data["success"] = true;
            // Добавление нового состояния в БД
            $model->save();
            $data["id"] = $model->id;
            $data["element_from"] = $model->element_from;;
            $data["element_to"] = $model->element_to;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление связи с завешением
     *
     * @return bool|\yii\console\Response|Response
     */
    public function actionEndConnection()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            // Формирование модели уровня
            $model = new StateConnection();
            $model->element_from = Yii::$app->request->post('element_from');
            $model->element_to = Yii::$app->request->post('element_to');

            // Успешный ввод данных
            $data["success"] = true;
            // Добавление нового состояния в БД
            $model->save();
            $data["id"] = $model->id;
            $data["element_from"] = $model->element_from;
            $data["element_to"] = $model->element_to;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


        /**
     * Добавление связи с завешением
     *
     * @return bool|\yii\console\Response|Response
     */
    public function actionFaultAndConnection()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            // Формирование модели уровня
            $model = new StateConnection();
            $model->element_from = Yii::$app->request->post('element_from');
            $model->element_to = Yii::$app->request->post('element_to');

            // Успешный ввод данных
            $data["success"] = true;
            // Добавление нового состояния в БД
            $model->save();
            $data["id"] = $model->id;
            $data["element_from"] = $model->element_from;
            $data["element_to"] = $model->element_to;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Удаление связи начала или завершения с состоянием
     *
     * @return bool|\yii\console\Response|Response
     */
    public function actionDelStateConnection()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
 
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $element_from = Yii::$app->request->post('element_from');
            $element_to = Yii::$app->request->post('element_to');
            $source_id = Yii::$app->request->post('source_id');
            $target_id = Yii::$app->request->post('target_id');

            $state_connection = StateConnection::find()->where(['element_from' => $element_from, 'element_to' => $element_to])->one();
            // $element_from_type = Element::find()->where(['id' => $element_from])->one();
            // $element_to_type = Element::find()->where(['id' => $element_to])->one();
            $state_connection_id = $state_connection->id;
            $state_connection -> delete();

            // Успешный ввод данных
            $data["success"] = true;
            // Добавление нового состояния в БД
            $data["id"] = $state_connection_id;
            // $data["element_from_type"] = $element_from_type->type;
            // $data["element_to_type"] = $element_to_type->type;
            $data["source_id"] = $source_id;
            $data["target_id"] = $target_id;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }

    


    /**
     * Сохранение отступов начала и завершения.
     *
     */
    public function actionSaveIndentStartOrEnd()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $start_to_end = Element::find()->where(['id' => Yii::$app->request->post('start_or_end_id')])->one();
            $start_to_end->indent_x = Yii::$app->request->post('indent_x');
            $start_to_end->indent_y = Yii::$app->request->post('indent_y');
            $start_to_end->updateAttributes(['indent_x']);
            $start_to_end->updateAttributes(['indent_y']);

            $data["indent_x"] = $start_to_end->indent_x;
            $data["indent_y"] = $start_to_end->indent_y;
            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }



    
    /**
     * Удаление связи начала или завершения с состоянием
     *
     * @return bool|\yii\console\Response|Response
     */
    public function actionDelConnection()
    { 
    
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $element_from = Yii::$app->request->post('element_from');
            $element_to = Yii::$app->request->post('element_to');
            
            $state_connection = StateConnection::find()->where(['element_from' => $element_from, 'element_to' => $element_to])->one();
            $state_connection_id = $state_connection->id;
           
            $state_connection -> delete();

            // Успешный ввод данных
            $data["success"] = true;
            // Добавление нового состояния в БД
            $data["id"] = $state_connection_id;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }




    
}