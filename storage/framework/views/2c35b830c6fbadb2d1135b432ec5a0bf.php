<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plant Request #<?php echo e($request->id); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2d5d31;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
        }
        .footer {
            background-color: #e9ecef;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 10px 10px;
            font-size: 14px;
            color: #6c757d;
        }
        .request-info {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #28a745;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background-color: white;
        }
        .items-table th, .items-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .highlight {
            background-color: #fff3cd;
            padding: 15px;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            margin: 15px 0;
        }
        .client-invitation {
            background-color: #d1ecf1;
            padding: 20px;
            border: 2px solid #0c5460;
            border-radius: 5px;
            margin: 20px 0;
        }
        .client-invitation h3 {
            color: #0c5460;
            margin-top: 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌿 Salenga Farm</h1>
        <p>
            <?php if($request->request_type == 'user'): ?>
                Plant Availability Response
            <?php else: ?>
                Plant Request Quotation
            <?php endif; ?>
        </p>
    </div>

    <div class="content">
        <h2>Dear <?php echo e($request->name); ?>,</h2>
        
        <?php if($request->request_type == 'user'): ?>
            <p>Thank you for your interest in our plants! We're pleased to inform you about the availability of the plants you inquired about.</p>
        <?php else: ?>
            <p>Thank you for your interest in our plants! We have prepared a detailed quotation for your plant request.</p>
        <?php endif; ?>
        
        <div class="request-info">
            <h3>Request Details</h3>
            <p><strong>Request ID:</strong> #<?php echo e($request->id); ?></p>
            <p><strong>Request Date:</strong> <?php echo e($request->request_date ? $request->request_date->format('M d, Y') : 'N/A'); ?></p>
            <p><strong>Due Date:</strong> <?php echo e($request->due_date ? $request->due_date->format('M d, Y') : 'N/A'); ?></p>
            <p><strong>Status:</strong> <span style="color: #28a745; font-weight: bold;"><?php echo e(ucfirst($request->status)); ?></span></p>
        </div>

        <?php if($request->items_json && count($request->items_json) > 0): ?>
        <h3>
            <?php if($request->request_type == 'user'): ?>
                Available Plants
            <?php else: ?>
                Requested Plants
            <?php endif; ?>
        </h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Plant Name</th>
                    <th>Quantity</th>
                    <?php if($request->request_type == 'client' && collect($request->items_json)->pluck('unit_price')->filter()->isNotEmpty()): ?>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <?php else: ?>
                    <th>Availability</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $request->items_json; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <strong><?php echo e($item['name'] ?? 'N/A'); ?></strong>
                        <?php if(!empty($item['code'])): ?>
                            <br><small style="color: #666;">Code: <?php echo e($item['code']); ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($item['quantity'] ?? 1); ?></td>
                    <?php if($request->request_type == 'client' && collect($request->items_json)->pluck('unit_price')->filter()->isNotEmpty()): ?>
                    <td>
                        <?php if(!empty($item['unit_price'])): ?>
                            ₱<?php echo e(number_format($item['unit_price'], 2)); ?>

                        <?php else: ?>
                            <em>Quote on request</em>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if(!empty($item['total_price'])): ?>
                            ₱<?php echo e(number_format($item['total_price'], 2)); ?>

                        <?php else: ?>
                            <em>Quote on request</em>
                        <?php endif; ?>
                    </td>
                    <?php else: ?>
                    <td>
                        <span style="color: #28a745; font-weight: bold;"><?php echo e($item['availability'] ?? 'Available'); ?></span>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>

        <?php if($request->request_type == 'client'): ?>
        <div class="highlight">
            <p><strong>📎 Document Attached:</strong> Please find the detailed quotation PDF attached to this email with complete pricing and specifications.</p>
        </div>
        <?php endif; ?>

        <?php if($request->request_type == 'user'): ?>
            <div class="client-invitation">
                <h3>🌿 Interested in Ordering These Plants?</h3>
                <p><strong>Become a Client:</strong> If you would like to place an order for these plants, we invite you to register as a client with Salenga Farm.</p>
                
                <p><strong>As a registered client, you will receive:</strong></p>
                <ul>
                    <li>✓ Detailed pricing and quotations</li>
                    <li>✓ Priority order processing</li>
                    <li>✓ Flexible delivery arrangements</li>
                    <li>✓ Ongoing support for all your plant needs</li>
                    <li>✓ Access to exclusive plant varieties</li>
                </ul>
                
                <p><strong>How to Become a Client:</strong></p>
                <p>Simply reply to this email expressing your interest in becoming a client, or contact us at <strong><?php echo e(config('mail.from.address')); ?></strong>. We will guide you through a simple verification process to set up your client account and provide you with detailed pricing information.</p>
            </div>

            <h3>Next Steps</h3>
            <ul>
                <li>Review the plant availability information above</li>
                <li>Contact us if you have any questions about the plants</li>
                <li>Express your interest in becoming a client to receive pricing and place orders</li>
                <li>We're here to help you find the perfect plants for your needs!</li>
            </ul>
        <?php else: ?>
            <h3>Next Steps</h3>
            <ul>
                <li>Review the attached quotation document</li>
                <li>Contact us if you have any questions about the plants or pricing</li>
                <li>Let us know when you'd like to proceed with the order</li>
                <li>We can arrange delivery or pickup based on your preference</li>
            </ul>
        <?php endif; ?>

        <p>If you have any questions or need clarification on any items, please don't hesitate to contact us. We're here to help you find the perfect plants for your needs!</p>

        <p>Best regards,<br>
        <strong>The Salenga Farm Team</strong></p>
    </div>

    <div class="footer">
        <p>
            <strong>Salenga Farm</strong><br>
            📧 <?php echo e(config('mail.from.address')); ?><br>
            🌐 <?php echo e(config('app.url')); ?><br>
            <br>
            <em>This email was sent regarding your plant request #<?php echo e($request->id); ?></em>
        </p>
    </div>
</body>
</html>
<?php /**PATH C:\CODING\my_Inventory\resources\views/emails/plant-request.blade.php ENDPATH**/ ?>