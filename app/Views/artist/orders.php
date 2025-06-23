<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container my-5">
    <h2 style="margin-top: 20vh;">Manage Orders</h2>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Buyer</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= esc($order['product_title']) ?></td>
                <td><?= esc($order['buyer_name']) ?></td>
                <td><?= esc($order['quantity']) ?></td>
                <td>
                    <?php if ($order['status'] == 'pending'): ?>
                        <span class="badge bg-warning">Pending</span>
                    <?php elseif ($order['status'] == 'accepted'): ?>
                        <span class="badge bg-success">Accepted</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Rejected</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($order['status'] == 'pending'): ?>
                        <a href="<?= base_url('artist/orders/accept/' . $order['id']) ?>" class="btn btn-success btn-sm">Accept</a>
                        <a href="<?= base_url('artist/orders/reject/' . $order['id']) ?>" class="btn btn-danger btn-sm">Reject</a>
                    <?php else: ?>
                        <span>-</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection(); ?>
