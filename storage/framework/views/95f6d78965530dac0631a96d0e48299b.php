<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
            background-color: #ffffff;
        }
        .content h3 {
            color: #28a745;
            margin-top: 0;
        }
        .content p {
            margin: 15px 0;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #28a745;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
        }
        .button:hover {
            background-color: #218838;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #dee2e6;
        }
        .footer p {
            margin: 5px 0;
        }
        .inquiry-id {
            background-color: #f8f9fa;
            padding: 10px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🌱 Salenga Farm</h2>
        </div>
        
        <div class="content">
            <h3>Hello <?php echo e($request->name); ?>,</h3>
            
            <p>Great news! Your plant inquiry has been reviewed by our team.</p>
            
            <div class="inquiry-id">
                <strong>Inquiry #<?php echo e($request->id); ?></strong><br>
                Submitted on: <?php echo e($request->created_at->format('M d, Y')); ?>

            </div>
            
            <p>We've checked the availability of the plants you requested and added our notes for each item.</p>
            
            <p>To view the detailed response including availability status and remarks for each plant, please log in to your dashboard:</p>
            
            <div class="button-container">
                <a href="<?php echo e($viewLink); ?>" class="button">View Response</a>
            </div>
            
            <p>If you have any questions or need further assistance, feel free to reply to this email or contact us directly.</p>
            
            <p>Thank you for choosing Salenga Farm!</p>
            
            <p style="margin-top: 30px;">
                Best regards,<br>
                <strong>The Salenga Farm Team</strong>
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; <?php echo e(date('Y')); ?> Salenga Farm. All rights reserved.</p>
            <p>This is an automated message. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\CODING\my_Inventory\resources\views/emails/inquiry-response.blade.php ENDPATH**/ ?>