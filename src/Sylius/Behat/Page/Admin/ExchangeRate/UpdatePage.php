<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Behat\Page\Admin\ExchangeRate;

use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRatio()
    {
        return $this->getElement('ratio')->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function changeRatio($ratio)
    {
        $this->getElement('ratio')->setValue($ratio);
    }

    /**
     * {@inheritdoc}
     */
    public function isBaseCurrencyDisabled()
    {
        return null !== $this->getElement('baseCurrency')->getAttribute('disabled');
    }

    /**
     * {@inheritdoc}
     */
    public function isCounterCurrencyDisabled()
    {
        return null !== $this->getElement('counterCurrency')->getAttribute('disabled');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'ratio' => '#sylius_exchange_rate_ratio',
            'baseCurrency' => '#sylius_exchange_rate_baseCurrency',
            'counterCurrency' => '#sylius_exchange_rate_counterCurrency',
        ]);
    }
}
