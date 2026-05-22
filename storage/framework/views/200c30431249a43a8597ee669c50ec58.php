<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Plant Availability Request #<?php echo e($request->id); ?></title>
    <style>
        @page {
            margin: 0.4cm;
            size: A4 portrait;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9px;
            line-height: 1.2;
            color: #000;
        }
        .document {
            border: 1px solid #000;
            padding: 5px;
            background: white;
            width: 100%;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 8px;
        }
        .header h1 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 3px 0;
            font-size: 12px;
            font-weight: normal;
        }
        .section {
            margin-bottom: 8px;
        }
        .section h3 {
            margin: 0 0 4px 0;
            font-size: 11px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            text-transform: uppercase;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        table.info td {
            padding: 1px;
            vertical-align: top;
        }
        table.info td:first-child {
            font-weight: bold;
            width: 100px;
        }
        table.items {
            border: 1px solid #000;
            table-layout: fixed;
        }
        table.items th, 
        table.items td {
            border: 1px solid #000;
            padding: 2px;
            text-align: left;
            font-size: 8px;
            vertical-align: middle;
            word-break: normal;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        table.items th {
            font-weight: bold;
            text-align: center;
            background-color: #f5f5f5;
        }
        table.items th:nth-child(1) { width: 5%; }  /* Item No. */
        table.items th:nth-child(2) { width: 5%; }  /* Qty */
        table.items th:nth-child(3) { width: 22%; } /* Plant Name */
        table.items th:nth-child(4) { width: 10%; }  /* Plant Code */
        table.items th:nth-child(5) { width: 8%; }  /* Height */
        table.items th:nth-child(6) { width: 8%; }  /* Spread */
        table.items th:nth-child(7) { width: 8%; }  /* Spacing */
        table.items th:nth-child(8) { width: 24%; } /* Remarks */
        table.items th:nth-child(9) { width: 10%; } /* Availability */
        
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 7px;
            color: #666;
        }
        .vendor-note {
            margin-top: 8px;
            font-size: 8px;
            margin-bottom: 8px;
            background-color: #f9f9f9;
            padding: 8px;
            border: 1px solid #ddd;
        }
        .vendor-note p {
            margin: 2px 0;
        }
        table.items td:nth-child(8) {
            word-wrap: break-word;
            white-space: pre-wrap;
            overflow-wrap: break-word;
        }
        .client-invitation {
            margin-top: 10px;
            padding: 10px;
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 3px;
            font-size: 9px;
        }
        .client-invitation h4 {
            margin: 0 0 5px 0;
            font-size: 10px;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="document">
        <div class="header">
            <h1>SALENGA FARM</h1>
            <h2>PLANT AVAILABILITY REQUEST</h2>
        </div>

        <div class="section">
            <h3>VENDOR INFORMATION</h3>
            <table class="info">
                <tr>
                    <td>Company:</td>
                    <td>
                        ESTHER LIBRES SALENGA ESTHER'S<br>
                        FLOWER GARDEN AND LANDSCAPING
                    </td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td>
                        INFRONT OF FATIMA VILLAGE SITIO<br>
                        MCL.DAVAO CITY.PH
                    </td>
                </tr>
                <tr>
                    <td>TIN:</td>
                    <td>47496058600000</td>
                </tr>
                <tr>
                    <td>E-mail:</td>
                    <td>salengafarm@example.com</td>
                </tr>
            </table>
        </div>

        <table>
            <tr>
                <td><strong>Request Date:</strong> <?php echo e($request->request_date->format('F d, Y')); ?></td>
                <td><strong>Due Date:</strong> <?php echo e($request->due_date->format('F d, Y')); ?></td>
            </tr>
        </table>

        <table>
            <tr>
                <td><strong>User Name:</strong> <?php echo e($request->name); ?></td>
                <td><strong>User Email:</strong> <?php echo e($request->email); ?></td>
            </tr>
        </table>

        <div class="section">
            <table class="items">
                <thead>
                    <tr>
                        <th>Item No.</th>
                        <th>Qty</th>
                        <th>Plant Name</th>
                        <th>Code</th>
                        <th>Height<br><small>(mm)</small></th>
                        <th>Spread<br><small>(mm)</small></th>
                        <th>Spacing<br><small>(mm)</small></th>
                        <th>Remarks</th>
                        <th>Availability</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $request->items_json; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="text-align: center; white-space: nowrap;"><?php echo e($index + 1); ?></td>
                        <td style="text-align: center;"><?php echo e(isset($item['quantity']) ? $item['quantity'] : ''); ?></td>
                        <td><?php echo e($item['name'] ?? ''); ?></td>
                        <td style="text-align: center;"><?php echo e(isset($item['code']) && $item['code'] != 'N/A' ? $item['code'] : ''); ?></td>
                        <td style="text-align: center;"><?php echo e(isset($item['height']) && $item['height'] != '' ? $item['height'] : ''); ?></td>
                        <td style="text-align: center;"><?php echo e(isset($item['spread']) && $item['spread'] != '' ? $item['spread'] : ''); ?></td>
                        <td style="text-align: center;"><?php echo e(isset($item['spacing']) && $item['spacing'] != '' ? $item['spacing'] : ''); ?></td>
                        <td><?php echo nl2br(e($item['remarks'] ?? '')); ?></td>
                        <td style="text-align: center;"><?php echo e($item['availability'] ?? ''); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    
                    <?php for($i = count($request->items_json); $i < 20; $i++): ?>
                        <tr style="height: 12px;">
                            <td style="text-align: center;"><?php echo e($i + 1); ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>

        <div class="vendor-note">
            <p><strong>PLANT AVAILABILITY NOTICE:</strong><br>
            The plants listed above are currently available at Salenga Farm. This document confirms the availability of the requested plants based on your inquiry.</p>
        </div>

        <div class="client-invitation">
            <h4>🌿 INTERESTED IN ORDERING?</h4>
            <p><strong>Become a Client:</strong> If you would like to place an order for these plants, we invite you to register as a client with Salenga Farm. As a registered client, you will receive:</p>
            <ul style="margin: 5px 0; padding-left: 20px;">
                <li>Detailed pricing and quotations</li>
                <li>Priority order processing</li>
                <li>Delivery arrangements</li>
                <li>Ongoing support for your plant needs</li>
            </ul>
            <p><strong>To become a client:</strong> Please contact us at salengafarm@example.com or reply to this email expressing your interest. We will guide you through a simple verification process to set up your client account.</p>
        </div>

        <div class="footer">
            <p>This is an automatically generated availability document. For any questions or to place an order, please contact us at info@salengafarm.com</p>
            <p>Request ID: <?php echo e($request->id); ?> | Generated on: <?php echo e(now()->format('F d, Y H:i:s')); ?></p>
        </div>
    </div>
</body>
</html> <?php /**PATH C:\CODING\my_Inventory\resources\views/pdf/user-request.blade.php ENDPATH**/ ?>