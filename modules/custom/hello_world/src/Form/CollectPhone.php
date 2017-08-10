<?php

/**
 * @file
 * Contains \Drupal\helloworld\Form\CollectPhone.
 *
 * В комментарии выше указываем, что содержится в данном файле.
 */

namespace Drupal\hello_world\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;



class CollectPhone extends FormBase{

  /**
   * То что ниже - это аннотация. Аннотации пишутся в комментариях и в них
   * объявляются различные данные. В данном случае указано, что документацию
   * к данному методу надо взять из комментария к самому классу.
   *
   * А в самом методе мы возвращаем название нашей формы в виде строки.
   * Эта строка используется для альтера формы (об этом ниже в тексте).
   *
   * {@inheritdoc}.
   */
  public function getFormId(){
    return 'collect_phone';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   * Создание нашей формы.
   *
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state){

    // Объявляем телефон.
    $form['phone_number'] = [
      '#type' => 'tel',
      // Не забываем из Drupal 7, что t, в D8 $this->t() можно использовать
      // только с английскими словами. Иначе не используйте t() а пишите
      // просто строку.
      '#title' => $this->t('Your phone number')
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your name')
    ];

    // Предоставляет обёртку для одного или более Action элементов.
    $form['actions']['#type'] = 'actions';
    // Добавляем нашу кнопку для отправки.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send name and phone'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * Form validation handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state){
    // Если длина имени меньше 5, выводим ошибку.
    if (strlen($form_state->getValue('name')) < 5) {
      $form_state->setErrorByName('name', $this->t('Name is too short.'));
    }
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state){
    // Мы ничего не хотим делать с данными, просто выведем их в системном
    // сообщении.
    drupal_set_message($this->t('Thank you @name, your phone number is @number', array(
      '@name' => $form_state->getValue('name'),
      '@number' => $form_state->getValue('phone_number')
    )));
  }


}