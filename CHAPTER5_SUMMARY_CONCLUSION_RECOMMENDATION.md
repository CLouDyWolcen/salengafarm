CHAPTER V


SUMMARY, CONCLUSION, AND RECOMMENDATION


This chapter provides a comprehensive overview of the study's key findings, conclusions derived from the evaluation results, and recommendations for future development and enhancement. The summary emphasizes the major insights obtained from assessing the system using the ISO 9126 Software Quality Framework. The conclusions interpret these findings in relation to the study's objectives, while the recommendations outline potential improvements and future directions to further enhance the system's functionality, usability, and effectiveness in supporting plant inventory management and site visit operations at Salenga Farm.


Summary


The Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm was developed to modernize and streamline plant inventory management, sales processing, client request handling, and site visit documentation. The system was designed to address the challenges of manual record-keeping and inefficient communication between the farm and its clients. Key features of the Super Admin module included managing user accounts, assigning roles, and monitoring system logs for security and maintenance purposes. The Admin module focused on managing plant inventory, processing walk-in sales transactions, handling client requests for quotation (RFQ) and user plant inquiries, conducting site visits with GPS location mapping, and viewing dashboard analytics for stock distribution and sales performance. The User and Client module enabled customers to browse the plant catalog, submit plant inquiries and RFQs with detailed specifications, view request status, download approved quotations, and participate in assigned site visits by uploading required documents.

The system was evaluated using the ISO 9126 Software Quality Framework, which assessed Functional Suitability, Performance Efficiency, Usability, Reliability, and Security. A total of 28 respondents participated in the evaluation, including 1 Super Administrator, 1 Administrator, 19 Users, 3 Clients, and 4 Advisory Committee members who evaluated all modules. The evaluation results demonstrated strong user satisfaction across all quality characteristics. The Super Admin module achieved a consolidated mean of 4.28 (Agree), indicating effective performance in managing user accounts, assigning roles, and monitoring system logs. The Admin module received a consolidated mean of 4.33 (Agree), reflecting efficient management of plant inventory, sales transactions, client requests, and site visits. The User and Client module obtained the highest consolidated mean of 4.56 (Strongly Agree), demonstrating that users and clients found the system highly functional, intuitive, and responsive to their needs in browsing the catalog, submitting requests, and tracking request status.

In terms of overall system quality based on the ISO 9126 framework, Functional Suitability achieved a mean of 4.39 (Agree), Performance Efficiency received 4.47 (Agree), Usability obtained 4.57 (Strongly Agree), Reliability achieved 4.55 (Strongly Agree), and Security received 4.54 (Strongly Agree). The overall mean rating of 4.50 (Strongly Agree) confirmed that the system successfully met user expectations and operational objectives. Feedback from respondents indicated that the system significantly improved efficiency, accuracy, and transparency in plant inventory management, sales operations, and client communication. However, suggestions for enhancement included improving search and filter functionality, refining log display clarity, and optimizing PDF generation speed. Overall, the system successfully modernized Salenga Farm's operations and provided a reliable, user-friendly platform for managing plant inventory, processing sales, handling client requests, and documenting site visits.


Conclusion


As a result of the research, the following conclusions were drawn:

1. The Super Admin module achieved a consolidated mean rating of 4.28, interpreted as "Agree." This indicates that respondents found the module effective in managing administrative tasks. The module was particularly strong in managing user accounts (mean = 4.40), assigning and modifying user roles (mean = 4.40), viewing and filtering log entries (mean = 4.40), and accessing dashboard analytics (mean = 4.40). These features demonstrated reliability in supporting administrative control and system oversight. However, monitoring system logs received a slightly lower rating (mean = 4.00), suggesting that this feature may benefit from enhancements in log display clarity and filtering options to improve usability and make it easier for administrators to identify and troubleshoot issues.

2. The Admin module achieved a consolidated mean rating of 4.33, interpreted as "Agree." Respondents highly rated its capability to manage plant inventory, process sales transactions, handle client requests, conduct site visits, and view dashboard analytics. The highest-rated features were managing plant inventory (mean = 4.80) and processing walk-in sales transactions (mean = 4.80), both interpreted as "Strongly Agree," demonstrating that these core business functions are highly reliable and effective in supporting daily operations. Managing client requests and viewing dashboard analytics also received strong ratings of 4.60 (Strongly Agree), indicating efficient request processing and inventory monitoring capabilities. However, searching and filtering plant records received the lowest rating (mean = 4.00), suggesting that improvements in search functionality or filter options would enhance user experience and make it easier for administrators to locate specific plants quickly.

3. The User and Client module achieved a consolidated mean rating of 4.56, interpreted as "Strongly Agree." Users and clients reported high satisfaction with the module's functionalities, including browsing the plant catalog (mean = 4.69), submitting requests with detailed specifications (mean = 4.65), viewing request status (mean = 4.57), and receiving notifications (mean = 4.53). These results reflect the system's user-friendliness and effectiveness in facilitating customer engagement with Salenga Farm's services. The lowest-rated feature was downloading approved quotations and viewing inquiry responses (mean = 4.38), which, although still positively rated, may benefit from minor enhancements in document access and response viewing to further improve user satisfaction.

4. The system achieved an overall mean of 4.39 for Functional Suitability, interpreted as "Agree," indicating general satisfaction with the system's ability to perform its intended functions across all user levels. According to the ISO 9126 Software Quality Framework, the system also performed exceptionally well in other quality characteristics: Performance Efficiency (mean = 4.47, "Agree"), Usability (mean = 4.57, "Strongly Agree"), Reliability (mean = 4.55, "Strongly Agree"), and Security (mean = 4.54, "Strongly Agree"). The overall mean of 4.50, interpreted as "Strongly Agree," demonstrates that the system not only meets functional requirements but also provides a reliable, secure, efficient, and user-friendly experience. These results confirm that the system successfully fulfills its intended purpose by efficiently supporting the tasks of Super Administrators, Administrators, Users, and Clients through accurate, accessible, and intuitive features.

The findings suggest that the Comprehensive Plant Inventory and Site Visit Management System successfully fulfills its intended purpose. It efficiently supports plant inventory management, sales processing, client request handling, and site visit documentation at Salenga Farm. While minor improvements can be made in search functionality, log monitoring, and document access features, the overall system is considered effective, reliable, secure, and aligned with the study objectives.


Recommendation


Based on the conducted assessment and feedback gathered from the 28 respondents during the system evaluation, the following recommendations are proposed to further enhance the Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm:

1. Implement multi-factor authentication (MFA) for user login to prevent unauthorized access to user accounts even if passwords are compromised. The system should support multiple authentication methods such as SMS codes, email codes, or authenticator apps, with Super Admin control over which user roles require MFA.

2. Develop a comprehensive audit trail system that logs all critical system changes and activities, including password changes, inventory updates, user account modifications, and confidential document access. The system should create immutable log entries with timestamps and user IDs, automatically archive monthly logs, and retain records for at least 12 months to support accountability and security investigations.

3. Establish an automated backup system with monthly scheduling to ensure data can be recovered in case of system failure or data corruption. The backup system should create complete database backups on the first day of each month, verify backup integrity, retain the most recent 6 monthly backups, and notify administrators of any backup failures or storage issues.

4. Implement file encryption for confidential documents to protect sensitive information even if storage is compromised. The system should use industry-standard encryption algorithms (AES-256) to encrypt site visit documents, client documents, and other confidential files at rest, with secure key management separate from encrypted files.

5. Stabilize the hosting environment to ensure all system modules function correctly in production. The system should successfully send email notifications, store uploaded files with proper permissions, and generate valid PDF reports without errors in the production hosting environment, with detailed error logging for troubleshooting.

6. Develop a flexible role management system that allows dynamic permission assignment without code changes. Super Admins should be able to create custom roles, assign granular permissions for each system module, and modify role permissions with immediate effect on all users, while maintaining an audit trail of all role configuration changes.

7. Enhance data validation and accuracy checks throughout the system to maintain data quality and catch errors before they affect reports. The system should validate all required fields, enforce value constraints, check for logical consistency, detect duplicates, and provide clear error messages when validation fails.

8. Improve the comprehensive reporting system to ensure all reports reflect current system data accurately. The system should support exporting reports in multiple formats (PDF, Excel, CSV), properly format PDF reports in production, and allow filtering and customization of report parameters before generation.

9. Integrate AI-based plant recommendations that analyze site conditions (soil type, climate, available space) and user preferences to suggest suitable plants. The AI module should provide at least 3 ranked recommendations with explanations, track recommendation outcomes for future learning, and update algorithms based on historical success rates.

10. Implement AI-based disease detection that allows users to upload plant photos and receive automated analysis for signs of disease or pest damage. The AI module should identify specific diseases with confidence scores, provide treatment recommendations, and store analysis results for future reference.

11. Develop automated inventory forecasting using AI to predict future stock needs based on historical consumption patterns, seasonal variations, and trends. The system should forecast inventory needs for 30, 60, and 90 days ahead, alert administrators when stock is predicted to fall below minimum levels, and display forecast accuracy metrics.

12. Create smart site visit scheduling using AI to optimize visit timing and resource allocation. The AI module should analyze historical visit patterns, recommend optimal visit dates based on plant growth stages, suggest efficient routing for multiple visits, and consider weather forecasts when recommending outdoor site visit dates.

These recommendations aim to enhance the system's security, reliability, efficiency, and intelligence. By implementing these improvements, particularly the high-priority security enhancements (MFA, audit trails, backups, encryption) and operational improvements (flexible roles, data validation, reporting), Super Administrators, Administrators, Users, and Clients would experience a more secure, robust, and intelligent system. The future AI-based features (plant recommendations, disease detection, inventory forecasting, smart scheduling) would further enhance decision-making capabilities and operational efficiency at Salenga Farm.
