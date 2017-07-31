<?php

/**
 * @file
 * Contains \Drupal\test_block\Plugin\Block\TestBlock.
 */


namespace Drupal\test_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Asset;


/**
 * Добавляем простой блок с текстом.
 * Ниже - аннотация, она также обязательна.
 *
 * @Block(
 *   id = "test_block",
 *   admin_label = @Translation("Test block example"),
 * )
 */
class TestBlock extends BlockBase{


  /**
   * Добавляем наши конфиги по умолчанию.
   *
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array(
      'count' => 5,
      'message' => 'Hello World!',
    );
  }

  /**
   * Добавляем в стандартную форму блока свои поля.
   *
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    // Получаем оригинальную форму для блока.
    $form = parent::blockForm($form, $form_state);
    // Получаем конфиги для данного блока.
    $config = $this->getConfiguration();

    //dpm($config);



    // Добавляем поле для ввода сообщения.
    $form['message'] = array(
      '#type' => 'textfield',
      '#title' => t('Message to printing'),
      '#default_value' => $config['message'],
    );

    // Добавляем поле для количества сообщений.
    $form['count'] = array(
      '#type' => 'number',
      '#min' => 1,
      '#title' => t('How many times display a message'),
      '#default_value' => $config['count'],
    );




    $form['photo'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Managed <em>@type</em>', ['@type' => 'file & butter']),
      '#upload_location' => 'public://test',
      '#progress_message' => $this->t('Please wait...'),
      '#default_value' => $config['photo'],
    ];


    return $form;
  }

  /**
   * Валидируем значения на наши условия.
   * Количество должно быть >= 1,
   * Сообщение должно иметь минимум 5 символов.
   *
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    $count = $form_state->getValue('count');
    $message = $form_state->getValue('message');

    // Проверяем введенное число.
    if (!is_numeric($count) || $count < 1) {
      $form_state->setErrorByName('count', t('Needs to be an interger and more or equal 1.'));
    }

    // Проверяем на длину строки.
    if (strlen($message) < 5) {
      $form_state->setErrorByName('message', t('Message must contain more than 5 letters'));
    }
  }

  /**
   * В субмите мы лишь сохраняем наши данные.
   *
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['count'] = $form_state->getValue('count');
    $this->configuration['message'] = $form_state->getValue('message');

    $photo = $form_state->getValue('photo');
    $this->configuration['photo'] = $photo;


    /* Load the object of the file by it's fid */
    $file = File::load($photo[0]);

    if (!empty($file)) {
      /* Set the status flag permanent of the file object */
      $file->setPermanent();

      /* Save the file in database */
      $file->save();

      $file_usage = \Drupal::service('file.usage');
      $file_usage->add($file, 'test_block', 'test_block', \Drupal::currentUser()->id());
    }
  }


  /**
   * Генерируем и выводим содержимое блока.
   *
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    $message = '';

    for ($i = 1; $i <= $config['count']; $i++) {
      $message .= $config['message'] . '<br />';
    }

    $file = File::load($config['photo'][0]);


    //drupal 7
    //$image = theme('image_style', array('style_name' => 'thumbnail', 'path' => 'public://my-image.png'));


    if (!empty($file)) {
      //dpm($file);
      //original
      //$path = file_create_url($file->getFileUri());

      $style = \Drupal::entityTypeManager()->getStorage('image_style')->load('thumbnail');
      $path = $style->buildUrl($file->getFileUri());
    }

    $message .= '<img src="'.$path.'" />';



    $block = [
      '#type' => 'markup',
      '#markup' => $message,
    ];
    return $block;
  }
}
