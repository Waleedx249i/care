<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="paymentForm" method="post" action="">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Record Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label>Invoice</label>
            <input type="text" id="paymentInvoiceId" class="form-control" readonly>
          </div>
          <div class="mb-2">
            <label>Remaining</label>
            <input type="text" id="paymentRemaining" class="form-control" readonly>
          </div>
          <div class="mb-2">
            <label>Amount</label>
            <input type="number" step="0.01" name="amount" id="paymentAmount" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Method</label>
            <select name="method" class="form-select" required>
              <option value="card">Card</option>
              <option value="cash">Cash</option>
              <option value="bank">Bank</option>
            </select>
          </div>
          <div class="mb-2">
            <label>Reference</label>
            <input type="text" name="reference" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var paymentModal = document.getElementById('paymentModal');
    paymentModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var invoiceId = button.getAttribute('data-invoice-id');
        var net = parseFloat(button.getAttribute('data-invoice-net') || 0);
        var paid = parseFloat(button.getAttribute('data-invoice-paid') || 0);
        var remaining = (net - paid).toFixed(2);

        var form = document.getElementById('paymentForm');
        form.action = '/patient/invoices/' + invoiceId + '/payments';
        document.getElementById('paymentInvoiceId').value = invoiceId;
        document.getElementById('paymentRemaining').value = remaining;
        document.getElementById('paymentAmount').value = remaining;
    });
});
</script>
