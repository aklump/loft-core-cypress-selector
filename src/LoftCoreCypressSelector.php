<?php
// SPDX-License-Identifier: BSD-3-Clause
declare(strict_types=1);

namespace AKlump\DomTestingSelectors\Selector;

use AKlump\Bem\Fluent\Bem;
use AKlump\Bem\Styles\OfficialPassThrough;
use AKlump\DomTestingSelectors\Exception\UnnamedSelectorException;
use AKlump\DomTestingSelectors\Selector\ElementSelectorInterface;

final class LoftCoreCypressSelector implements ElementSelectorInterface {

  /**
   * @var string
   */
  private $group = '';

  /**
   * @var string
   */
  private $name = '';

  /**
   * Return the string testing selector ready for HTML.
   *
   * @param string $target_element_name
   * @param string $current_attribute_value
   *
   * @return string
   */
  public function __invoke(string $target_element_name, string $current_attribute_value = ''): string {
    $this->setName($target_element_name);

    return sprintf('%s="%s"', $this->getAttributeName(), $this->getAttributeValue($current_attribute_value));
  }

  /**
   * {@inheritdoc}
   */
  public function getGroup(): string {
    return $this->group;
  }

  /**
   * {@inheritdoc}
   */
  public function setGroup(string $group): ElementSelectorInterface {
    $this->group = $group;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   * @see \AKlump\DomTestingSelectors\Selector\AbstractSelector::applyNamingConventions()
   */
  public function setName(string $name): ElementSelectorInterface {
    $this->name = $name;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAttributeValue(string $current_value = ''): string {
    $name = $this->getName();
    if (empty($name)) {
      throw new UnnamedSelectorException();
    }
    if ($this->group) {
      $bem = new Bem($this->group, NULL, new OfficialPassThrough());
      $value = $bem->element($this->name)->toString();
    }
    else {
      $bem = new Bem($this->name, NULL, new OfficialPassThrough());
      $value = $bem->block()->toString();
    }

    return $value;
  }

  public function getAttributeName(): string {
    return 'data-testid';
  }

}
