<?php
//
//namespace App\Entities;
//
//use Doctrine\ORM\Mapping as ORM;
//
///**
// * InvoiceCharge
// *
// * @ORM\Table(name="invoice_charges")
// * @ORM\Entity(repositoryClass="App\Repositories\InvoiceChargeRepository")
// */
//class InvoiceCharge extends Charge 
//{
//    const TYPE_INVOICED = 5;
//
//    /**
//     * @var Account 
//     *
//     * @ORM\ManyToOne(targetEntity="App\Entities\Order", inversedBy="invoiceCharges")
//     */
//    private $order;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(name="invoice", type="string")
//     */
//    private $invoice;
//
//    /**
//     * Get invoice.
//     *
//     * @return string
//     */
//    public function getInvoice()
//    {
//        return $this->invoice;
//    }
//
//    /**
//     * Set invoice.
//     *
//     * @param string $invoice
//     *
//     * @return InvoiceCharge
//     */
//    public function setInvoice($invoice)
//    {
//        $this->invoice = $invoice;
//
//        return $this;
//    }
//
//    /**
//     * Set order.
//     *
//     * @param Order $order
//     *
//     * @return Order
//     */
//    public function setOrder(Order $order)
//    {
//        $this->order = $order;
//
//        return $this;
//    }
//
//    /**
//     * Get order.
//     *
//     * @return Order 
//     */
//    public function getOrder()
//    {
//        return $this->order;
//    }
//}
