<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\InvoiceRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use App\Controller\InvoiceIncrementationController;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 * @ApiResource(
 *  itemOperations={"GET","DELETE","INCREMENT"={
 *      "method"="post",
 *      "path"="/invoices/{id}/increment",
 *      "controller"=InvoiceIncrementationController::class,
 *      "swagger_context"={
 *          "summary"="incr fact",
 *          "description"="increment de la fact"
 *      }
 *  }
 *  },
 *  normalizationContext = { "groups" = { "myInvoices" }},
 *  denormalizationContext = {"disable_type_enforcement"=true},
 *  subresourceOperations={
 *      "api_customers_invoices_get_subresource" ={
 *          "normalization_context"={ "groups"={"invoices_subresource"} } 
 *      }
 *  },
 *  attributes = {
 *      "pagination_enabled" = true,
 *      "pagination_items_per_page" = 10,
 *  }
 * )
 * 
 *  @ApiFilter( OrderFilter::class, properties={"Amount"} )
 * 
 */
class Invoice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({ "myInvoices" , "myCustomers", "invoices_subresource" })
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({ "myInvoices" , "myCustomers", "invoices_subresource" })
     * @Assert\Type( type="numeric", message="number expected :s " )
     */
    private $Amount;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({ "myInvoices" , "myCustomers", "invoices_subresource"  })
     * @Assert\NotBlank
     */
    private $sentAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "myInvoices", "myCustomers", "invoices_subresource"  })
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="invoices")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({ "myInvoices"  })
     */
    private $customer;

    /**
     * @ORM\Column(type="integer")
     * @Groups({ "myInvoices" })
     */
    private $Chrono;

    /**
     * @Groups({ "myInvoices" , "invoices_subresource" })
     * @return User
     */

    public function getUser() : User
    {
        return $this->customer->getUser();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount()
    {
        return $this->Amount;
    }

    public function setAmount($Amount): self
    {
        $this->Amount = $Amount;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt( $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getChrono(): ?int
    {
        return $this->Chrono;
    }

    public function setChrono(int $Chrono): self
    {
        $this->Chrono = $Chrono;

        return $this;
    }

}
