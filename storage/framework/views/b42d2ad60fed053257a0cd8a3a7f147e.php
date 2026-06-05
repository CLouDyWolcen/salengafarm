<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Request for Quotation</title>
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
        table.items th:nth-child(3) { width: 20%; } /* Plant Name */
        table.items th:nth-child(4) { width: 9%; }  /* Plant Code */
        table.items th:nth-child(5) { width: 7%; }  /* Height */
        table.items th:nth-child(6) { width: 7%; }  /* Spread */
        table.items th:nth-child(7) { width: 7%; }  /* Spacing */
        table.items th:nth-child(8) { width: 20%; } /* Remarks */
        table.items th:nth-child(9) { width: 8%; }  /* Unit Price */
        table.items th:nth-child(10) { width: 8%; } /* Total Price */
        table.items th:nth-child(11) { width: 4%; } /* Availability */
        
        .terms {
            margin-top: 8px;
        }
        .terms h3 {
            margin: 0 0 4px 0;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .terms ol {
            margin: 0;
            padding: 0 0 0 15px;
        }
        .terms li {
            font-size: 8px;
            margin-bottom: 1px;
        }
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
        }
        .vendor-note p {
            margin: 2px 0;
        }
        table.items td:nth-child(8) {
            word-wrap: break-word;
            white-space: pre-wrap;
            overflow-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="document">
        <div class="header">
            <h1>SALENGA FARM</h1>
            <h2>REQUEST FOR QUOTATION (RFQ)</h2>
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
                <td><strong>RFQ DATE:</strong> <?php echo e($request->request_date->format('F d, Y')); ?></td>
                <td><strong>RFQ Due Date:</strong> <?php echo e($request->due_date->format('F d, Y')); ?></td>
            </tr>
        </table>

        <table>
            <tr>
                <td><strong>Buyer Name:</strong> <?php echo e($request->name); ?></td>
                <td><strong>Buyer Email:</strong> <?php echo e($request->email); ?></td>
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
                        <th>Unit<br>Price</th>
                        <th>Total<br>Price</th>
                        <th>Avail</th>
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
                        <td style="text-align: right;">
                        <?php if(isset($item['unit_price']) && $item['unit_price'] !== '' && $item['unit_price'] != 0): ?>
                            <?php echo e($item['unit_price']); ?>

                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                        <?php if(isset($item['total_price']) && $item['total_price'] !== '' && $item['total_price'] != 0): ?>
                            <?php echo e($item['total_price']); ?>

                            <?php endif; ?>
                        </td>
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
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>

        <table>
            <tr>
                <td><strong>Total Amount before Taxes:</strong></td>
                <td style="text-align: right;">
                    <?php
                        $totalAmount = 0;
                        foreach($request->items_json as $item) {
                            if (isset($item['total_price']) && $item['total_price'] !== '' && $item['total_price'] != 0) {
                                if (is_numeric($item['total_price'])) {
                                $totalAmount += (float)$item['total_price'];
                                }
                            }
                        }
                    ?>
                    <?php if($totalAmount > 0): ?>
                        <?php echo e($totalAmount); ?>

                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <div class="vendor-note">
            <p><strong>TO OUR VENDORS:</strong><br>
            Specify brand/made and availability or quotation will not be honored. Vendor's proposal in response to this RFQ do not need to submit such documentation as part of this RFQ.</p>
        </div>

        <div class="terms">
            <h3>TERMS AND CONDITIONS</h3>
            <ol>
                <li>Please provide your best quotation for the items listed above.</li>
                <li>Quotation should include pricing, availability, and delivery timeline.</li>
                <li>All prices should be valid for at least 30 days from the date of quotation.</li>
                <li>Please respond to this RFQ by the due date indicated.</li>
            </ol>
        </div>

        <div class="footer">
            <p>This is an automatically generated RFQ. For any questions, please contact us at info@salengafarm.com</p>
            <p>RFQ ID: <?php echo e($request->id); ?> | Generated on: <?php echo e(now()->format('F d, Y H:i:s')); ?></p>
        </div>
    </div>
</body>
</html> <?php /**PATH C:\CODING\my_Inventory\resources\views/pdf/rfq.blade.php ENDPATH**/ ?>