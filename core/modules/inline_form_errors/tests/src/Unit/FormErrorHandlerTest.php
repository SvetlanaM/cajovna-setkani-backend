<?php

namespace Drupal\Tests\inline_form_errors\Unit;

use Drupal\Core\Form\FormState;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\inline_form_errors\FormErrorHandler;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\inline_form_errors\FormErrorHandler
 * @group InlineFormErrors
 */
class FormErrorHandlerTest extends UnitTestCase {

  /**
   * The form error handler.
   *
   * @var \Drupal\inline_form_errors\FormErrorHandler
   */
  protected $formErrorHandler;

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $messenger;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $renderer;

  /**
   * The link generator.
   *
   * @var \Drupal\Core\Utility\LinkGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $linkGenerator;

  /**
   * Form for testing.
   *
   * @var array
   */
  protected $testForm;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->renderer = $this->createMock(RendererInterface::class);
    $this->messenger = $this->createMock(MessengerInterface::class);

    $this->formErrorHandler = new FormErrorHandler($this->getStringTranslationStub(), $this->renderer, $this->messenger);

    $this->testForm = [
      '#parents' => [],
      '#form_id' => 'test_form',
      '#array_parents' => [],
    ];
    $this->testForm['test1'] = [
      '#type' => 'textfield',
      '#title' => 'Test 1',
      '#parents' => ['test1'],
      '#array_parents' => ['test1'],
      '#id' => 'edit-test1',
    ];
    $this->testForm['test2'] = [
      '#type' => 'textfield',
      '#title' => 'Test 2 & a half',
      '#parents' => ['test2'],
      '#array_parents' => ['test2'],
      '#id' => 'edit-test2',
    ];
    $this->testForm['fieldset'] = [
      '#parents' => ['fieldset'],
      '#array_parents' => ['fieldset'],
      'test3' => [
        '#type' => 'textfield',
        '#title' => 'Test 3',
        '#parents' => ['fieldset', 'test3'],
        '#array_parents' => ['fieldset', 'test3'],
        '#id' => 'edit-test3',
      ],
    ];
    $this->testForm['test4'] = [
      '#type' => 'textfield',
      '#title' => 'Test 4',
      '#parents' => ['test4'],
      '#array_parents' => ['test4'],
      '#id' => 'edit-test4',
      '#error_no_message' => TRUE,
    ];
    $this->testForm['test5'] = [
      '#type' => 'textfield',
      '#parents' => ['test5'],
      '#array_parents' => ['test5'],
      '#id' => 'edit-test5',
    ];
    $this->testForm['test6'] = [
      '#type' => 'value',
      '#title' => 'Test 6',
      '#parents' => ['test6'],
      '#array_parents' => ['test6'],
      '#id' => 'edit-test6',
    ];
  }

  /**
   * @covers ::handleFormErrors
   * @covers ::displayErrorMessages
   * @covers ::setElementErrorsFromFormState
   */
  public function testErrorMessagesInline() {
    $this->messenger->expects($this->at(0))
      ->method('addError')
      ->with('no title given');
    $this->messenger->expects($this->at(1))
      ->method('addError')
      ->with('element is invisible');
    $this->messenger->expects($this->at(2))
      ->method('addError')
      ->with('this missing element is invalid');
    $this->messenger->expects($this->at(3))
      ->method('addError')
      ->with('3 errors have been found: <ul-comma-list-mock><li-mock>Test 1</li-mock><li-mock>Test 2 &amp; a half</li-mock><li-mock>Test 3</li-mock></ul-comma-list-mock>');

    $this->renderer->expects($this->once())
      ->method('renderPlain')
      ->will($this->returnCallback(function ($render_array) {
        $links = [];
        foreach ($render_array[1]['#items'] as $item) {
          $links[] = htmlspecialchars($item['#title']);
        }
        return $render_array[0]['#markup'] . '<ul-comma-list-mock><li-mock>' . implode($links, '</li-mock><li-mock>') . '</li-mock></ul-comma-list-mock>';
      }));

    $form_state = new FormState();
    $form_state->setErrorByName('test1', 'invalid');
    $form_state->setErrorByName('test2', 'invalid');
    $form_state->setErrorByName('fieldset][test3', 'invalid');
    $form_state->setErrorByName('test4', 'no error message');
    $form_state->setErrorByName('test5', 'no title given');
    $form_state->setErrorByName('test6', 'element is invisible');
    $form_state->setErrorByName('missing_element', 'this missing element is invalid');
    $this->formErrorHandler->handleFormErrors($this->testForm, $form_state);

    // Assert the #errors is populated for proper input.
    $this->assertSame('invalid', $this->testForm['test1']['#errors']);
    $this->assertSame('invalid', $this->testForm['test2']['#errors']);
    $this->assertSame('invalid', $this->testForm['fieldset']['test3']['#errors']);
    $this->assertSame('no error message', $this->testForm['test4']['#errors']);
    $this->assertSame('no title given', $this->testForm['test5']['#errors']);
    $this->assertSame('element is invisible', $this->testForm['test6']['#errors']);
  }

  /**
   * Tests that opting out of Inline Form Errors works.
   */
  public function testErrorMessagesNotInline() {
    $this->messenger->expects($this->exactly(7))
      ->method('addMessage');

    // Asserts all messages are summarized.
    $this->messenger->expects($this->at(0))
      ->method('addMessage')
      ->with('invalid', 'error', FALSE);
    $this->messenger->expects($this->at(1))
      ->method('addMessage')
      ->with('invalid', 'error', FALSE);
    $this->messenger->expects($this->at(2))
      ->method('addMessage')
      ->with('invalid', 'error', FALSE);
    $this->messenger->expects($this->at(3))
      ->method('addMessage')
      ->with('no error message', 'error', FALSE);
    $this->messenger->expects($this->at(4))
      ->method('addMessage')
      ->with('no title given', 'error', FALSE);
    $this->messenger->expects($this->at(5))
      ->method('addMessage')
      ->with('element is invisible', 'error', FALSE);
    $this->messenger->expects($this->at(6))
      ->method('addMessage')
      ->with('this missing element is invalid', 'error', FALSE);

    $this->renderer->expects($this->never())
      ->method('renderPlain');

    $this->testForm['#disable_inline_form_errors'] = TRUE;

    $form_state = new FormState();
    $form_state->setErrorByName('test1', 'invalid');
    $form_state->setErrorByName('test2', 'invalid');
    $form_state->setErrorByName('fieldset][test3', 'invalid');
    $form_state->setErrorByName('test4', 'no error message');
    $form_state->setErrorByName('test5', 'no title given');
    $form_state->setErrorByName('test6', 'element is invisible');
    $form_state->setErrorByName('missing_element', 'this missing element is invalid');
    $this->formErrorHandler->handleFormErrors($this->testForm, $form_state);

    // Assert the #errors is populated for proper input.
    $this->assertSame('invalid', $this->testForm['test1']['#errors']);
    $this->assertSame('invalid', $this->testForm['test2']['#errors']);
    $this->assertSame('invalid', $this->testForm['fieldset']['test3']['#errors']);
    $this->assertSame('no error message', $this->testForm['test4']['#errors']);
    $this->assertSame('no title given', $this->testForm['test5']['#errors']);
    $this->assertSame('element is invisible', $this->testForm['test6']['#errors']);
  }

}
