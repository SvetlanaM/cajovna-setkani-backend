<?php

namespace Drupal\Tests\token\Kernel;

use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\taxonomy\VocabularyInterface;

/**
 * Tests entity tokens.
 *
 * @group token
 */
class EntityTest extends KernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['node', 'taxonomy', 'text'];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Create the default tags vocabulary.
    $vocabulary = Vocabulary::create([
      'name' => 'Tags',
      'vid' => 'tags',
    ]);
    $vocabulary->save();

    $this->installEntitySchema('taxonomy_term');
    $this->installEntitySchema('user');
    $this->installEntitySchema('node');

    $this->vocab = $vocabulary;
  }

  function testEntityMapping() {
    /** @var \Drupal\token\TokenEntityMapperInterface $mapper */
    $mapper = \Drupal::service('token.entity_mapper');
    $this->assertIdentical($mapper->getEntityTypeForTokenType('node'), 'node');
    $this->assertIdentical($mapper->getEntityTypeForTokenType('term'), 'taxonomy_term');
    $this->assertIdentical($mapper->getEntityTypeForTokenType('vocabulary'), 'taxonomy_vocabulary');
    $this->assertIdentical($mapper->getEntityTypeForTokenType('invalid'), FALSE);
    $this->assertIdentical($mapper->getEntityTypeForTokenType('invalid', TRUE), 'invalid');
    $this->assertIdentical($mapper->getTokenTypeForEntityType('node'), 'node');
    $this->assertIdentical($mapper->getTokenTypeForEntityType('taxonomy_term'), 'term');
    $this->assertIdentical($mapper->getTokenTypeForEntityType('taxonomy_vocabulary'), 'vocabulary');
    $this->assertIdentical($mapper->getTokenTypeForEntityType('invalid'), FALSE);
    $this->assertIdentical($mapper->getTokenTypeForEntityType('invalid', TRUE), 'invalid');

    // Test that when we send the mis-matched entity type into
    // Drupal\Core\Utility\Token::replace() that we still get the tokens
    // replaced.
    $vocabulary = Vocabulary::load('tags');
    $term = $this->addTerm($vocabulary);
    $this->assertIdentical(\Drupal::token()->replace('[vocabulary:name]', ['taxonomy_vocabulary' => $vocabulary]), $vocabulary->label());
    $this->assertIdentical(\Drupal::token()->replace('[term:name][term:vocabulary:name]', ['taxonomy_term' => $term]), $term->label() . $vocabulary->label());
  }

  function addTerm(VocabularyInterface $vocabulary, array $term = []) {
    $term += [
      'name' => mb_strtolower($this->randomMachineName(5)),
      'vid' => $vocabulary->id(),
    ];
    $term = Term::create($term);
    $term->save();
    return $term;
  }

  /**
   * Test the [entity:original:*] tokens.
   */
  function testEntityOriginal() {
    $node = Node::create(['type' => 'page', 'title' => 'Original title']);
    $node->save();

    $tokens = [
      'nid' => $node->id(),
      'title' => 'Original title',
      'original' => NULL,
      'original:nid' => NULL,
    ];
    $this->assertTokens('node', ['node' => $node], $tokens);

    // Emulate the original entity property that would be available from
    // node_save() and change the title for the node.
    $node->original = \Drupal::entityTypeManager()->getStorage('node')->loadUnchanged($node->id());
    $node->title = 'New title';

    $tokens = [
      'nid' => $node->id(),
      'title' => 'New title',
      'original' => 'Original title',
      'original:nid' => $node->id(),
    ];
    $this->assertTokens('node', ['node' => $node], $tokens);
  }

}
