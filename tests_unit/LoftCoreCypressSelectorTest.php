<?php

namespace AKlump\DomTestingSelectors\Tests\Unit;

use AKlump\DomTestingSelectors\Exception\UnnamedSelectorException;
use AKlump\DomTestingSelectors\Selector\LoftCoreCypressSelector;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\DomTestingSelectors\Selector\LoftCoreCypressSelector
 */
class LoftCoreCypressSelectorTest extends TestCase {

  public function testInvokeGroup() {
    $selector = new LoftCoreCypressSelector();
    $selector->setGroup('ordo');
    $result = $selector('lorem', '');
    $this->assertSame('data-testid="ordo__lorem"', $result);
  }
  public function testInvokeNoGroup() {
    $selector = new LoftCoreCypressSelector();
    $result = $selector('lorem', '');
    $this->assertSame('data-testid="lorem"', $result);
  }

  public static function dataFortestGetAttributeValue(): array {
    $tests = [];
    $tests[] = ['evergreen', '', 'evergreen', ''];
    $tests[] = ['trees__evergreen', '', 'evergreen', 'trees'];
    $tests[] = [
      'we.can.not.guess__some.time',
      '',
      'some.time',
      'we.can.not.guess',
    ];
    $tests[] = [
      'we-can-not-guess__some-time',
      '',
      'some-time',
      'we-can-not-guess',
    ];
    $tests[] = [
      'we__can__not__guess__some__time',
      '',
      'some__time',
      'we__can__not__guess',
    ];

    return $tests;
  }

  /**
   * @dataProvider dataFortestGetAttributeValue
   */
  public function testGetAttributeValue(string $expected, string $current_value, string $name, string $group) {
    $selector = new LoftCoreCypressSelector();
    $selector->setName($name);
    $selector->setGroup($group);
    $value = $selector->getAttributeValue($current_value);
    $this->assertSame($expected, $value);
  }

  public function testGetAttributeValueThrowsWithoutName() {
    $selector = new LoftCoreCypressSelector();
    $this->expectException(UnnamedSelectorException::class);
    $selector->getAttributeValue('');
  }

  public function testGetAttributeName() {
    $this->assertSame('data-testid', (new LoftCoreCypressSelector())->getAttributeName());
  }

  public function testGroup() {
    $selector = new LoftCoreCypressSelector();
    $this->assertSame('lorem', $selector->setGroup('lorem')->getGroup());
  }

  public function testName() {
    $selector = new LoftCoreCypressSelector();
    $this->assertSame('ipsum', $selector->setName('ipsum')->getName());
  }
}
