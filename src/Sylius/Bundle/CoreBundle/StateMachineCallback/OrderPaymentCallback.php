<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\StateMachineCallback;

use Doctrine\Common\Collections\Collection;
use Finite\Factory\FactoryInterface;
use Sylius\Component\Order\OrderTransitions;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\PaymentTransitions;

/**
 * Synchronization between payments and their order.
 *
 * @author Alexandre Bacco <alexandre.bacco@gmail.com>
 */
class OrderPaymentCallback
{
    /**
     * @var EntityRepository
     */
    protected $orderRepository;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory          = $factory;
    }

    public function updateOrderOnPayment(PaymentInterface $payment)
    {
        $order = $payment->getOrder();

        if (null === $order) {
            throw new \RuntimeException(sprintf('Cannot retrieve Order from Payment with id %s', $payment->getId()));
        }

        $total = 0;
        foreach ($order->getPayments() as $payment) {
            $total += $payment->getAmount();
        }

        if ($total === $order->getTotal()) {
            $this->factory->get($order, OrderTransitions::GRAPH)->apply(OrderTransitions::SYLIUS_CONFIRM);
        }
    }

    public function voidPayments(Collection $payments, $transition)
    {
        foreach ($payments as $payment) {
            $this->factory->get($payment, PaymentTransitions::GRAPH)->apply($transition);
        }
    }
}
