CHAPTER IV

RESULT AND DISCUSSION

This chapter presents the results gathered from the evaluation of the Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm. The discussion follows the system's specific objectives, focusing on the functionalities designed for the Super Administrator, Administrator, Client, and User. The results were derived from system testing, user feedback through surveys, and observations made during the system deployment.

The system allows Super Administrators to manage user accounts, assign roles, and monitor system logs for security and maintenance purposes. Administrators evaluated the functions that allow them to manage plant inventory, process sales transactions, handle client requests, and conduct site visits. The system enables Administrators to add, update, and delete plant records, generate PDF quotations, and send email notifications to clients. Clients tested the ability to submit requests for quotation (RFQ), download approved quotations, and participate in assigned site visits by uploading required documents. Regular Users assessed the functionality that allows them to browse the plant catalog, submit plant inquiries, and view inquiry responses from administrators.


Super Admin Module That Manages User Accounts and Assigns Roles

This module allows the Super Admin to manage user accounts and assign roles within the system. It enables the Super Admin to create new user accounts, update existing user information, delete accounts, and assign appropriate roles such as Admin, Client, or User. This functionality ensures proper system management, security, and access control by restricting functions to authorized users only.

Figure [X] shows the interface of the Super Admin Module for managing user accounts. Through this module, the Super Admin can view a list of all registered users, including their names, email addresses, roles, and account status. The interface provides action buttons for editing user details, changing roles, and removing accounts when necessary. This feature maintains system organization and ensures that only authorized personnel have access to administrative functions.

[INSERT FIGURE: GUI for Super Admin Module That Manages User Accounts]

The pseudocode in Figure [X+1] shows how the Super Admin manages user accounts within the system. It begins by displaying the user management interface with a table listing all users and their details. When the Super Admin clicks "Add User," the system opens a registration form where new user information can be entered, including name, email, password, and assigned role. If the "Edit" button is clicked, the system retrieves the selected user's data and displays it in an editable form. When changes are saved, the system updates the user information in the database. If the "Delete" button is clicked, the system displays a confirmation dialog to prevent accidental deletions. Upon confirmation, the user account is removed from the system. This function ensures efficient user management and maintains accurate account records.

FUNCTION: Super Admin Manages User Accounts
BEGIN
    Display "User Management" Interface
    Load AllUsers FROM Database
    
    WHEN "Add User" Clicked:
        IF InputValid THEN
            Create NewUser
            Assign Role
            Display "User created successfully"
        END IF
    
    WHEN "Edit" Clicked:
        IF UpdateValid THEN
            Update UserRecord
            Display "User updated successfully"
        END IF
    
    WHEN "Delete" Clicked:
        IF Confirmed THEN
            Delete UserAccount
            Display "User deleted successfully"
        END IF
END

Figure [X+1]. Pseudocode for Super Admin Module That Manages User Accounts


Super Admin Module That Monitors System Logs

This module allows the Super Admin to view, monitor, and manage system logs for security and maintenance purposes. System logs record all important activities within the application, including user logins, data modifications, errors, and system events. By accessing these logs, the Super Admin can track system performance, identify potential security issues, and troubleshoot errors efficiently.

Figure [X] shows the System Logs interface where the Super Admin can view detailed log entries. Each log entry includes information such as the timestamp, log level (info, warning, error), message description, and the user or system component that generated the log. The interface provides filtering options to search logs by date range, log level, or specific keywords, making it easier to locate relevant information quickly.

[INSERT FIGURE: GUI for Super Admin Module That Monitors System Logs]

The pseudocode in Figure [X+1] illustrates how the system log monitoring function operates. When the Super Admin accesses the logs page, the system retrieves log entries from the Laravel log files and displays them in a paginated table. The Super Admin can filter logs by selecting a date range, choosing a specific log level (such as error or warning), or entering keywords in the search box. The system also provides options to download logs as a file for backup purposes or clear old logs to free up storage space. This functionality helps maintain system health, ensures accountability, and supports quick resolution of technical issues.

FUNCTION: Super Admin Monitors System Logs
BEGIN
    Display "System Logs" Interface
    Load LogEntries FROM LogFiles
    
    WHEN FilterApplied:
        Filter LogEntries BY Criteria
        Display FilteredResults
    
    WHEN "Download Logs" Clicked:
        Generate LogFile
        Download TO UserDevice
        Display "Logs downloaded successfully"
    
    WHEN "Clear Logs" Clicked:
        IF Confirmed THEN
            Delete OldLogEntries
            Display "Logs cleared successfully"
        END IF
END

Figure [X+1]. Pseudocode for Super Admin Module That Monitors System Logs


Admin Module That Manages Plant Inventory

This module allows the Administrator to manage the plant inventory effectively. Through this feature, the Admin can add new plant records, update existing plant information, delete plants from the inventory, and upload plant photos. The system maintains accurate stock levels, pricing information, and plant specifications, ensuring that the catalog remains up-to-date for clients and users.

Figure [X] shows the Plant Inventory Management interface where the Administrator can view all plants in the system. The interface displays a table with plant names, categories, stock quantities, prices, and action buttons for editing or deleting records. The Admin can also use the search and filter functions to quickly locate specific plants by name or category.

[INSERT FIGURE: GUI for Admin Module That Manages Plant Inventory]

The pseudocode in Figure [X+1] demonstrates how the plant inventory management function works. When the Admin accesses the inventory page, the system retrieves all plant records from the database and displays them in a table. If the "Add Plant" button is clicked, a form appears where the Admin can enter plant details such as name, scientific name, category, stock quantity, price, and upload a photo. When the "Edit" button is clicked for a specific plant, the system loads the plant's current data into an editable form. After making changes, the Admin saves the updates, and the system refreshes the inventory table. If the "Delete" button is clicked, the system asks for confirmation before removing the plant record and its associated photo from storage. This function ensures that the plant catalog remains accurate, organized, and accessible. Additionally, the system provides search and filter capabilities to help administrators quickly locate specific plants by name, category, or stock level.

FUNCTION: Admin Manages Plant Inventory
BEGIN
    Display "Plant Inventory" Interface
    Load AllPlants FROM Database
    
    WHEN "Add Plant" Clicked:
        IF InputValid THEN
            Upload PlantPhoto
            Create PlantRecord
            Display "Plant added successfully"
        END IF
    
    WHEN "Edit" Clicked:
        IF UpdateValid THEN
            Update PlantRecord
            Display "Plant updated successfully"
        END IF
    
    WHEN "Delete" Clicked:
        IF Confirmed THEN
            Delete PlantRecord
            Display "Plant deleted successfully"
        END IF
END

Figure [X+1]. Pseudocode for Admin Module That Manages Plant Inventory


Admin Module That Processes Walk-In Sales Transactions

This module allows the Administrator to process walk-in sales transactions through a point-of-sale (POS) interface. The system enables the Admin to select plants, specify quantities, calculate totals automatically, and complete sales transactions. Upon completing a sale, the system automatically deducts the sold quantities from the inventory and generates a sales record for tracking and reporting purposes. The POS interface also includes real-time stock validation to prevent overselling and ensures accurate transaction processing with immediate database updates.

Figure [X] shows the Walk-In Sales interface where the Administrator can process customer purchases. The interface displays available plants with their current stock levels and prices. The Admin can add plants to the cart, adjust quantities, and view the running total. Once the customer is ready to pay, the Admin clicks the "Complete Sale" button to finalize the transaction. The system automatically validates stock availability before processing the sale to prevent overselling. Upon successful completion, the transaction is recorded in the sales database and inventory levels are immediately updated to reflect the new stock quantities. The interface also provides options to remove items from the cart or modify quantities during the checkout process.

[INSERT FIGURE: GUI for Admin Module That Processes Walk-In Sales]

The pseudocode in Figure [X+1] explains how the walk-in sales processing function operates. When the Admin opens the POS interface, the system loads all available plants from the inventory. The Admin selects plants and enters the quantity for each item. The system calculates the subtotal for each item and the grand total for the entire transaction. When the "Complete Sale" button is clicked, the system validates that sufficient stock is available for all items. If stock is sufficient, the system creates a sale record in the database, deducts the sold quantities from the plant inventory, and displays a success message. If any item has insufficient stock, the system alerts the Admin and prevents the transaction from completing. This function ensures accurate inventory tracking and efficient sales processing.

FUNCTION: Admin Processes Walk-In Sales
BEGIN
    Display "Point of Sale" Interface
    Load AvailablePlants
    
    WHEN PlantSelected:
        IF Quantity <= AvailableStock THEN
            Add Item TO ShoppingCart
        ELSE
            Display "Insufficient stock"
        END IF
    
    WHEN "Complete Sale" Clicked:
        IF StockAvailable THEN
            Create SaleRecord
            Deduct Quantity FROM Stock
            Display "Sale completed successfully"
        END IF
END

Figure [X+1]. Pseudocode for Admin Module That Processes Walk-In Sales


Admin Module That Manages Client Requests and Generates Quotations

This module allows the Administrator to manage client requests for quotation (RFQ) and user plant inquiries through a comprehensive request management system that displays all submitted requests in a tabbed interface, separating client RFQs from user inquiries, where for client RFQs, the Admin can set pricing for each requested plant, generate professional PDF quotations with detailed specifications and calculations, and send them via email with automatic status updates, while for user inquiries, the Admin can set availability status for each plant (Available, Limited Stock, Out of Stock, Pre-order) and send detailed responses with personalized remarks and recommendations.

Figure [X] shows the Request Management interface where the Administrator can view and process both client RFQs and user inquiries. The interface includes tabs for "Client Requests" and "User Inquiries," allowing the Admin to switch between request types easily. Each request displays the client or user name, email, request date, status, and action buttons for viewing details and sending responses. The interface also provides filtering and sorting capabilities to efficiently manage large volumes of requests.

[INSERT FIGURE: GUI for Admin Module That Manages Client Requests]

The pseudocode in Figure [X+1] demonstrates how the request management function works. When the Admin opens the requests page, the system loads all requests from the database and separates them by type. For client RFQs, the Admin enters prices and the system calculates totals automatically, then generates PDF quotations and sends them via email to clients. For user inquiries, the Admin sets availability status (Available, Limited Stock, Out of Stock, Pre-order) and adds remarks for each plant, then sends responses that update the inquiry status and notify users via email. This function streamlines request processing and ensures timely communication with clients and users.

FUNCTION: Admin Manages Requests and Generates Quotations
BEGIN
    Display "Request Management" Interface
    Load ClientRequests AND UserInquiries
    
    WHEN "View Details" Clicked FOR ClientRequest:
        WHEN PricesEntered:
            Calculate TotalPrice
        
        WHEN "Generate PDF" Clicked:
            Create PDFDocument
            Display "PDF generated successfully"
        
        WHEN "Send Email" Clicked:
            Send Email WITH PDF
            Display "Email sent successfully"
    
    WHEN "View Details" Clicked FOR UserInquiry:
        WHEN "Send Response" Clicked:
            Update InquiryStatus
            Send Email
            Display "Response sent successfully"
END

Figure [X+1]. Pseudocode for Admin Module That Manages Client Requests




Admin Module That Conducts and Records Site Visits

This module allows the Administrator to create, manage, and record site visits for clients. Site visits are essential for assessing client properties, documenting site conditions, and preparing proposals for landscaping or plant installation projects. The system enables the Admin to schedule visits, record GPS coordinates using an interactive map, complete assessment checklists, upload photos and documents, and track the progress of each visit from initial assessment to proposal approval.

Figure [X] shows the Site Visit Management interface where the Administrator can view all scheduled site visits. The interface displays a list of visits with client names, visit dates, locations, and current status (Scheduled, In Progress, Completed). The Admin can create new site visits, edit existing ones, and view detailed information for each visit including checklists and uploaded files. The system integrates GPS mapping functionality to record precise site locations and allows administrators to track visit progress through comprehensive checklists covering client data, site assessment, and physical factors. Additionally, the interface supports collaborative features where clients can upload required documents and administrators can attach proposal files such as design quotations and terms and conditions. The system also provides filtering and search capabilities to help administrators efficiently manage multiple site visits across different time periods and client categories.

[INSERT FIGURE: GUI for Admin Module That Conducts Site Visits]

The pseudocode in Figure [X+1] illustrates how the site visit management function operates. When the Admin creates a new site visit, the system displays a form where the Admin enters client information, visit date, and location using an interactive map to set GPS coordinates. Once created, the Admin can complete various checklists including Client Data, Site Assessment, Physical Factors, and Proposal sections, with options to upload files and add notes for each item. Clients assigned to the site visit can view details and upload required documents through the Client Data section. This function ensures comprehensive documentation of site visits and facilitates collaboration between administrators and clients.

FUNCTION: Admin Conducts and Records Site Visits
BEGIN
    Display "Site Visit Management" Interface
    Load AllSiteVisits FROM Database
    
    WHEN "Create Site Visit" Clicked:
        Capture GPSCoordinates FROM Map
        IF FormValid THEN
            Create SiteVisitRecord
            Display "Site visit created successfully"
        END IF
    
    WHEN "View Details" Clicked:
        Display SiteVisitData WITH Checklists
        
        WHEN "Upload File" Clicked:
            Upload File TO Storage
            Display "File uploaded successfully"
    
    WHEN "Delete" Clicked:
        IF Confirmed THEN
            Delete SiteVisitRecord
        END IF
END

Figure [X+1]. Pseudocode for Admin Module That Conducts Site Visits


Admin Module That Displays Dashboard Analytics

This module allows the Administrator to view comprehensive dashboard analytics for monitoring system performance and making data-driven decisions. The dashboard displays key metrics including total stock levels, low stock alerts, stock distribution by plant category, and sales analytics by category. Through visual representations such as charts and graphs, the Admin can quickly assess inventory health, identify trends, and take proactive actions to maintain optimal stock levels and improve sales performance.

Figure [X] shows the Admin Dashboard interface where the Administrator can view real-time analytics and statistics. The dashboard displays summary cards showing total stock count and the number of low stock items requiring attention. Interactive charts visualize stock distribution across different plant categories (trees, shrubs, palms, bamboo, grass, herbs) and sales performance by category. The dashboard also shows a list of recent plants added to the inventory and provides quick access to stock update functions.

[INSERT FIGURE: GUI for Admin Module That Displays Dashboard Analytics]

The pseudocode in Figure [X+1] demonstrates how the dashboard analytics function operates. When the Admin accesses the dashboard, the system retrieves data from the database and performs calculations to generate meaningful statistics including total stock, low stock items (less than 10 units), and stock distribution by category. The system generates visual charts using Chart.js library to display stock distribution as a doughnut chart and sales distribution as a bar chart. The dashboard updates in real-time whenever inventory or sales data changes, ensuring administrators always have access to current information for decision-making and optimizing sales strategies.

FUNCTION: Admin Views Dashboard Analytics
    BEGIN
        Display "Admin Dashboard" Interface
        
        Calculate TotalStock
        Get LowStockItems
        Display StockAlerts
        
        Calculate StockDistribution BY Category
        Generate DoughnutChart
        
        Calculate SalesDistribution BY Category
        Generate BarChart
        
        Display RecentPlants
        
        WHEN "Update Stock" Clicked:
            Update PlantQuantity
            Display "Stock updated successfully"
    END

Figure [X+1]. Pseudocode for Admin Module That Displays Dashboard Analytics


Client Module That Submits Requests for Quotation (RFQ)

This module allows Clients to submit requests for quotation by selecting plants from the catalog and specifying detailed requirements such as quantities, measurements (height, spread, spacing), and pricing preferences. The system processes these requests and generates professional PDF quotations that clients can download and review.

Figure [X] shows the RFQ submission interface where Clients can browse the plant catalog and select plants for their quotation request. Clients can click "Add to RFQ" to select plants, specify quantities and measurements, and choose pricing preferences before submitting their request.

[INSERT FIGURE: GUI for Client Module That Submits RFQ]

The pseudocode in Figure [X+1] demonstrates how the RFQ submission function works. When a Client accesses the RFQ page, the system loads all available plants from the catalog. The Client browses plants and adds desired items to their RFQ cart. For each selected plant, the Client enters the quantity and can optionally specify measurements such as height, spread, and spacing. The Client also selects a pricing preference to indicate their budget range. Once all plants are selected, the Client fills in their contact information and submits the RFQ. The system validates the input, creates an RFQ record in the database with request type set to 'client', generates a PDF quotation, and sends email notifications to administrators. The Client receives a confirmation message and can track the status of their RFQ in their dashboard. This function streamlines the quotation request process and ensures that clients receive professional, detailed quotations for their plant needs. The system also maintains a comprehensive audit trail of all RFQ activities, allowing administrators to monitor request patterns and response times for continuous service improvement. Additionally, the platform supports bulk plant selection and modification capabilities, enabling clients to efficiently manage large-scale landscaping projects with multiple plant varieties and specifications. The integrated notification system ensures real-time updates throughout the quotation process, keeping both clients and administrators informed of any status changes or required actions.

FUNCTION: Client Submits Request for Quotation
BEGIN
    Display "Request for Quotation" Interface
    Load AvailablePlants
    
    WHEN PlantSelected:
        Add Plant TO RFQCart
    
    WHEN "Submit RFQ" Clicked:
        IF FormValid THEN
            Create RFQRecord
            Generate PDFQuotation
            Send EmailNotification
            Display "RFQ submitted successfully"
        END IF
END

Figure [X+1]. Pseudocode for Client Module That Submits RFQ


Client Module That Participates in Site Visits

This module allows Clients to participate in assigned site visits by viewing visit details and uploading required documents. When an Administrator creates a site visit and assigns it to a client, the client receives access to view the visit information, checklists, and can upload necessary documents such as property information, site photos, and other required files. This collaboration feature ensures that all necessary information is collected efficiently before the site assessment.

Figure [X] shows the Client Site Visit interface where Clients can view their assigned site visits. The interface displays visit details including the scheduled date, location on a map, and checklist items that require client input. Clients can see which documents have been uploaded and which are still pending, making it easy to track their progress.

[INSERT FIGURE: GUI for Client Module That Participates in Site Visits]

The pseudocode in Figure [X+1] explains how the client site visit participation function works. When a Client logs in and accesses "My Site Visits," the system retrieves all site visits assigned to that client from the database. The Client can click on a visit to view its details, including the visit date, location, and checklist sections. For checklist items marked as requiring client input, the system displays upload buttons. When the Client clicks "Upload File," they can select a file from their device. The system validates the file type and size, uploads it to storage, and records the file information in the site visit data. The checklist item status automatically updates to "submitted" once a file is uploaded. Clients can also delete files they previously uploaded if they need to replace them. This function facilitates smooth collaboration between clients and administrators, ensuring that all necessary documentation is collected before the site visit occurs.

FUNCTION: Client Participates in Site Visits
BEGIN
    Display "My Site Visits" Interface
    Load AssignedSiteVisits
    
    WHEN "View Details" Clicked:
        Display SiteVisitData WITH Checklists
        
        WHEN "Upload File" Clicked:
            IF Valid THEN
                Upload File
                Display "File uploaded successfully"
            END IF
        
        WHEN "Delete File" Clicked:
            IF Confirmed THEN
                Delete File
            END IF
END

Figure [X+1]. Pseudocode for Client Module That Participates in Site Visits


User Module That Submits Plant Inquiries

This module allows regular Users to submit plant inquiries for availability checks. Unlike client RFQs, user inquiries are simpler requests where users select plants they are interested in and submit an inquiry to check availability. The system sends the inquiry to administrators, who then respond with availability status and remarks for each plant. Users can view responses in their dashboard and receive email notifications when administrators reply.

Figure [X] shows the Plant Inquiry interface where Users can browse the plant catalog and select plants for their inquiry. The interface displays plant cards with photos and basic information. When logged in, users see an "Add to Inquiry" button on each plant card. After selecting plants, users can click "View Inquiry" to open a modal where they can review their selections, adjust quantities and measurements, and submit the inquiry.

[INSERT FIGURE: GUI for User Module That Submits Plant Inquiries]

The pseudocode in Figure [X+1] demonstrates how the plant inquiry submission function works. When a User browses the public plant catalog while logged in, the system displays "Add to Inquiry" buttons on plant cards. When a plant is added, the system stores it in a temporary inquiry list. The User can click "View Inquiry" to open a modal displaying all selected plants in a table format. The User can edit quantities, measurements (height, spread, spacing), or remove plants from the inquiry. When ready to submit, the User fills in their contact information (name, email, contact number) and clicks "Submit Inquiry." The system validates the input, creates an inquiry record in the database with request type set to 'user', and sends email notifications to administrators. The User receives a confirmation message and can track the inquiry status in their dashboard. When an administrator responds, the User receives an email notification and can view the response showing availability status and remarks for each plant. This function provides users with a simple way to check plant availability without the complexity of a full RFQ process. The system automatically saves inquiry drafts to prevent data loss during the selection process, allowing users to continue their inquiry session even if interrupted. Additionally, the platform maintains a history of all user inquiries, enabling users to reference previous requests and resubmit similar inquiries for future plant needs.

FUNCTION: User Submits Plant Inquiry
BEGIN
    Display PlantCatalog
    
    IF UserLoggedIn THEN
        Display "Add to Inquiry" Button
    END IF
    
    WHEN "Add to Inquiry" Clicked:
        Add Plant TO InquiryList
    
    WHEN "Submit Inquiry" Clicked:
        IF FormValid THEN
            Create InquiryRecord
            Send EmailNotification
            Display "Inquiry submitted successfully"
        END IF
END

Figure [X+1]. Pseudocode for User Module That Submits Plant Inquiries


User Module That Views Inquiry Responses

This module allows Users to view responses from administrators regarding their submitted plant inquiries. When an administrator responds to an inquiry, the system updates the inquiry status and creates a notification for the user. Users can access their dashboard to see all their inquiries and click on responded inquiries to view detailed responses including availability status and remarks for each requested plant.

Figure [X] shows the User Dashboard where Users can view their recent inquiries. The interface displays a table with inquiry ID, inquiry date, status (Pending or Responded), and action buttons. For inquiries with "Responded" status, users can click "View Response" to see the administrator's reply.

[INSERT FIGURE: GUI for User Module That Views Inquiry Responses]

The pseudocode in Figure [X+1] explains how the inquiry response viewing function works. When a User logs into their dashboard, the system retrieves all inquiries submitted by that user from the database. The inquiries are displayed in a table sorted by date, with the most recent inquiries shown first. Each inquiry shows its status using color-coded badges (yellow for pending, green for responded). When the User clicks "View Response" for a responded inquiry, the system retrieves the inquiry details including all requested plants with their availability status and administrator remarks. The system displays each plant with a color-coded availability badge: green for "Available," yellow for "Limited Stock," red for "Out of Stock," and purple for "Pre-order." The User can see the administrator's remarks for each plant, providing additional information about availability, estimated restock dates, or alternative suggestions. This function keeps users informed about their inquiry status and helps them make informed decisions about their plant purchases.

FUNCTION: User Views Inquiry Responses
BEGIN
    Display "User Dashboard" Interface
    Load UserInquiries
    
    FOR EACH Inquiry DO
        IF Status = 'pending' THEN
            Display "Pending" Badge
        ELSE IF Status = 'responded' THEN
            Display "Responded" Badge
        END IF
    END FOR
    
    WHEN "View Response" Clicked:
        Display ResponsePage WITH PlantDetails
        Display AvailabilityStatus
        Display AdminRemarks
END

Figure [X+1]. Pseudocode for User Module That Views Inquiry Responses



## Evaluation Ratings of Respondents in Terms of Functional Suitability Based on the ISO 9126 Software Quality Framework

The evaluation ratings from the respondents in terms of Functional Suitability show how well the system performs its intended tasks. This part measures if the system works as expected, provides correct results, and meets the needs of its users. The feedback from the respondents helps identify the parts of the system that work properly and those that may need improvement to make the system perform better. This evaluation also helps determine the system's completeness and reliability in carrying out its functions. The evaluation ratings for the Super Administrator Module are shown in Table [X], the ratings for the Administrator Module are presented in Table [X+1], and the results for the User and Client Module are displayed in Table [X+2]. Together, these ratings give a clear view of how each group of respondents assessed the system's overall functional performance.


### Super Admin Module Functional Suitability

**Table [X]. Evaluation Ratings from the Respondents (Super Administrator and Advisory Committee) in Terms of Functional Suitability for the Super Administrator Module**

| Assessment Description | Mean | Verbal Interpretation |
|------------------------|------|----------------------|
| The system allows Super Admins to efficiently manage user accounts, including creating, updating, and deleting user records. | 4.40 | Agree |
| The system allows Super Admins to accurately assign and modify user roles (Admin, Client, User). | 4.20 | Agree |
| The system allows Super Admins to effectively monitor system logs for security and maintenance purposes. | 4.00 | Agree |
| The system allows Super Admins to accurately view and filter log entries by date, level, and keywords. | 4.40 | Agree |
| The system allows Super Admins to access dashboard analytics showing stock distribution and sales performance. | 4.40 | Agree |
| **Consolidated Mean** | **4.28** | **Agree** |

Table [X] shows that respondents generally agreed with the functional suitability of the Super Admin module, as reflected in the overall mean of 4.28 (Agree). This indicates that the system effectively performs its intended administrative functions based on the evaluation of the Super Admin and Advisory Committee.

The highest-rated indicators are managing user accounts, viewing and filtering log entries, and accessing dashboard analytics, each with a mean score of 4.40 (Agree). This demonstrates strong user confidence in these features, particularly in user management and system monitoring capabilities.

The lowest-rated indicator is monitoring system logs, with a mean of 4.00 (Agree). Although still positively rated, this score suggests that this feature may benefit from enhancements in log display clarity and filtering options to improve usability.

Overall, the results indicate that the Super Admin module performs its intended functions effectively, particularly in user and role management.


### Admin Module Functional Suitability

**Table [X+1]. Evaluation Ratings from the Respondents (Administrator and Advisory Committee) in Terms of Functional Suitability for the Administrator Module**

| Assessment Description | Mean | Verbal Interpretation |
|------------------------|------|----------------------|
| The system allows Admins to efficiently manage plant inventory, including adding, updating, and deleting plant records with photo uploads. | 4.80 | Strongly Agree |
| The system allows Admins to accurately process walk-in sales transactions with automatic inventory deduction. | 4.80 | Strongly Agree |
| The system allows Admins to effectively manage client requests for quotation (RFQ) and user plant inquiries. | 4.60 | Strongly Agree |
| The system allows Admins to accurately generate PDF quotations for client RFQ submissions. | 4.20 | Agree |
| The system allows Admins to efficiently send email notifications to clients and users regarding their requests. | 4.20 | Agree |
| The system allows Admins to effectively create and manage site visits with GPS location mapping. | 4.40 | Agree |
| The system allows Admins to accurately complete site visit checklists and upload proposal documents. | 4.40 | Agree |
| The system allows Admins to efficiently view dashboard analytics showing stock distribution, low stock alerts, and sales performance. | 4.60 | Strongly Agree |
| The system allows Admins to accurately search and filter plant records by name, category, and stock level. | 4.00 | Agree |
| **Consolidated Mean** | **4.33** | **Agree** |

Table [X+1] shows that respondents agreed with the functional suitability of the Administrator Module, as indicated by the overall mean of 4.33 (Agree). This reflects that the system effectively performs its intended administrative functions, enabling administrators to manage plant inventory, process sales transactions, handle client requests, conduct site visits, and view dashboard analytics efficiently.

The highest-rated indicators are managing plant inventory and processing walk-in sales, both receiving a mean score of 4.80 (Strongly Agree). These results indicate that users find these core business functions highly reliable and effective in supporting daily operations. Managing client requests and viewing dashboard analytics also received strong ratings of 4.60 (Strongly Agree), demonstrating efficient request processing and inventory monitoring capabilities.

The lowest-rated indicator is searching and filtering plant records, with a mean of 4.00 (Agree). Although still positively evaluated, this feature may benefit from improvements in search functionality or filter options to enhance user experience.

Overall, the evaluation confirms that the Administrator Module performs well in supporting daily business operations, inventory management, and customer service functions.


### User and Client Module Functional Suitability

**Table [X+2]. Evaluation Ratings from the Respondents (User, Client, and Advisory Committee) in Terms of Functional Suitability for the User and Client Module**

| Assessment Description | Mean | Verbal Interpretation |
|------------------------|------|----------------------|
| The system allows users and clients to securely access and browse the plant catalog, including viewing plant details and specifications efficiently. | 4.69 | Strongly Agree |
| The system enables users and clients to submit requests (plant inquiries or RFQs) accurately, specifying the required quantity, height, spread, spacing, pricing preferences, and type of plants. | 4.65 | Strongly Agree |
| The system allows users and clients to view the status of their submitted requests in their dashboard completely and accurately. | 4.57 | Strongly Agree |
| The system allows users and clients to download approved quotations in PDF format and view inquiry responses with availability status and remarks. | 4.38 | Agree |
| The system allows users and clients to receive confirmations or notifications regarding the status of their requests and participate in assigned site visits. | 4.53 | Strongly Agree |
| **Consolidated Mean** | **4.56** | **Strongly Agree** |

Table [X+2] shows that respondents strongly agreed with the functional suitability of the User and Client Module, as reflected in the overall mean of 4.56 (Strongly Agree). This indicates that the module effectively fulfills its intended functions, providing users and clients with a comprehensive system for browsing the plant catalog, submitting requests, and participating in site visits.

The highest-rated indicators are browsing the plant catalog and submitting requests with plant specifications, receiving mean scores of 4.69 and 4.65 respectively (both Strongly Agree). These results demonstrate that users and clients find the catalog browsing and request submission processes intuitive, efficient, and highly responsive to their needs.

The lowest-rated indicator is downloading approved quotations and viewing inquiry responses, with a mean of 4.38 (Agree). Although still positively rated, this feature may benefit from minor enhancements in document access and response viewing to further improve user satisfaction.

Overall, the results demonstrate that the User and Client Module is highly functional, user-friendly, and dependable, successfully supporting both regular users and business clients in browsing the plant catalog, submitting inquiries and RFQs, tracking request status, and participating in site visits with accuracy and convenience.


### Descriptive Rating of the Summary of Functionalities

Table [X+3] presents the summary of the descriptive evaluation of the system's functional suitability, derived from the assessments of respondents, including the Super Administrator, Administrator, User/Client, and members of the Advisory Committee. The User and Client functionality obtained the highest mean rating of 4.56, interpreted as "Strongly Agree," indicating that the module effectively fulfills its intended purpose by allowing users and clients to conveniently browse the plant catalog, submit requests for quotation and plant inquiries, view request status, and participate in site visits. The Administrator functionality received a mean rating of 4.33, interpreted as "Agree," signifying that administrative operations—such as managing plant inventory, processing sales transactions, handling client requests, conducting site visits, and viewing dashboard analytics—are efficiently executed and reliable. Meanwhile, the Super Administrator functionality acquired a mean rating of 4.28, interpreted as "Agree," implying that while the module effectively performs its core functions in managing user accounts, assigning roles, and monitoring system logs, there remains room for enhancement to further improve system oversight and log monitoring features. The overall mean rating of 4.39, interpreted as "Agree," indicates that the entire Comprehensive Plant Inventory and Site Visit Management System effectively performs its intended functions across all user levels, ensuring smooth coordination among different modules and supporting efficient digital management of plant inventory, sales operations, client relationships, and site visit documentation at Salenga Farm.

**Table [X+3]. Descriptive Rating of the Summary of Functionalities**

| Particulars | Mean | Verbal Interpretation |
|-------------|------|----------------------|
| Super Administrator Functionality | 4.28 | Agree |
| Administrator Functionality | 4.33 | Agree |
| User and Client Functionality | 4.56 | Strongly Agree |
| **OVERALL MEAN** | **4.39** | **Agree** |


### Summary of Respondents' Rating Based on ISO 9126 Software Quality Framework

The summary of user ratings based on the ISO 9126 Software Quality Framework presents how respondents evaluated the overall quality of the system according to internationally recognized software quality standards. The assessment encompasses key quality characteristics, namely Functional Suitability, Performance Efficiency, Usability, Reliability, and Security. Findings indicate that the system effectively performs its intended functions, operates with responsiveness and speed, and maintains stability during various transactions. Moreover, the system provides a user-friendly interface accessible to a wide range of users and delivers robust security measures to protect user data and system integrity. Overall, the results demonstrate that the system exhibits a high level of quality in accordance with the ISO 9126 framework, successfully meeting user expectations and operational objectives while identifying certain aspects that may still be refined to further enhance performance and user satisfaction.

As presented in Table [X+4], the summary of user ratings based on the ISO 9126 Software Quality Framework shows that the system performed exceptionally well across all evaluated quality characteristics. Functional Suitability received a mean rating of 4.39, interpreted as "Agree," indicating that the system effectively performs its intended tasks across all user roles. Performance Efficiency received a mean rating of 4.47, interpreted as "Agree," demonstrating that the system responds promptly and processes operations efficiently. Usability, Reliability, and Security received mean ratings of 4.57, 4.55, and 4.54 respectively, all interpreted as "Strongly Agree," reflecting the system's user-friendliness, dependability, and robustness in protecting user data.

The overall mean rating of 4.50, interpreted as "Strongly Agree," demonstrates that users are highly satisfied with the system's overall quality, reliability, and effectiveness in supporting plant inventory management, sales transactions, site visit coordination, and request processing operations at Salenga Farm.

Specifically, Performance Efficiency ensures that the system responds promptly to user actions, loads pages quickly, and performs well even when processing multiple user requests simultaneously. Usability shows that the interface is intuitive, provides clear and understandable instructions and messages, and can be used easily even by users with minimal technical knowledge. Reliability demonstrates that the system consistently displays accurate data, features are always available when needed, and submitted documents are kept safe and accessible. Finally, Security demonstrates that strong authentication mechanisms, secure data protection, and prevention of unauthorized access maintain system accountability and protect sensitive information.

**Table [X+4]. Summary of Respondents' Rating Based on ISO 9126 Software Quality Framework**

| Quality Characteristic | Mean | Verbal Interpretation |
|------------------------|------|----------------------|
| Functional Suitability | 4.39 | Agree |
| Performance Efficiency | 4.47 | Agree |
| Usability | 4.57 | Strongly Agree |
| Reliability | 4.55 | Strongly Agree |
| Security | 4.54 | Strongly Agree |
| **OVERALL MEAN** | **4.50** | **Strongly Agree** |

