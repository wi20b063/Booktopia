<tr>
    <td>" + orderId + "</td>
    <td>" + orderDate + "</td>
    <td>" + deliveryDate + "</td>
    <td>" + deliveryAddress + "</td>
    <td class='deliveryStatus" + deliveryStatus + "'>" + deliveryStatus + "</td>
    <td>" + quantity + "</td>
    <td>" + totalPrice + "</td>
    <td><button type='button' id='orderDetails" + orderId + "' class='btn btn-primary showOrderDetails'>Details</button>
    </td>
    <td><button type='button' id='invoiceByOrderId" + orderId + "' class='btn btn-primary showInvoiceCustomer'>Rechnung
            drucken</button></td>
</tr>


data-bs-toggle='modal' data-bs-target='#showOrderDetails'